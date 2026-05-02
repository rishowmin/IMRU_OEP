@extends('admin.layouts.app')
@section('title', 'Questions Library')
@section('title2', 'Question')

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
                                <li class="breadcrumb-item "><a href="{{ route('admin.academic.questions.library.index') }}">@yield('title')</a></li>
                                <li class="breadcrumb-item active">{{ isset($questionLib) ? 'Edit' : 'Create' }} @yield('title2')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        <a href="{{ route('admin.academic.questions.library.index') }}" class="btn btn-outline-theme btn-sm">
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
                                {{ isset($questionLib) ? 'Edit' : 'Create' }} @yield('title2') Form
                            </h6>
                        </button>
                    </h2>
                    <div id="collapsecourse" class="accordion-collapse collapse show" aria-labelledby="headingcourse" data-bs-parent="#accordionAcademinCourses">
                        <div class="accordion-body">


                            <form action="{{ isset($questionLib) ? route('admin.academic.questions.library.update', $questionLib->id) : route('admin.academic.questions.library.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(isset($questionLib))
                                @method('PUT')
                                @endif
                                @php
                                $isActive = old('is_active', isset($questionLib) ? $questionLib->is_active : 1);
                                @endphp

                                <div class="row">

                                    <div class="col-sm-8">

                                        {{-- Topic --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="topic" class="col-sm-3 col-form-label fw-bold"><small>Topic</small> <small class="text-danger">*</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Select the exam"><i class="bi bi-info-circle"></i></span>
                                                    <select class="form-select @error('topic') is-invalid @elseif(old('topic', $questionLib->topic ?? false)) is-valid @enderror" name="topic" id="topic" class="form-control">
                                                        <option value="General" {{ old('topic', $questionLib->topic ?? '') == 'General' ? 'selected' : '' }}>General</option>
                                                        @foreach($courseList as $course)
                                                        <option value="{{ $course->course_title }}" {{ old('topic', $questionLib->topic ?? '') == $course->course_title ? 'selected' : '' }}>
                                                            {{ $course->course_title }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('topic')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('topic', $questionLib->topic ?? false))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Question Type --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="question_type" class="col-sm-3 col-form-label fw-bold"><small>Question Type</small> <small class="text-danger">*</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Type of the question. (e.g., Multiple Choice, Short Answer, Essay, etc.)"><i class="bi bi-info-circle"></i></span>
                                                    <select class="form-select @error('question_type') is-invalid @elseif(old('question_type', $questionLib->question_type ?? false)) is-valid @enderror" id="question_type" name="question_type">
                                                        <option value="mcq_4" {{ old('question_type', $questionLib->question_type ?? '') == 'mcq_4' ? 'selected' : '' }}>MCQ (4 Options)</option>
                                                        <option value="mcq_2" {{ old('question_type', $questionLib->question_type ?? '') == 'mcq_2' ? 'selected' : '' }}>MCQ (2 Options)</option>
                                                        <option value="short_question" {{ old('question_type', $questionLib->question_type ?? '') == 'short_question' ? 'selected' : '' }}>Short Question</option>
                                                        <option value="long_question" {{ old('question_type', $questionLib->question_type ?? '') == 'long_question' ? 'selected' : '' }}>Long Question</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('question_type')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('question_type', $questionLib->question_type ?? false))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Question Text --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="question_text" class="col-sm-3 col-form-label fw-bold"><small>Question Text</small> <small class="text-danger">*</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Text of the question"><i class="bi bi-info-circle"></i></span>
                                                    <textarea class="form-control @error('question_text') is-invalid @elseif(old('question_text', $questionLib->question_text ?? false)) is-valid @enderror" id="question_text" name="question_text" placeholder="Question Text" rows="3">{{ old('question_text', $questionLib->question_text ?? '') }}</textarea>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('question_text')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('question_text', $questionLib->question_text ?? false))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Options --}}
                                        <div class="row align-items-baseline mb-2" id="option_row">
                                            <label for="options" class="col-sm-3 col-form-label fw-bold"><small>Options</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                                    <input type="text" class="form-control" id="option_a" name="option_a" placeholder="Option A" value="{{ old('option_a', $questionLib->option_a ?? '') }}">

                                                    <input type="text" class="form-control" id="option_b" name="option_b" placeholder="Option B" value="{{ old('option_b', $questionLib->option_b ?? '') }}">

                                                    <input type="text" class="form-control" id="option_c" name="option_c" placeholder="Option C" value="{{ old('option_c', $questionLib->option_c ?? '') }}">

                                                    <input type="text" class="form-control" id="option_d" name="option_d" placeholder="Option D" value="{{ old('option_d', $questionLib->option_d ?? '') }}">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Correct Answer --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="correct_answer" class="col-sm-3 col-form-label fw-bold"><small>Correct Answer</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                                    <textarea class="form-control" id="correct_answer" name="correct_answer" placeholder="Correct Answer" rows="3">{{ old('correct_answer', $questionLib->correct_answer ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-4">

                                        {{-- Status --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="is_active" class="col-sm-4 form-label fw-bold"><small>Status</small></label>
                                            <div class="col-sm-8">
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

                                        {{-- Question Figure --}}
                                        <div class="row align-items-baseline mb-2">
                                            <div class="col-sm-12">
                                                <label for="question_figure" class="form-label fw-bold"><small>Question Figure</small></label>
                                                <div class="input-group">
                                                    <input type="file" class="form-control form-control-sm" id="question_figure" name="question_figure" accept="image/*">
                                                </div>

                                                <div class="figure-preview mt-3">
                                                    @if(isset($questionLib) && $questionLib->question_figure)
                                                    <div class="p-2 border rounded">
                                                        <img id="figure_preview_img" src="{{ asset('storage/question_figure/library/' . $questionLib->question_figure) }}" alt="Question Figure" class="img-fluid" style="width:100%; max-height: 200px">
                                                    </div>
                                                    @else
                                                    <div class="p-2 border rounded">
                                                        <img id="figure_preview_img" src="{{ asset('assets/admin/img/img-prev.png') }}" alt="Question Figure" class="img-fluid" style="width:100%; max-height: 200px">
                                                    </div>
                                                    <p id="figure_preview_placeholder" class="text-muted text-center mb-0">No figure uploaded.</p>
                                                    @endif
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
                                        <span class="ms-1">{{ isset($questionLib) ? 'Update' : 'Save' }}</span>
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
    const questionType = document.getElementById('question_type');

    function handleOptions() {
        const type = questionType.value;

        const optionA = document.getElementById('option_a');
        const optionB = document.getElementById('option_b');
        const optionC = document.getElementById('option_c');
        const optionD = document.getElementById('option_d');
        const optionRow = document.getElementById('option_row');

        if (type === 'mcq_2') {
            optionA.style.display = '';
            optionB.style.display = '';
            optionC.style.display = 'none';
            optionD.style.display = 'none';
            optionC.value = '';
            optionD.value = '';
            optionRow.style.display = '';
        } else if (type === 'mcq_4') {
            optionA.style.display = '';
            optionB.style.display = '';
            optionC.style.display = '';
            optionD.style.display = '';
            optionRow.style.display = '';
        } else {
            optionRow.style.display = 'none';
        }
    }

    // Run on page load
    handleOptions();

    // Run on change
    questionType.addEventListener('change', handleOptions);

</script>

<script>
    document.getElementById('question_figure').addEventListener('change', function () {
        const file = this.files[0];
        const previewImg = document.getElementById('figure_preview_img');
        const placeholder = document.getElementById('figure_preview_placeholder');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('d-none');
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            previewImg.src = '';
            previewImg.classList.add('d-none');
            if (placeholder) placeholder.style.display = '';
        }
    });
</script>

@endsection

