<?php

namespace App\Services;

use App\Models\Academic\AcaQuestion;
use Illuminate\Support\Collection;

class ExamQuestionSelectionService
{
    private const QUESTION_TYPES = ['mcq_4', 'mcq_2', 'short_question', 'long_question', 'other'];
    private const DIFFICULTIES = ['easy', 'medium', 'hard', 'other'];
    private const QUESTION_TYPE_WEIGHTS = [
        'mcq_4' => 40,
        'mcq_2' => 30,
        'short_question' => 20,
        'long_question' => 10,
        'other' => 0,
    ];
    private const QUOTA_OVERFLOW_COST = 1000000;

    public function select(
        Collection $pool,
        int $requestedCount,
        array $excludedQuestionSets = [],
        ?Collection $distributionPool = null
    ): Collection
    {
        $distributionPool ??= $pool;
        $excludedSignatures = array_fill_keys(
            array_map(fn (array $questionIds) => $this->signature($questionIds), $excludedQuestionSets),
            true
        );

        $selected = $this->selectOnce($pool, $requestedCount, $distributionPool);
        for ($attempt = 0; $attempt < 20 && isset($excludedSignatures[$this->signature($selected->pluck('id')->all())]); $attempt++) {
            $selected = $this->selectOnce($pool, $requestedCount, $distributionPool);
        }

        return $selected;
    }

    private function selectOnce(Collection $pool, int $requestedCount, Collection $distributionPool): Collection
    {
        $count = min(max($requestedCount, 0), $pool->count());
        if ($count === 0) {
            return collect();
        }

        $cells = [];
        $typeCounts = array_fill_keys(self::QUESTION_TYPES, 0);
        $difficultyCounts = array_fill_keys(self::DIFFICULTIES, 0);
        $distributionTypeCounts = array_fill_keys(self::QUESTION_TYPES, 0);
        $distributionDifficultyCounts = array_fill_keys(self::DIFFICULTIES, 0);

        foreach ($pool as $question) {
            $type = $this->typeGroup($question);
            $difficulty = $this->difficultyGroup($question);

            $cells[$type][$difficulty][] = $question;
            $typeCounts[$type]++;
            $difficultyCounts[$difficulty]++;
        }

        foreach ($distributionPool as $question) {
            $distributionTypeCounts[$this->typeGroup($question)]++;
            $distributionDifficultyCounts[$this->difficultyGroup($question)]++;
        }

        $typeTargets = $this->allocateTargets($distributionTypeCounts, $count, self::QUESTION_TYPE_WEIGHTS);
        $difficultyTargets = $this->allocateTargets($distributionDifficultyCounts, $count);
        $selectionCounts = $this->allocateCells(
            $cells,
            $typeCounts,
            $difficultyCounts,
            $typeTargets,
            $difficultyTargets,
            $count
        );

        $selected = collect();
        foreach ($selectionCounts as $type => $difficulties) {
            foreach ($difficulties as $difficulty => $cellCount) {
                if ($cellCount > 0) {
                    $selected = $selected->merge(
                        collect($cells[$type][$difficulty] ?? [])->shuffle()->take($cellCount)
                    );
                }
            }
        }

        return $selected->shuffle()->values();
    }

    private function signature(array $questionIds): string
    {
        sort($questionIds);

        return implode(',', $questionIds);
    }

    private function typeGroup(AcaQuestion $question): string
    {
        return in_array($question->question_type, self::QUESTION_TYPES, true)
            ? $question->question_type
            : 'other';
    }

    private function difficultyGroup(AcaQuestion $question): string
    {
        return in_array($question->difficulty_level, self::DIFFICULTIES, true)
            ? $question->difficulty_level
            : 'other';
    }

    private function allocateTargets(array $categoryCounts, int $total, ?array $weights = null): array
    {
        $weights ??= $categoryCounts;
        $available = array_sum($weights);
        if ($available === 0) {
            return array_fill_keys(array_keys($categoryCounts), 0);
        }

        $targets = [];
        $remainders = [];
        $allocated = 0;

        foreach ($categoryCounts as $category => $categoryCount) {
            $exactTarget = ($weights[$category] ?? 0) * $total / $available;
            $targets[$category] = (int) floor($exactTarget);
            $remainders[$category] = $exactTarget - $targets[$category];
            $allocated += $targets[$category];
        }

        $order = array_keys($categoryCounts);
        usort($order, fn ($first, $second) => $remainders[$second] <=> $remainders[$first]);
        for ($index = 0; $index < $total - $allocated; $index++) {
            $targets[$order[$index]]++;
        }

        return $targets;
    }

