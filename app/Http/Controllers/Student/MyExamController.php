<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Academic\Enrollment;
use App\Models\Academic\Exam;
use App\Models\Academic\ExamAnswer;
use App\Models\Academic\ExamAttempt;
use App\Models\Academic\ExamRuleMap;
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

        // Record exam start attempt
        ExamAttempt::updateOrCreate(
            [
                'student_id' => $student,
                'exam_id'    => $exam->id,
            ],
            [
                'started_at' => $now,
                'status'     => 'New',
                'is_active'  => true,
            ]
        );

        // Calculate remaining time
        $now = now();
        $secondsUntilEnd = $now->diffInSeconds($endDT, false);
        $durationSeconds = ($exam->exam_duration_min ?? 0) * 60;
        $remainingSeconds = min($secondsUntilEnd, $durationSeconds);

        // Load limited questions in random order
        $exam->load(['questions' => function($query) use ($exam) {
            $query->inRandomOrder()
                ->limit($exam->total_questions);
        }]);

        // Load mapped active rules for this exam
        $mappedRules = ExamRuleMap::where('exam_id', $exam->id)
            ->where('is_active', true)
            ->with(['rule' => fn($q) => $q->where('is_active', true)])
            ->get()
            ->filter(fn($map) => $map->rule)
            ->values();

        return view('student.myExams.answer_sheet', compact('exam', 'remainingSeconds', 'mappedRules'));
    }

    public function storeAnswer2(Request $request, Exam $exam)
    {
        $examId = $exam->id;
        $student = auth()->id();
        $isStopped = $request->input('stopped', '0') === '1';

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

        // Load limited questions for saving
        $exam->load(['questions' => function($query) use ($exam) {
            $query->orderBy('question_order', 'ASC')
                ->limit($exam->total_questions);
        }]);

        // Save answers
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

        ExamAttempt::updateOrCreate(
            [
                'student_id' => $student,
                'exam_id'    => $examId,
            ],
            [
                'submitted_at' => $now,
                'status'       => 'Old',
                'is_active'    => true,
            ]
        );

        if ($isStopped) {
            return redirect()->route('student.myExams')
                ->with('error', 'You stopped the exam. Your answers have been recorded.');
        }

        return redirect()->route('student.myExams')
            ->with('success', 'Exam has been submitted successfully!');
    }

    public function storeAnswer(Request $request, Exam $exam)
    {
        $examId = $exam->id;
        $student = auth()->id();
        $isStopped = $request->input('stopped', '0') === '1';
        $stopReason = $request->input('stop_reason', null); // new field

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
            'answers'     => ['nullable', 'array'],
            'answers.*'   => ['nullable', 'string'],
            'stop_reason' => ['nullable', 'string'],
        ]);

        $answers = $request->input('answers', []);

        // Load limited questions for saving
        $exam->load(['questions' => function($query) use ($exam) {
            $query->orderBy('question_order', 'ASC')
                ->limit($exam->total_questions);
        }]);

        // Save answers
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

        ExamAttempt::updateOrCreate(
            [
                'student_id' => $student,
                'exam_id'    => $examId,
            ],
            [
                'submitted_at' => $now,
                'status'       => 'Old',
                'is_active'    => true,
            ]
        );

        if ($isStopped) {
            $message = match($stopReason) {
                'back_button'      => 'Exam stopped: You pressed the browser back button.',
                'tab_switching'    => 'Exam stopped: You minimized or switched browser tabs during the exam.',
                'browser_maximized'=> 'Exam stopped: You restored the browser window.',
                'manual_stop'      => 'Exam stopped: You manually stopped the exam.',
                'url_change'       => 'Exam stopped: You attempted to navigate away from the exam.',
                'timer_expired'    => 'Exam auto-submitted: Your exam time has expired.',
                default            => 'Exam stopped. Your answers have been recorded.',
            };

            return redirect()->route('student.myExams')->with('error', $message);
        }

        return redirect()->route('student.myExams')
            ->with('success', 'Exam submitted successfully!');
    }

    public function viewResult(Exam $exam)
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

        // Ensure student has submitted this exam
        $isSubmitted = ExamAnswer::where('student_id', $student)
            ->where('exam_id', $exam->id)
            ->exists();

        if (!$isSubmitted) {
            return redirect()->route('student.myExams')
                ->with('error', 'You have not submitted this exam yet.');
        }

        // Load questions with student's answers
        $exam->load(['questions' => function($query) use ($exam) {
            $query->orderBy('question_order', 'ASC')
                ->limit($exam->total_questions);
        }]);

        $answers = ExamAnswer::where('student_id', $student)
            ->where('exam_id', $exam->id)
            ->get()
            ->keyBy('question_id'); // key by question_id for easy lookup in blade

        return view('student.myExams.view_result', compact('exam', 'answers'));
    }

    public function examRules(Exam $exam)
    {
        $mappedRules = ExamRuleMap::with('rule')
            ->where('exam_id', $exam->id)
            ->where('is_active', true)
            ->whereHas('rule', fn($q) => $q->where('is_active', true))
            ->get()
            ->groupBy(fn($map) => $map->rule->type);

        $instructions = $mappedRules->get('instruction', collect());
        $rules        = $mappedRules->get('rule', collect());

        return view('student.myExams.exam_rules', compact('exam', 'instructions', 'rules'));
    }
}
