<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaCourse;
use App\Models\Academic\AcaEnrollment;
use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaExamAnswer;
use App\Models\Academic\AcaExamAttempt;
use App\Models\Academic\AcaExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechDashboardController extends Controller
{
    public function dashboard()
    {
        $teacher   = Auth::guard('teacher')->user();
        $teacherId = $teacher->id;

        // ── Courses taught by this teacher ──────────────────────────────
        $myCourseIds = AcaCourse::where('teacher_id', $teacherId)->pluck('id');
        $myCourses   = $myCourseIds->count();

        // ── Exams created by this teacher ───────────────────────────────
        $myExamIds = AcaExam::where('aca_created_by', $teacherId)->whereIn('course_id', $myCourseIds)->pluck('id');
        $myExams   = $myExamIds->count();

        // ── Total enrolled students across teacher's courses ─────────────
        $totalEnrolledStudents = AcaEnrollment::where('aca_created_by', $teacherId)->whereIn('course_id', $myCourseIds)
            ->where('is_active', true)
            ->count();

        // ── Pending subjective reviews (short + long questions) ──────────
        $pendingReviews = AcaExamAnswer::whereIn('exam_id', $myExamIds)
            ->whereHas('question', fn($q) => $q->whereIn('question_type', ['short_question', 'long_question']))
            ->whereDoesntHave('reviewAnswer')
            ->count();

        // ── Results scoped to teacher's exams ────────────────────────────
        $resultsQuery  = AcaExamResult::whereIn('exam_id', $myExamIds);
        $totalResults  = (clone $resultsQuery)->count();
        $passCount     = (clone $resultsQuery)->where('is_pass', 1)->count();
        $failCount     = (clone $resultsQuery)->where('is_pass', 0)->count();
        $avgScore      = $totalResults
            ? round((clone $resultsQuery)->avg('percentage'), 1)
            : 0;
        $passRate      = $totalResults
            ? round(($passCount / $totalResults) * 100, 1)
            : 0;

        // ── Monthly pass/fail trend (last 6 months) ──────────────────────
        $monthlyResults = collect(range(5, 0))->map(function ($i) use ($myExamIds) {
            $month = now()->subMonths($i);
            $base  = AcaExamResult::whereIn('exam_id', $myExamIds)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month);

            return [
                'month' => $month->format('M Y'),
                'pass'  => (clone $base)->where('is_pass', 1)->count(),
                'fail'  => (clone $base)->where('is_pass', 0)->count(),
            ];
        });

        // ── Exams per Course (bar chart) ─────────────────────────────────
        $examsPerCourse = AcaCourse::withCount(['exams' => fn($q) => $q->where('aca_created_by', $teacherId)])
            ->where('teacher_id', $teacherId)
            ->get()
            ->map(fn($c) => [
                'label' => $c->course_code,
                'count' => $c->exams_count,
            ]);

        // ── Upcoming / ongoing exams ─────────────────────────────────────
        $upcomingExams = AcaExam::with('course')
            ->whereIn('course_id', $myCourseIds)
            ->where('exam_date', '>=', now()->toDateString())
            ->where('deleted_at', null)
            ->where('aca_created_by', $teacherId)
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->take(6)
            ->get();

        // ── Pending reviews list ─────────────────────────────────────────
        $pendingReviewList = AcaExamAnswer::with(['student', 'exam', 'question'])
            ->whereIn('exam_id', $myExamIds)
            ->whereHas('question', fn($q) => $q->whereIn('question_type', ['short_question', 'long_question']))
            ->whereDoesntHave('reviewAnswer')
            ->latest()
            ->take(6)
            ->get();

        // ── Recent exam attempts ─────────────────────────────────────────
        $recentAttempts = AcaExamAttempt::with(['student', 'exam'])
            ->whereIn('exam_id', $myExamIds)
            ->latest()
            ->take(6)
            ->get();

        // ── Per-course stats ─────────────────────────────────────────────
        $courseStats = AcaCourse::withCount(['enrollments', 'exams'])
            ->where('teacher_id', $teacherId)
            ->get()
            ->map(function ($course) use ($myExamIds) {
                $courseExamIds = AcaExam::where('course_id', $course->id)->pluck('id');
                $total         = AcaExamResult::whereIn('exam_id', $courseExamIds)->count();
                $pass          = AcaExamResult::whereIn('exam_id', $courseExamIds)
                    ->where('is_pass', 1)->count();

                $course->pass_rate = $total ? round(($pass / $total) * 100, 1) : null;
                return $course;
            });

        return view('teacher.dashboard', compact(
            'myCourses',
            'myExams',
            'totalEnrolledStudents',
            'pendingReviews',
            'totalResults',
            'passCount',
            'failCount',
            'avgScore',
            'passRate',
            'monthlyResults',
            'examsPerCourse',
            'upcomingExams',
            'pendingReviewList',
            'recentAttempts',
            'courseStats',
        ));
    }
}
