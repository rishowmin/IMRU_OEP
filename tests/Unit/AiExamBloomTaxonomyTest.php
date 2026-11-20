<?php

namespace Tests\Unit;

use App\Services\AiExamGeneratorService;
use App\Services\AiQuestionGeneratorService;
use ReflectionMethod;
use Tests\TestCase;

class AiExamBloomTaxonomyTest extends TestCase
{
    public function test_it_rounds_bloom_percentages_to_exact_question_counts(): void
    {
        $generator = new AiExamGeneratorService(new AiQuestionGeneratorService());
        $method = new ReflectionMethod(AiExamGeneratorService::class, 'calculateBloomTargets');
        $method->setAccessible(true);

        $targets = $method->invoke($generator, 10, [
            'remember' => 17,
            'understand' => 17,
            'apply' => 17,
            'analyze' => 17,
            'evaluate' => 16,
            'create' => 16,
        ]);

        $this->assertSame([
            'remember' => 2,
            'understand' => 1,
            'apply' => 2,
            'analyze' => 2,
            'evaluate' => 1,
            'create' => 2,
        ], $targets);
        $this->assertSame(10, array_sum($targets));
    }

    public function test_it_assigns_each_bloom_level_to_the_exact_number_of_question_specs(): void
    {
        $generator = new AiExamGeneratorService(new AiQuestionGeneratorService());
        $method = new ReflectionMethod(AiExamGeneratorService::class, 'buildSpecs');
        $method->setAccessible(true);

        $specs = $method->invoke(
            $generator,
            ['easy' => 5, 'medium' => 3, 'hard' => 2],
            [
                'qt1_count' => 5,
                'qt2_count' => 5,
                'qt1_types' => ['mcq_4'],
                'qt2_types' => ['long_question'],
            ],
            [
                'remember' => 2,
                'understand' => 2,
                'apply' => 2,
                'analyze' => 2,
                'evaluate' => 1,
                'create' => 1,
            ]
        );

        $this->assertCount(10, $specs);
        $this->assertSame([
            'analyze' => 2,
            'apply' => 2,
            'create' => 1,
            'evaluate' => 1,
            'remember' => 2,
            'understand' => 2,
        ], collect($specs)->countBy('bloom_level')->sortKeys()->all());
    }

    public function test_it_includes_the_requested_bloom_level_and_guidance_in_the_prompt(): void
    {
        $generator = new AiQuestionGeneratorService();
        $method = new ReflectionMethod(AiQuestionGeneratorService::class, 'buildPrompt');
        $method->setAccessible(true);

        $prompt = $method->invoke($generator, 'Computer Networks', [[
            'question_type' => 'long_question',
            'difficulty_level' => 'hard',
            'bloom_level' => 'evaluate',
        ]], null);

        $this->assertStringContainsString('Bloom level=Evaluate', $prompt);
        $this->assertStringContainsString('judge a claim or solution using explicit criteria', $prompt);
        $this->assertStringContainsString('Bloom level is independent of difficulty', $prompt);
    }
}
