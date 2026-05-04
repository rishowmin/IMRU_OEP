<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\ExamFormRequest;
use App\Http\Requests\Academic\QuestionFormRequest;
use App\Models\Academic\AcaCourse;
use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaExamRule;
use App\Models\Academic\AcaExamRuleMap;
use App\Models\Academic\AcaQuestion;
use App\Models\Academic\AcaQuestionLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AcaExamController extends Controller
{
    public function index()
    {
        $serialNo = 1;
        $examList = AcaExam::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();
        return view('admin.academic.exams.index', compact('serialNo', 'examList'));
    }

    public function create()
    {
        $courseList = AcaCourse::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();
        return view('admin.academic.exams.form', compact('courseList'));
    }

    public function store(ExamFormRequest $request)
    {
        try {
            AcaExam::create([
                'course_id' => $request->course_id,
                'exam_title' => $request->exam_title,
                'exam_code' => $request->exam_code,
                'exam_type' => $request->exam_type,
                'exam_date' => $request->exam_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'exam_duration_min' => $request->exam_duration_min,
                'total_marks' => $request->total_marks,
                'passing_marks' => $request->passing_marks,
                'total_questions' => $request->total_questions,
                'instructions' => $request->instructions,
                'basic_rules' => $request->basic_rules,
                'is_active'  => $request->boolean('is_active'),
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('admin.academic.exams.index')->with('success', 'Exam has been created successfully!');
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Exam could not be created. Please try again.');
        }
    }

    public function edit(AcaExam $exam)
    {
        $courseList = AcaCourse::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();
        return view('admin.academic.exams.form', compact('exam', 'courseList'));
    }

    public function update(ExamFormRequest $request, AcaExam $exam)
    {
        try {
            $exam->update([
                'course_id' => $request->course_id,
                'exam_title' => $request->exam_title,
                'exam_code' => $request->exam_code,
                'exam_type' => $request->exam_type,
                'exam_date' => $request->exam_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'exam_duration_min' => $request->exam_duration_min,
                'total_marks' => $request->total_marks,
                'passing_marks' => $request->passing_marks,
                'total_questions' => $request->total_questions,
                'instructions' => $request->instructions,
                'basic_rules' => $request->basic_rules,
                'is_active'  => $request->boolean('is_active'),
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('admin.academic.exams.index')->with('success', 'Exam has been updated successfully!');
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Exam update failed. Please try again.');
        }
    }

    public function destroy(AcaExam $exam)
    {
        $exam->delete(); // Soft delete

        return redirect()
            ->route('admin.academic.exams.index')
            ->with('status', 'Exam has been deleted successfully!');
    }

    public function examSettings(AcaExam $exam)
    {
        $allRules = AcaExamRule::whereNull('deleted_at')
            ->orderBy('type')
            ->orderBy('order')
            ->get()
            ->groupBy('type');

        $mappedRuleIds = AcaExamRuleMap::where('exam_id', $exam->id)
            ->where('is_active', true)
            ->pluck('rule_id')
            ->toArray();

        return view('admin.academic.exams.exam_settings', compact('exam', 'allRules', 'mappedRuleIds'));
    }

    public function updateExamSettings(Request $request, AcaExam $exam)
    {
        $selectedRuleIds = $request->input('rules', []);

        // Get all existing maps for this exam
        $existingMaps = AcaExamRuleMap::where('exam_id', $exam->id)->get();

        foreach ($existingMaps as $map) {
            if (in_array($map->rule_id, $selectedRuleIds)) {
                // Rule is checked — ensure active
                $map->update(['is_active' => true]);
            } else {
                // Rule is unchecked — set inactive
                $map->update(['is_active' => false]);
            }
        }

        // Create new maps for newly checked rules
        $existingRuleIds = $existingMaps->pluck('rule_id')->toArray();
        foreach ($selectedRuleIds as $ruleId) {
            if (!in_array($ruleId, $existingRuleIds)) {
                AcaExamRuleMap::create([
                    'exam_id'    => $exam->id,
                    'rule_id'    => $ruleId,
                    'is_active'  => true,
                    'order'      => 0,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Exam settings updated successfully.');
    }








    public function questionPaper(AcaExam $exam)
    {
        $serialNo = 1;
        $exam->load('questions');
        $questionLibrary = AcaQuestionLibrary::where('is_active', true)
            ->orderBy('topic')
            ->orderBy('id')
            ->get()
            ->groupBy('topic');

        return view('admin.academic.exams.question_paper', compact('exam', 'questionLibrary', 'serialNo'));
    }

    public function storeQuestion(QuestionFormRequest $request, AcaExam $exam)
    {
        try {
            $questionFigurePath = null;

            if ($request->hasFile('question_figure')) {
                $uploadPath = 'storage/question_figure/';
                $file       = $request->file('question_figure');
                $extension  = $file->getClientOriginalExtension();
                $fileName   = substr(Str::slug($request->question_text), 0, 20) . '-' . time() . '.' . $extension;
                $file->move($uploadPath, $fileName);
                $questionFigurePath = $fileName;
            }

            AcaQuestion::create([
                'exam_id'          => $exam->id,
                'question_type'    => $request->question_type,
                'question_text'    => $request->question_text,
                'difficulty_level' => $request->difficulty_level,
                'marks'            => $request->marks,
                'evaluation_type'  => $request->evaluation_type,
                'option_a'         => $request->option_a,
                'option_b'         => $request->option_b,
                'option_c'         => $request->option_c,
                'option_d'         => $request->option_d,
                'correct_answer'   => $request->correct_answer,
                'question_figure'  => $questionFigurePath,
                'question_order'   => $request->question_order ?? 0,
                'is_active'        => true,
                'created_by'       => auth()->id(),
            ]);

            $exam->update([
                'total_questions' => $exam->questions()->count(),
                'total_marks'     => $exam->questions()->sum('marks'),
                'passing_marks'   => $exam->questions()->sum('marks') * 0.4,
            ]);

            return redirect()->route('admin.academic.exams.questionPaper', $exam->id)
                ->with('success', 'Question added successfully.');

        } catch (\Throwable $exception) {
            return back()->withInput()
                ->with('error', 'Question could not be created. Please try again.' . $exception->getMessage());
        }
    }

    public function storeFromLibrary(Request $request, AcaExam $exam)
    {
        $request->validate([
            'library_question_ids'   => ['required', 'array', 'min:1'],
            'library_question_ids.*' => ['integer', 'exists:aca_question_libraries,id'],
        ]);

        try {
            $libraries = AcaQuestionLibrary::whereIn('id', $request->library_question_ids)
                                        ->where('is_active', true)
                                        ->get();

            foreach ($libraries as $lib) {
                $alreadyExists = $exam->questions()
                                    ->where('question_text', $lib->question_text)
                                    ->exists();
                if ($alreadyExists) continue;

                // Copy question_figure from library folder to question folder
                $copiedFigure = null;
                if ($lib->question_figure) {
                    $sourcePath = public_path('storage/question_figure/library/' . $lib->question_figure);

                    if (file_exists($sourcePath)) {
                        $extension   = pathinfo($lib->question_figure, PATHINFO_EXTENSION);
                        $newFileName = substr(Str::slug(Str::limit($lib->question_text, 20)), 0, 20)
                                    . '-' . time() . '-' . uniqid()
                                    . '.' . $extension;

                        $destPath = public_path('storage/question_figure/' . $newFileName);

                        copy($sourcePath, $destPath);
                        $copiedFigure = $newFileName;
                    }
                }

                AcaQuestion::create([
                    'exam_id'          => $exam->id,
                    'question_type'    => $lib->question_type,
                    'question_text'    => $lib->question_text,
                    'difficulty_level' => $lib->difficulty_level ?? 'medium',
                    'marks'            => $lib->marks            ?? 1,
                    'evaluation_type'  => $lib->correct_answer ? 'automatic' : 'manual',
                    'option_a'         => $lib->option_a,
                    'option_b'         => $lib->option_b,
                    'option_c'         => $lib->option_c,
                    'option_d'         => $lib->option_d,
                    'correct_answer'   => $lib->correct_answer,
                    'question_figure'  => $copiedFigure,
                    'question_order'   => $lib->question_order ?? 0,
                    'is_active'        => true,
                    'created_by'       => auth()->id(),
                ]);
            }

            $exam->update([
                'total_questions' => $exam->questions()->count(),
                'total_marks'     => $exam->questions()->sum('marks'),
                'passing_marks'   => $exam->questions()->sum('marks') * 0.4,
            ]);

            return redirect()->route('admin.academic.exams.questionPaper', $exam->id)
                            ->with('success', $libraries->count() . ' question(s) added from library successfully.');

        } catch (\Throwable $exception) {
            return back()->with('error', 'Questions could not be added. ' . $exception->getMessage());
        }
    }

    public function updateQuestion(QuestionFormRequest $request, AcaExam $exam, AcaQuestion $question)
    {
        try {
            if ($request->hasFile('question_figure')) {
                $uploadPath = 'storage/question_figure/';

                // Delete old
                if ($question->question_figure && file_exists($uploadPath . $question->question_figure)) {
                    unlink($uploadPath . $question->question_figure);
                }

                $file      = $request->file('question_figure');
                $extension = $file->getClientOriginalExtension();
                $fileName  = substr(Str::slug($request->question_text), 0, 20) . '-' . time() . '.' . $extension;
                $file->move($uploadPath, $fileName);
                $question->question_figure = $fileName;
            }

            $question->update([
                'topic'            => $request->topic ?? 'General',
                'question_type'    => $request->question_type,
                'question_text'    => $request->question_text,
                'difficulty_level' => $request->difficulty_level,
                'marks'            => $request->marks,
                'evaluation_type'  => $request->evaluation_type,
                'option_a'         => $request->option_a,
                'option_b'         => $request->option_b,
                'option_c'         => $request->option_c,
                'option_d'         => $request->option_d,
                'correct_answer'   => $request->correct_answer,
                'question_order'   => $request->question_order ?? $question->question_order,
                'updated_by'       => auth()->id(),
            ]);

            $exam->update([
                'total_marks' => $exam->questions()->sum('marks'),
                'passing_marks'   => $exam->questions()->sum('marks') * 0.4,
            ]);

            return redirect()->route('admin.academic.exams.questionPaper', $exam->id)
                ->with('success', 'Question updated successfully.');

        } catch (\Throwable $exception) {
            return back()->withInput()
                ->with('error', 'Question could not be updated. Please try again.');
        }
    }

    public function destroyQuestion(AcaExam $exam, AcaQuestion $question)
    {
        $question->delete(); // Soft delete

        return redirect()
            ->route('admin.academic.exams.questionPaper', $exam->id)
            ->with('status', 'Question has been deleted successfully!');
    }
}
