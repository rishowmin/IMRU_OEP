<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaExamAnswer;
use App\Models\Academic\AcaExamAttempt;
use App\Models\Academic\AcaReviewAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TechExamAttemptController extends Controller
{
    public function index()
    {
        $teacherId = auth()->id();
        $serialNo  = 1;

        $attemptList = AcaExamAttempt::with(['student', 'exam', 'acaUpdatedBy', 'updatedBy'])
            ->whereNull('aca_exam_attempts.deleted_at')
            ->whereHas('exam', function ($query) use ($teacherId) {
                $query->where('aca_created_by', $teacherId);
            })
            ->orderBy('aca_exam_attempts.id', 'ASC')
            ->get();

        return view('teacher.modules.examAttempt.index', compact('attemptList', 'serialNo'));
    }

    public function reset(AcaExamAttempt $attempt)
    {
        // Authorization: ensure this attempt belongs to one of the teacher's courses
        $teacherId = auth()->id();

        $isMyCourseAttempt = $attempt->exam()
            ->whereHas('course', fn($q) => $q->where('teacher_id', $teacherId))
            ->exists();

        if (! $isMyCourseAttempt) {
            abort(403, 'You are not authorized to reset this attempt.');
        }

        // Only allow reset if the attempt has already been submitted (status = Old)
        if ($attempt->status !== 'Old') {
            return back()->with('error', 'Only submitted attempts can be reset.');
        }

        DB::transaction(function () use ($attempt, $teacherId) {
            $answerIds = AcaExamAnswer::where('student_id', $attempt->student_id)
                ->where('exam_id', $attempt->exam_id)
                ->pluck('id');

            AcaReviewAnswer::whereIn('exam_answers_id', $answerIds)->delete();
            AcaExamAnswer::whereIn('id', $answerIds)->delete();

            $attempt->update([
                'status'         => 'New',
                'started_at'     => null,
                'submitted_at'   => null,
                'aca_updated_by' => $teacherId,
            ]);
        });

        return back()->with('success', 'Exam attempt has been reset. The student can now start the exam again.');
    }
}
