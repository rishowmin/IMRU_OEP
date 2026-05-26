<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaExam;
use App\Models\Student;

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

        return view('student.dashboard', compact(
            'enrolledCourses',
            'upcomingExamsList',
            'attemptHistory',
            'recentResults',
            'totalCourses',
            'upcomingExams',
            'totalAttempts',
            'averageScore',
        ));
    }
}
