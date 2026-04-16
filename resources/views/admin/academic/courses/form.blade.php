@extends('admin.layouts.app')
@section('title', 'Courses')
@section('title2', 'Course')

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
                                <li class="breadcrumb-item "><a href="{{ route('admin.academic.courses.index') }}">@yield('title')</a></li>
                                <li class="breadcrumb-item active">{{ isset($course) ? 'Edit' : 'Create' }} @yield('title2')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        <a href="{{ route('admin.academic.courses.index') }}" class="btn btn-outline-theme btn-sm">
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
                                <i class="bi bi-plus-square"></i>
                                {{ isset($course) ? 'Edit' : 'Create' }} @yield('title2') Form
                            </h6>
                        </button>
                    </h2>
                    <div id="collapsecourse" class="accordion-collapse collapse show" aria-labelledby="headingcourse" data-bs-parent="#accordionAcademinCourses">
                        <div class="accordion-body">


                            <form action="{{ isset($course) ? route('admin.academic.courses.update', $course->id) : route('admin.academic.courses.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(isset($course))
                                    @method('PUT')
                                @endif
                                @php
                                    $isActive = old('is_active', isset($course) ? $course->is_active : 1);
                                @endphp
                                <div class="row align-items-baseline mb-2">
                                    <label for="Name" class="col-sm-3 col-form-label fw-bold"><small>Course Title</small> <small class="text-danger">*</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                            <input type="text" class="form-control @error('course_title') is-invalid @elseif(old('course_title', $course->course_title ?? false)) is-valid @enderror" id="course_title" name="course_title" placeholder="Course Title" value="{{ old('course_title', $course->course_title ?? '') }}">
                                        </div>

                                        <div class="d-flex align-items-center">
                                            @error('course_title')
                                            <div class="invalid-feedback d-block">
                                                <i class="bi bi-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                            @elseif(old('course_title', $course->course_title ?? false))
                                            <div class="valid-feedback d-block">
                                                <i class="bi bi-check-circle"></i>
                                                Looks good!
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Course Code --}}
                                <div class="row align-items-baseline mb-2">
                                    <label for="course_code" class="col-sm-3 col-form-label fw-bold"><small>Course Code</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                            <input type="text" class="form-control" id="course_code" name="course_code" placeholder="Course Code" value="{{ old('course_code', $course->course_code ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Credits --}}
                                <div class="row align-items-baseline mb-2">
                                    <label for="credits" class="col-sm-3 col-form-label fw-bold"><small>Credits</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                            <input type="number" class="form-control" id="credits" name="credits" placeholder="Credits" value="{{ old('credits', $course->credits ?? '') }}" min="0" step="0.5">
                                        </div>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="row align-items-baseline mb-2">
                                    <label for="description" class="col-sm-3 col-form-label fw-bold"><small>Description</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                            <textarea class="form-control" id="description" name="description" placeholder="Description" rows="3">{{ old('description', $course->description ?? '') }}</textarea>
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
                                                        {{ $isActive ? 'Active' : 'Deactive' }}
                                                    </span>
                                                </label>
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
                                        <span class="ms-1">{{ isset($course) ? 'Update' : 'Save' }}</span>
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

{{-- Status: Active / Deactive --}}
<script>
    function updateLabelText(checkbox) {
        const label = document.getElementById("isActiveLabel");
        const span = label.querySelector("span"); // Get the <span> with the badge
        const icon = span.querySelector("i"); // Get the icon element

        if (checkbox.checked) {
            span.classList.remove("bg-danger"); // Remove danger class (Deactive)
            span.classList.add("bg-success"); // Add success class (Active)
            icon.classList.remove("bi-x-square"); // Remove the 'x' icon (Deactive)
            icon.classList.add("bi-check-square"); // Add the 'check' icon (Active)
            span.innerHTML = '<i class="bi bi-check-square me-1"></i> Active'; // Update the text content to Active
        } else {
            span.classList.remove("bg-success"); // Remove success class (Active)
            span.classList.add("bg-danger"); // Add danger class (Deactive)
            icon.classList.remove("bi-check-square"); // Remove the 'check' icon (Active)
            icon.classList.add("bi-x-square"); // Add the 'x' icon (Deactive)
            span.innerHTML = '<i class="bi bi-x-square me-1"></i> Deactive'; // Update the text content to Deactive
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
        const oldDepartment = @json(old('department'));
        const oldDesignation = @json(old('designation'));

        $('#department').select2({
            tags: true
        , });
        $("#designation").select2({
            tags: true
        });
        $("#gender").select2({});
        $("#blood_group").select2({});
        $("#country").select2({});

        if (oldDepartment) {
            const option = new Option(oldDepartment, oldDepartment, true, true);
            $('#department').append(option).trigger('change');
        }

        if (oldDesignation) {
            const option = new Option(oldDesignation, oldDesignation, true, true);
            $('#designation').append(option).trigger('change');
        }
    });

</script>

{{-- Employee Image Preview --}}
<script>
    function updateEmployeePreview(input) {
        const previewImg = document.getElementById('employeeImg');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImg.src = e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

</script>

@endsection
