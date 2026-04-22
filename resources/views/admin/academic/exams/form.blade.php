@extends('admin.layouts.app')
@section('title', 'Exams')
@section('title2', 'Exam')

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
                                <li class="breadcrumb-item "><a href="{{ route('admin.academic.exams.index') }}">@yield('title')</a></li>
                                <li class="breadcrumb-item active">{{ isset($exam) ? 'Edit' : 'Create' }} @yield('title2')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        <a href="{{ route('admin.academic.exams.index') }}" class="btn btn-outline-theme btn-sm">
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
                                {{ isset($exam) ? 'Edit' : 'Create' }} @yield('title2') Form
                            </h6>
                        </button>
                    </h2>
                    <div id="collapsecourse" class="accordion-collapse collapse show" aria-labelledby="headingcourse" data-bs-parent="#accordionAcademinCourses">
                        <div class="accordion-body">


                            <form action="{{ isset($exam) ? route('admin.academic.exams.update', $exam->id) : route('admin.academic.exams.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(isset($exam))
                                @method('PUT')
                                @endif
                                @php
                                $isActive = old('is_active', isset($exam) ? $exam->is_active : 1);
                                @endphp

                                {{-- Course ID --}}
                                <div class="row align-items-baseline mb-2">
                                    <label for="course_id" class="col-sm-3 col-form-label fw-bold"><small>Course Code & Title</small> <small class="text-danger">*</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Code & title of the course"><i class="bi bi-info-circle"></i></span>
                                            <select class="form-select @error('course_id') is-invalid @elseif(old('course_id', $exam->course_id ?? false)) is-valid @enderror" name="course_id" id="course_id" class="form-control">
                                                <option selected disabled>Select Course</option>
                                                @foreach($courseList as $course)
                                                <option value="{{ $course->id }}" {{ old('course_id', $exam->course_id ?? '') == $course->id ? 'selected' : '' }}>
                                                    [{{ $course->course_code }}] - {{ $course->course_title }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            @error('course_id')
                                            <div class="invalid-feedback d-block">
                                                <i class="bi bi-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                            @else
                                                @if(old('course_id', $exam->course_id ?? false))
                                                <div class="valid-feedback d-block">
                                                    <i class="bi bi-check-circle"></i>
                                                    Looks good!
                                                </div>
                                                @endif
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Exam Type --}}
                                <div class="row align-items-baseline mb-2">
                                    <label for="exam_type" class="col-sm-3 col-form-label fw-bold"><small>Exam Type</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Type of the exam. (e.g., Quiz, Class Test, Assignment, etc.)"><i class="bi bi-info-circle"></i></span>
                                            <select class="form-select" id="exam_type" name="exam_type">
                                                <option value="" disabled {{ old('exam_type', $exam->exam_type ?? '') == '' ? 'selected' : '' }}>Select Exam Type</option>
                                                <option value="Quiz" {{ old('exam_type', $exam->exam_type ?? '') == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                                                <option value="Class Test" {{ old('exam_type', $exam->exam_type ?? '') == 'Class Test' ? 'selected' : '' }}>Class Test</option>
                                                <option value="Term" {{ old('exam_type', $exam->exam_type ?? '') == 'Term' ? 'selected' : '' }}>Term</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Exam Title & Code --}}
                                <div class="row align-items-baseline mb-2">
                                    <label for="exam_title" class="col-sm-3 col-form-label fw-bold"><small>Exam Title & Code</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Code & title of the exam"><i class="bi bi-info-circle"></i></span>
                                            <input type="text" class="form-control" id="exam_title" name="exam_title" placeholder="Exam Title" value="{{ old('exam_title', $exam->exam_title ?? '') }}">

                                            <input type="text" class="form-control" id="exam_code" name="exam_code" placeholder="Exam Code" value="{{ old('exam_code', $exam->exam_code ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Exam Date --}}
                                <div class="row align-items-baseline mb-2">
                                    <label for="exam_date" class="col-sm-3 col-form-label fw-bold"><small>Exam Date</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                            <input type="date" class="form-control" id="exam_date" name="exam_date" placeholder="Exam Date" value="{{ old('exam_date', isset($exam) ? ($exam->exam_date ? $exam->exam_date->format('Y-m-d') : '') : '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Start Time & End Time --}}
                                <div class="row align-items-baseline mb-2">
                                    <label for="start_time" class="col-sm-3 col-form-label fw-bold"><small>Start & End Time</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                            <input type="time" class="form-control" id="start_time" name="start_time" placeholder="Start Time" value="{{ old('start_time', isset($exam) ? ($exam->start_time ? $exam->start_time->format('H:i') : '') : '') }}">

                                            <input type="time" class="form-control" id="end_time" name="end_time" placeholder="End Time" value="{{ old('end_time', isset($exam) ? ($exam->end_time ? $exam->end_time->format('H:i') : '') : '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Duration (Minutes) --}}
                                <div class="row align-items-baseline mb-2">
                                    <label for="exam_duration_min" class="col-sm-3 col-form-label fw-bold"><small>Duration (Minutes)</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                            <input type="number" class="form-control" id="exam_duration_min" name="exam_duration_min" placeholder="Duration (Minutes)" value="{{ old('exam_duration_min', isset($exam) ? $exam->exam_duration_min : '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Marks --}}
                                <div class="row align-items-baseline mb-2">
                                    <label for="total_marks" class="col-sm-3 col-form-label fw-bold"><small>Marks</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                            <input type="number" class="form-control" id="total_marks" name="total_marks" placeholder="Total Marks" value="{{ old('total_marks', $exam->total_marks ?? '') }}">

                                            <input type="number" class="form-control" id="passing_marks" name="passing_marks" placeholder="Passing Marks" value="{{ old('passing_marks', $exam->passing_marks ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Total Questions --}}
                                <div class="row align-items-baseline mb-2">
                                    <label for="total_questions" class="col-sm-3 col-form-label fw-bold"><small>Total Questions</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                            <input type="number" class="form-control" id="total_questions" name="total_questions" placeholder="Total Questions" value="{{ old('total_questions', $exam->total_questions ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- Instructions --}}
                                <div class="row align-items-baseline mb-2">
                                    <label for="instructions" class="col-sm-3 col-form-label fw-bold"><small>Instructions</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                            <textarea class="form-control" id="instructions" name="instructions" placeholder="Instructions" rows="3">{{ old('instructions', $exam->instructions ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Basic Rules --}}
                                <div class="row align-items-baseline mb-2">
                                    <label for="basic_rules" class="col-sm-3 col-form-label fw-bold"><small>Basic Rules</small></label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                            <textarea class="form-control" id="basic_rules" name="basic_rules" placeholder="Basic Rules" rows="3">{{ old('basic_rules', $exam->basic_rules ?? '') }}</textarea>
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
                                        <span class="ms-1">{{ isset($exam) ? 'Update' : 'Save' }}</span>
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
        // $("#course_id").select2({});
    });

</script>


<script>
    function calculateDuration() {
        const start = document.getElementById('start_time').value;
        const end   = document.getElementById('end_time').value;

        if (start && end) {
            const [startH, startM] = start.split(':').map(Number);
            const [endH,   endM  ] = end.split(':').map(Number);

            const startTotal = startH * 60 + startM;
            const endTotal   = endH   * 60 + endM;
            const diff       = endTotal - startTotal;

            document.getElementById('exam_duration_min').value = diff > 0 ? diff : 0;
        }
    }

    document.getElementById('start_time').addEventListener('change', calculateDuration);
    document.getElementById('end_time').addEventListener('change', calculateDuration);
</script>

@endsection
