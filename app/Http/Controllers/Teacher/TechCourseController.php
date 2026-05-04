<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\CourseFormRequest;
use App\Models\Academic\AcaCourse;
use Illuminate\Http\Request;

class TechCourseController extends Controller
{
    public function index()
    {
        $serialNo = 1;
        $courseList = AcaCourse::orderBy('id', 'ASC')
                    ->where('deleted_at', NULL)
                    ->where('aca_created_by', auth('teacher')->id())
                    ->get();
        return view('teacher.courses.index', compact('serialNo', 'courseList'));
    }

    public function create()
    {
        return view('teacher.courses.form');
    }

    public function store(CourseFormRequest $request)
    {
        try {
            AcaCourse::create([
                'course_title' => $request->course_title,
                'course_code' => $request->course_code,
                'credits' => $request->credits,
                'description' => $request->description,
                'is_active'  => $request->boolean('is_active'),
                'aca_created_by' => auth()->id(),
            ]);

            return redirect()->route('teacher.courses.index')->with('success', 'Course has been created successfully!');
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Course could not be created. Please try again.');
        }
    }

    public function edit(AcaCourse $course)
    {
        return view('teacher.courses.form', compact('course'));
    }

    public function update(CourseFormRequest $request, AcaCourse $course)
    {
        try {
            $course->update([
                'course_title' => $request->course_title,
                'course_code' => $request->course_code,
                'credits' => $request->credits,
                'description' => $request->description,
                'is_active'  => $request->boolean('is_active'),
                'aca_updated_by' => auth()->id(),
            ]);

            return redirect()->route('teacher.courses.index')->with('success', 'Course has been updated successfully!');
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Course update failed. Please try again.');
        }
    }



    public function destroy(AcaCourse $course)
    {
        $course->delete(); // Soft delete

        return redirect()
            ->route('teacher.courses.index')
            ->with('status', 'Course has been deleted successfully!');
    }
}
