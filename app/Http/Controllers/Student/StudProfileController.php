<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudProfileController extends Controller
{
    // Student Profile
    public function myProfile(Student $student)
    {
        $student->load('info');
        return view('student.myProfile.form', compact('student'));
    }

    public function myProfileStore(Request $request, Student $student)
    {
        $request->validate([
            'student_id_no' => ['nullable', 'string', 'unique:student_infos,student_id_no,' . optional($student->info)->id],
            'session' => ['nullable', 'string'],
            'batch' => ['nullable', 'string'],
            'semester' => ['nullable', 'string'],
            'department' => ['nullable', 'string'],
            'program' => ['nullable', 'string'],
            'admission_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'dob' => ['nullable', 'date'],
            'blood_group' => ['nullable', 'string'],
            'religion' => ['nullable', 'string'],
            'nationality' => ['nullable', 'string'],
            'marital_status' => ['nullable', 'in:single,married,divorced,widowed'],
            'nid_number' => ['nullable', 'string'],
            'birth_certificate_no' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string'],
            'emergency_contact_phone' => ['nullable', 'string'],
            'emergency_contact_relation' => ['nullable', 'string'],
            'present_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'district' => ['nullable', 'string'],
            'division' => ['nullable', 'string'],
            'postal_code' => ['nullable', 'string'],
            'country' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->except(['profile_photo', '_token']);

        if ($request->hasFile('profile_photo')) {
            $uploadPath = 'storage/profile_photo/student/';

            // Delete old file if exists
            $oldFileName = optional($student->info)->profile_photo;
            if ($oldFileName) {
                $oldFilePath = $uploadPath . $oldFileName;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $file = $request->file('profile_photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'profile_photo_' . substr(Str::slug($request->student_id_no), 0, 20) . '-' . time() . '.' . $extension;

            $file->move($uploadPath, $fileName);

            $data['profile_photo'] = $fileName; // ← add this line
        }

        $data['created_by'] = auth('admin')->id();
        $data['updated_by'] = auth('admin')->id();

        StudentInfo::updateOrCreate(
            ['student_id' => $student->id],
            $data
        );

        return redirect()->route('student.myProfile', $student->id)
            ->with('success', 'Student profile saved successfully.');
    }
}
