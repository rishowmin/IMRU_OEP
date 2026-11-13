<?php

namespace Tests\Unit;

use App\Models\Academic\AcaQuestion;
use App\Services\ExamQuestionSelectionService;
use PHPUnit\Framework\TestCase;

class ExamQuestionSelectionServiceTest extends TestCase
{
    public function test_it_preserves_both_question_type_and_difficulty_distribution(): void
    {
        $pool = collect();
        $typeLimits = [12 => 'mcq_4', 21 => 'mcq_2', 27 => 'short_question', 30 => 'long_question'];
        for ($id = 1; $id <= 30; $id++) {
            $type = 'long_question';
            foreach ($typeLimits as $limit => $typeName) {
                if ($id <= $limit) {
                    $type = $typeName;
                    break;
                }
            }
            $difficulty = $id <= 18 ? 'easy' : ($id <= 25 ? 'medium' : 'hard');
            $pool->push((new AcaQuestion())->forceFill([
                'id' => $id,
                'question_type' => $type,
                'difficulty_level' => $difficulty,
            ]));
        }

        $selected = (new ExamQuestionSelectionService())->select($pool, 10);

        $this->assertCount(10, $selected);
        $this->assertCount(10, $selected->unique('id'));
        $this->assertSame(4, $selected->where('question_type', 'mcq_4')->count());
        $this->assertSame(3, $selected->where('question_type', 'mcq_2')->count());
        $this->assertSame(2, $selected->where('question_type', 'short_question')->count());
        $this->assertSame(1, $selected->where('question_type', 'long_question')->count());
        $this->assertSame(6, $selected->where('difficulty_level', 'easy')->count());
        $this->assertSame(2, $selected->where('difficulty_level', 'medium')->count());
        $this->assertSame(2, $selected->where('difficulty_level', 'hard')->count());
    }

    public function test_it_handles_short_category_pools_without_duplicates(): void
    {
        $pool = collect();
        for ($id = 1; $id <= 2; $id++) {
            $pool->push((new AcaQuestion())->forceFill([
                'id' => $id,
                'question_type' => 'mcq_4',
                'difficulty_level' => 'easy',
            ]));
        }
        for ($id = 3; $id <= 12; $id++) {
            $pool->push((new AcaQuestion())->forceFill([
                'id' => $id,
                'question_type' => 'short_question',
                'difficulty_level' => $id <= 8 ? 'easy' : ($id <= 10 ? 'medium' : 'hard'),
            ]));
        }

        $selected = (new ExamQuestionSelectionService())->select($pool, 10);

        $this->assertCount(10, $selected);
        $this->assertCount(10, $selected->unique('id'));
        $this->assertCount(2, $selected->where('question_type', 'mcq_4'));
        $this->assertCount(8, $selected->where('question_type', 'short_question'));
    }

    public function test_it_avoids_question_sets_already_assigned_to_other_students(): void
    {
        $pool = collect();
        for ($id = 1; $id <= 30; $id++) {
            $pool->push((new AcaQuestion())->forceFill([
                'id' => $id,
                'question_type' => $id <= 21 ? 'mcq_4' : 'short_question',
                'difficulty_level' => $id <= 18 ? 'easy' : ($id <= 25 ? 'medium' : 'hard'),
            ]));
        }

        $service = new ExamQuestionSelectionService();
        $firstSelection = $service->select($pool, 10);
        $nextSelection = $service->select($pool, 10, [$firstSelection->pluck('id')->all()]);

        $this->assertNotSame(
            $firstSelection->pluck('id')->sort()->values()->all(),
            $nextSelection->pluck('id')->sort()->values()->all()
        );
    }

    public function test_it_uses_the_original_bank_balance_when_questions_are_inactive(): void
    {
        $distributionPool = collect();
        $activePool = collect();
        for ($id = 1; $id <= 30; $id++) {
            $type = match (true) {
                $id <= 12 => 'mcq_4',
                $id <= 21 => 'mcq_2',
                $id <= 27 => 'short_question',
                default => 'long_question',
            };
            $difficulty = $id <= 18 ? 'easy' : ($id <= 25 ? 'medium' : 'hard');
            $question = (new AcaQuestion())->forceFill([
                'id' => $id,
                'question_type' => $type,
                'difficulty_level' => $difficulty,
            ]);
            $distributionPool->push($question);
            if ($type !== 'long_question') {
                $activePool->push($question);
            }
        }

        $selected = (new ExamQuestionSelectionService())->select($activePool, 5, [], $distributionPool);

        $this->assertCount(5, $selected);
        $this->assertSame(2, $selected->where('question_type', 'mcq_4')->count());
        $this->assertSame(2, $selected->where('question_type', 'mcq_2')->count());
        $this->assertSame(0, $selected->where('question_type', 'long_question')->count());
        $this->assertSame(1, $selected->where('question_type', 'short_question')->count());
        $this->assertSame(3, $selected->where('difficulty_level', 'easy')->count());
        $this->assertSame(1, $selected->where('difficulty_level', 'medium')->count());
        $this->assertSame(1, $selected->where('difficulty_level', 'hard')->count());
    }
}
