<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\AcaCourse;
use App\Models\Academic\AcaExam;
use App\Models\Academic\AcaExamInstance;
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

        return view('admin.academic.exams.aiExamSets.index', compact('examSets', 'topics', 'serialNo'));
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

        return view('admin.academic.exams.aiExamSets.form', compact('topics', 'questionStats'));
    }

    // -------------------------------------------------------------------------
    // Store — run AI generation
    // -------------------------------------------------------------------------

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:200',
            'topic'            => 'required|string|max:100',
            'total_questions'  => 'required|integer|min:5|max:200',
            'easy_percent'     => 'required|integer|min:0|max:100',
            'medium_percent'   => 'required|integer|min:0|max:100',
            'hard_percent'     => 'required|integer|min:0|max:100',
            'duration_minutes' => 'required|integer|min:10|max:300',
        ]);

        // Ensure percentages add up to 100
        $pctSum = $validated['easy_percent'] + $validated['medium_percent'] + $validated['hard_percent'];
        if ($pctSum !== 100) {
            return back()
                ->withInput()
                ->withErrors(['easy_percent' => "Difficulty percentages must add up to 100 (currently {$pctSum})."]);;
        }

        try {
            $examSet = $this->aiService->generate($validated);

            return redirect()
                ->route('admin.academic.aiExamSets.show', $examSet)
                ->with('success', "Exam set \"{$examSet->title}\" generated successfully using AI!");

        } catch (\RuntimeException $e) {
            return back()
                ->withInput()
                ->withErrors(['ai' => $e->getMessage()]);
        }
    }

    // -------------------------------------------------------------------------
    // Show — view generated exam set details
    // -------------------------------------------------------------------------

    public function show(AcaExamSet $examSet)
    {
        // $questions = $examSet->getQuestionsInOrder();
        // return view('admin.academic.exams.aiExamSets.show', compact('examSet', 'questions'));
        $questions  = $examSet->getQuestionsInOrder();
        $courseList = AcaCourse::whereNull('deleted_at')->orderBy('course_title')->get();

        return view('admin.academic.exams.aiExamSets.show', compact('examSet', 'questions', 'courseList'));
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
    // Publish to aca_exams & aca_questions
    // -------------------------------------------------------------------------

    public function publishToExam(Request $request, AcaExamSet $examSet)
    {
        $request->validate([
            'course_id'       => ['required', 'integer', 'exists:aca_courses,id'],
            'exam_date'       => ['nullable', 'date'],
            'start_time'      => ['nullable', 'date_format:H:i'],
            'end_time'        => ['nullable', 'date_format:H:i'],
            'total_questions' => ['nullable', 'integer', 'min:1', 'max:' . $examSet->total_questions],
        ]);

        if ($examSet->status !== 'active') {
            return back()->with('error', 'Only active exam sets can be published. Please activate it first.');
        }

        if ($examSet->published_exam_id) {
            return back()->with('error', 'This exam set has already been published as Exam #' . $examSet->published_exam_id . '.');
        }

        try {
            DB::beginTransaction();

            // How many questions to use (user can reduce, defaults to all)
            // $useCount  = $request->filled('total_questions')
            //                 ? (int) $request->total_questions
            //                 : $examSet->total_questions;

            // $questions = $examSet->getQuestionsInOrder()->take($useCount);
            $questions = $examSet->getQuestionsInOrder(); // no ->take()


            $useCount   = $request->filled('total_questions')
                            ? (int) $request->total_questions
                            : $examSet->total_questions;


            // ✅ Calculate total_marks from actual questions — not from request
            $totalMarks = $questions->sum('marks');

            $exam = AcaExam::create([
                'course_id'         => $request->course_id,
                'exam_title'        => $examSet->title,
                'exam_code'         => 'AI-' . strtoupper(Str::random(6)),
                'exam_type'         => 'ai_generated',
                'exam_date'         => $request->exam_date,
                'start_time'        => $request->start_time,
                'end_time'          => $request->end_time,
                'exam_duration_min' => $examSet->duration_minutes,
                'total_marks'       => $totalMarks,                          // ✅ from questions
                'passing_marks'     => round($totalMarks * 0.4, 2),          // ✅ 40% of actual marks
                'total_questions'   => $useCount,
                'comments'          => 'AI-generated exam. Easy: ' . $examSet->easy_count
                                    . ', Medium: ' . $examSet->medium_count
                                    . ', Hard: ' . $examSet->hard_count . '.',
                'is_active'         => true,
                'created_by'        => auth()->id(),
            ]);

            foreach ($questions as $index => $lib) {
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
                    'marks'            => $lib->marks ?? 1,
                    'evaluation_type'  => $lib->correct_answer ? 'automatic' : 'manual',
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
