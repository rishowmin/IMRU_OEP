@extends('admin.layouts.app')
@section('title', 'Questions')
@section('title2', 'Question')

@section('content')

@if(session('success') || session('status') || session('error'))
@include('admin.layouts.common.status')
@endif

<div class="pagetitle mb-0">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-header-left">
                            <h1>
                                <i class="bi bi-file-earmark-text"></i>
                                <span class="ms-1">@yield('title2')</span>
                            </h1>
                            <nav style="--bs-breadcrumb-divider: '•';">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                    <li class="breadcrumb-item "><a href="{{ route('admin.academic.questions.index') }}">@yield('title')</a></li>
                                    <li class="breadcrumb-item active">{{ isset($question) ? 'Edit' : 'Create' }} @yield('title2')</li>
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
    </section>
</div>

<section class="section">

    <div class="row">
        <div class="col-lg-12">

            <form action="{{ isset($question) ? route('admin.academic.questions.update', $question->id) : route('admin.academic.questions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($question))
                @method('PUT')
                @endif
                @php
                $isActive = old('is_active', isset($question) ? $question->is_active : 1);
                @endphp

                <div class="card mb-0">
                    <div class="card-header">
                        <div class="d-flex align-item-center justify-content-between">
                            <h6 class="card-title p-0 m-0">
                                <i class="bi bi-pencil-square me-1"></i>
                                {{ isset($question) ? 'Edit' : 'Create' }} @yield('title2') Form
                            </h6>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ $isActive ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-sm-8">

                                <div class="row g-2 g-2">
                                    {{-- Exam ID --}}
                                    <div class="col-sm-12">
                                        <label for="exam_id" class="form-label fw-bold mb-1"><small>Exam Title & Code</small> <small class="text-danger">*</small></label>
                                        <select class="form-select @error('exam_id') is-invalid @elseif(old('exam_id', $question->exam_id ?? false)) is-valid @enderror" name="exam_id" id="exam_id" class="form-control">
                                            <option selected disabled>Select Exam</option>
                                            @foreach($examList as $exam)
                                            <option value="{{ $exam->id }}" {{ old('exam_id', $question->exam_id ?? '') == $exam->id ? 'selected' : '' }}>
                                                {{ $exam->exam_title }} - [{{ $exam->exam_code }}]
                                            </option>
                                            @endforeach
                                        </select>

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

                                    {{-- Question Type --}}
                                    <div class="col-sm-3">
                                        <label for="question_type" class="form-label fw-bold mb-1"><small>Question Type</small> <small class="text-danger">*</small></label>
                                        <select class="form-select @error('question_type') is-invalid @elseif(old('question_type', $question->question_type ?? false)) is-valid @enderror" id="question_type" name="question_type">
                                            <option value="mcq_4" {{ old('question_type', $question->question_type ?? '') == 'mcq_4' ? 'selected' : '' }}>MCQ (4 Options)</option>
                                            <option value="mcq_2" {{ old('question_type', $question->question_type ?? '') == 'mcq_2' ? 'selected' : '' }}>MCQ (2 Options)</option>
                                            <option value="short_question" {{ old('question_type', $question->question_type ?? '') == 'short_question' ? 'selected' : '' }}>Short Question</option>
                                            <option value="long_question" {{ old('question_type', $question->question_type ?? '') == 'long_question' ? 'selected' : '' }}>Long Question</option>
                                        </select>

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

                                    {{-- Difficulty Level --}}
                                    <div class="col-sm-3">
                                        <label for="difficulty_level" class="form-label fw-bold mb-1"><small>Difficulty Level</small> <small class="text-danger">*</small></label>
                                        <select class="form-select @error('difficulty_level') is-invalid @elseif(old('difficulty_level', $question->difficulty_level ?? false)) is-valid @enderror" id="difficulty_level" name="difficulty_level">
                                            <option value="easy" {{ old('difficulty_level', $question->difficulty_level ?? '') == 'easy' ? 'selected' : '' }}>Easy</option>
                                            <option value="medium" {{ old('difficulty_level', $question->difficulty_level ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="hard" {{ old('difficulty_level', $question->difficulty_level ?? '') == 'hard' ? 'selected' : '' }}>Hard</option>
                                        </select>

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

                                    {{-- Marks --}}
                                    <div class="col-sm-3">
                                        <label for="marks" class="form-label fw-bold mb-1"><small>Marks</small> <small class="text-danger">*</small></label>
                                        <input type="number" class="form-control @error('marks') is-invalid @elseif(old('marks', $question->marks ?? false)) is-valid @enderror" id="marks" name="marks" placeholder="Question Text" value="{{ old('marks', $question->marks ?? 1) }}">

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

                                    {{-- Evaluation Type --}}
                                    <div class="col-sm-3">
                                        <label for="evaluation_type" class="form-label fw-bold mb-1"><small>Evaluation Type</small> <small class="text-danger">*</small></label>
                                        <select class="form-select @error('evaluation_type') is-invalid @elseif(old('evaluation_type', $question->evaluation_type ?? false)) is-valid @enderror" id="evaluation_type" name="evaluation_type">
                                            <option value="automatic" {{ old('evaluation_type', $question->evaluation_type ?? '') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                                            <option value="manual" {{ old('evaluation_type', $question->evaluation_type ?? '') == 'manual' ? 'selected' : '' }}>Manual</option>
                                        </select>

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

                                    {{-- Question Text --}}
                                    <div class="col-sm-12">
                                        <label for="question_text" class="form-label fw-bold mb-1"><small>Question Text</small> <small class="text-danger">*</small></label>
                                        <textarea class="form-control @error('question_text') is-invalid @elseif(old('question_text', $question->question_text ?? false)) is-valid @enderror" id="question_text" name="question_text" placeholder="Question Text" rows="3">{{ old('question_text', $question->question_text ?? '') }}</textarea>

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

                                    @php
                                    $correctOption = old('correct_option');
                                    if (!$correctOption && isset($question) && $question->correct_answer !== null) {
                                        foreach (['a', 'b', 'c', 'd'] as $k) {
                                            if ($question->correct_answer === $question->{'option_' . $k}) {
                                                $correctOption = $k;
                                                break;
                                            }
                                        }
                                    }
                                    @endphp

                                    {{-- Options (MCQ only) --}}
                                    <div class="col-sm-12" id="option_row">
                                        <label class="form-label fw-bold mb-1">
                                            <small>Options</small>
                                            <span class="small"><small class="text-muted fw-normal ms-1">[Tick the correct one]</small></span>
                                        </label>
                                        <div class="row g-2">
                                            @foreach(['a', 'b', 'c', 'd'] as $k)
                                            <div class="col-6 col-lg-6 input-group-div" id="option_col_{{ $k }}">
                                                <div class="input-group flex-nowrap">
                                                    <div class="input-group-text border-theme">
                                                        <input class="form-check-input option-checkbox mt-0" type="radio" name="correct_option" value="{{ $k }}" title="Mark as correct answer" {{ $correctOption === $k ? 'checked' : '' }}>
                                                    </div>
                                                    <input type="text" class="form-control border-theme" style="min-width: 0;" id="option_{{ $k }}" name="option_{{ $k }}" placeholder="Option {{ strtoupper($k) }}" value="{{ old('option_' . $k, $question->{'option_' . $k} ?? '') }}">
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" id="correct_answer_mcq" name="correct_answer" disabled>
                                    </div>

                                    {{-- Correct Answer (Short / Long only) --}}
                                    <div class="col-sm-12" id="correct_answer_wrap">
                                        <label for="correct_answer" class="form-label fw-bold mb-1"><small>Correct Answer</small></label>
                                        <textarea class="form-control" id="correct_answer" name="correct_answer" placeholder="Correct Answer" rows="3">{{ old('correct_answer', $question->correct_answer ?? '') }}</textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="col-sm-4">

                                <div class="row g-2">
                                    {{-- Question Order --}}
                                    <div class="col-sm-12">
                                        <label for="question_order" class="form-label fw-bold mb-1"><small>Question Order</small></label>
                                        <input type="number" class="form-control" id="question_order" name="question_order" placeholder="Question Order" value="{{ old('question_order', $question->question_order ?? '') }}">
                                    </div>

                                    {{-- Question Figure --}}
                                    <div class="col-sm-12">
                                        <label for="question_figure" class="form-label fw-bold mb-1"><small>Question Figure</small></label>
                                        <input type="file" class="form-control" id="question_figure" name="question_figure" accept="image/*">

                                        <div class="figure-preview mt-3">
                                            @if(isset($question) && $question->question_figure)
                                            <div class="p-2 border rounded">
                                                <img id="figure_preview_img" src="{{ asset('assets/storage/question_figure/' . $question->question_figure) }}" alt="Question Figure" class="img-fluid" style="width:100%; max-height: 180px">
                                            </div>
                                            @else
                                            <div class="p-2 border rounded">
                                                <img id="figure_preview_img" src="{{ asset('assets/admin/img/img-prev.png') }}" alt="Question Figure" class="img-fluid" style="width:100%; max-height: 180px">
                                            </div>
                                            {{-- <p id="figure_preview_placeholder" class="text-muted text-center mb-0">No figure uploaded.</p> --}}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex align-item-center justify-content-between gap-2">
                            <button type="reset" class="btn btn-danger w-25">
                                <i class="bi bi-arrow-clockwise"></i>
                                <span class="ms-1">Reset</span>
                            </button>
                            <button type="submit" class="btn btn-outline-success w-25">
                                <i class="bi bi-floppy"></i>
                                <span class="ms-1">{{ isset($question) ? 'Update' : 'Save' }}</span>
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>

</section>

@endsection


@section('scripts')

<script>
    function handleOptions() {
        const type = $('#question_type').val();
        const isMcq = type === 'mcq_2' || type === 'mcq_4';
        const is4 = type === 'mcq_4';

        $('#option_row').toggle(isMcq);
        $('#option_col_c, #option_col_d').toggle(is4);

        if (!isMcq) {
            $('#option_a, #option_b, #option_c, #option_d').val('');
            $('input[name="correct_option"]').prop('checked', false);
        } else if (!is4) {
            $('#option_c, #option_d').val('');
            $('input[name="correct_option"][value="c"], input[name="correct_option"][value="d"]').prop('checked', false);
        }

        // MCQ: the answer comes from the ticked option. Short/Long: from the textarea.
        $('#correct_answer_wrap').toggle(!isMcq);
        $('#correct_answer').prop('disabled', isMcq);
        $('#correct_answer_mcq').prop('disabled', !isMcq);

        highlightCorrectOption();
    }

    function highlightCorrectOption() {
        $('#option_row .input-group-div').removeClass('option-correct');
        $('input[name="correct_option"]:checked').closest('.input-group-div').addClass('option-correct');
    }

    $(document).ready(function () {
        $('#exam_id').select2({});
        $('#question_type').select2({}).on('change', handleOptions);
        $('#difficulty_level').select2({});
        $('#evaluation_type').select2({});

        handleOptions();

        // Copy the ticked option's text into correct_answer before submitting
        $('#question_type').closest('form').on('submit', function (e) {
            const type = $('#question_type').val();
            if (type !== 'mcq_2' && type !== 'mcq_4') return;

            const key = $('input[name="correct_option"]:checked').val();
            if (!key) {
                e.preventDefault();
                alert('Please tick the correct option.');
                return;
            }

            const text = $('#option_' + key).val().trim();
            if (!text) {
                e.preventDefault();
                alert('The option you marked as correct is empty.');
                return;
            }

            $('#correct_answer_mcq').val(text);
        });

        $(document).on('change', 'input[name="correct_option"]', highlightCorrectOption);

        $('button[type="reset"]').on('click', function () {
            setTimeout(function () {
                $('#exam_id, #question_type, #difficulty_level, #evaluation_type').trigger('change');
                highlightCorrectOption();
            }, 0);
        });
    });
</script>

<script>
    document.getElementById('question_figure').addEventListener('change', function () {
        const file = this.files[0];
        const previewImg = document.getElementById('figure_preview_img');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            previewImg.src = '';
            previewImg.classList.add('d-none');
        }
    });
</script>

@endsection

