<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\Exam;
use App\Models\Academic\ReviewAnswer;
use Illuminate\Http\Request;

class ReviewAnswerController extends Controller
{
    public function index()
    {
        $exams = Exam::whereHas('questions', function($q) {
            $q->whereIn('question_type', ['short_question', 'long_question']);
        })
        ->whereHas('examAnswers')
        ->with(['course'])
        ->withCount([
            'examAnswers as total_submissions' => function($q) {
                $q->distinct('student_id');
            },
        ])
        ->get();

        // Add reviewed count to each exam
        $exams->each(function($exam) {
            $exam->reviewed_count = ReviewAnswer::whereHas('examAnswer', fn($q) =>
                $q->where('exam_id', $exam->id)
            )->distinct('exam_answers_id')->count();
        });

        return view('admin.academic.reviewAnswer.index', compact('exams'));
    }

    // public function show(Exam $exam)
    // {
    //     // Get all students who submitted this exam with subjective answers
    //     $submissions = ExamAnswer::where('exam_id', $exam->id)
    //         ->whereHas('question', fn($q) => $q->whereIn('question_type', ['short_question', 'long_question']))
    //         ->with(['student', 'question', 'reviewAnswer'])
    //         ->get()
    //         ->groupBy('student_id');

    //     return view('admin.academic.reviewAnswer.show', compact('exam', 'submissions'));
    // }

    // public function storeReview(Request $request, Exam $exam)
    // {
    //     $request->validate([
    //         'reviews'                  => ['required', 'array'],
    //         'reviews.*.exam_answer_id' => ['required', 'exists:aca_exam_answers,id'],
    //         'reviews.*.review'         => ['required', 'in:0,1'],
    //         'reviews.*.marks_awarded'  => ['required', 'numeric', 'min:0'],
    //     ]);

    //     foreach ($request->reviews as $item) {
    //         ReviewAnswer::updateOrCreate(
    //             ['exam_answers_id' => $item['exam_answer_id']],
    //             [
    //                 'review'        => $item['review'],
    //                 'marks_awarded' => $item['marks_awarded'],
    //                 'is_active'     => true,
    //                 'aca_created_by' => auth()->id(),
    //                 'aca_updated_by' => auth()->id(),
    //             ]
    //         );
    //     }

    //     return redirect()->back()->with('success', 'Reviews submitted successfully.');
    // }
}
