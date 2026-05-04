<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaExamAnswer;
use App\Models\Academic\AcaReviewAnswer;
use App\Models\Student;
use Illuminate\Http\Request;

class AcaReviewAnswerController extends Controller
{
    // List all exams that have subjective answers
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

    // List all students who submitted this exam
    public function show(AcaExam $exam)
    {
        $submissions = AcaExamAnswer::where('exam_id', $exam->id)
            ->distinct('student_id')
            ->with('student')
            ->get()
            ->groupBy('student_id');

        $students = $submissions->map(function ($answers, $studentId) use ($exam) {
            $student = $answers->first()->student;

            $totalSubjective = $answers->filter(fn($a) =>
                in_array($a->question?->question_type ?? '', ['short_question', 'long_question'])
            )->count();

            $reviewed = AcaReviewAnswer::whereHas('examAnswer', fn($q) =>
                $q->where('exam_id', $exam->id)->where('student_id', $studentId)
            )->count();

            return [
                'student'         => $student,
                'total_answers'   => $answers->count(),
                'total_subjective'=> $totalSubjective,
                'reviewed'        => $reviewed,
                'is_fully_reviewed' => $reviewed >= $totalSubjective && $totalSubjective > 0,
            ];
        });

        return view('admin.academic.reviewAnswer.show', compact('exam', 'students'));
    }

    // Show one student's answers for review
    public function studentAnswers(AcaExam $exam, Student $student)
    {
        $answers = AcaExamAnswer::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->with(['question', 'reviewAnswer'])
            ->get();

        return view('admin.academic.reviewAnswer.student_answers', compact('exam', 'student', 'answers'));
    }

    // Store review
    public function storeReview(Request $request, AcaExam $exam, Student $student)
    {
        $request->validate([
            'reviews'                   => ['required', 'array'],
            'reviews.*.exam_answer_id'  => ['required', 'exists:aca_exam_answers,id'],
            'reviews.*.review'          => ['required', 'in:0,1'],
            'reviews.*.marks_awarded'   => ['required', 'numeric', 'min:0'],
        ]);

        foreach ($request->reviews as $item) {
            AcaReviewAnswer::updateOrCreate(
                ['exam_answers_id' => $item['exam_answer_id']],
                [
                    'review'        => $item['review'],
                    'marks_awarded' => $item['marks_awarded'],
                    'is_active'     => true,
                    'aca_created_by' => auth()->id(),
                    'aca_updated_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('admin.academic.reviewAnswer.show', $exam->id)
            ->with('success', 'Review submitted successfully.');
    }
}
