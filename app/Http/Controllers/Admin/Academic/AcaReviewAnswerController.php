<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaExamAnswer;
use App\Models\Academic\AcaReviewAnswer;
use App\Models\Student;
use App\Services\ExamGradingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AcaReviewAnswerController extends Controller
{
    public function __construct(protected ExamGradingService $gradingService) {}

    // ──────────────────────────────────────────────────────────────────────
    // List all exams that have subjective answers
    // ──────────────────────────────────────────────────────────────────────

    public function index()
    {
        $exams = AcaExam::whereHas('questions', function ($q) {
            $q->whereIn('question_type', ['short_question', 'long_question']);
        })
        ->whereHas('examAnswers')
        ->with(['course'])
        ->withCount([
            'examAnswers as total_submissions' => function ($q) {
                $q->distinct('student_id');
            },
        ])
        ->get();

        $exams->each(function ($exam) {
            $exam->reviewed_count = AcaReviewAnswer::whereHas('examAnswer', fn($q) =>
                $q->where('exam_id', $exam->id)
            )->distinct('exam_answers_id')->count();
        });

        return view('admin.academic.reviewAnswer.index', compact('exams'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // List all students who submitted this exam
    // ──────────────────────────────────────────────────────────────────────

    public function show(AcaExam $exam)
    {
        // ✅ FIX: Get distinct student IDs first, then load ALL their answers
        //    properly — not from a distinct('student_id') query which only
        //    returns one row per student and makes $totalSubjective always 0.
        $studentIds = AcaExamAnswer::where('exam_id', $exam->id)
            ->distinct()
            ->pluck('student_id');

        $students = $studentIds->map(function ($studentId) use ($exam) {

            // Load ALL answers for this student in this exam
            $allAnswers = AcaExamAnswer::where('exam_id', $exam->id)
                ->where('student_id', $studentId)
                ->with(['question', 'reviewAnswer'])
                ->get();

            $student = Student::find($studentId);

            // Count subjective questions from the student's actual answer sheet
            $subjectiveAnswers = $allAnswers->filter(fn($a) =>
                in_array($a->question?->question_type, ['short_question', 'long_question'])
            );

            $totalSubjective = $subjectiveAnswers->count();

            // Count how many subjective answers have been reviewed
            $reviewed = $subjectiveAnswers->filter(fn($a) =>
                $a->reviewAnswer !== null
            )->count();

            return [
                'student'           => $student,
                'total_answers'     => $allAnswers->count(),
                'total_subjective'  => $totalSubjective,
                'reviewed'          => $reviewed,
                // ✅ Only true when ALL subjective answers have a review row
                'is_fully_reviewed' => $totalSubjective > 0 && $reviewed >= $totalSubjective,
            ];
        });

        return view('admin.academic.reviewAnswer.show', compact('exam', 'students'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Show one student's answers for review
    // ──────────────────────────────────────────────────────────────────────

    public function studentAnswers(AcaExam $exam, Student $student)
    {
        $answers = AcaExamAnswer::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->with(['question', 'reviewAnswer'])
            ->get();

        return view('admin.academic.reviewAnswer.student_answers', compact('exam', 'student', 'answers'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Store review + re-grade the student automatically
    // ──────────────────────────────────────────────────────────────────────

    public function storeReview(Request $request, AcaExam $exam, Student $student)
    {
        $request->validate([
            'reviews'                   => ['required', 'array'],
            'reviews.*.exam_answer_id'  => ['required', 'exists:aca_exam_answers,id'],
            'reviews.*.review'          => ['required', 'in:0,1'],
            'reviews.*.marks_awarded'   => ['required', 'numeric', 'min:0'],
        ]);

        // ── Save each subjective review ───────────────────────────────────
        foreach ($request->reviews as $item) {
            AcaReviewAnswer::updateOrCreate(
                ['exam_answers_id' => $item['exam_answer_id']],
                [
                    'review'         => $item['review'],
                    'marks_awarded'  => $item['marks_awarded'],
                    'is_active'      => true,
                    'aca_created_by' => auth()->id(),
                    'aca_updated_by' => auth()->id(),
                ]
            );
        }

        // ✅ AUTO RE-GRADE: After saving subjective marks, recompute this
        //    student's total score, percentage, grade, and grading_status.
        //    Without this, aca_exam_results still shows the old partial score.
        try {
            $result = $this->gradingService->gradeStudent($exam, $student);

            $gradingInfo = "Score updated: {$result->percentage}% | "
                         . "Grade: {$result->grade} | "
                         . ($result->is_pass ? 'Passed ✅' : 'Failed ❌');

        } catch (\Throwable $e) {
            Log::error('Auto re-grade failed after subjective review', [
                'exam_id'    => $exam->id,
                'student_id' => $student->id,
                'error'      => $e->getMessage(),
            ]);
            $gradingInfo = null;
        }

        $message = 'Review submitted successfully.';
        if ($gradingInfo) {
            $message .= ' ' . $gradingInfo;
        }

        return redirect()
            ->route('admin.academic.reviewAnswer.show', $exam->id)
            ->with('success', $message);
    }
}
