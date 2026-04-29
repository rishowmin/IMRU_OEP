<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\ExamAnswer;
use App\Models\Academic\ExamAttempt;
use App\Models\Academic\ReviewAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamAttemptController extends Controller
{
    public function index()
    {
        $serialNo    = 1;
        $attemptList = ExamAttempt::with(['student', 'exam', 'acaUpdatedBy', 'updatedBy'])
            ->whereNull('deleted_at')
            ->orderBy('id', 'ASC')
            ->get();

        return view('admin.academic.examAttempt.index', compact('attemptList', 'serialNo'));
    }

    public function reset(ExamAttempt $attempt)
    {
        // Only allow reset if the attempt has already been submitted (status = Old)
        if ($attempt->status !== 'Old') {
            return back()->with('error', 'Only submitted attempts can be reset.');
        }

        DB::transaction(function () use ($attempt) {

            // Step 1: Get all exam answer IDs for this student+exam
            $answerIds = ExamAnswer::where('student_id', $attempt->student_id)
                ->where('exam_id', $attempt->exam_id)
                ->pluck('id');

            // Step 2: Soft-delete ReviewAnswers manually (since SoftDeletes won't cascade)
            ReviewAnswer::whereIn('exam_answers_id', $answerIds)->delete();

            // Step 3: Soft-delete ExamAnswers
            ExamAnswer::whereIn('id', $answerIds)->delete();

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
