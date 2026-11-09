<?php

namespace App\Services;

use App\Models\Academic\AcaExamSet;
use App\Services\AiQuestionGeneratorService;
use Illuminate\Support\Collection;

/**
 * Builds an exam set by asking the LLM to WRITE new questions.
 * Nothing is selected from the question bank any more.
 *
 * Replace the whole file: app/Services/AiExamGeneratorService.php
 * (needs app/Services/AiQuestionGeneratorService.php as well)
 */
class AiExamGeneratorService
{
    private string $model;

    public function __construct(private AiQuestionGeneratorService $questionGenerator)
    {
        $this->model = config('services.groq.model', 'openai/gpt-oss-120b');
    }

    public function generate(array $config): AcaExamSet
    {
        $diffTargets = $this->calculateTargetCounts(
            $config['total_questions'],
            $config['easy_percent'],
            $config['medium_percent'],
            $config['hard_percent'],
        );

        $qtTargets = $this->calculateQtTargets(
            $config['total_questions'],
            $config['question_type'] ?? 'All',
            $config['qtype1_percent'] ?? 100,
            $config['qtype2_percent'] ?? 0,
        );

        $created = $this->questionGenerator->generate(
            $config['topic'],
            $this->buildSpecs($diffTargets, $qtTargets),
            $config['extra_instructions'] ?? null,
            $this->auditFields()
        );

        return $this->persistExamSet($config, $created, $qtTargets);
    }

    // -------------------------------------------------------------------------
    // Targets
    // -------------------------------------------------------------------------

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

        $map = match ($questionType) {
            'All'        => ['qt1_types' => ['mcq_4', 'mcq_2'],     'qt2_types' => ['short_question', 'long_question']],
            'objective'  => ['qt1_types' => ['mcq_4'],              'qt2_types' => ['mcq_2']],
            'subjective' => ['qt1_types' => ['short_question'],     'qt2_types' => ['long_question']],
            default      => ['qt1_types' => [$questionType],        'qt2_types' => []],   // single type
        };

        return [
            'qt1_count' => $qt1Count,
            'qt2_count' => $qt2Count,
            'qt1_types' => $map['qt1_types'],
            'qt2_types' => $map['qt2_types'],
        ];
    }

    /**
     * One "slot" per question to write:
     * [['question_type' => 'mcq_4', 'difficulty_level' => 'easy'], ...]
     */
    private function buildSpecs(array $diffTargets, array $qtTargets): array
    {
        $levels = [];

        foreach ($diffTargets as $level => $count) {
            $levels = array_merge($levels, array_fill(0, $count, $level));
        }

        // Shuffle so difficulty is independent of question type (totals stay exact)
        shuffle($levels);

        $specs = [];

        foreach (['qt1', 'qt2'] as $key) {
            $types = $qtTargets["{$key}_types"];

            for ($n = 0; $n < $qtTargets["{$key}_count"]; $n++) {
                $specs[] = [
                    'question_type'    => $types[$n % count($types)],   // rotate when a bucket has 2 types
                    'difficulty_level' => array_shift($levels),
                ];
            }
        }

        return $specs;
    }

    // -------------------------------------------------------------------------
    // Persist
    // -------------------------------------------------------------------------

    private function auditFields(): array
    {
        return [
            'created_by'     => auth('admin')->check()   ? auth('admin')->id()   : null,
            'aca_created_by' => auth('teacher')->check() ? auth('teacher')->id() : null,
        ];
    }

    private function persistExamSet(array $config, Collection $created, array $qtTargets): AcaExamSet
    {
        $idsFor = fn (string $level) => $created->where('difficulty_level', $level)->pluck('id')->values()->all();

        $easy   = $idsFor('easy');
        $medium = $idsFor('medium');
        $hard   = $idsFor('hard');

        return AcaExamSet::create(array_merge($this->auditFields(), [
            'title'            => $config['title'],
            'topic'            => $config['topic'],
            'question_type'    => $config['question_type'],
            'total_questions'  => $created->count(),
            'easy_count'       => count($easy),
            'medium_count'     => count($medium),
            'hard_count'       => count($hard),
            'qt1_count'        => $created->whereIn('question_type', $qtTargets['qt1_types'])->count(),
            'qt2_count'        => $created->whereIn('question_type', $qtTargets['qt2_types'])->count(),
            'duration_minutes' => $config['duration_minutes'],
            'total_marks'      => $created->sum('marks'),
            'ai_reasoning'     => sprintf(
                'Generated %d new questions on "%s" with %s (easy %d, medium %d, hard %d).',
                $created->count(),
                $config['topic'],
                $this->model,
                count($easy),
                count($medium),
                count($hard)
            ),
            'question_ids'     => $created->pluck('id')->values()->all(),
            'custom_marks'     => null,
            'status'           => 'draft',
        ]));
    }
}
