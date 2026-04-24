<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-sm-12 d-flex flex-column align-items-center justify-content-center">

            <div class="pb-2">
                <h5 class="card-title text-center pt-2 pb-0 fs-4">Teacher Login</h5>
                <p class="text-center small">Enter your email & password to login</p>
            </div>

            <form method="POST" action="{{ route('teacher.login') }}" class="row g-3 needs-validation" novalidate>
                @csrf

                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text brr-0" id="inputGroupPrepend"><i class="bi bi-envelope auth-icon"></i></span>
                        <input id="teacher_email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" autofocus>
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
                        <input id="teacher_password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                        <button class="btn btn-outline-theme" type="button" id="teacher_password_toggle">
                            <i class="bi bi-eye-slash" id="teacher_password_icon"></i>
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
                            <a href="{{ route('teacher.password.request') }}">Forgot Password?</a>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-outline-theme w-100">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        {{ __('TeacherLogin') }}
                    </button>
                </div>

                <div class="col-12">
                    <p class="small mb-0 w-100 text-center">
                        <span>Don't have account?</span>
                        <a href="{{ route('teacher.register') }}">Create a teacher account <i class="bi bi-person-add"></i></a>
                    </p>
                </div>
            </form>

        </div>
    </div>
</div>

