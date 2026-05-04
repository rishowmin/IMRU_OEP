<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\EnrollmentFormRequest;
use App\Models\Academic\AcaCourse;
use App\Models\Academic\AcaEnrollment;
use App\Models\Student;
use Illuminate\Http\Request;

class AcaEnrollmentController extends Controller
{
    public function index()
    {
        $serialNo = 1;
        $courses = AcaCourse::where('is_active', 1)->get();
        $students = Student::where('is_active', 1)->get();
        $enrollments = AcaEnrollment::with(['course', 'student'])->get();

        return view('admin.academic.enrollments.index', compact('courses', 'students', 'enrollments', 'serialNo'));
    }

    // public function store(EnrollmentFormRequest $request)
    // {
    //     try {
    //         AcaEnrollment::create([
    //             'course_id'  => $request->course_id,
    //             'student_id' => $request->student_id,
    //             'is_active'  => $request->boolean('is_active'),
    //             'created_by' => auth()->id(),
    //         ]);

    //         return redirect()->route('admin.academic.enrollments.index')
    //             ->with('success', 'Enrollment has been created successfully!');
    //     } catch (\Throwable $e) {
    //         return back()->withInput()->with('error', 'Enrollment failed. Please try again.');
    //     }
    // }

    public function store(EnrollmentFormRequest $request)
    {
        try {
            $studentIds = (array) $request->input('student_id');
            $courseId   = $request->course_id;
            $isActive   = $request->boolean('is_active');
            $createdBy  = auth()->id();

            $enrolled  = 0;
            $skipped   = 0;

            foreach ($studentIds as $studentId) {
                // Skip if this student is already enrolled in this course
                $alreadyExists = AcaEnrollment::where('course_id', $courseId)
                    ->where('student_id', $studentId)
                    ->exists();

                if ($alreadyExists) {
                    $skipped++;
                    continue;
                }

                AcaEnrollment::create([
                    'course_id'  => $courseId,
                    'student_id' => $studentId,
                    'is_active'  => $isActive,
                    'created_by' => $createdBy,
                ]);

                $enrolled++;
            }

            // Build a meaningful feedback message
            $message = match(true) {
                $enrolled > 0 && $skipped > 0 => "{$enrolled} student(s) enrolled successfully. {$skipped} skipped (already enrolled).",
                $enrolled > 0                 => "{$enrolled} student(s) enrolled successfully!",
                default                       => "No new enrollments. All selected student(s) are already enrolled in this course.",
            };

            return redirect()->route('admin.academic.enrollments.index')
                ->with($enrolled > 0 ? 'success' : 'status', $message);

        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Enrollment failed. Please try again.');
        }
    }

    public function edit(AcaEnrollment $enroll)
    {
        $serialNo    = 1;
        $courses     = AcaCourse::where('is_active', 1)->get();
        $students    = Student::where('is_active', 1)->get();
        $enrollments = AcaEnrollment::with(['course', 'student'])->get();

        return view('admin.academic.enrollments.index', compact('enroll', 'courses', 'students', 'enrollments', 'serialNo'));
    }

    public function update(EnrollmentFormRequest $request, AcaEnrollment $enroll)
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

    public function destroy(AcaEnrollment $enroll)
    {
        $enroll->delete();

        return redirect()->route('admin.academic.enrollments.index')
            ->with('status', 'Enrollment has been deleted successfully!');
    }
}
