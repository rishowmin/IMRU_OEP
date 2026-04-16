<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\ExamFormRequest;
use App\Models\Academic\Course;
use App\Models\Academic\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $serialNo = 1;
        $examList = Exam::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();
        return view('admin.academic.exams.index', compact('serialNo', 'examList'));
    }

    public function create()
    {
        $courseList = Course::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();
        return view('admin.academic.exams.form', compact('courseList'));
    }

    public function store(ExamFormRequest $request)
    {
        try {
            Exam::create([
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
                'is_active' => $request->has('is_active') ? 1 : 0,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('admin.academic.exams.index')->with('success', 'Exam has been created successfully!');
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Exam could not be created. Please try again.');
        }
    }

    public function edit(Exam $exam)
    {
        $courseList = Course::orderBy('id', 'ASC')->where('deleted_at', NULL)->get();
        return view('admin.academic.exams.form', compact('exam', 'courseList'));
    }

    public function update(ExamFormRequest $request, Exam $exam)
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
                'is_active' => $request->has('is_active') ? 1 : 0,
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('admin.academic.exams.index')->with('success', 'Exam has been updated successfully!');
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Exam update failed. Please try again.');
        }
    }

    public function destroy(Exam $exam)
    {
        $exam->delete(); // Soft delete

        return redirect()
            ->route('admin.academic.exams.index')
            ->with('status', 'Exam has been deleted successfully!');
    }
}
