<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\TeacherFormRequest;
use App\Models\TeacherInfo;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function index()
    {
        $serialNo = 1;
        $teacherList = Teacher::orderBy('id', 'ASC')->get();
        return view('admin.academic.teachers.index', compact('teacherList', 'serialNo'));
    }

    public function create()
    {
        return view('admin.academic.teachers.form');
    }

    public function store(TeacherFormRequest $request)
    {
        try {
            Teacher::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => $request->boolean('is_active'),
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('admin.academic.teachers.index')->with('success', 'Teacher has been created successfully!');
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Teacher could not be created. Please try again.');
        }
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.academic.teachers.form', compact('teacher'));
    }

    public function update(TeacherFormRequest $request, Teacher $teacher)
    {
        try {
            $data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'is_active' => $request->boolean('is_active'),
                'updated_by' => auth()->id(),
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $teacher->update($data);

            return redirect()->route('admin.academic.teachers.index')
                ->with('success', 'Teacher has been updated successfully!');
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Teacher update failed. Please try again.');
        }
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete(); // Soft delete

        return redirect()
            ->route('admin.academic.teachers.index')
            ->with('status', 'Teacher has been deleted successfully!');
    }



    // Teacher Profile
    public function teacherProfile(Teacher $teacher)
    {
        $teacher->load('info');
        return view('admin.academic.teachers.profile.form', compact('teacher'));
    }

    public function teacherProfileStore(Request $request, Teacher $teacher)
    {
        $request->validate([
            // Academic
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

        $data = $request->except(['profile_photo', 'signature', '_token']);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $uploadPath = 'storage/profile_photo/teacher/';

            // Delete old file if exists
            $oldFileName = optional($teacher->info)->profile_photo;
            if ($oldFileName && file_exists($uploadPath . $oldFileName)) {
                unlink($uploadPath . $oldFileName);
            }

            $file      = $request->file('profile_photo');
            $extension = $file->getClientOriginalExtension();
            $fileName  = 'teacher_photo_' . substr(Str::slug($teacher->first_name . '_' . $teacher->last_name), 0, 20) . '_' . time() . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $data['profile_photo'] = $fileName;
        }

        // Handle signature upload
        if ($request->hasFile('signature')) {
            $uploadPath = 'storage/signature/teacher/';

            // Delete old file if exists
            $oldSignature = optional($teacher->info)->signature;
            if ($oldSignature && file_exists($uploadPath . $oldSignature)) {
                unlink($uploadPath . $oldSignature);
            }

            $file      = $request->file('signature');
            $extension = $file->getClientOriginalExtension();
            $fileName  = 'teacher_signature_' . substr(Str::slug($teacher->first_name . '_' . $teacher->last_name), 0, 20) . '_' . time() . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $data['signature'] = $fileName;
        }

        $data['created_by'] = auth('admin')->id();
        $data['updated_by'] = auth('admin')->id();

        TeacherInfo::updateOrCreate(
            ['teacher_id' => $teacher->id],
            $data
        );

        return redirect()->route('admin.academic.teachers.profile', $teacher->id)
            ->with('success', 'Teacher profile saved successfully.');
    }
}
