<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaCourse;
use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaExamSet;
use App\Models\Academic\AcaQuestion;
use App\Models\Academic\AcaQuestionLibrary;
use App\Services\AiExamGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AcaExamSetController extends Controller
{
    public function __construct(
        private AiExamGeneratorService $aiService
    ) {}

    // -------------------------------------------------------------------------
    // Index — list all exam sets
    // -------------------------------------------------------------------------

    public function index()
    {
        $serialNo = 1;
        $examSets = AcaExamSet::latest()->get();

        $topics = AcaQuestionLibrary::active()
            ->distinct()
            ->pluck('topic')
            ->sort()
            ->values();

        return view('admin.academic.aiExamSets.index', compact('examSets', 'topics', 'serialNo'));
    }

    // -------------------------------------------------------------------------
    // Create — show configuration form
    // -------------------------------------------------------------------------

    public function create()
    {
        $topics = AcaQuestionLibrary::active()
            ->distinct()
            ->pluck('topic')
            ->sort()
            ->values();

        // Stats to help the user know what's available
        $questionStats = AcaQuestionLibrary::active()
            ->select('topic', 'difficulty_level', DB::raw('count(*) as count'))
            ->groupBy('topic', 'difficulty_level')
            ->get()
            ->groupBy('topic');

        return view('admin.academic.aiExamSets.form', compact('topics', 'questionStats'));
    }

    // -------------------------------------------------------------------------
    // Store — run AI generation
    // -------------------------------------------------------------------------

    public function store(Request $request)
    {
        $singleTypes = ['mcq_4', 'mcq_2', 'short_question', 'long_question'];
        $isSingle    = in_array($request->question_type, $singleTypes);

        $validated = $request->validate([
            'title'            => 'required|string|max:200',
            'topic'            => 'required|string|max:100',
            'question_type'    => 'required|string|max:100',
            'total_questions'  => 'required|integer|min:5|max:200',
            'easy_percent'     => 'required|integer|min:0|max:100',
            'medium_percent'   => 'required|integer|min:0|max:100',
            'hard_percent'     => 'required|integer|min:0|max:100',
            'qtype1_percent'   => 'required|integer|min:0|max:100',
            'qtype2_percent'   => $isSingle ? 'nullable|integer' : 'required|integer|min:0|max:100',
            'duration_minutes' => 'required|integer|min:10|max:300',
        ]);

        // For single types, force qt values
        if ($isSingle) {
            $validated['qtype1_percent'] = 100;
            $validated['qtype2_percent'] = 0;
        }

        // Validate difficulty percentages
        $pctSum = $validated['easy_percent'] + $validated['medium_percent'] + $validated['hard_percent'];
        if ($pctSum !== 100) {
            return back()->withInput()
                ->withErrors(['easy_percent' => "Difficulty percentages must add up to 100 (currently {$pctSum})."]);
        }

        // Validate question type percentages — skip for single types
        if (!$isSingle) {
            $qtSum = $validated['qtype1_percent'] + $validated['qtype2_percent'];
            if ($qtSum !== 100) {
                return back()->withInput()
                    ->withErrors(['qtype1_percent' => "Question type percentages must add up to 100 (currently {$qtSum})."]);
            }
        }

        try {
            $examSet = $this->aiService->generate($validated);

            return redirect()
                ->route('admin.academic.aiExamSets.show', $examSet)
                ->with('success', "Exam set \"{$examSet->title}\" generated successfully using AI!");

        } catch (\RuntimeException $e) {
            return back()->withInput()
                ->withErrors(['ai' => $e->getMessage()]);
        }
    }

    // -------------------------------------------------------------------------
    // Show — view generated exam set details
    // -------------------------------------------------------------------------

    public function show(AcaExamSet $examSet)
    {
        $questions  = $examSet->getQuestionsInOrder();
        $courseList = AcaCourse::whereNull('deleted_at')->orderBy('course_title')->get();

        return view('admin.academic.aiExamSets.show', compact('examSet', 'questions', 'courseList'));
    }

    // -------------------------------------------------------------------------
    // Activate / Deactivate status toggle
    // -------------------------------------------------------------------------

    public function updateStatus(Request $request, AcaExamSet $examSet)
    {
        $request->validate(['status' => 'required|in:draft,active,archived']);
        $examSet->update(['status' => $request->status]);

        return back()->with('success', 'Exam set status updated.');
    }

    // -------------------------------------------------------------------------
    // Update Question Marks
    // -------------------------------------------------------------------------

    public function updateMarks(Request $request, AcaExamSet $examSet)
    {
        $request->validate([
            'marks'   => ['required', 'array'],
            'marks.*' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        if ($examSet->published_exam_id) {
            return back()->with('error', 'Cannot update marks — this exam set is already published.');
        }

        try {
            $validIds    = array_flip($examSet->question_ids ?? []);
            $customMarks = [];
            $totalMarks  = 0;

            foreach ($request->input('marks', []) as $questionId => $mark) {
                if (!isset($validIds[(int) $questionId])) continue;

                // ✅ Store as string key (JSON always stores keys as strings)
                $customMarks[(string) $questionId] = (float) $mark;
                $totalMarks += (float) $mark;
            }

            // ✅ Save to exam_set — never touch aca_question_libraries
            $examSet->update([
                'custom_marks' => $customMarks,
                'total_marks'  => $totalMarks,
                'updated_by'   => auth()->id(),
            ]);

            return back()->with('success', 'Marks saved. Total marks: ' . $totalMarks);

        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to update marks: ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // Publish to aca_exams & aca_questions
    // -------------------------------------------------------------------------

    public function publishToExam(Request $request, AcaExamSet $examSet)
    {
        $request->validate([
            'course_id'  => ['required', 'integer', 'exists:aca_courses,id'],
            'exam_date'  => ['nullable', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time'   => ['nullable', 'date_format:H:i'],
        ]);

        if ($examSet->status !== 'active') {
            return back()->with('error', 'Only active exam sets can be published. Please activate it first.');
        }

        if ($examSet->published_exam_id) {
            return back()->with('error', 'This exam set has already been published as Exam #' . $examSet->published_exam_id . '.');
        }

        try {
            DB::beginTransaction();

            $questions = $examSet->getQuestionsInOrder();

            $useCount   = $request->filled('total_questions')
                            ? (int) $request->total_questions
                            : $examSet->total_questions;

            // ✅ Normalize custom_marks keys to string for reliable lookup
            $customMarks = collect($examSet->custom_marks ?? [])
                ->mapWithKeys(fn($mark, $id) => [(string) $id => (float) $mark])
                ->toArray();

            // ✅ Total marks = only for the questions student will see (useCount)
            // Take first $useCount questions for marks calculation
            $studentQuestions = $questions->take($useCount);

            $totalMarks = $studentQuestions->sum(function ($lib) use ($customMarks) {
                return $customMarks[(string) $lib->id] ?? (float) ($lib->marks ?? 1);
            });

            $exam = AcaExam::create([
                'course_id'         => $request->course_id,
                'exam_title'        => $examSet->title,
                'exam_code'         => 'AI-' . strtoupper(Str::random(6)),
                'exam_type'         => 'ai_generated',
                'exam_date'         => $request->exam_date,
                'start_time'        => $request->start_time,
                'end_time'          => $request->end_time,
                'exam_duration_min' => $examSet->duration_minutes,
                'total_marks'       => $totalMarks,
                'passing_marks'     => round($totalMarks * 0.4, 2),
                'total_questions'   => $useCount,
                'comments'          => 'AI-generated. Easy: ' . $examSet->easy_count
                                    . ', Medium: ' . $examSet->medium_count
                                    . ', Hard: ' . $examSet->hard_count . '.',
                'is_active'         => true,
                'created_by'        => auth()->id(),
            ]);

            foreach ($questions as $index => $lib) {

                // ✅ THIS is the key line — use custom mark if admin set it, else library mark
                $assignedMark = $customMarks[(string) $lib->id] ?? (float) ($lib->marks ?? 1);

                $copiedFigure = null;
                if ($lib->question_figure) {
                    $sourcePath = public_path('storage/question_figure/library/' . $lib->question_figure);
                    if (file_exists($sourcePath)) {
                        $extension   = pathinfo($lib->question_figure, PATHINFO_EXTENSION);
                        $newFileName = substr(Str::slug(Str::limit($lib->question_text, 20)), 0, 20)
                                    . '-' . time() . '-' . $lib->id . '.' . $extension;
                        $destPath    = public_path('storage/question_figure/' . $newFileName);
                        copy($sourcePath, $destPath);
                        $copiedFigure = $newFileName;
                    }
                }

                AcaQuestion::create([
                    'exam_id'          => $exam->id,
                    'question_type'    => $lib->question_type,
                    'question_text'    => $lib->question_text,
                    'difficulty_level' => $lib->difficulty_level ?? 'medium',
                    'marks'            => $assignedMark,
                    // 'evaluation_type'  => $lib->correct_answer ? 'automatic' : 'manual',
                    'evaluation_type' => $this->resolveEvaluationType($lib->question_type, $lib->correct_answer),
                    'option_a'         => $lib->option_a,
                    'option_b'         => $lib->option_b,
                    'option_c'         => $lib->option_c,
                    'option_d'         => $lib->option_d,
                    'correct_answer'   => $lib->correct_answer,
                    'question_figure'  => $copiedFigure,
                    'question_order'   => $index + 1,
                    'is_active'        => true,
                    'created_by'       => auth()->id(),
                ]);
            }

            $examSet->update([
                'published_exam_id' => $exam->id,
                'status'            => 'archived',
                'updated_by'        => auth()->id(),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.academic.exams.questionPaper', $exam->id)
                ->with('success', 'Exam "' . $exam->exam_title . '" created with ' . $questions->count() . ' questions. Total marks: ' . $totalMarks);

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Publishing failed: ' . $e->getMessage());
        }
    }

    private function resolveEvaluationType(string $questionType, $correctAnswer): string
    {
        // MCQ types — automatic if correct answer exists
        if (in_array($questionType, ['mcq_4', 'mcq_2'])) {
            return $correctAnswer ? 'automatic' : 'manual';
        }

        // Short/Long questions — always manual (teacher grades)
        if (in_array($questionType, ['short_question', 'long_question'])) {
            return 'manual';
        }

        // Fallback
        return $correctAnswer ? 'automatic' : 'manual';
    }

    // -------------------------------------------------------------------------
    // Destroy
    // -------------------------------------------------------------------------

    public function destroy(AcaExamSet $examSet)
    {
        $examSet->delete();
        return redirect()
            ->route('admin.academic.aiExamSets.index')
            ->with('success', 'Exam set deleted.');
    }

}
