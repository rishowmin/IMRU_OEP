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
                <div class="row">
                    <div class="col-sm-12">
                        <div class="d-flex align-items-center justify-content-between">
                            @include('teacher.auth.login')
                            <div class="divider"></div>
                            @include('student.auth.login')
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row justify-content-center text-center">
            <div class="credits small">
                Developed by <a href="https://github.com/rishowmin" target="_blank">Muhammad Raisul Islam, IIT, JU</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

// Teacher Login Password Toggle
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rows = document.querySelectorAll(".credential-row");

        rows.forEach(row => {
            row.addEventListener("click", function() {
                const email = this.dataset.email;
                const password = this.dataset.password;

                document.getElementById("student_email").value = email;
                document.getElementById("student_password").value = password;
            });
        });

        // Password toggle functionality
        const passwordToggle = document.getElementById('student_password_toggle');
        const passwordInput = document.getElementById('student_password');
        const passwordIcon = document.getElementById('student_password_icon');

        passwordToggle.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            }
        });
    });

</script>


// Student Login Password Toggle
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rows = document.querySelectorAll(".credential-row");

        rows.forEach(row => {
            row.addEventListener("click", function() {
                const email = this.dataset.email;
                const password = this.dataset.password;

                document.getElementById("teacher_email").value = email;
                document.getElementById("teacher_password").value = password;
            });
        });

        // Password toggle functionality
        const passwordToggle = document.getElementById('teacher_password_toggle');
        const passwordInput = document.getElementById('teacher_password');
        const passwordIcon = document.getElementById('teacher_password_icon');

        passwordToggle.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            }
        });
    });

</script>
@endsection
