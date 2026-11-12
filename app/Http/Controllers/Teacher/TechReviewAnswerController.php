<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaEnrollment;
use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaExamAnswer;
use App\Models\Academic\AcaReviewAnswer;
use App\Models\Student;
use App\Services\ExamGradingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TechReviewAnswerController extends Controller
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
        ->whereHas('course', fn ($query) => $query->where('teacher_id', auth()->id()))
        ->whereHas('examAnswers', function ($query) {
            $query->whereIn('student_id', AcaEnrollment::whereColumn('aca_enrollments.course_id', 'aca_exams.course_id')
                ->select('student_id'));
        })
        ->with(['course'])
        ->get()
        ->each(function ($exam) {
            $enrolledStudentIds = AcaEnrollment::where('course_id', $exam->course_id)
                ->select('student_id');

            // ── 1. Enrolled students for this course ──────────────────────
            $exam->total_enrolled = AcaEnrollment::where('course_id', $exam->course_id)
                ->count();

            // ── 2. Distinct students who submitted ────────────────────────
            $studentIds = AcaExamAnswer::where('exam_id', $exam->id)
                ->whereIn('student_id', $enrolledStudentIds)
                ->distinct('student_id')
                ->pluck('student_id');

            $exam->total_submissions = $studentIds->count();

            // ── 3. Per-student subjective review status ───────────────────
            $fullyReviewed   = 0;
            $partialReviewed = 0;
            $withSubjective  = 0;

            foreach ($studentIds as $studentId) {
                $subjectiveAnswers = AcaExamAnswer::where('exam_id', $exam->id)
                    ->where('student_id', $studentId)
                    ->whereIn('student_id', $enrolledStudentIds)
                    ->whereHas('question', fn($q) =>
                        $q->whereIn('question_type', ['short_question', 'long_question'])
                    )
                    ->with('reviewAnswer')
                    ->get();

                $totalSubjective = $subjectiveAnswers->count();
                if ($totalSubjective === 0) continue;

                $withSubjective++;
                $reviewed = $subjectiveAnswers->filter(fn($a) => $a->reviewAnswer !== null)->count();

                if ($reviewed >= $totalSubjective) {
                    $fullyReviewed++;
                } elseif ($reviewed > 0) {
                    $partialReviewed++;
                }
            }

            $exam->students_with_subjective = $withSubjective;
            $exam->fully_reviewed_count     = $fullyReviewed;
            $exam->partial_reviewed_count   = $partialReviewed;
        });

        return view('teacher.modules.reviewAnswer.index', compact('exams'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // List all students who submitted this exam
    // ──────────────────────────────────────────────────────────────────────

    public function show(AcaExam $exam)
    {
        $this->authorizeTeacherExam($exam);
        $enrolledStudentIds = AcaEnrollment::where('course_id', $exam->course_id)
            ->select('student_id');

        // ✅ FIX: Get distinct student IDs first, then load ALL their answers
        //    properly — not from a distinct('student_id') query which only
        //    returns one row per student and makes $totalSubjective always 0.
        $studentIds = AcaExamAnswer::where('exam_id', $exam->id)
            ->whereIn('student_id', $enrolledStudentIds)
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

        return view('teacher.modules.reviewAnswer.show', compact('exam', 'students'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Show one student's answers for review
    // ──────────────────────────────────────────────────────────────────────

    public function studentAnswers(AcaExam $exam, Student $student)
    {
        $this->authorizeTeacherExam($exam, $student);

        $answers = AcaExamAnswer::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->with(['question', 'reviewAnswer'])
            ->get();

        return view('teacher.modules.reviewAnswer.student_answers', compact('exam', 'student', 'answers'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // Store review + re-grade the student automatically
    // ──────────────────────────────────────────────────────────────────────

    public function storeReview(Request $request, AcaExam $exam, Student $student)
    {
        $this->authorizeTeacherExam($exam, $student);

        $request->validate([
            'reviews'                   => ['required', 'array'],
            'reviews.*.exam_answer_id'  => ['required', 'exists:aca_exam_answers,id'],
            'reviews.*.review'          => ['required', 'in:0,1'],
            'reviews.*.marks_awarded'   => ['required', 'numeric', 'min:0'],
        ]);

        $answerIds = collect($request->input('reviews'))
            ->pluck('exam_answer_id')
            ->unique();
        abort_unless(
            AcaExamAnswer::whereIn('id', $answerIds)
                ->where('exam_id', $exam->id)
                ->where('student_id', $student->id)
                ->count() === $answerIds->count(),
            403
        );

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
            ->route('teacher.reviewAnswer.show', $exam->id)
            ->with('success', $message);
    }

    private function authorizeTeacherExam(AcaExam $exam, ?Student $student = null): void
    {
        abort_unless($exam->course()->where('teacher_id', auth()->id())->exists(), 403);

        if ($student) {
            abort_unless(
                AcaEnrollment::where('course_id', $exam->course_id)
                    ->where('student_id', $student->id)
                    ->exists(),
                403
            );
        }
    }
}
