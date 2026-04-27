@extends('student.layouts.app')
@section('title', 'Answer Sheet')

@section('content')

@if(session('success') || session('status') || session('error'))
@include('student.layouts.common.status')
@endif

<section class="section">

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-header-left">
                        <h5>
                            <i class="bi bi-file-text"></i>
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
                        <div class="fixed-timer">
                            <div class="timer">
                                <h4 class="mb-0" id="exam_timer">
                                    <span class="badge bg-theme">
                                        <i class="bi bi-stopwatch me-2"></i>00h:00m:00s
                                    </span>
                                </h4>
                            </div>
                            <div class="answered-question">
                                <small class="badge bg-light text-dark border fw-semibold">0/10 Questions Answered</small>
                            </div>
                        </div>
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

        <input type="hidden" name="stopped" id="stoppedFlag" value="0">
        <input type="hidden" name="stop_reason" id="stopReasonFlag" value="">

        <div class="row">

            {{-- Left: Answer Sheet --}}
            <div class="col-lg-8 mb-3">
                <div class="rounded-top {{ $statusClass }}" style="height: 5px;"></div>
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title fw-semibold text-success mb-0 p-0">
                                <i class="bi bi-clipboard-check me-1"></i>
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
                                            <strong>Q{{ $index + 1 }}:</strong> {{ $question->question_text }}
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
                                    <div class="options small mb-2">
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

                    <div class="card-footer p-4">

                        <div class="d-flex flex-row gap-2">
                            <a href="javascript:void(0)" class="btn btn-outline-danger flex-fill w-50 stopExamBtn" data-bs-toggle="modal" data-bs-target="#stop_exam_confirm_modal">
                                <i class="bi bi-stop-circle me-1"></i>Stop Exam
                            </a>
                            {{-- <button type="submit" class="btn btn-success flex-fill w-50">
                                <i class="bi bi-send me-1"></i>Submit Answers
                            </button> --}}
                            <button type="button" class="btn btn-success flex-fill w-50" data-bs-toggle="modal" data-bs-target="#submit_exam_confirm_modal">
                                <i class="bi bi-send me-1"></i>Submit Answers
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Right: Exam Summary --}}
            <div class="col-lg-4 mb-3">

                <div class="rounded-top {{ $statusClass }}" style="height: 5px;"></div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h6 class="card-title fw-semibold text-success mb-0 p-0">
                            <i class="bi bi-file-earmark-text me-1"></i>
                            Exam Summary
                        </h6>
                    </div>

                    <div class="card-body d-flex flex-column justify-content-between">

                        <ul class="list-group small mb-4">
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

                        <div class="d-grid gap-2 mb-2">
                            {{-- <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-send me-1"></i>Submit Answers
                            </button> --}}
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#submit_exam_confirm_modal">
                                <i class="bi bi-send me-1"></i>Submit Answers
                            </button>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm stopExamBtn" data-bs-toggle="modal" data-bs-target="#stop_exam_confirm_modal">
                                <i class="bi bi-stop-circle me-1"></i>Stop Exam
                            </a>
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </form>

</section>

@include('student.layouts.common.submitExamConfirmModal')
@include('student.layouts.common.stopExamConfirmModal')

@endsection

@section('scripts')
{{-- Global exam state --}}
<script>
    var formSubmitting = false;

    function submitExam(stopped, reason) {
        if (formSubmitting) return;
        formSubmitting = true;
        document.getElementById('stoppedFlag').value = stopped ? '1' : '0';
        document.getElementById('stopReasonFlag').value = reason ?? '';
        document.getElementById('examAnswerForm').submit();
    }
</script>

