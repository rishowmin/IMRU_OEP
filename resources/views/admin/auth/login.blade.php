@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6 d-flex flex-column align-items-center justify-content-center">

                    <div class="d-flex justify-content-center py-4">
                        <a href="index.html" class="logo d-flex align-items-center w-auto">
                            <img src="{{ asset('assets/admin/img/brand/logo.png') }}" alt="IMRU OEP Logo" width="100">
                        </a>
                    </div><!-- End Logo -->

                    <div class="card mb-3">

                        <div class="card-body">

                            <div class="pb-2">
                                <h5 class="card-title text-center pt-2 pb-0 fs-4">Admin Login</h5>
                                <p class="text-center small">Enter your email & password to login</p>
                            </div>


                            <form method="POST" action="{{ route('admin.login') }}" class="row g-3 needs-validation" novalidate>
                                @csrf

                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text brr-0" id="inputGroupPrepend"><i class="bi bi-envelope auth-icon"></i></span>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" autofocus>
                                    </div>
                                    @error('email')
                                    <small class="text-danger d-flex mt-1">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text brr-0" id="inputGroupPrepend"><i class="bi bi-key auth-icon"></i></span>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                                        <button class="btn btn-outline-theme" type="button" id="password-toggle">
                                            <i class="bi bi-eye-slash" id="password-icon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                    <small class="text-danger d-flex mt-1">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div class="form-check mb-0 small">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                        <div class="forgot-password small">
                                            <a href="{{ route('admin.password.request') }}">Forgot Password?</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-outline-theme w-100">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>
                                        {{ __('Login') }}
                                    </button>
                                </div>

                                <div class="col-12">
                                    <p class="small mb-0 w-100 text-center">
                                        <span>Don't have account?</span>
                                        <a href="{{ route('admin.register') }}">Create an account <i class="bi bi-person-add"></i></a>
                                    </p>
                                </div>
                            </form>

                        </div>
                    </div>

                    <div class="credits small">
                        Developed by <a href="https://github.com/rishowmin" target="_blank">Muhammad Raisul Islam, IIT, JU</a>
                    </div>

                </div>
            </div>
        </div>

    </section>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rows = document.querySelectorAll(".credential-row");

        rows.forEach(row => {
            row.addEventListener("click", function() {
                const email = this.dataset.email;
                const password = this.dataset.password;

                document.getElementById("email").value = email;
                document.getElementById("password").value = password;
            });
        });

        // Password toggle functionality
        const passwordToggle = document.getElementById('password-toggle');
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('password-icon');

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

