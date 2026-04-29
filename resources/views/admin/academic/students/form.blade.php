@extends('admin.layouts.app')
@section('title', 'Students')
@section('title2', 'Student')

@section('content')

@if(session('success') || session('status') || session('error'))
@include('admin.layouts.common.status')
@endif

<section class="section">

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-header-left">
                        <h5>
                            <i class="bi bi-plus-square"></i>
                            <span class="ms-1">@yield('title2')</span>
                        </h5>
                        <nav style="--bs-breadcrumb-divider: '•';">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                <li class="breadcrumb-item "><a href="{{ route('admin.academic.students.index') }}">@yield('title')</a></li>
                                <li class="breadcrumb-item active">{{ isset($student) ? 'Edit' : 'Create' }} @yield('title2')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        <a href="{{ route('admin.academic.students.index') }}" class="btn btn-outline-theme btn-sm">
                            <i class="bi bi-arrow-left-square"></i>
                            <span class="ms-1">Back to List</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <div class="accordion mb-3" id="accordionAcademinCourses">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingcourse">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsecourse" aria-expanded="true" aria-controls="collapsecourse">
                            <h6 class="card-title p-0 m-0">
                                <i class="bi bi-pencil-square"></i>
                                {{ isset($student) ? 'Edit' : 'Create' }} @yield('title2') Form
                            </h6>
                        </button>
                    </h2>
                    <div id="collapsecourse" class="accordion-collapse collapse show" aria-labelledby="headingcourse" data-bs-parent="#accordionAcademinCourses">
                        <div class="accordion-body">


                            <form action="{{ isset($student) ? route('admin.academic.students.update', $student->id) : route('admin.academic.students.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(isset($student))
                                @method('PUT')
                                @endif
                                @php
                                $isActive = old('is_active', isset($student) ? $student->is_active : 1);
                                @endphp

                                <div class="row">

                                    <div class="col-sm-12">

                                        {{-- First Name --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="first_name" class="col-sm-3 col-form-label fw-bold"><small>Student Name</small> <small class="text-danger">*</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Student's First & Last Name"><i class="bi bi-info-circle"></i></span>
                                                    <input type="text" id="first_name" class="form-control @error('first_name') is-invalid @elseif(old('first_name', $student->first_name ?? false)) is-valid @enderror" name="first_name" value="{{ old('first_name', $student->first_name ?? '') }}" placeholder="First Name">

                                                    <input type="text" id="last_name" class="form-control @error('last_name') is-invalid @elseif(old('last_name', $student->last_name ?? false)) is-valid @enderror" name="last_name" value="{{ old('last_name', $student->last_name ?? '') }}" placeholder="Last Name">
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('first_name')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @enderror

                                                    @error('last_name')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @enderror

                                                    @if(!$errors->has('first_name') && !$errors->has('last_name'))
                                                    @if(old('first_name', $student->first_name ?? false) && old('last_name', $student->last_name ?? false))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @endif

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Email --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="email" class="col-sm-3 col-form-label fw-bold"><small>Email</small> <small class="text-danger">*</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Text of the question"><i class="bi bi-info-circle"></i></span>
                                                    <input type="email" id="email" class="form-control @error('email') is-invalid @elseif(old('email', $student->email ?? false)) is-valid @enderror" name="email" value="{{ old('email', $student->email ?? '') }}" placeholder="Email">
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('email')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('email', $student->email ?? false))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Password --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="password" class="col-sm-3 col-form-label fw-bold"><small>{{ isset($student) ? 'Update Password' : 'Password' }}</small> <small class="text-danger">{{ isset($student) ? '' : '*' }}</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                                    <input type="password" id="password" class="form-control @error('password') is-invalid @elseif(old('password', $student->password ?? false)) is-valid @enderror" name="password" placeholder="Password">
                                                    <button class="btn btn-outline-theme" type="button" id="password-toggle">
                                                        <i class="bi bi-eye-slash" id="password-icon"></i>
                                                    </button>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('password')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('password', $student->password ?? false))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Status --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="is_active" class="col-sm-3 col-form-label fw-bold"><small>Status</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ $isActive ? 'checked' : '' }} onchange="updateLabelText(this)">
                                                        <label class="form-check-label ms-2" for="is_active" id="isActiveLabel">
                                                            <span class="badge {{ $isActive ? 'bg-success' : 'bg-danger' }}">
                                                                <i class="bi {{ $isActive ? 'bi-check-square' : 'bi-x-square' }} me-1"></i>
                                                                {{ $isActive ? 'Active' : 'Inactive' }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="row d-flex align-items-center justify-content-center mt-4">
                                    <button type="reset" class="btn btn-danger w-25 me-1">
                                        <i class="bi bi-arrow-clockwise"></i>
                                        <span class="ms-1">Reset</span>
                                    </button>
                                    <button type="submit" class="btn btn-outline-success w-25 ms-1">
                                        <i class="bi bi-floppy"></i>
                                        <span class="ms-1">{{ isset($student) ? 'Update' : 'Save' }}</span>
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>

@endsection






@section('scripts')

{{-- Status: Active / Inactive --}}
<script>
    function updateLabelText(checkbox) {
        const label = document.getElementById("isActiveLabel");
        const span = label.querySelector("span"); // Get the <span> with the badge
        const icon = span.querySelector("i"); // Get the icon element

        if (checkbox.checked) {
            span.classList.remove("bg-danger"); // Remove danger class (Inactive)
            span.classList.add("bg-success"); // Add success class (Active)
            icon.classList.remove("bi-x-square"); // Remove the 'x' icon (Inactive)
            icon.classList.add("bi-check-square"); // Add the 'check' icon (Active)
            span.innerHTML = '<i class="bi bi-check-square me-1"></i> Active'; // Update the text content to Active
        } else {
            span.classList.remove("bg-success"); // Remove success class (Active)
            span.classList.add("bg-danger"); // Add danger class (Inactive)
            icon.classList.remove("bi-check-square"); // Remove the 'check' icon (Active)
            icon.classList.add("bi-x-square"); // Add the 'x' icon (Inactive)
            span.innerHTML = '<i class="bi bi-x-square me-1"></i> Inactive'; // Update the text content to Inactive
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('is_active');
        if (checkbox) {
            updateLabelText(checkbox);
        }
    });

</script>

<script>
    $(document).ready(function() {
        // $("#course_id").select2({});
    });

</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');
        const passwordIcon = document.getElementById('password-icon');

        if (passwordToggle && passwordInput && passwordIcon) {
            passwordToggle.addEventListener('click', function() {
                const show = passwordInput.type === 'password';
                const newType = show ? 'text' : 'password';

                passwordInput.type = newType;

                passwordIcon.classList.toggle('bi-eye', show);
                passwordIcon.classList.toggle('bi-eye-slash', !show);
            });
        }
    });

</script>

@endsection

