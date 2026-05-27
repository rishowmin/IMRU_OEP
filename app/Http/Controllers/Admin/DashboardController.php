<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaCourse;
use App\Models\Academic\AcaEnrollment;
use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaExamResult;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\Academic\AcaExamAttempt;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // ── Stat Cards ──
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalCourses  = AcaCourse::count();
        $totalExams    = AcaExam::count();

        // ── Exam Results Overview ──
        $totalResults = AcaExamResult::count();
        $passCount    = AcaExamResult::where('is_pass', true)->count();
        $failCount    = AcaExamResult::where('is_pass', false)->count();
        $passRate     = $totalResults > 0 ? round(($passCount / $totalResults) * 100) : 0;
        $avgScore     = AcaExamResult::whereNotNull('percentage')->avg('percentage');
        $avgScore     = $avgScore ? round($avgScore) : 0;

        // ── Recent Exam Attempts ──
        $recentAttempts = AcaExamAttempt::with(['student', 'exam'])
            ->latest()
            ->take(5)
            ->get();

        // ── Upcoming Exams ──
        $upcomingExams = AcaExam::with('course')
            ->whereDate('exam_date', '>=', today())
            ->orderBy('exam_date')
            ->take(5)
            ->get();

        // ── Per-Course Stats ──
        $courseStats = AcaCourse::withCount(['enrollments', 'exams'])
            ->with('instructor')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($course) {
                $resultIds = AcaExam::where('course_id', $course->id)->pluck('id');
                $total     = AcaExamResult::whereIn('exam_id', $resultIds)->count();
                $pass      = AcaExamResult::whereIn('exam_id', $resultIds)->where('is_pass', true)->count();
                $course->pass_rate = $total > 0 ? round(($pass / $total) * 100) : null;
                return $course;
            });

        // ── Recent Enrollments ──
        $recentEnrollments = AcaEnrollment::with(['student', 'course'])
            ->latest()
            ->take(5)
            ->get();

        // ── Chart: Pass vs Fail over last 6 months ──
        $monthlyResults = AcaExamResult::selectRaw("
                DATE_FORMAT(created_at, '%b %Y') as month,
                SUM(is_pass = 1) as pass,
                SUM(is_pass = 0) as fail
            ")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw("DATE_FORMAT(created_at, '%b %Y')")
            ->orderBy('created_at')
            ->get();

        // ── Chart: Exams per course (top 6) ──
        $examsPerCourse = AcaCourse::withCount('exams')
            ->orderByDesc('exams_count')
            ->take(6)
            ->get()
            ->map(fn($c) => ['label' => $c->course_code, 'count' => $c->exams_count]);

        return view('admin.dashboard', compact(
            'totalStudents', 'totalTeachers', 'totalCourses', 'totalExams',
            'totalResults', 'passCount', 'failCount', 'passRate', 'avgScore',
            'recentAttempts', 'upcomingExams', 'courseStats', 'recentEnrollments',
            'monthlyResults', 'examsPerCourse',
        ));
    }

    public function academicDashboard()
    {
        // ── Stat Cards ──
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalCourses  = AcaCourse::count();
        $totalExams    = AcaExam::count();

        // ── Exam Results Overview ──
        $totalResults = AcaExamResult::count();
        $passCount    = AcaExamResult::where('is_pass', true)->count();
        $failCount    = AcaExamResult::where('is_pass', false)->count();
        $passRate     = $totalResults > 0 ? round(($passCount / $totalResults) * 100) : 0;
        $avgScore     = AcaExamResult::whereNotNull('percentage')->avg('percentage');
        $avgScore     = $avgScore ? round($avgScore) : 0;

        // ── Recent Exam Attempts ──
        $recentAttempts = AcaExamAttempt::with(['student', 'exam'])
            ->latest()
            ->take(5)
            ->get();

        // ── Upcoming Exams ──
        $upcomingExams = AcaExam::with('course')
            ->whereDate('exam_date', '>=', today())
            ->orderBy('exam_date')
            ->take(5)
            ->get();

        // ── Per-Course Stats ──
        $courseStats = AcaCourse::withCount(['enrollments', 'exams'])
            ->with('instructor')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($course) {
                $resultIds = AcaExam::where('course_id', $course->id)->pluck('id');
                $total     = AcaExamResult::whereIn('exam_id', $resultIds)->count();
                $pass      = AcaExamResult::whereIn('exam_id', $resultIds)->where('is_pass', true)->count();
                $course->pass_rate = $total > 0 ? round(($pass / $total) * 100) : null;
                return $course;
            });

        // ── Recent Enrollments ──
        $recentEnrollments = AcaEnrollment::with(['student', 'course'])
            ->latest()
            ->take(5)
            ->get();

        // ── Chart: Pass vs Fail over last 6 months ──
        $monthlyResults = AcaExamResult::selectRaw("
                DATE_FORMAT(created_at, '%b %Y') as month,
                SUM(is_pass = 1) as pass,
                SUM(is_pass = 0) as fail
            ")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw("DATE_FORMAT(created_at, '%b %Y')")
            ->orderBy('created_at')
            ->get();

        // ── Chart: Exams per course (top 6) ──
        $examsPerCourse = AcaCourse::withCount('exams')
            ->orderByDesc('exams_count')
            ->take(6)
            ->get()
            ->map(fn($c) => ['label' => $c->course_code, 'count' => $c->exams_count]);

        return view('admin.academic.dashboard', compact(
            'totalStudents', 'totalTeachers', 'totalCourses', 'totalExams',
            'totalResults', 'passCount', 'failCount', 'passRate', 'avgScore',
            'recentAttempts', 'upcomingExams', 'courseStats', 'recentEnrollments',
            'monthlyResults', 'examsPerCourse',
        ));
    }

    public function professionalDashboard()
    {
        return view('admin.professional.dashboard');
    }
}
