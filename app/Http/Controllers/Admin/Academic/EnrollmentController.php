<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\EnrollmentFormRequest;
use App\Models\Academic\Course;
use App\Models\Academic\Enrollment;
use App\Models\Student;

class EnrollmentController extends Controller
{
    public function index()
    {
        $serialNo = 1;
        $courses = Course::where('is_active', 1)->get();
        $students = Student::where('is_active', 1)->get();
        $enrollments = Enrollment::with(['course', 'student'])->get();

        return view('admin.academic.enrollments.index', compact('courses', 'students', 'enrollments', 'serialNo'));
    }

    public function store(EnrollmentFormRequest $request)
    {
        try {
            Enrollment::create([
                'course_id'  => $request->course_id,
                'student_id' => $request->student_id,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('admin.academic.enrollments.index')
                ->with('success', 'Enrollment has been created successfully!');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Enrollment failed. Please try again.');
        }
    }

    public function edit(Enrollment $enroll)
    {
        $serialNo    = 1;
        $courses     = Course::where('is_active', 1)->get();
        $students    = Student::where('is_active', 1)->get();
        $enrollments = Enrollment::with(['course', 'student'])->get();

        return view('admin.academic.enrollments.index', compact('enroll', 'courses', 'students', 'enrollments', 'serialNo'));
    }

    public function update(EnrollmentFormRequest $request, Enrollment $enroll)
    {
        try {
            $enroll->update([
                'course_id'  => $request->course_id,
                'student_id' => $request->student_id,
                'is_active'  => $request->boolean('is_active'),
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('admin.academic.enrollments.index')
                ->with('success', 'Enrollment has been updated successfully!');
        } catch (\Throwable $e) {
            // \Log::error('Enrollment update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Enrollment update failed. Please try again.');
        }
    }

    public function destroy(Enrollment $enroll)
    {
        $enroll->delete();

        return redirect()->route('admin.academic.enrollments.index')
            ->with('status', 'Enrollment has been deleted successfully!');
    }
}
