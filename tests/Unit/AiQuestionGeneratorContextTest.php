<?php

namespace Tests\Unit;

use App\Services\AiQuestionGeneratorService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use ReflectionMethod;
use Tests\TestCase;

class AiQuestionGeneratorContextTest extends TestCase
{
    public function test_it_retries_a_413_with_reduced_syllabus_context(): void
    {
        config(['services.groq.key' => 'test-key']);
        $requestBodies = [];

        Http::fake(function (Request $request) use (&$requestBodies) {
            $requestBodies[] = $request->body();

            if (count($requestBodies) === 1) {
                return Http::response(['error' => 'Request too large'], 413);
            }

            return Http::response([
                'choices' => [
                    ['message' => ['content' => '{"questions":[]}']],
                ],
            ]);
        });

        $service = new AiQuestionGeneratorService();
        $requestBatch = new ReflectionMethod(AiQuestionGeneratorService::class, 'requestBatch');
        $requestBatch->setAccessible(true);

        $result = $requestBatch->invoke(
            $service,
            'Operating Systems',
            [['question_type' => 'mcq_4', 'difficulty_level' => 'easy']],
            str_repeat('Syllabus reference text ', 100)
        );

        $this->assertSame([], $result);
        $this->assertCount(2, $requestBodies);
        $this->assertLessThan(strlen($requestBodies[0]), strlen($requestBodies[1]));
    }
}
