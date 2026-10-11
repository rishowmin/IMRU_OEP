<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AcademicRememberMeTest extends TestCase
{
    use RefreshDatabase;

    public function test_academic_login_page_shows_remember_me_checkboxes(): void
    {
        $response = $this->get(route('academic.login'));

        $response->assertOk();
        $response->assertSee('id="student_remember"', false);
        $response->assertSee('id="teacher_remember"', false);
    }

    public function test_teacher_login_with_remember_me_stores_token_and_sets_cookie(): void
    {
        $teacher = $this->createTeacher();

        $response = $this->post(route('teacher.login'), [
            'email' => $teacher->email,
            'password' => 'password',
            'remember' => '1',
        ]);

        $this->assertAuthenticatedAs($teacher, 'teacher');
        $response->assertCookie(Auth::guard('teacher')->getRecallerName());
        $this->assertNotEmpty($teacher->fresh()->remember_token);
    }

    public function test_teacher_login_without_remember_me_does_not_store_token_or_cookie(): void
    {
        $teacher = $this->createTeacher();

        $response = $this->post(route('teacher.login'), [
            'email' => $teacher->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($teacher, 'teacher');
        $response->assertCookieMissing(Auth::guard('teacher')->getRecallerName());
        $this->assertNull($teacher->fresh()->remember_token);
    }

    public function test_student_login_with_remember_me_stores_token_and_sets_cookie(): void
    {
        $student = $this->createStudent();

        $response = $this->post(route('student.login'), [
            'email' => $student->email,
            'password' => 'password',
            'remember' => '1',
        ]);

        $this->assertAuthenticatedAs($student, 'student');
        $response->assertCookie(Auth::guard('student')->getRecallerName());
        $this->assertNotEmpty($student->fresh()->remember_token);
    }

    public function test_student_login_without_remember_me_does_not_store_token_or_cookie(): void
    {
        $student = $this->createStudent();

        $response = $this->post(route('student.login'), [
            'email' => $student->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($student, 'student');
        $response->assertCookieMissing(Auth::guard('student')->getRecallerName());
        $this->assertNull($student->fresh()->remember_token);
    }

    private function createTeacher(): Teacher
    {
        return Teacher::create([
            'first_name' => 'Test',
            'last_name' => 'Teacher',
            'email' => 'teacher@example.test',
            'password' => Hash::make('password'),
        ]);
    }

    private function createStudent(): Student
    {
        return Student::create([
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => 'student@example.test',
            'password' => Hash::make('password'),
        ]);
    }
}
