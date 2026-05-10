<?php

namespace App\Services;

use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaExamAnswer;
use App\Models\Academic\AcaExamResult;
use App\Models\Academic\AcaReviewAnswer;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamGradingService
{
    // MCQ question types — must match question_type values in aca_questions
    protected array $mcqTypes = ['mcq_4', 'mcq_2'];

    // Subjective question types
    protected array $subjectiveTypes = ['short_question', 'long_question'];

    // Pass mark threshold (%)
    protected float $passThreshold = 40.0;

    // ──────────────────────────────────────────────────────────────────────
    // PUBLIC: Grade a single student for a single exam
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Grade one student's exam. Called:
     *  - Automatically after storeAnswer()
     *  - Manually by admin re-trigger
     */
    public function gradeStudent(AcaExam $exam, Student|int $student): AcaExamResult
    {
        $studentId = $student instanceof Student ? $student->id : $student;

        DB::beginTransaction();

        try {
            // 1. Load ONLY the questions this student actually received.
            //    Their aca_exam_answers rows hold exactly the random subset
            //    (e.g. 12 of 25). We never touch questions they weren't shown.
            $studentAnswers = AcaExamAnswer::where('exam_id', $exam->id)
                ->where('student_id', $studentId)
                ->with(['question', 'reviewAnswer'])   // eager-load to avoid N+1
                ->get()
                ->keyBy('question_id');

            // 2. Extract the actual question models from the student's answer sheet
            $assignedQuestions = $studentAnswers
                ->map(fn($a) => $a->question)
                ->filter()                             // drop any orphaned answers
                ->keyBy('id');

            // 3. Separate into MCQ and Subjective — from the assigned set ONLY
            //    Primary signal: evaluation_type ('automatic' = MCQ, 'manual' = subjective)
            //    Fallback signal: question_type in ['mcq_4', 'mcq_2'] for legacy rows
            $mcqQuestions = $assignedQuestions->filter(
                fn($q) => $q->evaluation_type === 'automatic'
                       || in_array($q->question_type, $this->mcqTypes)
            );
            $subjectiveQuestions = $assignedQuestions->filter(
                fn($q) => $q->evaluation_type === 'manual'
                       || in_array($q->question_type, $this->subjectiveTypes)
            );

            // 4. Auto-grade MCQ answers
            $mcqResult = $this->gradeMcqAnswers($mcqQuestions, $studentAnswers);

            // 5. Compute subjective summary (marks already stored in aca_review_answers by teacher)
            $subjectiveResult = $this->computeSubjectiveResult($subjectiveQuestions, $studentAnswers);

            // 6. Compute grand totals
            $totalMarksObtained = $mcqResult['marks_obtained'] + $subjectiveResult['marks_obtained'];
            $totalMarks         = $mcqResult['total_marks'] + $subjectiveResult['total_marks'];
            $percentage         = $totalMarks > 0
                ? round(($totalMarksObtained / $totalMarks) * 100, 2)
                : 0;

            $grade  = AcaExamResult::calculateGrade($percentage);
            $isPass = $percentage >= $this->passThreshold;

            // 7. Determine grading status
            $gradingStatus = $this->resolveGradingStatus($subjectiveResult);

            // 8. Upsert result
            $result = AcaExamResult::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'exam_id'    => $exam->id,
                ],
                [
                    // MCQ
                    'mcq_total'           => $mcqResult['total'],
                    'mcq_correct'         => $mcqResult['correct'],
                    'mcq_wrong'           => $mcqResult['wrong'],
                    'mcq_unanswered'      => $mcqResult['unanswered'],
                    'mcq_marks_obtained'  => $mcqResult['marks_obtained'],
                    'mcq_total_marks'     => $mcqResult['total_marks'],

                    // Subjective
                    'subjective_total'           => $subjectiveResult['total'],
                    'subjective_reviewed'        => $subjectiveResult['reviewed'],
                    'subjective_marks_obtained'  => $subjectiveResult['marks_obtained'],
                    'subjective_total_marks'     => $subjectiveResult['total_marks'],

                    // Grand total
                    'total_marks_obtained' => $totalMarksObtained,
                    'total_marks'          => $totalMarks,
                    'percentage'           => $percentage,
                    'grade'                => $grade,
                    'is_pass'              => $isPass,
                    'grading_status'       => $gradingStatus,
                    'graded_at'            => now(),
                    'updated_by'           => auth()->id() ?? 'system',
                ]
            );

            DB::commit();

            return $result;

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("ExamGradingService::gradeStudent failed", [
                'exam_id'    => $exam->id,
                'student_id' => $studentId,
                'error'      => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    // PUBLIC: Grade ALL students for an exam (batch re-trigger)
    // ──────────────────────────────────────────────────────────────────────

    public function gradeAllStudents(AcaExam $exam): array
    {
        // Get distinct students who submitted
        $studentIds = AcaExamAnswer::where('exam_id', $exam->id)
            ->distinct('student_id')
            ->pluck('student_id');

        $results = [
            'total'   => $studentIds->count(),
            'success' => 0,
            'failed'  => 0,
            'errors'  => [],
        ];

        foreach ($studentIds as $studentId) {
            try {
                $this->gradeStudent($exam, $studentId);
                $results['success']++;
            } catch (\Throwable $e) {
                $results['failed']++;
                $results['errors'][] = "Student #{$studentId}: " . $e->getMessage();
            }
        }

        return $results;
    }

    // ──────────────────────────────────────────────────────────────────────
    // PRIVATE: MCQ grading
    // ──────────────────────────────────────────────────────────────────────

    private function gradeMcqAnswers(
        $mcqQuestions,
        $studentAnswers
    ): array {
        $correct       = 0;
        $wrong         = 0;
        $unanswered    = 0;
        $marksObtained = 0.0;
        $totalMarks    = 0.0;

        foreach ($mcqQuestions as $question) {
            $questionMark  = (float) ($question->marks ?? 1);
            $totalMarks   += $questionMark;

            // $studentAnswers is keyed by question_id
            $studentAnswer = $studentAnswers->get($question->id);

            if (!$studentAnswer || empty($studentAnswer->answer)) {
                // Question was in the student's sheet but left blank
                $unanswered++;

                // Still upsert a "wrong/0" review so the row exists
                if ($studentAnswer) {
                    $this->upsertReviewAnswer($studentAnswer->id, false, 0);
                }
                continue;
            }

            $isCorrect = $this->compareAnswers($studentAnswer->answer, $question->correct_answer);

            if ($isCorrect) {
                $correct++;
                $marksObtained += $questionMark;
                $this->upsertReviewAnswer($studentAnswer->id, true, $questionMark);
            } else {
                $wrong++;
                $this->upsertReviewAnswer($studentAnswer->id, false, 0);
            }
        }

        return [
            'total'          => $mcqQuestions->count(),
            'correct'        => $correct,
            'wrong'          => $wrong,
            'unanswered'     => $unanswered,
            'marks_obtained' => $marksObtained,
            'total_marks'    => $totalMarks,
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    // PRIVATE: Upsert AcaReviewAnswer for an MCQ question
    // ──────────────────────────────────────────────────────────────────────

    private function upsertReviewAnswer(
        int $examAnswerId,
        bool $isCorrect,
        float $marksAwarded
    ): void {
        AcaReviewAnswer::updateOrCreate(
            ['exam_answers_id' => $examAnswerId],
            [
                'review'         => $isCorrect ? 1 : 0,
                'marks_awarded'  => $marksAwarded,
                'is_active'      => true,
                'aca_updated_by' => auth()->id() ?? 'system',
            ]
        );
    }

    // ──────────────────────────────────────────────────────────────────────
    // PRIVATE: Compute subjective result from teacher reviews
    // ──────────────────────────────────────────────────────────────────────

    private function computeSubjectiveResult($subjectiveQuestions, $studentAnswers): array
    {
        $total         = $subjectiveQuestions->count();
        $reviewed      = 0;
        $marksObtained = 0.0;
        $totalMarks    = 0.0;

        foreach ($subjectiveQuestions as $question) {
            $totalMarks += (float) ($question->marks ?? 0);

            // reviewAnswer is already eager-loaded via with(['question','reviewAnswer'])
            $studentAnswer = $studentAnswers->get($question->id);
            if (!$studentAnswer) continue;

            // Only count teacher-reviewed subjective answers
            if ($studentAnswer->reviewAnswer) {
                $reviewed++;
                $marksObtained += (float) ($studentAnswer->reviewAnswer->marks_awarded ?? 0);
            }
        }

        return [
            'total'          => $total,
            'reviewed'       => $reviewed,
            'marks_obtained' => $marksObtained,
            'total_marks'    => $totalMarks,
        ];
    }

    // ──────────────────────────────────────────────────────────────────────
    // PRIVATE: Determine grading status
    // ──────────────────────────────────────────────────────────────────────

    private function resolveGradingStatus(array $subjectiveResult): string
    {
        if ($subjectiveResult['total'] === 0) {
            return 'complete'; // MCQ only exam
        }

        if ($subjectiveResult['reviewed'] === 0) {
            return 'pending'; // No subjective reviewed yet
        }

        if ($subjectiveResult['reviewed'] < $subjectiveResult['total']) {
            return 'partial'; // Some reviewed
        }

        return 'complete'; // All reviewed
    }

    // ──────────────────────────────────────────────────────────────────────
    // PRIVATE: Answer comparison
    //
    // Both $given and $correct store the full option TEXT (e.g. "Paris").
    // The answer sheet submits: value="{{ $optVal }}" which is the raw text
    // of option_a / option_b / option_c / option_d from aca_questions.
    // So a direct case-insensitive, whitespace-normalised compare is correct.
    // ──────────────────────────────────────────────────────────────────────

    private function compareAnswers(string $given, string $correct): bool
    {
        // Normalise: lowercase + collapse all internal whitespace
        $normalise = fn(string $s): string =>
            preg_replace('/\s+/', ' ', strtolower(trim($s)));

        return $normalise($given) === $normalise($correct);
    }
}
