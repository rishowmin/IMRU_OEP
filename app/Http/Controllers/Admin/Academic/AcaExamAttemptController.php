<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaExamAnswer;
use App\Models\Academic\AcaExamAttempt;
use App\Models\Academic\AcaReviewAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcaExamAttemptController extends Controller
{
    public function index()
    {
        $serialNo    = 1;
        $attemptList = AcaExamAttempt::with(['student', 'exam', 'acaUpdatedBy', 'updatedBy'])
            ->whereNull('deleted_at')
            ->orderBy('id', 'ASC')
            ->get();

        return view('admin.academic.examAttempt.index', compact('attemptList', 'serialNo'));
    }

    public function reset(AcaExamAttempt $attempt)
    {
        // Only allow reset if the attempt has already been submitted (status = Old)
        if ($attempt->status !== 'Old') {
            return back()->with('error', 'Only submitted attempts can be reset.');
        }

        DB::transaction(function () use ($attempt) {

            // Step 1: Get all exam answer IDs for this student+exam
            $answerIds = AcaExamAnswer::where('student_id', $attempt->student_id)
                ->where('exam_id', $attempt->exam_id)
                ->pluck('id');

            // Step 2: Soft-delete ReviewAnswers manually (since SoftDeletes won't cascade)
            AcaReviewAnswer::whereIn('exam_answers_id', $answerIds)->delete();

            // Step 3: Soft-delete ExamAnswers
            AcaExamAnswer::whereIn('id', $answerIds)->delete();

            // Step 4: Reset the attempt back to initial state
            $attempt->update([
                'status'       => 'New',
                'started_at'   => null,
                'submitted_at' => null,
                'updated_by'   => auth()->id(),
            ]);
        });

        return back()->with('success', 'Exam attempt has been reset. The student can now start the exam again.');
    }
}
