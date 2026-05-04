<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\ExamRuleFormRequest;
use App\Models\Academic\AcaExamRule;
use Illuminate\Http\Request;

class AcaExamRuleController extends Controller
{
    public function index()
    {
        $serialNo = 1;
        $ruleList = AcaExamRule::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();

        return view('admin.academic.examRules.index', compact('ruleList', 'serialNo'));
    }

    public function store(ExamRuleFormRequest $request)
    {
        try {
            AcaExamRule::create([
                'type'        => $request->type,
                'key'         => $request->key,
                'title'       => $request->title,
                'description' => $request->description ?? null,
                'order'       => $request->order ?? 0,
                'is_active'   => $request->boolean('is_active'),
                'created_by'  => auth()->id(),
            ]);

            return redirect()->route('admin.academic.examRules.index')
                ->with('success', 'Exam rule has been created successfully!');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Exam rule failed. Please try again.');
        }
    }

    public function edit(AcaExamRule $examRule)
    {
        $serialNo    = 1;
        $ruleList    = AcaExamRule::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();

        return view('admin.academic.examRules.index', compact('examRule', 'ruleList', 'serialNo'));
    }

    public function update(ExamRuleFormRequest $request, AcaExamRule $examRule)
    {
        try {
            $examRule->update([
                'type'        => $request->type,
                'key'         => $request->key,
                'title'       => $request->title,
                'description' => $request->description ?? null,
                'order'       => $request->order ?? 0,
                'is_active'   => $request->boolean('is_active'),
                'updated_by'  => auth()->id(),
            ]);

            return redirect()->route('admin.academic.examRules.index')
                ->with('success', 'Exam rule has been updated successfully!');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Exam rule update failed. Please try again.');
        }
    }

    public function destroy(AcaExamRule $examRule)
    {
        $examRule->delete();

        return redirect()->route('admin.academic.examRules.index')
            ->with('status', 'Exam rule has been deleted successfully!');
    }
}
