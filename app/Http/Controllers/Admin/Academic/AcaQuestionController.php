<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\QuestionFormRequest;
use App\Models\Academic\AcaCourse;
use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AcaQuestionController extends Controller
{
    public function index()
    {
        $serialNo = 1;
        $questionList = AcaQuestion::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();
        return view('admin.academic.questions.index', compact('serialNo', 'questionList'));
    }

    public function create()
    {
        $examList = AcaExam::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();
        $courseList = AcaCourse::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();
        return view('admin.academic.questions.form', compact('examList', 'courseList'));
    }

    public function store(QuestionFormRequest $request)
    {
        try {
            $questionFigurePath = null;

            if ($request->hasFile('question_figure')) {
                $uploadPath = 'storage/question_figure/';

                $file = $request->file('question_figure');
                $extension = $file->getClientOriginalExtension();
                // $fileName = 'question_text-' . time() . '.' . $extension;
                $fileName = substr(Str::slug($request->question_text), 0, 20) . '-' . time() . '.' . $extension;

                $file->move($uploadPath, $fileName);

                $questionFigurePath = $fileName;
            }

            AcaQuestion::create([
                'exam_id' => $request->exam_id,
                'question_type' => $request->question_type,
                'question_text' => $request->question_text,
                'difficulty_level' => $request->difficulty_level,
                'marks' => $request->marks,
                'evaluation_type' => $request->evaluation_type,
                'option_a' => $request->option_a,
                'option_b' => $request->option_b,
                'option_c' => $request->option_c,
                'option_d' => $request->option_d,
                'correct_answer' => $request->correct_answer,
                'question_figure' => $questionFigurePath,
                'question_order' => $request->question_order ?? 0,
                'is_active'  => $request->boolean('is_active'),
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('admin.academic.questions.index')->with('success', 'Question has been created successfully!');
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Question could not be created. Please try again.');
        }
    }

    public function edit(AcaQuestion $question)
    {
        $examList = AcaExam::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();
        $courseList = AcaCourse::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();
        return view('admin.academic.questions.form', compact('question', 'examList', 'courseList'));
    }

    public function update(QuestionFormRequest $request, AcaQuestion $question)
    {
        try {
            if ($request->hasFile('question_figure')) {
                $uploadPath = 'storage/question_figure/';

                // Delete old file if exists
                $oldFileName = $question->question_figure;
                if ($oldFileName) {
                    $oldFilePath = $uploadPath . $oldFileName;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $file      = $request->file('question_figure');
                $extension = $file->getClientOriginalExtension();
                $fileName  = substr(Str::slug($request->question_text), 0, 20) . '-' . time() . '.' . $extension;

                $file->move($uploadPath, $fileName);

                $question->question_figure = $fileName;
            }

            $question->update([
                'exam_id' => $request->exam_id,
                'question_type' => $request->question_type,
                'question_text' => $request->question_text,
                'difficulty_level' => $request->difficulty_level,
                'marks' => $request->marks,
                'evaluation_type' => $request->evaluation_type,
                'option_a' => $request->option_a,
                'option_b' => $request->option_b,
                'option_c' => $request->option_c,
                'option_d' => $request->option_d,
                'correct_answer' => $request->correct_answer,
                'question_order' => $request->question_order ?? 0,
                'is_active'  => $request->boolean('is_active'),
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('admin.academic.questions.index')->with('success', 'Question has been updated successfully!');
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Question update failed. Please try again.');
        }
    }

    public function destroy(AcaQuestion $question)
    {
        $question->delete(); // Soft delete

        return redirect()
            ->route('admin.academic.questions.index')
            ->with('status', 'Question has been deleted successfully!');
    }
}
