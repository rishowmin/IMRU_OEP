@extends('layouts.auth')
@section('title', 'Academic Login')

@section('content')

<div class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="back-to-home">
                    <a href="{{ url('/') }}"><i class="bi bi-arrow-left"></i></a>
                </div>
                <div class="row justify-content-center">
                    <a href="{{ url('/') }}" class="logo login-logo d-flex align-items-center w-auto">
                        <img src="{{ asset('assets/admin/img/brand/logo.png') }}" alt="IMRU OEP Logo" width="100%">
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-lg-9 col-sm-12">

                        {{-- Toggle Buttons --}}
                        <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                            <button type="button" class="btn btn-outline-theme px-4 login-toggle-btn" id="showStudentLogin">
                                <i class="bi bi-person me-1"></i>Student Login
                            </button>
                            <button type="button" class="btn btn-outline-theme px-4 login-toggle-btn" id="showTeacherLogin">
                                <i class="bi bi-person-badge me-1"></i>Teacher Login
                            </button>
                        </div>

                        {{-- Student Login Form --}}
                        <div id="studentLoginForm">
                            @include('student.auth.login')
                        </div>

                        {{-- Teacher Login Form --}}
                        <div id="teacherLoginForm" style="display: none;">
                            @include('teacher.auth.login')
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="row justify-content-center text-center mt-2">
            <div class="credits small">
                Developed by <a href="https://github.com/rishowmin" target="_blank">Muhammad Raisul Islam, IIT, JU</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const studentBtn      = document.getElementById('showStudentLogin');
    const teacherBtn      = document.getElementById('showTeacherLogin');
    const studentForm     = document.getElementById('studentLoginForm');
    const teacherForm     = document.getElementById('teacherLoginForm');

    function showStudent() {
        studentForm.style.display = 'block';
        teacherForm.style.display = 'none';
        studentBtn.classList.add('active');
        teacherBtn.classList.remove('active');
        localStorage.setItem('academicLoginTab', 'student');
    }

    function showTeacher() {
        teacherForm.style.display = 'block';
        studentForm.style.display = 'none';
        teacherBtn.classList.add('active');
        studentBtn.classList.remove('active');
        localStorage.setItem('academicLoginTab', 'teacher');
    }

    studentBtn.addEventListener('click', showStudent);
    teacherBtn.addEventListener('click', showTeacher);

    // Restore last active tab
    const savedTab = localStorage.getItem('academicLoginTab') || 'student';
    if (savedTab === 'teacher') {
        showTeacher();
    } else {
        showStudent();
    }

    // ======= Student Password Toggle =======
    const studentPasswordToggle = document.getElementById('student_password_toggle');
    const studentPasswordInput  = document.getElementById('student_password');
    const studentPasswordIcon   = document.getElementById('student_password_icon');

    if (studentPasswordToggle) {
        studentPasswordToggle.addEventListener('click', function () {
            if (studentPasswordInput.type === 'password') {
                studentPasswordInput.type = 'text';
                studentPasswordIcon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                studentPasswordInput.type = 'password';
                studentPasswordIcon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });
    }

    // ======= Teacher Password Toggle =======
    const teacherPasswordToggle = document.getElementById('teacher_password_toggle');
    const teacherPasswordInput  = document.getElementById('teacher_password');
    const teacherPasswordIcon   = document.getElementById('teacher_password_icon');

    if (teacherPasswordToggle) {
        teacherPasswordToggle.addEventListener('click', function () {
            if (teacherPasswordInput.type === 'password') {
                teacherPasswordInput.type = 'text';
                teacherPasswordIcon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                teacherPasswordInput.type = 'password';
                teacherPasswordIcon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });
    }

});
</script>
@endsection
