<?php

namespace App\Services;

use App\Models\Academic\AcaExamSet;
use App\Models\Academic\AcaQuestionLibrary;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiExamGeneratorService
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.groq.key');
        $this->model  = config('services.groq.model', 'llama-3.3-70b-versatile');
    }

    public function generate(array $config): AcaExamSet
    {
        // Pass question_type to fetch
        $questions = $this->fetchCandidateQuestions(
            $config['topic'],
            $config['question_type'] ?? 'All'
        );

        if ($questions->isEmpty()) {
            throw new \RuntimeException(
                "No active questions found for topic: \"{$config['topic']}\" and type: \"{$config['question_type']}\"."
            );
        }

        $targets = $this->calculateTargetCounts(
            $config['total_questions'],
            $config['easy_percent'],
            $config['medium_percent'],
            $config['hard_percent'],
        );

        $aiResult = $this->callAiForSelection($questions, $targets, $config);

        return $this->persistExamSet($config, $targets, $aiResult);
    }

    public function generateCandidateInstance(AcaExamSet $examSet, string $candidateId): array
    {
        $questionIds = $examSet->question_ids;

        $seed = crc32($examSet->id . $candidateId);
        mt_srand($seed);
        shuffle($questionIds);

        $optionShuffles = [];
        $questions = AcaQuestionLibrary::whereIn('id', $questionIds)->get()->keyBy('id');

        foreach ($questionIds as $qid) {
            $q = $questions[$qid] ?? null;
            if ($q && in_array($q->question_type, ['mcq', 'multiple_choice'])) {
                $options = ['a', 'b', 'c', 'd'];
                mt_srand($seed + $qid);
                shuffle($options);
                $optionShuffles[$qid] = $options;
            }
        }

        return [
            'question_order'  => $questionIds,
            'option_shuffles' => $optionShuffles,
        ];
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function fetchCandidateQuestions(string $topic, string $questionType): Collection
    {
        $query = AcaQuestionLibrary::where('is_active', true)
            ->whereNull('deleted_at')
            ->select(['id', 'topic', 'question_type', 'question_text',
                    'difficulty_level', 'marks']);

        // Filter by topic
        if (!in_array($topic, ['General', 'All'])) {
            $query->where('topic', $topic);
        }

        // Filter by question type
        match($questionType) {
            'mcq_4'         => $query->where('question_type', 'mcq_4'),
            'mcq_2'         => $query->where('question_type', 'mcq_2'),
            'short_question'=> $query->where('question_type', 'short_question'),
            'long_question' => $query->where('question_type', 'long_question'),
            'objective'     => $query->whereIn('question_type', ['mcq_4', 'mcq_2']),
            'subjective'    => $query->whereIn('question_type', ['short_question', 'long_question']),
            default         => null, // 'All' — no filter
        };

        return $query->get();
    }

    private function calculateTargetCounts(int $total, int $easyPct, int $mediumPct, int $hardPct): array
    {
        $easy   = (int) round($total * $easyPct / 100);
        $hard   = (int) round($total * $hardPct / 100);
        $medium = $total - $easy - $hard;

        return [
            'easy'   => max(0, $easy),
            'medium' => max(0, $medium),
            'hard'   => max(0, $hard),
        ];
    }

    private function callAiForSelection(Collection $questions, array $targets, array $config): array
    {
        try {
            if (empty($this->apiKey)) {
                Log::error('[Groq] API key is empty.');
                return $this->fallbackRandomSelection($questions, $targets);
            }

            // ── KEY FIX: Pre-select candidates per difficulty before sending to AI ──
            // Instead of sending ALL 250 questions, send only a smart subset.
            // This prevents token limit issues and speeds up the response.
            $multiplier = 3; // send 3x the needed count per difficulty for variety
            $candidates = collect();

            foreach (['easy', 'medium', 'hard'] as $diff) {
                $needed = $targets[$diff];
                $pool   = $questions->where('difficulty_level', $diff)->shuffle()->take($needed * $multiplier);

                // If not enough of this difficulty, top up from adjacent
                if ($pool->count() < $needed) {
                    Log::info("[Groq] Not enough '{$diff}' questions, topping up from other difficulties.");
                    $extras = $questions->whereNotIn('id', $pool->pluck('id'))
                        ->shuffle()
                        ->take($needed - $pool->count());
                    $pool = $pool->merge($extras);
                }

                $candidates = $candidates->merge($pool);
            }

            $prompt = $this->buildPrompt(
                $this->prepareQuestionsForPrompt($candidates),
                $targets,
                $config
            );

            Log::info('[Groq] Sending request', [
                'model'          => $this->model,
                'key_hint'       => substr($this->apiKey, 0, 8) . '...' . substr($this->apiKey, -4),
                'total_in_bank'  => $questions->count(),
                'sent_to_ai'     => $candidates->count(),   // ← much smaller now
                'targets'        => $targets,
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->timeout(60)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'       => $this->model,
                'temperature' => 0.2,
                'messages'    => [
                    [
                        'role'    => 'system',
                        'content' => 'You are an academic exam designer. Respond ONLY with valid JSON. No markdown, no extra text.',
                    ],
                    [
                        'role'    => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

            if ($response->failed()) {
                Log::error('[Groq] HTTP request failed', [
                    'http_status' => $response->status(),
                    'body'        => $response->body(),
                ]);
                return $this->fallbackRandomSelection($questions, $targets);
            }

            Log::info('[Groq] HTTP response received', ['http_status' => $response->status()]);

            $text = $response->json('choices.0.message.content', '');

            if (empty($text)) {
                Log::error('[Groq] Empty text in response', ['full_response' => $response->json()]);
                return $this->fallbackRandomSelection($questions, $targets);
            }

            // Strip markdown fences
            $text = preg_replace('/^```json\s*/i', '', trim($text));
            $text = preg_replace('/\s*```$/i', '', $text);

            $decoded = json_decode(trim($text), true);

            if (!is_array($decoded)) {
                Log::error('[Groq] JSON decode failed', [
                    'raw_text'   => substr($text, 0, 500),
                    'json_error' => json_last_error_msg(),
                ]);
                return $this->fallbackRandomSelection($questions, $targets);
            }

            $decoded = $this->sanitizeAiResult($decoded, $questions, $targets);

            if ($decoded === null) {
                Log::error('[Groq] Result could not be sanitized');
                return $this->fallbackRandomSelection($questions, $targets);
            }

            Log::info('[Groq] ✅ Success', [
                'selected_count' => count($decoded['selected_ids']),
                'reasoning'      => $decoded['reasoning'] ?? null,
            ]);

            return $decoded;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('[Groq] Connection failed', ['error' => $e->getMessage()]);
            return $this->fallbackRandomSelection($questions, $targets);

        } catch (\Exception $e) {
            Log::error('[Groq] Unexpected exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->fallbackRandomSelection($questions, $targets);
        }
    }

    private function sanitizeAiResult(array $result, Collection $questions, array $targets): ?array
    {
        if (empty($result['selected_ids']) || !is_array($result['selected_ids'])) {
            Log::warning('[Groq] Sanitize: selected_ids missing');
            return null;
        }

        $validIds = $questions->pluck('id')->flip();

        // Strip hallucinated IDs from all arrays
        foreach (['easy_ids', 'medium_ids', 'hard_ids', 'selected_ids'] as $key) {
            if (!isset($result[$key]) || !is_array($result[$key])) {
                $result[$key] = [];
            }
            $result[$key] = array_values(array_unique(array_filter(
                $result[$key],
                fn($id) => isset($validIds[$id])
            )));
        }

        // ── Build a master "already used" tracker ──────────────────────────────
        // Collect all IDs currently assigned across all difficulty buckets
        $usedIds = array_unique(array_merge(
            $result['easy_ids'],
            $result['medium_ids'],
            $result['hard_ids']
        ));

        // ── Enforce exact counts per difficulty ────────────────────────────────
        foreach (['easy' => 'easy_ids', 'medium' => 'medium_ids', 'hard' => 'hard_ids'] as $diff => $key) {
            $needed  = $targets[$diff];
            $current = $result[$key];

            if (count($current) > $needed) {
                // Too many — trim and release the extra IDs back to the pool
                $released        = array_slice($current, $needed);
                $result[$key]    = array_slice($current, 0, $needed);
                $usedIds         = array_values(array_diff($usedIds, $released));

            } elseif (count($current) < $needed) {
                $shortage = $needed - count($current);

                // Try same difficulty first, then any difficulty
                $extras = $questions
                    ->where('difficulty_level', $diff)
                    ->whereNotIn('id', $usedIds)      // ← excludes ALL already used IDs
                    ->shuffle()
                    ->take($shortage);

                // If still not enough, pull from any difficulty
                if ($extras->count() < $shortage) {
                    $remaining = $shortage - $extras->count();
                    $moreExtras = $questions
                        ->whereNotIn('id', $usedIds)
                        ->whereNotIn('id', $extras->pluck('id')->toArray())
                        ->shuffle()
                        ->take($remaining);
                    $extras = $extras->merge($moreExtras);
                }

                $extraIds     = $extras->pluck('id')->toArray();
                $result[$key] = array_values(array_unique(array_merge($current, $extraIds)));
                $usedIds      = array_values(array_unique(array_merge($usedIds, $extraIds)));

                Log::info("[Groq] Sanitize: topped up '{$diff}' by " . count($extraIds) . " questions");
            }
        }

        // ── Rebuild selected_ids — no duplicates guaranteed ───────────────────
        $result['selected_ids'] = array_values(array_unique(array_merge(
            $result['easy_ids'],
            $result['medium_ids'],
            $result['hard_ids']
        )));

        // ── Recalculate total_marks ───────────────────────────────────────────
        $result['total_marks'] = $questions
            ->whereIn('id', $result['selected_ids'])
            ->sum('marks');

        Log::info('[Groq] Sanitized result', [
            'easy'   => count($result['easy_ids']),
            'medium' => count($result['medium_ids']),
            'hard'   => count($result['hard_ids']),
            'total'  => count($result['selected_ids']),
            'unique' => count(array_unique($result['selected_ids'])), // should match total
        ]);

        return $result;
    }

    private function prepareQuestionsForPrompt(Collection $questions): array
    {
        return $questions->map(fn($q) => [
            'id'               => $q->id,
            'difficulty_level' => $q->difficulty_level,
            'question_type'    => $q->question_type,
            'marks'            => $q->marks,
            // ── KEY FIX: Truncate question text to 100 chars ──
            // Sending full question text for 250 questions = huge token usage
            'preview'          => mb_substr(strip_tags($q->question_text), 0, 100),
        ])->values()->toArray();
    }

    private function buildPrompt(array $questions, array $targets, array $config): string
    {
        $questionsJson = json_encode($questions);   // no JSON_PRETTY_PRINT — saves tokens
        $total = $targets['easy'] + $targets['medium'] + $targets['hard'];

        return <<<PROMPT
        Select questions for an academic exam. Respond ONLY with valid JSON, no markdown.

        EXAM: {$config['title']} | Topic: {$config['topic']} | Question Type: {$config['question_type']} | Duration: {$config['duration_minutes']} min
        NEED: {$targets['easy']} easy, {$targets['medium']} medium, {$targets['hard']} hard (total: {$total})

        QUESTIONS:
        {$questionsJson}

        RULES:
        1. Select EXACTLY {$targets['easy']} easy, {$targets['medium']} medium, {$targets['hard']} hard
        2. If a difficulty is short, use nearest available and note in reasoning
        3. Prefer variety in question_type
        4. No duplicate or very similar previews

        RESPOND WITH ONLY THIS JSON:
        {"selected_ids":[integer IDs in exam order],"easy_ids":[IDs],"medium_ids":[IDs],"hard_ids":[IDs],"total_marks":integer,"reasoning":"brief explanation"}
        PROMPT;
    }

    private function fallbackRandomSelection(Collection $questions, array $targets): array
    {
        $selected = collect();

        foreach (['easy', 'medium', 'hard'] as $diff) {
            $pool = $questions->where('difficulty_level', $diff)->shuffle()->take($targets[$diff]);

            if ($pool->count() < $targets[$diff]) {
                $extras = $questions
                    ->whereNotIn('id', $selected->pluck('id'))
                    ->whereNotIn('id', $pool->pluck('id'))
                    ->shuffle()
                    ->take($targets[$diff] - $pool->count());
                $pool = $pool->merge($extras);
            }

            $selected = $selected->merge($pool);
        }

        $selectedIds = $selected->pluck('id')->shuffle()->values()->toArray();

        return [
            'selected_ids' => $selectedIds,
            'easy_ids'     => $selected->where('difficulty_level', 'easy')->pluck('id')->values()->toArray(),
            'medium_ids'   => $selected->where('difficulty_level', 'medium')->pluck('id')->values()->toArray(),
            'hard_ids'     => $selected->where('difficulty_level', 'hard')->pluck('id')->values()->toArray(),
            'total_marks'  => $selected->sum('marks'),
            'reasoning'    => 'Fallback: questions were selected randomly due to AI service unavailability.',
        ];
    }

    private function persistExamSet(array $config, array $targets, array $aiResult): AcaExamSet
    {
        $selectedIds = $aiResult['selected_ids'] ?? [];

        if (empty($selectedIds)) {
            throw new \RuntimeException('AI selection produced no questions. Please try again.');
        }

        return AcaExamSet::create([
            'title'            => $config['title'],
            'topic'            => $config['topic'],
            'question_type'    => $config['question_type'],
            'total_questions'  => $config['total_questions'],
            'easy_count'       => count($aiResult['easy_ids']   ?? []),
            'medium_count'     => count($aiResult['medium_ids'] ?? []),
            'hard_count'       => count($aiResult['hard_ids']   ?? []),
            'duration_minutes' => $config['duration_minutes'],
            'total_marks'      => $aiResult['total_marks'] ?? 0,
            'ai_reasoning'     => $aiResult['reasoning']   ?? null,
            'question_ids'     => $selectedIds,
            'custom_marks'     => null,   // ← ADD THIS
            'status'           => 'draft',
            'created_by'       => auth()->id(),
        ]);
    }
}
