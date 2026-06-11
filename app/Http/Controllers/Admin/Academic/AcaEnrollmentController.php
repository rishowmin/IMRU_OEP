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
    private function getEnrollments()
    {
        return AcaEnrollment::with(['course', 'student.info'])
            ->whereHas('course')
            ->whereHas('student')
            ->get()
            ->groupBy('course_id');
    }

    public function index()
    {
        $serialNo    = 1;
        $courses     = AcaCourse::where('is_active', 1)->get();
        $students    = Student::where('is_active', 1)->get();
        $enrollments = $this->getEnrollments();

        return view('admin.academic.enrollments.index', compact('serialNo', 'courses', 'students', 'enrollments'));
    }

    public function store(EnrollmentFormRequest $request)
    {
        try {
            $studentIds = (array) $request->input('student_id');
            $courseId   = $request->course_id;
            $isActive   = $request->boolean('is_active');
            $createdBy  = auth()->id();

            $enrolled = 0;
            $skipped  = 0;
            $restored = 0;

            foreach ($studentIds as $studentId) {
                // Check active (non-deleted) enrollment
                $activeExists = AcaEnrollment::where('course_id', $courseId)
                    ->where('student_id', $studentId)
                    ->exists();

                if ($activeExists) {
                    $skipped++;
                    continue;
                }

                // Check soft-deleted enrollment
                $trashed = AcaEnrollment::withTrashed()
                    ->where('course_id', $courseId)
                    ->where('student_id', $studentId)
                    ->first();

                if ($trashed) {
                    // Restore and update instead of creating duplicate
                    $trashed->restore();
                    $trashed->update([
                        'is_active'  => $isActive,
                        'created_by' => $createdBy,
                        'updated_by' => $createdBy,
                    ]);
                    $restored++;
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

            $message = match(true) {
                ($enrolled + $restored) > 0 && $skipped > 0   => "{$enrolled} enrolled, {$restored} restored. {$skipped} skipped (already enrolled).",
                $enrolled > 0 && $restored > 0                => "{$enrolled} student(s) enrolled, {$restored} re-enrolled successfully!",
                $enrolled > 0                                 => "{$enrolled} student(s) enrolled successfully!",
                $restored > 0                                 => "{$restored} student(s) re-enrolled successfully!",
                default                                       => "No new enrollments. All selected student(s) are already enrolled.",
            };

            return redirect()->route('admin.academic.enrollments.index')
                ->with(($enrolled + $restored) > 0 ? 'success' : 'status', $message);

        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Enrollment failed. Please try again.');
        }
    }

    public function destroy(AcaEnrollment $enroll)
    {
        $enroll->delete();

        return redirect()->route('admin.academic.enrollments.index')
            ->with('status', 'Enrollment deleted successfully!');
    }

    // Bulk delete all enrollments for a course
    public function destroyCourse(AcaCourse $course)
    {
        $count = AcaEnrollment::where('course_id', $course->id)->count();
        AcaEnrollment::where('course_id', $course->id)->delete();

        return redirect()->route('admin.academic.enrollments.index')
            ->with('status', "All {$count} enrollment(s) for \"{$course->course_title}\" deleted successfully!");
    }
}