    private function allocateCells(
        array $cells,
        array $typeCounts,
        array $difficultyCounts,
        array $typeTargets,
        array $difficultyTargets,
        int $requestedCount
    ): array {
        $source = 0;
        $typeNodes = [];
        $difficultyNodes = [];
        $nextNode = 1;

        foreach (self::QUESTION_TYPES as $type) {
            $typeNodes[$type] = $nextNode++;
        }
        foreach (self::DIFFICULTIES as $difficulty) {
            $difficultyNodes[$difficulty] = $nextNode++;
        }
        $sink = $nextNode;
        $graph = array_fill(0, $sink + 1, []);
        $cellEdges = [];

        foreach (self::QUESTION_TYPES as $type) {
            $node = $typeNodes[$type];
            $target = $typeTargets[$type] ?? 0;
            $this->addEdge($graph, $source, $node, $target, 0);
            $this->addEdge($graph, $source, $node, $typeCounts[$type] - $target, self::QUOTA_OVERFLOW_COST);
        }

        foreach (self::DIFFICULTIES as $difficulty) {
            $node = $difficultyNodes[$difficulty];
            $target = $difficultyTargets[$difficulty] ?? 0;
            $this->addEdge($graph, $node, $sink, $target, 0);
            $this->addEdge(
                $graph,
                $node,
                $sink,
                $difficultyCounts[$difficulty] - $target,
                self::QUOTA_OVERFLOW_COST
            );
        }

        foreach (self::QUESTION_TYPES as $type) {
            foreach (self::DIFFICULTIES as $difficulty) {
                $capacity = count($cells[$type][$difficulty] ?? []);
                if ($capacity === 0) {
                    continue;
                }

                $edgeIndex = count($graph[$typeNodes[$type]]);
                $this->addEdge(
                    $graph,
                    $typeNodes[$type],
                    $difficultyNodes[$difficulty],
                    $capacity,
                    random_int(0, 1000)
                );
                $cellEdges[] = [$type, $difficulty, $typeNodes[$type], $edgeIndex, $capacity];
            }
        }

        $flow = 0;
        while ($flow < $requestedCount) {
            $distances = array_fill(0, count($graph), PHP_INT_MAX);
            $previousNodes = array_fill(0, count($graph), -1);
            $previousEdges = array_fill(0, count($graph), -1);
            $distances[$source] = 0;

            for ($iteration = 0; $iteration < count($graph) - 1; $iteration++) {
                $changed = false;
                foreach ($graph as $node => $edges) {
                    if ($distances[$node] === PHP_INT_MAX) {
                        continue;
                    }

                    foreach ($edges as $edgeIndex => $edge) {
                        if ($edge['capacity'] <= 0) {
                            continue;
                        }

                        $distance = $distances[$node] + $edge['cost'];
                        if ($distance < $distances[$edge['to']]) {
                            $distances[$edge['to']] = $distance;
                            $previousNodes[$edge['to']] = $node;
                            $previousEdges[$edge['to']] = $edgeIndex;
                            $changed = true;
                        }
                    }
                }

                if (!$changed) {
                    break;
                }
            }

            if ($previousNodes[$sink] === -1) {
                break;
            }

            $pathCapacity = $requestedCount - $flow;
            for ($node = $sink; $node !== $source; $node = $previousNodes[$node]) {
                $pathCapacity = min(
                    $pathCapacity,
                    $graph[$previousNodes[$node]][$previousEdges[$node]]['capacity']
                );
            }

            for ($node = $sink; $node !== $source; $node = $previousNodes[$node]) {
                $previousNode = $previousNodes[$node];
                $edgeIndex = $previousEdges[$node];
                $reverseIndex = $graph[$previousNode][$edgeIndex]['reverse'];
                $graph[$previousNode][$edgeIndex]['capacity'] -= $pathCapacity;
                $graph[$node][$reverseIndex]['capacity'] += $pathCapacity;
            }

            $flow += $pathCapacity;
        }

        $selectionCounts = [];
        foreach ($cellEdges as [$type, $difficulty, $node, $edgeIndex, $capacity]) {
            $selectionCounts[$type][$difficulty] =
                $capacity - $graph[$node][$edgeIndex]['capacity'];
        }

        return $selectionCounts;
    }

    private function addEdge(array &$graph, int $from, int $to, int $capacity, int $cost): void
    {
        if ($capacity <= 0) {
            return;
        }

        $forwardIndex = count($graph[$from]);
        $reverseIndex = count($graph[$to]);
        $graph[$from][] = [
            'to' => $to,
            'reverse' => $reverseIndex,
            'capacity' => $capacity,
            'cost' => $cost,
        ];
        $graph[$to][] = [
            'to' => $from,
            'reverse' => $forwardIndex,
            'capacity' => 0,
            'cost' => -$cost,
        ];
    }
}
