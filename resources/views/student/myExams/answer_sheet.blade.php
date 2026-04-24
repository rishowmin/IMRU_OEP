@extends('student.layouts.app')
@section('title', 'Answer Sheet')

@section('content')

@if(session('success') || session('status') || session('error'))
@include('student.layouts.common.status')
@endif

<section class="section">

    <div class="fixed-timer">
        <small style="font-size: 10px; opacity: 0.7;">Time Left</small>
        <h5 class="mb-0" id="exam_timer">00:00</h5>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-header-left">
                        <h5>
                            <i class="bi bi-person-badge"></i>
                            <span class="ms-1">@yield('title')</span>
                        </h5>
                        <nav style="--bs-breadcrumb-divider: '•';">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('student.myExams') }}">My Exams</a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        <a href="{{ route('student.myExams') }}" class="btn btn-outline-theme btn-sm">
                            <i class="bi bi-arrow-left-square"></i>
                            <span class="ms-1">Back to My Exams</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $now = now();
        $startDT = \Carbon\Carbon::parse(
            $exam->exam_date->toDateString() . ' ' . \Carbon\Carbon::parse($exam->start_time)->format('H:i:s')
        );
        $endDT = \Carbon\Carbon::parse(
            $exam->exam_date->toDateString() . ' ' . \Carbon\Carbon::parse($exam->end_time)->format('H:i:s')
        );

        if ($now->lt($startDT)) {
            $status = 'Upcoming';
            $statusClass = 'bg-primary';
            $statusIconClass = 'bi-hourglass-split';
            $canStart = false;
        } elseif ($now->between($startDT, $endDT)) {
            $status = 'Ongoing';
            $statusClass = 'bg-success';
            $statusIconClass = 'bi-play-circle';
            $canStart = true;
        } else {
            $status = 'Completed';
            $statusClass = 'bg-secondary';
            $statusIconClass = 'bi-check-circle';
            $canStart = false;
        }

        $noQuestions = !$exam->total_questions || $exam->total_questions == 0;
    @endphp

    <form method="POST" action="{{ route('student.myExams.store', $exam->id) }}" id="examAnswerForm">
        @csrf

        <div class="row">

            {{-- Left: Exam Details --}}
            <div class="col-lg-8 mb-3">
                <div class="rounded-top {{ $statusClass }}" style="height: 5px;"></div>
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title fw-semibold mb-0 p-0">
                                {{ $exam->exam_title }}
                                <small class="text-muted">[{{ $exam->exam_code }}]</small>
                            </h5>
                            <div class="d-flex gap-2">
                                <span class="badge rounded-pill {{ $statusClass }}">
                                    <i class="bi {{ $statusIconClass }} me-1"></i>{{ $status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="question-list">
                                @if ($exam->questions->count() > 0)
                                @foreach ($exam->questions as $index => $question)
                                <div class="question-item mb-3 p-3 border rounded">



                                    <div class="question-info d-flex align-items-center gap-1 mb-2">
                                        <p class="exam_paper_difficulty_level mb-0 fw-bold">
                                            @if($question->difficulty_level == 'easy')
                                            <span class="badge bg-light border border-success text-success">Easy</span>
                                            @elseif($question->difficulty_level == 'medium')
                                            <span class="badge bg-light border border-warning text-warning">Medium</span>
                                            @else
                                            <span class="badge bg-light border border-danger text-danger">Hard</span>
                                            @endif
                                        </p>

                                        <p class="exam_paper_question_type mb-0">
                                            @if($question->question_type == 'mcq_2')
                                            <span class="badge border border-dark bg-dark">MCQ (2 options)</span>
                                            @elseif($question->question_type == 'mcq_4')
                                            <span class="badge border border-dark bg-dark">MCQ (4 options)</span>
                                            @elseif($question->question_type == 'short_question')
                                            <span class="badge border border-dark bg-dark">Short Question</span>
                                            @else
                                            <span class="badge border border-dark bg-dark">Long Question</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="d-flex align-items-baseline justify-content-between mb-2">
                                        <p class="question mb-0" style="width: 95%;">
                                            <strong>Q{{ $question->question_order }}:</strong> {{ $question->question_text }}
                                        </p>
                                        <p class="exam_paper_marks fw-bold text-end mb-0" style="width: 5%;">
                                            {{ intval($question->marks) }}
                                        </p>
                                    </div>

                                    @if($question->question_figure)
                                    <div class="question-figure p-2 border rounded w-50 m-auto mb-2">
                                        <img src="{{ asset('storage/question_figure/' . $question->question_figure) }}" alt="Question Figure" class="img-fluid" style="width:100%; max-height: 200px">
                                    </div>
                                    @endif

                                    {{-- MCQ Options --}}
                                    @if(in_array($question->question_type, ['mcq_2', 'mcq_4']))
                                    <div class="options mb-2">
                                        @if($question->option_a)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_option_a" value="{{ $question->option_a }}">
                                            <label class="form-check-label" for="q{{ $question->id }}_option_a">
                                                <strong>A.</strong> {{ $question->option_a }}
                                            </label>
                                        </div>
                                        @endif

                                        @if($question->option_b)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_option_b" value="{{ $question->option_b }}">
                                            <label class="form-check-label" for="q{{ $question->id }}_option_b">
                                                <strong>B.</strong> {{ $question->option_b }}
                                            </label>
                                        </div>
                                        @endif

                                        @if($question->option_c)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_option_c" value="{{ $question->option_c }}">
                                            <label class="form-check-label" for="q{{ $question->id }}_option_c">
                                                <strong>C.</strong> {{ $question->option_c }}
                                            </label>
                                        </div>
                                        @endif

                                        @if($question->option_d)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_option_d" value="{{ $question->option_d }}">
                                            <label class="form-check-label" for="q{{ $question->id }}_option_d">
                                                <strong>D.</strong> {{ $question->option_d }}
                                            </label>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Short/Long Answer Textarea --}}
                                    @elseif(in_array($question->question_type, ['short_question', 'long_question']))
                                    <div class="answer mb-2">
                                        <textarea class="form-control" name="answers[{{ $question->id }}]" id="q{{ $question->id }}_answer" rows="{{ $question->question_type == 'short_question' ? 3 : 6 }}" placeholder="Write your answer here..."></textarea>
                                    </div>
                                    @endif

                                </div>
                                @endforeach
                                @else
                                <p class="text-center text-muted">Right now no questions are available for this exam.</p>
                                @endif
                            </div>

                        </div>


                    </div>

                    <div class="card-footer">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-success">
                                <i class="bi bi-play-fill me-1"></i>Submit
                            </button>
                        </div>


                    </div>
                </div>
            </div>

            {{-- Right: Action Panel --}}
            <div class="col-lg-4 mb-3">

                <div class="rounded-top {{ $statusClass }}" style="height: 5px;"></div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h6 class="card-title fw-semibold mb-0 p-0">Exam Summary</h6>
                    </div>

                    <div class="card-body d-flex flex-column justify-content-between">

                        <ul class="list-group small">
                            <li class="list-group-item d-flex justify-content-between small">
                                <span class="text-muted"><i class="bi bi-book text-muted mt-1 me-1"></i> Course</span>
                                <strong>
                                    {{ $exam->course->course_title }}
                                    <span class="text-muted small">[{{ $exam->course->course_code }}]</span>
                                </strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between small">
                                <span class="text-muted"><i class="bi bi-tag text-muted mt-1 me-1"></i> Exam Type</span>
                                <strong>{{ $exam->exam_type ?? 'General' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between small">
                                <span class="text-muted"><i class="bi bi-bookmark text-muted mt-1 me-1"></i> Status</span>
                                <strong>
                                    <span class="badge rounded-pill {{ $statusClass }}">
                                        <i class="bi {{ $statusIconClass }} me-1"></i>{{ $status }}
                                    </span>
                                </strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between small">
                                <span class="text-muted"><i class="bi bi-calendar3 text-muted mt-1 me-1"></i> Exam Date</span>
                                <strong>{{ $exam->exam_date->format('d M Y') }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between small">
                                <span class="text-muted"><i class="bi bi-clock text-muted mt-1 me-1"></i> Exam Time</span>
                                <strong>
                                    {{ $exam->start_time?->format('h:i A') ?? 'N/A' }}
                                    <span class="text-muted">-</span>
                                    {{ $exam->end_time?->format('h:i A') ?? 'N/A' }}
                                </strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between small">
                                <span class="text-muted"><i class="bi bi-stopwatch text-muted mt-1 me-1"></i> Duration</span>
                                <strong>{{ $exam->exam_duration_min ?? 0 }} mins</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between small">
                                <span class="text-muted"><i class="bi bi-question-circle text-muted mt-1 me-1"></i> Questions</span>
                                <strong>{{ $exam->total_questions ?? 0 }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between small">
                                <span class="text-muted"><i class="bi bi-patch-check text-muted mt-1 me-1"></i> Total Marks</span>
                                <strong>{{ intval($exam->total_marks) }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between small">
                                <span class="text-muted"><i class="bi bi-question-circle text-muted mt-1 me-1"></i> Passing Marks</span>
                                <strong>{{ intval($exam->passing_marks) ?? 'N/A' }}</strong>
                            </li>
                        </ul>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-success">
                                <i class="bi bi-play-fill me-1"></i>Submit
                            </button>
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </form>

</section>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const durationMinutes = {{ $exam->exam_duration_min ?? 0 }};
        let timeLeft = durationMinutes * 60;
        const timerEl = document.getElementById('exam_timer');
        const timerBox = document.querySelector('.fixed-timer');
        const examAnswerForm = document.getElementById('examAnswerForm');

        function updateTimer() {
            if (timeLeft <= 0) {
                timerEl.textContent = '00:00';
                // Auto submit
                examAnswerForm.submit();
                return;
            }

            const mins = Math.floor(timeLeft / 60);
            const secs = timeLeft % 60;
            timerEl.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');

            // Turn red under 5 minutes
            if (timeLeft <= 300) {
                timerBox.style.background = '#dc3545';
                timerBox.style.borderColor = '#dc3545';
            }

            timeLeft--;
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    });
</script>
@endsection