{{-- Timer & Counter --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const totalQuestions = {{ $exam->questions->count() }};
    let timeLeft = {{ $remainingSeconds ?? 0 }};
    const timerSpan = document.querySelector('#exam_timer .badge');
    const examAnswerForm = document.getElementById('examAnswerForm');
    const answeredBadge = document.querySelector('.answered-question small');

    function updateTimer() {
        if (timeLeft <= 0) {
            timerSpan.innerHTML = '<i class="bi bi-stopwatch me-2"></i>00h:00m:00s';
            timerSpan.classList.remove('bg-theme');
            timerSpan.classList.add('bg-danger');
            submitExam(false, 'timer_expired');
            return;
        }

        const hours = Math.floor(timeLeft / 3600);
        const mins  = Math.floor((timeLeft % 3600) / 60);
        const secs  = timeLeft % 60;

        timerSpan.innerHTML = `<i class="bi bi-stopwatch me-2"></i>${String(hours).padStart(2, '0')}h:${String(mins).padStart(2, '0')}m:${String(secs).padStart(2, '0')}s`;

        if (timeLeft <= 300) {
            timerSpan.classList.remove('bg-theme');
            timerSpan.classList.add('bg-danger');
        }

        timeLeft--;
    }

    updateTimer();
    setInterval(updateTimer, 1000);

    function updateAnsweredCount() {
        let answered = 0;
        document.querySelectorAll('.question-item').forEach(function (item) {
            const radios = item.querySelectorAll('input[type="radio"]');
            const textarea = item.querySelector('textarea');
            if (radios.length > 0) {
                if (item.querySelector('input[type="radio"]:checked')) answered++;
            } else if (textarea) {
                if (textarea.value.trim() !== '') answered++;
            }
        });
        answeredBadge.textContent = `${answered}/${totalQuestions} Questions Answered`;
    }

    document.querySelectorAll('input[type="radio"]').forEach(function (radio) {
        radio.addEventListener('change', updateAnsweredCount);
    });

    document.querySelectorAll('textarea').forEach(function (textarea) {
        textarea.addEventListener('input', updateAnsweredCount);
    });

    updateAnsweredCount();

});
</script>

{{-- Submit / Stop Modal --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('confirmSubmitExam').addEventListener('click', function () {
        submitExam(false, '');
    });

    document.getElementById('confirmStopExam').addEventListener('click', function () {
        submitExam(true, document.getElementById('stopReasonFlag').value || 'manual_stop');
    });

});
</script>

{{-- Dynamic Rule Protection --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    @foreach($mappedRules as $map)
    @php $ruleKey = $map->rule->key ?? ''; @endphp

    @if($ruleKey === 'back_button')
    (function () {
        history.pushState(null, null, location.href);

        window.addEventListener('popstate', function () {
            history.pushState(null, null, location.href);
            document.getElementById('stopReasonFlag').value = 'back_button';
            new bootstrap.Modal(document.getElementById('stop_exam_confirm_modal')).show();
        });

        document.addEventListener('click', function (e) {
            const link = e.target.closest('a');
            if (
                link &&
                link.href &&
                !link.href.startsWith('javascript') &&
                !link.getAttribute('data-bs-toggle') &&
                !formSubmitting
            ) {
                e.preventDefault();
                document.getElementById('stopReasonFlag').value = 'url_change';
                new bootstrap.Modal(document.getElementById('stop_exam_confirm_modal')).show();
            }
        });

        window.addEventListener('beforeunload', function () {
            if (!formSubmitting) {
                navigator.sendBeacon(
                    '{{ route("student.myExams.store", $exam->id) }}',
                    new URLSearchParams({
                        _token: '{{ csrf_token() }}',
                        stopped: '1',
                        stop_reason: 'url_change',
                    })
                );
            }
        });
    })();
    @endif

    @if($ruleKey === 'tab_switching')
    (function () {
        let tabSwitchCount = 0;
        const maxTabSwitches = 1;
        document.addEventListener('visibilitychange', function () {
            if (document.hidden && window.outerHeight > 0) {
                tabSwitchCount++;
                if (tabSwitchCount >= maxTabSwitches) {
                    submitExam(true, 'tab_switching');
                }
            } else if (!document.hidden && tabSwitchCount < maxTabSwitches) {
                document.getElementById('stopReasonFlag').value = 'tab_switching';
                new bootstrap.Modal(document.getElementById('stop_exam_confirm_modal')).show();
            }
        });
    })();
    @endif

    @if($ruleKey === 'browser_maximized')
    (function () {
        const screenWidth  = window.screen.width;
        const screenHeight = window.screen.height;

        function checkMaximized() {
            return (
                window.outerWidth  >= screenWidth  * 0.95 &&
                window.outerHeight >= screenHeight * 0.95
            );
        }

        document.addEventListener('visibilitychange', function () {
            if (document.hidden && window.outerHeight === 0) {
                submitExam(true, 'browser_maximized');
            }
        });

        let resizeTimeout;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function () {
                if (!checkMaximized()) {
                    submitExam(true, 'browser_maximized');
                }
            }, 300);
        });
    })();
    @endif

    @endforeach

});
</script>

@endsection

