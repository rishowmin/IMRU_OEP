<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaExamAnswer;
use App\Models\Academic\AcaExamResult;
use App\Models\Academic\AcaQuestion;
use App\Models\Student;
use App\Services\ExamGradingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AcaPerformanceController extends Controller
{
    public function __construct(protected ExamGradingService $gradingService) {}

    // ──────────────────────────────────────────────────────────────────────
    // 1. List all exams with grading status summary
    // ──────────────────────────────────────────────────────────────────────

    public function index()
    {
        $exams = AcaExam::with('course')
            ->withCount([
                'examAnswers as total_submissions' => fn($q) => $q->distinct('student_id'),
            ])
            ->orderBy('exam_date', 'desc')
            ->get()
            ->map(function ($exam) {
                $exam->result_summary = AcaExamResult::where('exam_id', $exam->id)
                    ->selectRaw("
                        COUNT(*) as total_graded,
                        SUM(CASE WHEN grading_status = 'complete' THEN 1 ELSE 0 END) as complete,
                        SUM(CASE WHEN grading_status = 'partial'  THEN 1 ELSE 0 END) as partial,
                        SUM(CASE WHEN grading_status = 'pending'  THEN 1 ELSE 0 END) as pending,
                        AVG(percentage) as avg_percentage,
                        MAX(percentage) as max_percentage,
                        MIN(percentage) as min_percentage
                    ")
                    ->first();
                return $exam;
            });

        return view('admin.academic.performance.index', compact('exams'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // 2. Class-wide analytics for one exam (charts + stats)
    // ──────────────────────────────────────────────────────────────────────

    public function examAnalytics(AcaExam $exam)
    {
        // Grade distribution
        $gradeDistribution = AcaExamResult::where('exam_id', $exam->id)
            ->selectRaw("grade, COUNT(*) as count")
            ->groupBy('grade')
            ->orderByRaw("FIELD(grade, 'A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'D', 'F')")
            ->pluck('count', 'grade');

        // Pass/fail ratio
        $passFailData = AcaExamResult::where('exam_id', $exam->id)
            ->selectRaw("is_pass, COUNT(*) as count")
            ->groupBy('is_pass')
            ->pluck('count', 'is_pass');

        // Score distribution (buckets: 0-10, 11-20, ... 91-100)
        $scoreDistribution = AcaExamResult::where('exam_id', $exam->id)
            ->selectRaw("
                FLOOR(percentage / 10) * 10 AS score_bucket,
                COUNT(*) as count
            ")
            ->groupBy('score_bucket')
            ->orderBy('score_bucket')
            ->get()
            ->mapWithKeys(fn($row) =>
                [($row->score_bucket . '-' . ($row->score_bucket + 9)) => $row->count]
            );

        // Summary statistics
        $stats = AcaExamResult::where('exam_id', $exam->id)
            ->selectRaw("
                COUNT(*) as total_students,
                SUM(is_pass) as passed,
                COUNT(*) - SUM(is_pass) as failed,
                ROUND(AVG(percentage), 2) as avg_percentage,
                ROUND(MAX(percentage), 2) as max_percentage,
                ROUND(MIN(percentage), 2) as min_percentage,
                ROUND(AVG(mcq_marks_obtained), 2) as avg_mcq_marks,
                ROUND(AVG(subjective_marks_obtained), 2) as avg_subjective_marks
            ")
            ->first();

        // All student results (table)
        $results = AcaExamResult::where('exam_id', $exam->id)
            ->with('student')
            ->orderBy('percentage', 'desc')
            ->get()
            ->map(function ($result, $index) {
                $result->rank = $index + 1;
                return $result;
            });

        // Question-wise difficulty
        $questionDifficulty = $this->getQuestionDifficulty($exam);

        return view('admin.academic.performance.exam_analytics', compact(
            'exam',
            'gradeDistribution',
            'passFailData',
            'scoreDistribution',
            'stats',
            'results',
            'questionDifficulty'
        ));
    }

    // ──────────────────────────────────────────────────────────────────────
    // 3. Per-student score report
    // ──────────────────────────────────────────────────────────────────────

    public function studentReport(AcaExam $exam, Student $student)
    {
        $result = AcaExamResult::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$result) {
            return redirect()->route('admin.academic.performance.examAnalytics', $exam->id)
                ->with('error', 'Result not found. Please trigger grading first.');
        }

        $answers = AcaExamAnswer::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->with(['question', 'reviewAnswer'])
            ->get();

        // Rank of this student in this exam
        $rank = AcaExamResult::where('exam_id', $exam->id)
            ->where('percentage', '>', $result->percentage)
            ->count() + 1;

        $totalStudents = AcaExamResult::where('exam_id', $exam->id)->count();

        return view('admin.academic.performance.student_report', compact(
            'exam',
            'student',
            'result',
            'answers',
            'rank',
            'totalStudents'
        ));
    }

    // ──────────────────────────────────────────────────────────────────────
    // 4. Re-trigger grading for entire exam
    // ──────────────────────────────────────────────────────────────────────

    public function retriggerGrading(AcaExam $exam)
    {
        try {
            $summary = $this->gradingService->gradeAllStudents($exam);

            $message = "Grading complete. {$summary['success']} of {$summary['total']} students graded.";

            if ($summary['failed'] > 0) {
                $message .= " {$summary['failed']} failed.";
            }

            return redirect()
                ->route('admin.academic.performance.examAnalytics', $exam->id)
                ->with('success', $message);

        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.academic.performance.examAnalytics', $exam->id)
                ->with('error', 'Grading failed: ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    // 5. Re-trigger grading for a single student
    // ──────────────────────────────────────────────────────────────────────

    public function retriggerStudentGrading(AcaExam $exam, Student $student)
    {
        try {
            $this->gradingService->gradeStudent($exam, $student);

            return redirect()
                ->route('admin.academic.performance.studentReport', [$exam->id, $student->id])
                ->with('success', 'Student result updated successfully.');

        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Grading failed: ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    // PRIVATE: Question-wise difficulty analysis
    // ──────────────────────────────────────────────────────────────────────

    private function getQuestionDifficulty(AcaExam $exam): \Illuminate\Support\Collection
    {
        // Only analyse questions that were actually assigned to at least one student.
        // Since each student gets a random subset (e.g. 12 of 25), questions never
        // assigned to anyone would show 0% and distort the difficulty report.
        $assignedQuestionIds = AcaExamAnswer::where('exam_id', $exam->id)
            ->distinct('question_id')
            ->pluck('question_id');

        if ($assignedQuestionIds->isEmpty()) return collect();

        $questions = AcaQuestion::whereIn('id', $assignedQuestionIds)->get();

        // Per-question: how many distinct students received it?
        // (Not every student gets the same 12, so the denominator varies per question)
        return $questions->map(function ($question) use ($exam) {
            $answers = AcaExamAnswer::where('exam_id', $exam->id)
                ->where('question_id', $question->id)
                ->with('reviewAnswer')
                ->get();

            // Students who actually received this question
            $receivedCount = $answers->count();

            // Students who answered (non-blank)
            $attempted = $answers->whereNotNull('answer')->where('answer', '!=', '')->count();

            // Correct answers (via review)
            $correct = $answers->filter(fn($a) =>
                $a->reviewAnswer && $a->reviewAnswer->review == 1
            )->count();

            // Correct rate: of those who attempted, how many got it right
            $correctRate = $attempted > 0 ? round(($correct / $attempted) * 100, 1) : 0;

            // Attempted rate: of those who received it, how many answered
            $attemptedRate = $receivedCount > 0 ? round(($attempted / $receivedCount) * 100, 1) : 0;

            $difficulty = match(true) {
                $correctRate >= 80 => 'Easy',
                $correctRate >= 50 => 'Medium',
                $correctRate >= 25 => 'Hard',
                default            => 'Very Hard',
            };

            return [
                'question_id'    => $question->id,
                'question_text'  => Str::limit($question->question_text, 80),
                'question_type'  => $question->question_type,
                'marks'          => $question->marks,
                'received'       => $receivedCount,
                'attempted'      => $attempted,
                'attempted_rate' => $attemptedRate,
                'correct'        => $correct,
                'correct_rate'   => $correctRate,
                'difficulty'     => $difficulty,
            ];
        })->sortBy('correct_rate')->values();
    }
}
