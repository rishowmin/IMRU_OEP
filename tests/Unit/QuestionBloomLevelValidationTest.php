<?php

namespace Tests\Unit;

use App\Http\Requests\Academic\QuestionFormRequest;
use App\Http\Requests\Academic\QuestionLibraryFormRequest;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class QuestionBloomLevelValidationTest extends TestCase
{
    public function test_admin_question_create_and_edit_require_a_valid_bloom_level(): void
    {
        foreach (['admin.academic.questions.store', 'admin.academic.questions.update'] as $routeName) {
            $rules = ['bloom_level' => $this->questionRequestForRoute($routeName)->rules()['bloom_level']];

            $this->assertTrue(Validator::make([], $rules)->fails());
            $this->assertFalse(Validator::make(['bloom_level' => 'analyze'], $rules)->fails());
            $this->assertTrue(Validator::make(['bloom_level' => 'invalid'], $rules)->fails());
        }
    }

    public function test_exam_question_forms_can_omit_bloom_level(): void
    {
        $rules = ['bloom_level' => $this->questionRequestForRoute('teacher.exams.questionPaper.store')->rules()['bloom_level']];

        $this->assertFalse(Validator::make([], $rules)->fails());
    }

    public function test_teacher_question_create_and_edit_require_a_valid_bloom_level(): void
    {
        foreach (['teacher.questions.store', 'teacher.questions.update'] as $routeName) {
            $rules = ['bloom_level' => $this->questionRequestForRoute($routeName)->rules()['bloom_level']];

            $this->assertTrue(Validator::make([], $rules)->fails());
            $this->assertFalse(Validator::make(['bloom_level' => 'analyze'], $rules)->fails());
        }
    }

    public function test_question_bank_requires_a_valid_bloom_level(): void
    {
        $request = new QuestionLibraryFormRequest();
        $route = new Route(['POST'], '/', []);
        $route->name('admin.academic.questions.library.store');
        $request->setRouteResolver(fn () => $route);
        $rules = ['bloom_level' => $request->rules()['bloom_level']];

        $this->assertTrue(Validator::make([], $rules)->fails());
        $this->assertFalse(Validator::make(['bloom_level' => 'create'], $rules)->fails());
        $this->assertTrue(Validator::make(['bloom_level' => 'invalid'], $rules)->fails());
    }

    public function test_teacher_question_bank_requires_a_valid_bloom_level(): void
    {
        foreach (['teacher.questions.library.store', 'teacher.questions.library.update'] as $routeName) {
            $request = new QuestionLibraryFormRequest();
            $route = new Route(['POST'], '/', []);
            $route->name($routeName);
            $request->setRouteResolver(fn () => $route);
            $rules = ['bloom_level' => $request->rules()['bloom_level']];

            $this->assertTrue(Validator::make([], $rules)->fails());
            $this->assertFalse(Validator::make(['bloom_level' => 'apply'], $rules)->fails());
        }
    }

    private function questionRequestForRoute(string $routeName): QuestionFormRequest
    {
        $request = new QuestionFormRequest();
        $route = new Route(['POST'], '/', []);
        $route->name($routeName);
        $request->setRouteResolver(fn () => $route);

        return $request;
    }
}
