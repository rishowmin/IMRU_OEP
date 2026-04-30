<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StudentFormRequest;
use App\Models\Student;
use App\Models\StudentInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index()
    {
        $serialNo = 1;
        $studentList = Student::orderBy('id', 'ASC')->get();
        return view('admin.academic.students.index', compact('studentList', 'serialNo'));
    }

    public function create()
    {
        return view('admin.academic.students.form');
    }

    public function store(StudentFormRequest $request)
    {
        try {
            Student::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => $request->boolean('is_active'),
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('admin.academic.students.index')->with('success', 'Student has been created successfully!');
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Student could not be created. Please try again.');
        }
    }

    public function edit(Student $student)
    {
        return view('admin.academic.students.form', compact('student'));
    }

    public function update(StudentFormRequest $request, Student $student)
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

            $student->update($data);

            return redirect()->route('admin.academic.students.index')
                ->with('success', 'Student has been updated successfully!');
        } catch (\Throwable $exception) {
            return back()->withInput()->with('error', 'Student update failed. Please try again.');
        }
    }

    public function destroy(Student $student)
    {
        $student->delete(); // Soft delete

        return redirect()
            ->route('admin.academic.students.index')
            ->with('status', 'Student has been deleted successfully!');
    }



    // Student Profile
    public function studentProfile(Student $student)
    {
        $student->load('info');
        return view('admin.academic.students.profile.form', compact('student'));
    }

    public function studentProfileStore(Request $request, Student $student)
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

        return redirect()->route('admin.academic.students.profile', $student->id)
            ->with('success', 'Student profile saved successfully.');
    }

}
