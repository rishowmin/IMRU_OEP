<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaExam;
use App\Models\Student;
use Illuminate\Support\Str;

class StudDashboardController extends Controller
{
    public function dashboard()
    {
        /** @var Student $student */
        $student = auth()->user();

        // Courses the student is enrolled in
        $enrolledCourses = $student->courses()
            ->withCount('exams')
            ->with('instructor')
            ->latest()
            ->take(5)
            ->get();

        // All exam ids from enrolled courses
        $courseIds = $student->courses()->pluck('aca_courses.id');
        $examIds   = AcaExam::whereIn('course_id', $courseIds)->pluck('id');

        // Upcoming exams (today or future, not yet attempted)
        $attemptedExamIds  = $student->examAttempts()->pluck('exam_id');
        $upcomingExamsList = AcaExam::whereIn('id', $examIds)
            ->whereDate('exam_date', '>=', today())
            ->whereNotIn('id', $attemptedExamIds)
            ->with('course')
            ->orderBy('exam_date')
            ->take(5)
            ->get();

        // Attempt history
        $attemptHistory = $student->examAttempts()
            ->with('exam')
            ->latest()
            ->take(5)
            ->get();

        // Recent results
        $recentResults = $student->examResults()
            ->with('exam')
            ->latest()
            ->take(5)
            ->get();

        // Stat counts
        $totalCourses  = $enrolledCourses->count();
        $upcomingExams = $upcomingExamsList->count();
        $totalAttempts = $student->examAttempts()->count();
        $averageScore = $student->examResults()
            ->whereNotNull('percentage')
            ->avg('percentage');

        $averageScore = $averageScore ? round($averageScore) : 0;

        // ── Chart Data ──

        // 1. Pass vs Fail pie chart
        $passCount = $student->examResults()->where('is_pass', true)->count();
        $failCount = $student->examResults()->where('is_pass', false)->count();

        // 2. Score trend line chart — last 10 results ordered by date
        $scoreTrend = $student->examResults()
            ->with('exam')
            ->whereNotNull('percentage')
            ->latest()
            ->take(10)
            ->get()
            ->reverse()
            ->values()
            ->map(fn($r) => [
                'label' => Str::limit($r->exam->exam_title ?? 'Exam', 12),
                'score' => round($r->percentage),
            ]);

        // 3. Attempts per course bar chart
        $attemptsPerCourse = $student->examAttempts()
            ->with('exam.course')
            ->get()
            ->groupBy(fn($a) => $a->exam->course->course_code ?? 'N/A')
            ->map(fn($group) => $group->count())
            ->take(6);

        // 4. Score distribution bar chart — buckets: 0-20, 21-40, 41-60, 61-80, 81-100
        $allPercentages = $student->examResults()->whereNotNull('percentage')->pluck('percentage');
        $scoreDistribution = [
            '0–20'   => $allPercentages->filter(fn($p) => $p <= 20)->count(),
            '21–40'  => $allPercentages->filter(fn($p) => $p > 20 && $p <= 40)->count(),
            '41–60'  => $allPercentages->filter(fn($p) => $p > 40 && $p <= 60)->count(),
            '61–80'  => $allPercentages->filter(fn($p) => $p > 60 && $p <= 80)->count(),
            '81–100' => $allPercentages->filter(fn($p) => $p > 80)->count(),
        ];

        return view('student.dashboard', compact(
            'enrolledCourses',
            'upcomingExamsList',
            'attemptHistory',
            'recentResults',
            'totalCourses',
            'upcomingExams',
            'totalAttempts',
            'averageScore',
            'passCount',
            'failCount',
            'scoreTrend',
            'attemptsPerCourse',
            'scoreDistribution',
        ));
    }
}
