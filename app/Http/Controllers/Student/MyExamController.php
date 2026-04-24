<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Academic\Enrollment;
use App\Models\Academic\Exam;
use App\Models\Academic\ExamAnswer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MyExamController extends Controller
{
    public function index()
    {
        $myCourseEnrollment = Enrollment::where('student_id', auth()->id())
            ->pluck('course_id')
            ->toArray();

        $myExamList = Exam::whereIn('course_id', $myCourseEnrollment)
            ->withCount('questions') // adds questions_count to each exam
            ->orderBy('id', 'ASC')
            ->get();

        $submittedExamIds = ExamAnswer::where('student_id', auth()->id())
            ->pluck('exam_id')
            ->unique()
            ->toArray();

        return view('student.myExams.index', compact('myExamList', 'myCourseEnrollment', 'submittedExamIds'));
    }

    public function show(Exam $exam)
    {
        $student = auth()->id();

        // Ensure student is enrolled in this exam's course
        $isEnrolled = Enrollment::where('student_id', $student)
            ->where('course_id', $exam->course_id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('student.myExams')
                ->with('error', 'You are not enrolled in this course.');
        }

        $isSubmitted = ExamAnswer::where('student_id', $student)
            ->where('exam_id', $exam->id)
            ->exists();

        return view('student.myExams.show', compact('exam', 'isSubmitted'));
    }

    public function startExam(Exam $exam)
    {
        $student = auth()->id();

        // Ensure student is enrolled
        $isEnrolled = Enrollment::where('student_id', $student)
            ->where('course_id', $exam->course_id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('student.myExams')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Check exam is ongoing
        $now = now();
        $startDT = Carbon::parse(
            $exam->exam_date->toDateString() . ' ' . Carbon::parse($exam->start_time)->format('H:i:s')
        );
        $endDT = Carbon::parse(
            $exam->exam_date->toDateString() . ' ' . Carbon::parse($exam->end_time)->format('H:i:s')
        );

        if (!$now->between($startDT, $endDT)) {
            return redirect()->route('student.myExams')
                ->with('error', 'This exam is not currently open.');
        }

        // Block if no questions set
        if (!$exam->total_questions || $exam->total_questions == 0) {
            return redirect()->route('student.myExams')
                ->with('error', 'This exam has no questions set yet.');
        }

        // Block re-entry if already submitted
        $alreadySubmitted = ExamAnswer::where('student_id', $student)
            ->where('exam_id', $exam->id)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->route('student.myExams')
                ->with('error', 'You have already submitted this exam.');
        }

        // Eager load questions
        $exam->load('questions');

        return view('student.myExams.answer_sheet', compact('exam'));
    }

    public function storeAnswer(Request $request, Exam $exam)
    {
        $examId = $exam->id;
        $student = auth()->id();

        // Ensure student is enrolled
        $isEnrolled = Enrollment::where('student_id', $student)
            ->where('course_id', $exam->course_id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('student.myExams')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Validate exam is still ongoing
        $now = now();
        $startDT = Carbon::parse(
            $exam->exam_date->toDateString() . ' ' . Carbon::parse($exam->start_time)->format('H:i:s')
        );
        $endDT = Carbon::parse(
            $exam->exam_date->toDateString() . ' ' . Carbon::parse($exam->end_time)->format('H:i:s')
        );

        if (!$now->between($startDT, $endDT)) {
            return redirect()->route('student.myExams')
                ->with('error', 'Exam is not available for submission.');
        }

        // Prevent re-submission
        $alreadySubmitted = ExamAnswer::where('student_id', $student)
            ->where('exam_id', $examId)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->route('student.myExams')
                ->with('error', 'You have already submitted this exam.');
        }

        // Validate answers
        $request->validate([
            'answers'   => ['nullable', 'array'],
            'answers.*' => ['nullable', 'string'],
        ]);

        $answers = $request->input('answers', []);

        // Save each answer using insert for performance
        $records = [];
        $now = now();

        foreach ($exam->questions as $question) {
            $records[] = [
                'student_id'  => $student,
                'exam_id'     => $examId,
                'question_id' => $question->id,
                'answer'      => $answers[$question->id] ?? null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        ExamAnswer::insert($records);

        return redirect()->route('student.myExams')
            ->with('success', 'Exam has been submitted successfully!');
    }

    public function viewResult(Exam $exam)
    {
        $student = auth('student')->user();

        $answers = ExamAnswer::where('student_id', $student->id)
                    ->where('exam_id', $exam->id)
                    ->get();

        return view('student.myExams.view_result', compact('exam', 'answers'));
    }
}
