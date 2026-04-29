@extends('layouts.auth')
@section('title', 'Register')

@section('content')
<div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-6 d-flex flex-column align-items-center justify-content-center">

                    <div class="d-flex justify-content-center py-4">
                        <a href="index.html" class="logo d-flex align-items-center w-auto">
                            <img src="{{ asset('assets/admin/img/brand/logo.png') }}" alt="IMRU OEP Logo" width="100">
                        </a>
                    </div><!-- End Logo -->

                    <div class="card mb-3">

                        <div class="card-body">

                            <div class="pb-2">
                                <h5 class="card-title text-center pt-2 pb-0 fs-4">Admin Register</h5>
                                <p class="text-center small">Enter your personal details to create account</p>
                            </div>

                            <form method="POST" action="{{ route('admin.register') }}" class="row g-3 needs-validation" novalidate>
                                @csrf

                                {{-- First Name --}}
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text brr-0" id="inputGroupPrepend"><i class="bi bi-person auth-icon"></i></span>
                                        <input id="first_name" type="text" class="form-control brr-0 @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" placeholder="First Name" autofocus>

                                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" placeholder="Last Name" autofocus>
                                    </div>
                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                {{-- Email Address --}}
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text brr-0" id="inputGroupPrepend"><i class="bi bi-envelope auth-icon"></i></span>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address">
                                    </div>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                {{-- Password --}}
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text brr-0" id="inputGroupPrepend"><i class="bi bi-at auth-icon"></i></span>
                                        <input id="password" type="password" class="form-control brr-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                                        <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                                        <button class="btn btn-outline-theme" type="button" id="password-toggle">
                                            <i class="bi bi-eye-slash" id="password-icon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-theme w-100">
                                        <i class="bi bi-person-plus me-1"></i>
                                        {{ __('Register') }}
                                    </button>
                                </div>

                                <div class="col-12">
                                    <p class="small mb-0 w-100 text-center">
                                        <span>Do you have an account?</span>
                                        <a href="{{ route('admin.login') }}">Go to login <i class="bi bi-box-arrow-in-right"></i></a>
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
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password-confirm');
        const passwordToggle = document.getElementById('password-toggle');
        const passwordIcon = document.getElementById('password-icon');

        if (passwordToggle && passwordInput && confirmPasswordInput && passwordIcon) {
            passwordToggle.addEventListener('click', function () {
                const show = passwordInput.type === 'password';
                const newType = show ? 'text' : 'password';

                passwordInput.type = newType;
                confirmPasswordInput.type = newType;

                passwordIcon.classList.toggle('bi-eye', show);
                passwordIcon.classList.toggle('bi-eye-slash', !show);
            });
        }
    });
</script>
@endsection

