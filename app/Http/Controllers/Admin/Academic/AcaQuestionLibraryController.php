<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\QuestionLibraryFormRequest;
use App\Models\Academic\AcaCourse;
use App\Models\Academic\AcaQuestionLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AcaQuestionLibraryController extends Controller
{
    public function index()
    {
        $serialNo = 1;
        $questionList = AcaQuestionLibrary::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();
        return view('admin.academic.questionsLibrary.index', compact('serialNo', 'questionList'));
    }

    public function create()
    {
        $courseList = AcaCourse::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();
        return view('admin.academic.questionsLibrary.form', compact('courseList'));
    }

    public function store(QuestionLibraryFormRequest $request)
    {
        try {
            $questionLibFigurePath = null;

            if ($request->hasFile('question_figure')) {
                $uploadPath = 'storage/question_figure/library/';

                $file = $request->file('question_figure');
                $extension = $file->getClientOriginalExtension();
                // $fileName = 'question_text-' . time() . '.' . $extension;
                $fileName = substr(Str::slug($request->question_text), 0, 20) . '-' . time() . '.' . $extension;

                $file->move($uploadPath, $fileName);

                $questionLibFigurePath = $fileName;
            }

            AcaQuestionLibrary::create([
                'topic' => $request->topic,
                'question_type' => $request->question_type,
                'question_text' => $request->question_text,
                'option_a' => $request->option_a,
                'option_b' => $request->option_b,
                'option_c' => $request->option_c,
                'option_d' => $request->option_d,
                'correct_answer' => $request->correct_answer,
                'question_figure' => $questionLibFigurePath,
                'is_active'  => $request->boolean('is_active'),
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('admin.academic.questions.library.index')->with('success', 'Question has been created successfully to library!');
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Question could not be created. Please try again.');
        }
    }

    public function edit(AcaQuestionLibrary $questionLib)
    {
        $courseList = AcaCourse::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();
        return view('admin.academic.questionsLibrary.form', compact('questionLib', 'courseList'));
    }

    public function update(QuestionLibraryFormRequest $request, AcaQuestionLibrary $questionLib)
    {
        try {
            if ($request->hasFile('question_figure')) {
                $uploadPath = 'storage/question_figure/library/';

                // Delete old file if exists
                $oldFileName = $questionLib->question_figure;
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

                $questionLib->question_figure = $fileName;
            }

            $questionLib->update([
                'topic' => $request->topic,
                'question_type' => $request->question_type,
                'question_text' => $request->question_text,
                'option_a' => $request->option_a,
                'option_b' => $request->option_b,
                'option_c' => $request->option_c,
                'option_d' => $request->option_d,
                'correct_answer' => $request->correct_answer,
                'is_active'  => $request->boolean('is_active'),
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('admin.academic.questions.library.index')->with('success', 'Question has been updated successfully to library!');
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Question update failed. Please try again.');
        }
    }

    public function destroy(AcaQuestionLibrary $questionLib)
    {
        $questionLib->delete(); // Soft delete

        return redirect()
            ->route('admin.academic.questions.library.index')
            ->with('status', 'Question has been deleted successfully!');
    }
}
