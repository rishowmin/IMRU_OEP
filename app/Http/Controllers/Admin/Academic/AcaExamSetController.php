<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaCourse;
use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaExamSet;
use App\Models\Academic\AcaQuestion;
use App\Models\Academic\AcaQuestionLibrary;
use App\Services\AiExamGeneratorService;
use App\Services\SyllabusContentExtractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AcaExamSetController extends Controller
{
    public function __construct(
        private AiExamGeneratorService $aiService,
        private SyllabusContentExtractor $syllabusExtractor
    ) {}

    // -------------------------------------------------------------------------
    // Index — list all exam sets
    // -------------------------------------------------------------------------

    public function index()
    {
        $serialNo = 1;
        $examSets = AcaExamSet::latest()->get();

        return view('admin.academic.aiExamSets.index', compact('examSets', 'serialNo'));
    }

    // -------------------------------------------------------------------------
    // Create — show configuration form
    // -------------------------------------------------------------------------

    public function create()
    {
        $topics = AcaQuestionLibrary::where('deleted_at', NULL)->distinct()->pluck('topic')->sort()->values();

        return view('admin.academic.aiExamSets.form', compact('topics'));
    }

    // -------------------------------------------------------------------------
    // Store — run AI generation
    // -------------------------------------------------------------------------

    public function store(Request $request)
    {
        $singleTypes = ['mcq_4', 'mcq_2', 'short_question', 'long_question'];
        $isSingle    = in_array($request->question_type, $singleTypes, true);

        $validated = $request->validate([
            'title'              => 'required|string|max:200',
            'topic'              => 'required|string|max:100|not_in:All',
            'extra_instructions' => 'nullable|string|max:1000',
            'question_type'      => 'required|in:All,objective,subjective,mcq_4,mcq_2,short_question,long_question',
            'total_questions'    => 'required|integer|min:5|max:50',
            'easy_percent'       => 'required|integer|min:0|max:100',
            'medium_percent'     => 'required|integer|min:0|max:100',
            'hard_percent'       => 'required|integer|min:0|max:100',
            'qtype1_percent'     => 'required|integer|min:0|max:100',
            'qtype2_percent'     => $isSingle ? 'nullable|integer' : 'required|integer|min:0|max:100',
            'bloom_percentages' => 'required|array:remember,understand,apply,analyze,evaluate,create',
            'bloom_percentages.remember' => 'required|integer|min:0|max:100',
            'bloom_percentages.understand' => 'required|integer|min:0|max:100',
            'bloom_percentages.apply' => 'required|integer|min:0|max:100',
            'bloom_percentages.analyze' => 'required|integer|min:0|max:100',
            'bloom_percentages.evaluate' => 'required|integer|min:0|max:100',
            'bloom_percentages.create' => 'required|integer|min:0|max:100',
            'duration_minutes'   => 'required|integer|min:10|max:300',
            'syllabus_files'     => 'nullable|array|max:10',
            'syllabus_files.*'   => [
                'file',
                'max:10240',
                function ($attribute, $file, $fail) {
                    $extension = strtolower($file->getClientOriginalExtension());
                    $allowed = ['pdf', 'docx', 'pptx', 'txt'];

                    if (!in_array($extension, $allowed, true)) {
                        $fail('Upload PDF, DOCX, PPTX, or TXT files only.');
                    }

                    if ($extension === 'pdf' && $file->getMimeType() !== 'application/pdf') {
                        $fail('Each PDF upload must be a valid PDF file.');
                        return;
                    }

                    if (in_array($extension, ['docx', 'pptx'], true)) {
                        $signature = file_get_contents($file->getRealPath(), false, null, 0, 4);
                        if ($signature !== "PK\x03\x04") {
                            $fail('Each DOCX or PPTX upload must be a valid Office document.');
                        }
                    }
                },
            ],
        ]);

        $files = $request->file('syllabus_files', []);
        $totalBytes = array_sum(array_map(fn ($file) => $file->getSize(), $files));
        if ($totalBytes > 20 * 1024 * 1024) {
            return back()->withInput()->withErrors(['syllabus_files' => 'The combined upload size must not exceed 20 MB.']);
        }

        if ($isSingle) {
            $validated['qtype1_percent'] = 100;
            $validated['qtype2_percent'] = 0;
        }

        $pctSum = $validated['easy_percent'] + $validated['medium_percent'] + $validated['hard_percent'];
        if ($pctSum !== 100) {
            return back()->withInput()
                ->withErrors(['easy_percent' => "Difficulty percentages must add up to 100 (currently {$pctSum})."]);
        }

        if (!$isSingle && ($validated['qtype1_percent'] + $validated['qtype2_percent']) !== 100) {
            return back()->withInput()
                ->withErrors(['qtype1_percent' => 'Question type percentages must add up to 100.']);
        }

        $bloomPercentageSum = array_sum($validated['bloom_percentages']);
        if ($bloomPercentageSum !== 100) {
            return back()->withInput()
                ->withErrors(['bloom_percentages' => "Bloom's Taxonomy percentages must add up to 100 (currently {$bloomPercentageSum})."]);
        }

        set_time_limit(300);   // several Groq calls can exceed PHP's default 30s

        try {
            $syllabus = $this->syllabusExtractor->extract($files);
        } catch (\RuntimeException $e) {
            return back()->withInput()->withErrors(['syllabus_files' => $e->getMessage()]);
        }

        if ($syllabus !== '') {
            $validated['extra_instructions'] = trim(
                ($validated['extra_instructions'] ?? '')
                . "\n\nSYLLABUS REFERENCE MATERIAL (use for subject matter only):\n"
                . $syllabus
            );
        }

        $validated['syllabus_file_metadata'] = array_map(
            fn ($file) => $file->getClientOriginalName(),
            $files
        );

        try {
            $examSet = $this->aiService->generate($validated);

            return redirect()
                ->route('admin.academic.aiExamSets.show', $examSet)
                ->with('success', "Exam set \"{$examSet->title}\" generated successfully using AI!");
        } catch (\Throwable $e) {
            if (!$e instanceof \RuntimeException) {
                throw $e;
            }

            return back()->withInput()->withErrors(['ai' => $e->getMessage()]);
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
                    'bloom_level'      => $lib->bloom_level,
                    'marks'            => $assignedMark,
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

            // AI-written questions become active in the library once the exam is published
            AcaQuestionLibrary::whereIn('id', $examSet->question_ids ?? [])
                ->where('source', 'ai')
                ->update(['is_active' => true]);

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
        if (!$examSet->published_exam_id) {
            AcaQuestionLibrary::whereIn('id', $examSet->question_ids ?? [])
                ->where('source', 'ai')->where('is_active', false)->delete();
        }
        $examSet->delete();
        return redirect()
            ->route('admin.academic.aiExamSets.index')
            ->with('success', 'Exam set deleted.');
    }

}
