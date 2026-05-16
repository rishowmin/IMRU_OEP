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
        $questionType = $config['question_type'] ?? 'All';

        $questions = $this->fetchCandidateQuestions($config['topic'], $questionType);

        if ($questions->isEmpty()) {
            throw new \RuntimeException(
                "No active questions found for topic: \"{$config['topic']}\" and type: \"{$config['question_type']}\"."
            );
        }

        $diffTargets = $this->calculateTargetCounts(
            $config['total_questions'],
            $config['easy_percent'],
            $config['medium_percent'],
            $config['hard_percent'],
        );

        // Calculate question type targets (qt1/qt2)
        $qtTargets = $this->calculateQtTargets(
            $config['total_questions'],
            $questionType,
            $config['qtype1_percent'] ?? 100,
            $config['qtype2_percent'] ?? 0,
        );

        $aiResult = $this->callAiForSelection($questions, $diffTargets, $qtTargets, $config);

        return $this->persistExamSet($config, $diffTargets, $qtTargets, $aiResult);
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

    private function calculateQtTargets(int $total, string $questionType, int $qt1Pct, int $qt2Pct): array
    {
        $qt1Count = (int) round($total * $qt1Pct / 100);
        $qt2Count = $total - $qt1Count;

        $map = match($questionType) {
            'All'        => [
                'qt1_types' => ['mcq_4', 'mcq_2'],
                'qt2_types' => ['short_question', 'long_question'],
            ],
            'objective'  => [
                'qt1_types' => ['mcq_4'],
                'qt2_types' => ['mcq_2'],
            ],
            'subjective' => [
                'qt1_types' => ['short_question'],
                'qt2_types' => ['long_question'],
            ],
            default      => [  // single type — all qt1
                'qt1_types' => [$questionType],
                'qt2_types' => [],
            ],
        };

        return [
            'qt1_count' => $qt1Count,
            'qt2_count' => $qt2Count,
            'qt1_types' => $map['qt1_types'],
            'qt2_types' => $map['qt2_types'],
        ];
    }

    private function callAiForSelection(Collection $questions, array $diffTargets, array $qtTargets, array $config): array
    {
        try {
            if (empty($this->apiKey)) {
                Log::error('[Groq] API key is empty.');
                return $this->fallbackRandomSelection($questions, $diffTargets, $qtTargets);
            }

            $multiplier = 3; // send 3x the needed count per difficulty for variety
            $candidates = collect();
            $usedIds    = [];

            foreach (['qt1', 'qt2'] as $qtKey) {
                $qtCount = $qtTargets["{$qtKey}_count"];
                $qtTypes = $qtTargets["{$qtKey}_types"];

                if ($qtCount === 0 || empty($qtTypes)) continue;

                $qtPool = $questions->whereIn('question_type', $qtTypes)
                                    ->whereNotIn('id', $usedIds);

                // From this qt pool, try to maintain difficulty ratio
                foreach (['easy', 'medium', 'hard'] as $diff) {
                    $diffRatio  = $diffTargets[$diff] / max(array_sum($diffTargets), 1);
                    $needed     = (int) round($qtCount * $diffRatio);
                    $pool       = $qtPool->where('difficulty_level', $diff)
                                         ->shuffle()
                                         ->take($needed * $multiplier);
                    $candidates = $candidates->merge($pool);
                    $usedIds    = array_merge($usedIds, $pool->pluck('id')->toArray());
                }

                // Top up if we didn't get enough from this qt type
                $stillNeeded = ($qtCount * $multiplier) - $candidates->count();
                if ($stillNeeded > 0) {
                    $extras     = $qtPool->whereNotIn('id', $usedIds)->shuffle()->take($stillNeeded);
                    $candidates = $candidates->merge($extras);
                    $usedIds    = array_merge($usedIds, $extras->pluck('id')->toArray());
                }
            }

            // Deduplicate candidates
            $candidates = $candidates->unique('id')->values();

            $prompt = $this->buildPrompt(
                $this->prepareQuestionsForPrompt($candidates),
                $diffTargets,
                $qtTargets,
                $config
            );

            Log::info('[Groq] Sending request', [
                'model'          => $this->model,
                'key_hint'       => substr($this->apiKey, 0, 8) . '...' . substr($this->apiKey, -4),
                'total_in_bank'  => $questions->count(),
                'sent_to_ai'     => $candidates->count(),
                'diff_targets'  => $diffTargets,
                'qt_targets'    => ['qt1' => $qtTargets['qt1_count'], 'qt2' => $qtTargets['qt2_count']],
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
                return $this->fallbackRandomSelection($questions, $diffTargets, $qtTargets);
            }

            Log::info('[Groq] HTTP response received', ['http_status' => $response->status()]);

            $text = $response->json('choices.0.message.content', '');

            if (empty($text)) {
                Log::error('[Groq] Empty text in response', ['full_response' => $response->json()]);
                return $this->fallbackRandomSelection($questions, $diffTargets, $qtTargets);
            }

            // Strip markdown fences
            $text = preg_replace('/^```json\s*/i', '', trim($text));
            $text = preg_replace('/\s*```$/i', '', $text);
            $decoded = json_decode(trim($text), true);

            if (!is_array($decoded)) {
                Log::error('[Groq] JSON decode failed', ['raw_text' => substr($text, 0, 500)]);
                return $this->fallbackRandomSelection($questions, $diffTargets, $qtTargets);
            }

            $decoded = $this->sanitizeAiResult($decoded, $questions, $diffTargets);

            if ($decoded === null) {
                Log::error('[Groq] Result could not be sanitized');
                return $this->fallbackRandomSelection($questions, $diffTargets, $qtTargets);
            }

            $decoded = $this->sanitizeQtCounts($decoded, $questions, $qtTargets);

            // Count qt1/qt2 from actual selected questions (already set by sanitizeQtCounts)
            Log::info('[Groq] ✅ Success', [
                'selected_count' => count($decoded['selected_ids']),
                'qt1_count'      => $decoded['qt1_count'],
                'qt2_count'      => $decoded['qt2_count'],
                'reasoning'      => $decoded['reasoning'] ?? null,
            ]);

            return $decoded;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('[Groq] Connection failed', ['error' => $e->getMessage()]);
            return $this->fallbackRandomSelection($questions, $diffTargets, $qtTargets);
        } catch (\Exception $e) {
            Log::error('[Groq] Unexpected exception', ['error' => $e->getMessage()]);
            return $this->fallbackRandomSelection($questions, $diffTargets, $qtTargets);
        }
    }

    private function sanitizeAiResult(array $result, Collection $questions, array $diffTargets): ?array
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
            $needed  = $diffTargets[$diff];
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

    private function sanitizeQtCounts(array $result, Collection $questions, array $qtTargets): array
    {
        $qt1Types = $qtTargets['qt1_types'];
        $qt2Types = $qtTargets['qt2_types'];
        $qt1Need  = $qtTargets['qt1_count'];
        $qt2Need  = $qtTargets['qt2_count'];

        // Nothing to enforce for single types
        if (empty($qt2Types) || $qt2Need === 0) {
            $result['qt1_count'] = count($result['selected_ids']);
            $result['qt2_count'] = 0;
            return $result;
        }

        // Bucket current selected into qt1 / qt2 / other
        $selectedIds = $result['selected_ids'];
        $keyed       = $questions->whereIn('id', $selectedIds)->keyBy('id');

        $qt1Ids  = [];
        $qt2Ids  = [];

        foreach ($selectedIds as $id) {
            $q = $keyed[$id] ?? null;
            if (!$q) continue;
            if (in_array($q->question_type, $qt1Types)) {
                $qt1Ids[] = $id;
            } elseif (in_array($q->question_type, $qt2Types)) {
                $qt2Ids[] = $id;
            }
        }

        // ── Fix qt1 ───────────────────────────────────────────────────────────────
        if (count($qt1Ids) > $qt1Need) {
            // Too many qt1 — move excess to replacement from qt2 pool
            $excess    = array_slice($qt1Ids, $qt1Need);
            $qt1Ids    = array_slice($qt1Ids, 0, $qt1Need);
            $usedIds   = array_merge($qt1Ids, $qt2Ids);

            // Replace excess qt1 with qt2 questions
            $replacements = $questions
                ->whereIn('question_type', $qt2Types)
                ->whereNotIn('id', $usedIds)
                ->shuffle()
                ->take(count($excess));

            foreach ($replacements as $r) {
                $qt2Ids[] = $r->id;
            }

            // Remove excess qt1 from selected, add replacements
            $selectedIds = array_merge(
                array_diff($selectedIds, $excess),
                $replacements->pluck('id')->toArray()
            );

            Log::info('[Groq] Qt sanitize: moved ' . count($excess) . ' qt1→qt2');

        } elseif (count($qt1Ids) < $qt1Need) {
            // Too few qt1 — replace some qt2 with qt1 questions
            $shortage  = $qt1Need - count($qt1Ids);
            $usedIds   = array_merge($qt1Ids, $qt2Ids);

            $replacements = $questions
                ->whereIn('question_type', $qt1Types)
                ->whereNotIn('id', $usedIds)
                ->shuffle()
                ->take($shortage);

            $toRemoveFromQt2 = array_slice($qt2Ids, 0, count($replacements));
            $qt2Ids          = array_diff($qt2Ids, $toRemoveFromQt2);
            $qt1Ids          = array_merge($qt1Ids, $replacements->pluck('id')->toArray());

            $selectedIds = array_merge(
                array_diff($selectedIds, $toRemoveFromQt2),
                $replacements->pluck('id')->toArray()
            );

            Log::info('[Groq] Qt sanitize: added ' . count($replacements) . ' qt1 (was short)');
        }

        // ── Rebuild selected_ids cleanly ──────────────────────────────────────────
        $result['selected_ids'] = array_values(array_unique($selectedIds));

        // Recount from actual selected
        $finalSelected        = $questions->whereIn('id', $result['selected_ids']);
        $result['qt1_count']  = $finalSelected->whereIn('question_type', $qt1Types)->count();
        $result['qt2_count']  = $finalSelected->whereIn('question_type', $qt2Types)->count();
        $result['total_marks']= $finalSelected->sum('marks');

        Log::info('[Groq] Qt sanitized', [
            'qt1_need'  => $qt1Need,
            'qt2_need'  => $qt2Need,
            'qt1_final' => $result['qt1_count'],
            'qt2_final' => $result['qt2_count'],
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
            'preview'          => mb_substr(strip_tags($q->question_text), 0, 100),
        ])->values()->toArray();
    }

    private function buildPrompt(array $questions, array $diffTargets, array $qtTargets, array $config): string
    {
        $questionsJson = json_encode($questions);
        $total  = $diffTargets['easy'] + $diffTargets['medium'] + $diffTargets['hard'];
        $qt1Types = implode(', ', $qtTargets['qt1_types']);
        $qt2Types = implode(', ', $qtTargets['qt2_types']);

        return <<<PROMPT
Select questions for an academic exam. Respond ONLY with valid JSON, no markdown.

EXAM: {$config['title']} | Topic: {$config['topic']} | Duration: {$config['duration_minutes']} min
TOTAL NEEDED: {$total}

DIFFICULTY TARGETS:
- Easy: {$diffTargets['easy']}
- Medium: {$diffTargets['medium']}
- Hard: {$diffTargets['hard']}

QUESTION TYPE TARGETS:
- Type 1 [{$qt1Types}]: {$qtTargets['qt1_count']} questions
- Type 2 [{$qt2Types}]: {$qtTargets['qt2_count']} questions

AVAILABLE QUESTIONS:
{$questionsJson}

RULES:
1. Select EXACTLY {$diffTargets['easy']} easy, {$diffTargets['medium']} medium, {$diffTargets['hard']} hard
2. Select EXACTLY {$qtTargets['qt1_count']} questions from type [{$qt1Types}] and {$qtTargets['qt2_count']} from [{$qt2Types}]
3. If targets cannot be met exactly, get as close as possible and note in reasoning
4. No duplicate or very similar question previews

RESPOND WITH ONLY THIS JSON:
{"selected_ids":[integer IDs in exam order],"easy_ids":[IDs],"medium_ids":[IDs],"hard_ids":[IDs],"total_marks":integer,"reasoning":"brief explanation"}
PROMPT;
    }

    private function fallbackRandomSelection(Collection $questions, array $diffTargets, array $qtTargets): array
    {
        $selected = collect();
        $usedIds  = [];

        foreach (['qt1', 'qt2'] as $qtKey) {
            $needed = $qtTargets["{$qtKey}_count"];
            $types  = $qtTargets["{$qtKey}_types"];
            if ($needed === 0 || empty($types)) continue;

            $alreadySelected = $selected->pluck('id')->toArray();

            $qtPool = $questions
                ->whereIn('question_type', $types)
                ->whereNotIn('id', $alreadySelected);

            $bucketSelected = collect();

            // Maintain difficulty ratio within this qt bucket
            foreach (['easy', 'medium', 'hard'] as $diff) {
                $diffTotal = max(array_sum($diffTargets), 1);
                $take      = (int) round($needed * $diffTargets[$diff] / $diffTotal);
                $pool      = $qtPool
                    ->where('difficulty_level', $diff)
                    ->whereNotIn('id', $bucketSelected->pluck('id')->toArray())
                    ->shuffle()
                    ->take($take);
                $bucketSelected = $bucketSelected->merge($pool);
            }

            // Top up if short
            if ($bucketSelected->count() < $needed) {
                $extras = $qtPool
                    ->whereNotIn('id', $bucketSelected->pluck('id')->toArray())
                    ->shuffle()
                    ->take($needed - $bucketSelected->count());
                $bucketSelected = $bucketSelected->merge($extras);
            }

            $selected = $selected->merge($bucketSelected);
        }

        $selectedIds = $selected->unique('id')->pluck('id')->shuffle()->values()->toArray();

        return [
            'selected_ids' => $selectedIds,
            'easy_ids'     => $selected->where('difficulty_level', 'easy')->pluck('id')->values()->toArray(),
            'medium_ids'   => $selected->where('difficulty_level', 'medium')->pluck('id')->values()->toArray(),
            'hard_ids'     => $selected->where('difficulty_level', 'hard')->pluck('id')->values()->toArray(),
            'qt1_count'    => $selected->whereIn('question_type', $qtTargets['qt1_types'])->count(),
            'qt2_count'    => $selected->whereIn('question_type', $qtTargets['qt2_types'])->count(),
            'total_marks'  => $selected->sum('marks'),
            'reasoning'    => 'Fallback: questions were selected randomly due to AI service unavailability.',
        ];
    }

    private function persistExamSet(array $config, array $diffTargets, array $qtTargets, array $aiResult): AcaExamSet
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
            'qt1_count'        => $aiResult['qt1_count'] ?? 0,
            'qt2_count'        => $aiResult['qt2_count'] ?? 0,
            'duration_minutes' => $config['duration_minutes'],
            'total_marks'      => $aiResult['total_marks'] ?? 0,
            'ai_reasoning'     => $aiResult['reasoning']   ?? null,
            'question_ids'     => $selectedIds,
            'custom_marks'     => null,
            'status'           => 'draft',
            'created_by'       => auth()->id(),
        ]);
    }
}



