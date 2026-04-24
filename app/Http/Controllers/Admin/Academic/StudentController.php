<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StudentFormRequest;
use App\Models\Academic\Course;
use App\Models\Academic\EnrollStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => $request->has('is_active') ? 1 : 0,
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
                'last_name'  => $request->last_name,
                'username'   => $request->username,
                'email'      => $request->email,
                'is_active' => $request->has('is_active') ? 1 : 0,
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

}
