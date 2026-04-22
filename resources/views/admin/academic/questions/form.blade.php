@extends('admin.layouts.app')
@section('title', 'Questions')
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
                                <li class="breadcrumb-item "><a href="{{ route('admin.academic.questions.index') }}">@yield('title')</a></li>
                                <li class="breadcrumb-item active">{{ isset($exam) ? 'Edit' : 'Create' }} @yield('title2')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        <a href="{{ route('admin.academic.questions.index') }}" class="btn btn-outline-theme btn-sm">
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
                                {{ isset($question) ? 'Edit' : 'Create' }} @yield('title2') Form
                            </h6>
                        </button>
                    </h2>
                    <div id="collapsecourse" class="accordion-collapse collapse show" aria-labelledby="headingcourse" data-bs-parent="#accordionAcademinCourses">
                        <div class="accordion-body">


                            <form action="{{ isset($question) ? route('admin.academic.questions.update', $question->id) : route('admin.academic.questions.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(isset($question))
                                @method('PUT')
                                @endif
                                @php
                                $isActive = old('is_active', isset($question) ? $question->is_active : 1);
                                @endphp

                                <div class="row">

                                    <div class="col-sm-8">

                                        {{-- Exam ID --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="exam_id" class="col-sm-3 col-form-label fw-bold"><small>Exam Title & Code</small> <small class="text-danger">*</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Select the exam"><i class="bi bi-info-circle"></i></span>
                                                    <select class="form-select @error('exam_id') is-invalid @elseif(old('exam_id', $question->exam_id ?? false)) is-valid @enderror" name="exam_id" id="exam_id" class="form-control">
                                                        <option selected disabled>Select Exam</option>
                                                        @foreach($examList as $exam)
                                                        <option value="{{ $exam->id }}" {{ old('exam_id', $question->exam_id ?? '') == $exam->id ? 'selected' : '' }}>
                                                            {{ $exam->exam_title }} - [{{ $exam->exam_code }}]
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('exam_id')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('exam_id', $question->exam_id ?? false))
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
                                                    <select class="form-select @error('question_type') is-invalid @elseif(old('question_type', $question->question_type ?? false)) is-valid @enderror" id="question_type" name="question_type">
                                                        <option value="mcq_4" {{ old('question_type', $question->question_type ?? '') == 'mcq_4' ? 'selected' : '' }}>MCQ (4 Options)</option>
                                                        <option value="mcq_2" {{ old('question_type', $question->question_type ?? '') == 'mcq_2' ? 'selected' : '' }}>MCQ (2 Options)</option>
                                                        <option value="short_question" {{ old('question_type', $question->question_type ?? '') == 'short_question' ? 'selected' : '' }}>Short Question</option>
                                                        <option value="long_question" {{ old('question_type', $question->question_type ?? '') == 'long_question' ? 'selected' : '' }}>Long Question</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('question_type')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('question_type', $question->question_type ?? false))
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
                                                    <textarea class="form-control @error('question_text') is-invalid @elseif(old('question_text', $question->question_text ?? false)) is-valid @enderror" id="question_text" name="question_text" placeholder="Question Text" rows="3">{{ old('question_text', $question->question_text ?? '') }}</textarea>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('question_text')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('question_text', $question->question_text ?? false))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Difficulty Level --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="difficulty_level" class="col-sm-3 col-form-label fw-bold"><small>Difficulty Level</small> <small class="text-danger">*</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                                    <select class="form-select @error('difficulty_level') is-invalid @elseif(old('difficulty_level', $question->difficulty_level ?? false)) is-valid @enderror" id="difficulty_level" name="difficulty_level">
                                                        <option value="easy" {{ old('difficulty_level', $question->difficulty_level ?? '') == 'easy' ? 'selected' : '' }}>Easy</option>
                                                        <option value="medium" {{ old('difficulty_level', $question->difficulty_level ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                                                        <option value="hard" {{ old('difficulty_level', $question->difficulty_level ?? '') == 'hard' ? 'selected' : '' }}>Hard</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('difficulty_level')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('difficulty_level', $question->difficulty_level ?? false))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Marks --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="marks" class="col-sm-3 col-form-label fw-bold"><small>Marks</small> <small class="text-danger">*</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                                    <input type="number" class="form-control @error('marks') is-invalid @elseif(old('marks', $question->marks ?? false)) is-valid @enderror" id="marks" name="marks" placeholder="Question Text" value="{{ old('marks', $question->marks ?? 1) }}">
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('marks')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('marks', $question->marks ?? false))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Evaluation Type --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="evaluation_type" class="col-sm-3 col-form-label fw-bold"><small>Evaluation Type</small> <small class="text-danger">*</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                                    <select class="form-select @error('evaluation_type') is-invalid @elseif(old('evaluation_type', $question->evaluation_type ?? false)) is-valid @enderror" id="evaluation_type" name="evaluation_type">
                                                        <option value="automatic" {{ old('evaluation_type', $question->evaluation_type ?? '') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                                                        <option value="manual" {{ old('evaluation_type', $question->evaluation_type ?? '') == 'manual' ? 'selected' : '' }}>Manual</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('evaluation_type')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('evaluation_type', $question->evaluation_type ?? false))
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
                                                    <input type="text" class="form-control" id="option_a" name="option_a" placeholder="Option A" value="{{ old('option_a', $question->option_a ?? '') }}">

                                                    <input type="text" class="form-control" id="option_b" name="option_b" placeholder="Option B" value="{{ old('option_b', $question->option_b ?? '') }}">

                                                    <input type="text" class="form-control" id="option_c" name="option_c" placeholder="Option C" value="{{ old('option_c', $question->option_c ?? '') }}">

                                                    <input type="text" class="form-control" id="option_d" name="option_d" placeholder="Option D" value="{{ old('option_d', $question->option_d ?? '') }}">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Correct Answer --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="correct_answer" class="col-sm-3 col-form-label fw-bold"><small>Correct Answer</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                                    <textarea class="form-control" id="correct_answer" name="correct_answer" placeholder="Correct Answer" rows="3">{{ old('correct_answer', $question->correct_answer ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Question Order --}}
                                        <div class="row align-items-baseline mb-2">
                                            <label for="question_order" class="col-sm-3 col-form-label fw-bold"><small>Question Order</small></label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-info-circle"></i></span>
                                                    <input type="number" class="form-control" id="question_order" name="question_order" placeholder="Question Order" value="{{ old('question_order', $question->question_order ?? '') }}">
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
                                                    @if(isset($question) && $question->question_figure)
                                                    <div class="p-2 border rounded">
                                                        <img id="figure_preview_img" src="{{ asset('storage/question_figure/' . $question->question_figure) }}" alt="Question Figure" class="img-fluid" style="width:100%; max-height: 200px">
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
                                        <span class="ms-1">{{ isset($question) ? 'Update' : 'Save' }}</span>
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

