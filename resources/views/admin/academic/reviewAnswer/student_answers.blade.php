@extends('admin.layouts.app')
@section('title', 'Review Answers')
@section('title2', 'Review Student Answers')

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
                            <i class="bi bi-pencil-square"></i>
                            <span class="ms-1">@yield('title2')</span>
                        </h5>
                        <nav style="--bs-breadcrumb-divider: '•';">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.academic.reviewAnswer.index') }}">@yield('title')</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.academic.reviewAnswer.show', $exam->id) }}">Students</a></li>
                                <li class="breadcrumb-item active">@yield('title2')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        <a href="{{ route('admin.academic.reviewAnswer.show', $exam->id) }}" class="btn btn-outline-theme btn-sm">
                            <i class="bi bi-arrow-left-square"></i>
                            <span class="ms-1">Back</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">

            <form method="POST" action="{{ route('admin.academic.reviewAnswer.store', [$exam->id, $student->id]) }}">
                @csrf

                @foreach($answers as $i => $answer)
                @if(in_array($answer->question?->question_type, ['short_question', 'long_question']))

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            @if($answer->question->question_type == 'short_question')
                            <span class="badge bg-dark">Short Question</span>
                            @else
                            <span class="badge bg-dark">Long Question</span>
                            @endif

                            @if($answer->question->difficulty_level == 'easy')
                            <span class="badge bg-light border border-success text-success">Easy</span>
                            @elseif($answer->question->difficulty_level == 'medium')
                            <span class="badge bg-light border border-warning text-warning">Medium</span>
                            @else
                            <span class="badge bg-light border border-danger text-danger">Hard</span>
                            @endif
                        </div>
                        <span class="badge bg-theme">{{ intval($answer->question->marks) }} marks</span>
                    </div>

                    <div class="card-body">

                        {{-- Question --}}
                        <p class="fw-semibold mb-3">
                            <strong>Q{{ $i + 1 }}:</strong> {{ $answer->question->question_text }}
                        </p>

                        {{-- Student Answer --}}
                        <div class="p-3 bg-light rounded border mb-3">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-person me-1"></i>Student Answer:
                            </small>
                            <p class="mb-0">{{ $answer->answer ?? 'No answer provided.' }}</p>
                        </div>

                        <input type="hidden" name="reviews[{{ $i }}][exam_answer_id]" value="{{ $answer->id }}">

                        <div class="row g-3">
                            {{-- Review --}}
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold small">Review</label>
                                <select name="reviews[{{ $i }}][review]"
                                        class="form-select form-select-sm">
                                    <option value="1" {{ optional($answer->reviewAnswer)->review == 1 ? 'selected' : '' }}>
                                        Correct
                                    </option>
                                    <option value="0" {{ optional($answer->reviewAnswer)->review == 0 ? 'selected' : '' }}>
                                        Wrong
                                    </option>
                                </select>
                            </div>

                            {{-- Marks Awarded --}}
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold small">
                                    Marks Awarded
                                    <small class="text-muted">(max: {{ intval($answer->question->marks) }})</small>
                                </label>
                                <input type="number"
                                       name="reviews[{{ $i }}][marks_awarded]"
                                       class="form-control form-control-sm"
                                       min="0"
                                       max="{{ $answer->question->marks }}"
                                       step="0.5"
                                       value="{{ optional($answer->reviewAnswer)->marks_awarded ?? 0 }}">
                            </div>
                        </div>

                    </div>
                </div>

                @endif
                @endforeach

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <button type="submit" class="btn btn-outline-theme">
                            <i class="bi bi-save me-1"></i>Save Review
                        </button>
                    </div>
                </div>

            </form>

        </div>

        {{-- Student Info --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header">
                    <h6 class="card-title fw-semibold mb-0 p-0">
                        <i class="bi bi-person me-1"></i>Student Info
                    </h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Name</span>
                            <strong>{{ $student->first_name ?? 'N/A' }} {{ $student->last_name ?? 'N/A' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Email</span>
                            <strong>{{ $student->email }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Exam</span>
                            <strong>{{ $exam->exam_title }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Course</span>
                            <strong>{{ $exam->course->course_title }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Total Marks</span>
                            <strong>{{ intval($exam->total_marks) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Passing Marks</span>
                            <strong>{{ intval($exam->passing_marks) }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

</section>
@endsection
