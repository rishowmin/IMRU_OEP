<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TechProfileController extends Controller
{
    // Teacher Profile
    public function myProfile(Teacher $teacher)
    {
        $teacher->load('info');
        return view('teacher.modules.myProfile.form', compact('teacher'));
    }

    public function myProfileStore(Request $request, Teacher $teacher)
    {
        $request->validate([
            'teacher_id_no'              => ['nullable', 'string', 'unique:teacher_infos,teacher_id_no,' . optional($teacher->info)->id],
            'designation'                => ['nullable', 'string'],
            'department'                 => ['nullable', 'string'],
            'specialization'             => ['nullable', 'string'],
            'qualification'              => ['nullable', 'string'],
            'experience_years'           => ['nullable', 'integer', 'min:0'],
            'joining_date'               => ['nullable', 'date'],
            'gender'                     => ['nullable', 'in:male,female,other'],
            'dob'                        => ['nullable', 'date'],
            'blood_group'                => ['nullable', 'string'],
            'religion'                   => ['nullable', 'string'],
            'nationality'                => ['nullable', 'string'],
            'marital_status'             => ['nullable', 'in:single,married,divorced,widowed'],
            'nid_number'                 => ['nullable', 'string'],
            'birth_certificate_no'       => ['nullable', 'string'],
            'phone'                      => ['nullable', 'string'],
            'emergency_contact_name'     => ['nullable', 'string'],
            'emergency_contact_phone'    => ['nullable', 'string'],
            'emergency_contact_relation' => ['nullable', 'string'],
            'present_address'            => ['nullable', 'string'],
            'permanent_address'          => ['nullable', 'string'],
            'city'                       => ['nullable', 'string'],
            'district'                   => ['nullable', 'string'],
            'division'                   => ['nullable', 'string'],
            'postal_code'                => ['nullable', 'string'],
            'country'                    => ['nullable', 'string'],
            'linkedin'                   => ['nullable', 'url'],
            'google_scholar'             => ['nullable', 'url'],
            'researchgate'               => ['nullable', 'url'],
            'website'                    => ['nullable', 'url'],
            'bio'                        => ['nullable', 'string'],
            'profile_photo'              => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->except(['profile_photo', '_token']);

        if ($request->hasFile('profile_photo')) {
            $uploadPath = 'assets/storage/profile_photo/teacher/';

            // Delete old file if exists
            $oldFileName = optional($teacher->info)->profile_photo;
            if ($oldFileName) {
                $oldFilePath = $uploadPath . $oldFileName;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $file = $request->file('profile_photo');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'profile_photo_' . substr(Str::slug($request->teacher_id_no), 0, 20) . '-' . time() . '.' . $extension;

            $file->move($uploadPath, $fileName);

            $data['profile_photo'] = $fileName; // ← add this line
        }

        $data['created_by'] = auth('admin')->id();
        $data['updated_by'] = auth('admin')->id();

        TeacherInfo::updateOrCreate(
            ['teacher_id' => $teacher->id],
            $data
        );

        return redirect()->route('teacher.myProfile', $teacher->id)
            ->with('success', 'Teacher profile saved successfully.');
    }
}
