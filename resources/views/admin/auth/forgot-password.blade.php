@extends('layouts.auth')
@section('title', 'Forgot Password')

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
                            <h5 class="card-title text-center pt-0 pb-0 fs-4 mb-0">@yield('title')?</h5>
                            <p class="text-center small">Let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.password.email') }}" class="row g-3 needs-validation" novalidate>
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
                                <button type="submit" class="btn btn-outline-theme w-100">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>
                                    {{ __('Email Password Reset Link') }}
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
