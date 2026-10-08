@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')

@if(session('success') || session('status') || session('error'))
@include('admin.layouts.common.status')
@endif

<section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6 d-flex flex-column align-items-center justify-content-center">

                <div class="card mb-3">
                    <div class="card-header">
                        <div class="back-to-home">
                            <a href="{{ route('admin.login') }}" class="btn btn-sm btn-outline-theme"><i class="bi bi-arrow-left"></i></a>
                        </div>
                        <div class="row justify-content-center">
                            <a href="{{ url('/') }}" class="logo d-flex align-items-center w-auto">
                                <img src="{{ asset('assets/admin/img/brand/logo.png') }}" alt="IMRU OEP Logo" width="100%">
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="pb-2">
                            <h5 class="card-title text-center pt-0 pb-0 fs-4 mb-0">@yield('title')</h5>
                            <p class="text-center small">Enter new password to reset your password.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.password.store') }}" class="row g-3 needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text brr-0" id="inputGroupPrepend"><i class="bi bi-envelope auth-icon"></i></span>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username" placeholder="Email" autofocus>
                                </div>

                                @error('email')
                                <small class="text-danger d-flex mt-1">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text brr-0" id="inputGroupPrepend"><i class="bi bi-key auth-icon"></i></span>
                                    <input id="password" type="password" class="form-control brr-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                                    <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
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
                                <button type="submit" class="btn btn-outline-theme w-100">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>
                                    {{ __('Reset Password') }}
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

                <div class="row justify-content-center text-center mt-2">
                    <div class="credits small">
                        Developed by <a href="https://github.com/rishowmin" target="_blank">Muhammad Raisul Islam, IIT, JU</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const passwordToggle = document.getElementById('password-toggle');
        const passwordIcon = document.getElementById('password-icon');

        if (passwordToggle && passwordInput && confirmPasswordInput && passwordIcon) {
            passwordToggle.addEventListener('click', function() {
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
