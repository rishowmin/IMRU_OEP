<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaExamAnswer;
use App\Models\Student;
use Carbon\Carbon;

class StudMyCourseController extends Controller
{
    public function index()
    {
        /** @var Student $student */
        $student = auth('student')->user();
        $serialNo = 1;
        $enrolledCourses = $student->courses()
            ->with(['teacher', 'exams'])
            ->withCount('exams')
            ->latest()
            ->get();

        $submittedExamIds = AcaExamAnswer::query()
            ->where('student_id', $student->id)
            ->whereIn('exam_id', $enrolledCourses->flatMap(
                fn ($course) => $course->exams->pluck('id')
            )->unique())
            ->distinct()
            ->pluck('exam_id')
            ->flip();

        $now = now();

        foreach ($enrolledCourses as $course) {
            $course->setAttribute('ongoing_exams_count', 0);
            $course->setAttribute('upcoming_exams_count', 0);
            $course->setAttribute('submitted_exams_count', 0);

            foreach ($course->exams as $exam) {
                if (isset($submittedExamIds[$exam->id])) {
                    $course->setAttribute(
                        'submitted_exams_count',
                        $course->submitted_exams_count + 1
                    );
                    continue;
                }

                if (!$exam->exam_date || !$exam->start_time || !$exam->end_time) {
                    $course->setAttribute(
                        'upcoming_exams_count',
                        $course->upcoming_exams_count + 1
                    );
                    continue;
                }

                $examDate = $exam->exam_date->toDateString();
                $start = Carbon::parse($examDate . ' ' . Carbon::parse($exam->start_time)->format('H:i:s'));
                $end = Carbon::parse($examDate . ' ' . Carbon::parse($exam->end_time)->format('H:i:s'));

                if ($now->between($start, $end)) {
                    $course->setAttribute(
                        'ongoing_exams_count',
                        $course->ongoing_exams_count + 1
                    );
                } elseif ($now->lt($start)) {
                    $course->setAttribute(
                        'upcoming_exams_count',
                        $course->upcoming_exams_count + 1
                    );
                }
            }
        }

        return view('student.modules.myCourses.index', compact('serialNo', 'enrolledCourses'));
    }
}
