<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaEnrollment;
use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaExamAnswer;
use App\Models\Academic\AcaExamAttempt;
use App\Models\Academic\AcaExamResult;
use App\Models\Academic\AcaExamRuleMap;
use App\Models\Academic\AcaQuestion;
use App\Services\ExamGradingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudMyExamController extends Controller
{
    protected ExamGradingService $gradingService;

    public function __construct(ExamGradingService $gradingService)
    {
        $this->gradingService = $gradingService;
    }

    public function index()
    {
        $myCourseEnrollment = AcaEnrollment::where('student_id', auth()->id())
            ->pluck('course_id')
            ->toArray();

        $myExamList = AcaExam::whereIn('course_id', $myCourseEnrollment)
            ->withCount('questions') // adds questions_count to each exam
            ->orderBy('id', 'ASC')
            ->get();

        $submittedExamIds = AcaExamAnswer::where('student_id', auth()->id())
            ->pluck('exam_id')
            ->unique()
            ->toArray();

        return view('student.myExams.index', compact('myExamList', 'myCourseEnrollment', 'submittedExamIds'));
    }

    public function show(AcaExam $exam)
    {
        $student = auth()->id();

        $isEnrolled = AcaEnrollment::where('student_id', $student)
            ->where('course_id', $exam->course_id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('student.myExams')
                ->with('error', 'You are not enrolled in this course.');
        }

        $isSubmitted = AcaExamAnswer::where('student_id', $student)
            ->where('exam_id', $exam->id)
            ->exists();

        // Load questions count
        $exam->loadCount('questions');

        return view('student.myExams.show', compact('exam', 'isSubmitted'));
    }

    public function startExam(AcaExam $exam)
    {
        $student = auth()->id();

        // Ensure student is enrolled
        $isEnrolled = AcaEnrollment::where('student_id', $student)
            ->where('course_id', $exam->course_id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('student.myExams')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Check exam is ongoing
        $now     = now();
        $startDT = Carbon::parse(
            $exam->exam_date->toDateString() . ' ' . Carbon::parse($exam->start_time)->format('H:i:s')
        );
        $endDT   = Carbon::parse(
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
        $alreadySubmitted = AcaExamAnswer::where('student_id', $student)
            ->where('exam_id', $exam->id)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->route('student.myExams')
                ->with('error', 'You have already submitted this exam.');
        }

        // ✅ Store attempt in variable so we can pass it to the view
        $attempt = AcaExamAttempt::updateOrCreate(
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
        $now              = now();
        $secondsUntilEnd  = $now->diffInSeconds($endDT, false);
        $durationSeconds  = ($exam->exam_duration_min ?? 0) * 60;
        $remainingSeconds = min($secondsUntilEnd, $durationSeconds);

        // Load limited questions in random order
        $exam->load(['questions' => function ($query) use ($exam) {
            $query->inRandomOrder()->limit($exam->total_questions);
        }]);

        // Load mapped active rules for this exam
        $mappedRules = AcaExamRuleMap::where('exam_id', $exam->id)
            ->where('is_active', true)
            ->with(['rule' => fn($q) => $q->where('is_active', true)])
            ->get()
            ->filter(fn($map) => $map->rule)
            ->values();

        return view('student.myExams.answer_sheet', compact(
            'exam',
            'attempt',          // ✅ Added
            'remainingSeconds',
            'mappedRules'
        ));
    }

    public function storeAnswer(Request $request, AcaExam $exam)
    {
        $examId     = $exam->id;
        $student    = auth()->id();
        $isStopped  = $request->input('stopped', '0') === '1';
        $stopReason = $request->input('stop_reason', null);

        $isEnrolled = AcaEnrollment::where('student_id', $student)
            ->where('course_id', $exam->course_id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('student.myExams')
                ->with('error', 'You are not enrolled in this course.');
        }

        $now     = now();
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

        $alreadySubmitted = AcaExamAnswer::where('student_id', $student)
            ->where('exam_id', $examId)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->route('student.myExams')
                ->with('error', 'You have already submitted this exam.');
        }

        $request->validate([
            'answers'     => ['nullable', 'array'],
            'answers.*'   => ['nullable', 'string'],
            'stop_reason' => ['nullable', 'string'],
        ]);

        // ── Security: fetch all valid question IDs for this exam ──────────
        $validQuestionIds = AcaQuestion::where('exam_id', $examId)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        // ── The form submits ALL assigned question IDs as keys (via hidden inputs).
        //    answered   → value = text/option
        //    unanswered → value = "" (empty string from hidden input)
        //    We store both; empty string is normalised to null.
        $submittedAnswers = $request->input('answers', []);

        // Only keep IDs that truly belong to this exam (security check)
        $assignedQuestionIds = array_filter(
            array_map('intval', array_keys($submittedAnswers)),
            fn($id) => in_array($id, $validQuestionIds)
        );

        $records = [];
        $now     = now();

        foreach ($assignedQuestionIds as $questionId) {
            $answerText = $submittedAnswers[$questionId] ?? null;

            // Blank string (unanswered) → null
            if (is_string($answerText) && trim($answerText) === '') {
                $answerText = null;
            }

            $records[] = [
                'student_id'  => $student,
                'exam_id'     => $examId,
                'question_id' => $questionId,
                'answer'      => $answerText,   // null if unanswered
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        // Insert all 10 rows (answered + unanswered)
        AcaExamAnswer::insert($records);

        AcaExamAttempt::updateOrCreate(
            ['student_id' => $student, 'exam_id' => $examId],
            ['submitted_at' => $now, 'status' => 'Old', 'is_active' => true]
        );

        // ✅ AUTO-GRADE immediately after submission
        try {
            $this->gradingService->gradeStudent($exam, $student);
        } catch (\Throwable $e) {
            Log::error('Auto-grading failed after submission', [
                'exam_id'    => $exam->id,
                'student_id' => $student,
                'error'      => $e->getMessage(),
            ]);
        }

        if ($isStopped) {
            $message = match ($stopReason) {
                'back_button'   => 'Exam stopped: You pressed the browser back button.',
                'manual_stop'   => 'Exam stopped: You manually stopped the exam.',
                'url_change'    => 'Exam stopped: You attempted to navigate away from the exam.',
                'timer_expired' => 'Exam auto-submitted: Your exam time has expired.',
                default         => 'Exam stopped. Your answers have been recorded.',
            };

            return redirect()->route('student.myExams')->with('error', $message);
        }

        return redirect()->route('student.myExams')
            ->with('success', 'Exam submitted successfully! Your result will be available once grading is complete.');
    }

    public function viewResult(AcaExam $exam)
    {
        $student = auth()->id();

        $isEnrolled = AcaEnrollment::where('student_id', $student)
            ->where('course_id', $exam->course_id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('student.myExams')
                ->with('error', 'You are not enrolled in this course.');
        }

        $answers = AcaExamAnswer::where('student_id', $student)
            ->where('exam_id', $exam->id)
            ->with(['question', 'reviewAnswer'])
            ->orderBy('id', 'ASC')
            ->get()
            ->keyBy('question_id');

        if ($answers->isEmpty()) {
            return redirect()->route('student.myExams')
                ->with('error', 'You have not submitted this exam yet.');
        }

        // ✅ Load result from aca_exam_results — has correct total_marks
        //    based on assigned questions only, NOT $exam->total_marks
        $result = AcaExamResult::where('exam_id', $exam->id)
            ->where('student_id', $student)
            ->first();

        $subjectiveAnswers = $answers->filter(fn($a) =>
            in_array($a->question?->question_type, ['short_question', 'long_question'])
        );

        $reviewedAnswers = $subjectiveAnswers->filter(fn($a) => $a->reviewAnswer !== null);

        $subjectiveMarksObtained = $reviewedAnswers->sum(fn($a) => $a->reviewAnswer->marks_awarded ?? 0);

        $allReviewed = $subjectiveAnswers->count() > 0 &&
                       $subjectiveAnswers->count() === $reviewedAnswers->count();

        return view('student.myExams.view_result', compact(
            'exam',
            'result',                    // ✅ NEW — null-safe, blade handles fallback
            'answers',
            'subjectiveAnswers',
            'reviewedAnswers',
            'subjectiveMarksObtained',
            'allReviewed'
        ));
    }

    public function myResult(AcaExam $exam)
    {
        $student = auth()->id();

        $isEnrolled = AcaEnrollment::where('student_id', $student)
            ->where('course_id', $exam->course_id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('student.myExams')
                ->with('error', 'You are not enrolled in this course.');
        }

        $result = AcaExamResult::where('exam_id', $exam->id)
            ->where('student_id', $student)
            ->first();

        if (!$result) {
            return redirect()->route('student.myExams')
                ->with('error', 'Your result is not available yet.');
        }

        $rank = AcaExamResult::where('exam_id', $exam->id)
            ->where('percentage', '>', $result->percentage)
            ->count() + 1;

        $totalStudents = AcaExamResult::where('exam_id', $exam->id)->count();

        $answers = AcaExamAnswer::where('exam_id', $exam->id)
            ->where('student_id', $student)
            ->with(['question', 'reviewAnswer'])
            ->orderBy('id')
            ->get();

        return view('student.myExams.my_result', compact(
            'exam',
            'result',
            'answers',
            'rank',
            'totalStudents'
        ));
    }

    public function examRules(AcaExam $exam)
    {
        $mappedRules = AcaExamRuleMap::with('rule')
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
