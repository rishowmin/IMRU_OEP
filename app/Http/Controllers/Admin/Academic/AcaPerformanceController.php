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
use Illuminate\Support\Facades\Log;
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
    // 4. Re-grade ALL students for an exam
    // ──────────────────────────────────────────────────────────────────────

    public function retriggerGrading(Request $request, AcaExam $exam)
    {
        // Get all distinct students who submitted this exam
        $studentIds = AcaExamAnswer::where('exam_id', $exam->id)
            ->distinct('student_id')
            ->pluck('student_id');

        $total   = $studentIds->count();
        $success = 0;
        $failed  = 0;
        $errors  = [];

        if ($total === 0) {
            return redirect()
                ->route('admin.academic.performance.examAnalytics', $exam->id)
                ->with('error', 'No submissions found for this exam. Nothing to grade.');
        }

        foreach ($studentIds as $studentId) {
            try {
                $this->gradingService->gradeStudent($exam, $studentId);
                $success++;
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = "Student #{$studentId}: " . $e->getMessage();
                Log::error('retriggerGrading failed for student', [
                    'exam_id'    => $exam->id,
                    'student_id' => $studentId,
                    'error'      => $e->getMessage(),
                ]);
            }
        }

        // Build feedback message
        if ($failed === 0) {
            $message = "✅ Re-grading complete. All {$success} student(s) graded successfully.";
            $type    = 'success';
        } elseif ($success > 0) {
            $message = "⚠️ Re-grading partially complete. {$success} succeeded, {$failed} failed.";
            $type    = 'warning';
        } else {
            $message = "❌ Re-grading failed for all {$failed} student(s). Check logs for details.";
            $type    = 'error';
        }

        return redirect()
            ->route('admin.academic.performance.examAnalytics', $exam->id)
            ->with($type, $message);
    }

    // ──────────────────────────────────────────────────────────────────────
    // 5. Re-grade ONE student
    // ──────────────────────────────────────────────────────────────────────

    public function retriggerStudentGrading(Request $request, AcaExam $exam, Student $student)
    {
        // Check this student has actually submitted
        $hasSubmission = AcaExamAnswer::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->exists();

        if (!$hasSubmission) {
            return redirect()
                ->route('admin.academic.performance.examAnalytics', $exam->id)
                ->with('error', "No submission found for {$student->first_name} {$student->last_name}.");
        }

        try {
            $result = $this->gradingService->gradeStudent($exam, $student);

            $message = "✅ {$student->first_name} {$student->last_name} re-graded successfully. "
                     . "Score: {$result->percentage}% | Grade: {$result->grade} | "
                     . ($result->is_pass ? 'Passed' : 'Failed');

            return redirect()
                ->route('admin.academic.performance.studentReport', [$exam->id, $student->id])
                ->with('success', $message);

        } catch (\Throwable $e) {
            Log::error('retriggerStudentGrading failed', [
                'exam_id'    => $exam->id,
                'student_id' => $student->id,
                'error'      => $e->getMessage(),
            ]);

            return redirect()
                ->route('admin.academic.performance.studentReport', [$exam->id, $student->id])
                ->with('error', "❌ Re-grading failed: " . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────────────────
    // PRIVATE: Question-wise difficulty analysis
    // ──────────────────────────────────────────────────────────────────────

    private function getQuestionDifficulty(AcaExam $exam): \Illuminate\Support\Collection
    {
        $assignedQuestionIds = AcaExamAnswer::where('exam_id', $exam->id)
            ->distinct('question_id')
            ->pluck('question_id');

        if ($assignedQuestionIds->isEmpty()) return collect();

        $questions = AcaQuestion::whereIn('id', $assignedQuestionIds)->get();

        return $questions->map(function ($question) use ($exam) {
            $answers = AcaExamAnswer::where('exam_id', $exam->id)
                ->where('question_id', $question->id)
                ->with('reviewAnswer')
                ->get();

            $receivedCount = $answers->count();
            $attempted     = $answers->whereNotNull('answer')->where('answer', '!=', '')->count();
            $correct       = $answers->filter(fn($a) =>
                $a->reviewAnswer && $a->reviewAnswer->review == 1
            )->count();

            $correctRate   = $attempted > 0 ? round(($correct / $attempted) * 100, 1) : 0;
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
