@extends('student.layouts.app')
@section('title', 'Answer Sheet')

@section('content')

@if(session('success') || session('status') || session('error'))
    @include('student.layouts.common.status')
@endif

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
@endphp

<section class="section py-3">

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-header-left">
                        <h5>
                            <i class="bi bi-clipboard-check"></i>
                            <span class="ms-1">@yield('title')</span>
                        </h5>
                        <nav style="--bs-breadcrumb-divider: '•';">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-secondary text-white fw-normal px-3 py-2 rounded-pill small" id="answeredBadge">
                                <i class="bi bi-ui-checks me-1"></i>0 / {{ $exam->questions->count() }} Answered
                            </span>
                            <span class="badge bg-success text-white fw-semibold px-3 py-2 rounded-pill" id="exam_timer">
                                <i class="bi bi-stopwatch me-1"></i>00h : 00m : 00s
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <form method="POST" action="{{ route('student.myExams.store', $exam->id) }}" id="examAnswerForm">
        @csrf
        <input type="hidden" name="stopped" id="stoppedFlag" value="0">
        <input type="hidden" name="stop_reason" id="stopReasonFlag" value="">

        <div class="row g-3 align-items-start">

            {{-- LEFT: Questions --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">

                    {{-- Card Header --}}
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">{{ $exam->exam_title }}</h5>
                                <small class="text-muted">{{ $exam->exam_code }}</small>
                            </div>
                            <span class="badge rounded-pill {{ $statusClass }} px-3 py-2">
                                <i class="bi {{ $statusIconClass }} me-1"></i>{{ $status }}
                            </span>
                        </div>
                    </div>

                    {{-- Progress bar --}}
                    <div class="px-4 pt-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted fw-semibold">Exam Progress</small>
                            <small class="text-muted" id="progressLabel">0%</small>
                        </div>
                        <div class="progress rounded-pill" style="height: 6px;">
                            <div class="progress-bar bg-success rounded-pill" id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    {{-- Questions --}}
                    <div class="card-body px-4 py-3">
                        @if($exam->questions->count() > 0)
                            @foreach($exam->questions as $index => $question)
                            <div class="question-item border rounded-3 p-4 mb-3 bg-light bg-opacity-50">

                                {{-- Question Meta --}}
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="badge bg-dark bg-opacity-75 rounded-pill px-2 py-1 small">Q{{ $index + 1 }}</span>

                                    @if($question->difficulty_level == 'easy')
                                        <span class="badge border border-success text-success bg-success bg-opacity-10 small">Easy</span>
                                    @elseif($question->difficulty_level == 'medium')
                                        <span class="badge border border-warning text-warning bg-warning bg-opacity-10 small">Medium</span>
                                    @else
                                        <span class="badge border border-danger text-danger bg-danger bg-opacity-10 small">Hard</span>
                                    @endif

                                    @if($question->question_type == 'mcq_2')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary small">MCQ · 2 Options</span>
                                    @elseif($question->question_type == 'mcq_4')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary small">MCQ · 4 Options</span>
                                    @elseif($question->question_type == 'short_question')
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info small">Short Answer</span>
                                    @else
                                        <span class="badge bg-purple bg-opacity-10 text-secondary border border-secondary small">Long Answer</span>
                                    @endif

                                    <span class="ms-auto badge bg-success bg-opacity-10 text-success border border-success fw-bold small">
                                        {{ intval($question->marks) }} {{ intval($question->marks) == 1 ? 'Mark' : 'Marks' }}
                                    </span>
                                </div>

                                {{-- Question Text --}}
                                <p class="fw-semibold text-dark mb-3 lh-base">{{ $question->question_text }}</p>

                                {{-- Question Figure --}}
                                @if($question->question_figure)
                                <div class="text-center mb-3">
                                    <img
                                        src="{{ asset('storage/question_figure/' . $question->question_figure) }}"
                                        alt="Question Figure"
                                        class="img-fluid rounded border"
                                        style="max-height: 220px;"
                                    >
                                </div>
                                @endif

                                {{-- MCQ Options --}}
                                @if(in_array($question->question_type, ['mcq_2', 'mcq_4']))
                                    <div class="d-flex flex-column gap-2">
                                        @foreach(['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d] as $optKey => $optVal)
                                            @if($optVal)
                                            <label class="d-flex align-items-center gap-3 border rounded-3 px-3 py-2 bg-white cursor-pointer fw-normal text-dark w-100 mb-0" for="q{{ $question->id }}_option_{{ $optKey }}">
                                                <input
                                                    class="form-check-input mt-0 flex-shrink-0"
                                                    type="radio"
                                                    name="answers[{{ $question->id }}]"
                                                    id="q{{ $question->id }}_option_{{ $optKey }}"
                                                    value="{{ $optVal }}"
                                                >
                                                <span class="badge bg-secondary bg-opacity-25 text-dark fw-bold">{{ strtoupper($optKey) }}</span>
                                                <span class="small">{{ $optVal }}</span>
                                            </label>
                                            @endif
                                        @endforeach
                                    </div>

                                {{-- Short / Long Answer --}}
                                @elseif(in_array($question->question_type, ['short_question', 'long_question']))
                                    <textarea
                                        class="form-control bg-white border rounded-3"
                                        name="answers[{{ $question->id }}]"
                                        id="q{{ $question->id }}_answer"
                                        rows="{{ $question->question_type == 'short_question' ? 3 : 6 }}"
                                        placeholder="Write your answer here…"
                                    ></textarea>
                                @endif

                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-1 d-block mb-2 text-muted opacity-50"></i>
                                <p class="mb-0">No questions available for this exam.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Card Footer: Actions --}}
                    <div class="card-footer bg-white border-top px-4 py-3">
                        <div class="d-flex gap-2">
                            <button
                                type="button"
                                class="btn btn-outline-danger w-50 stopExamBtn"
                                data-bs-toggle="modal"
                                data-bs-target="#stop_exam_confirm_modal"
                            >
                                <i class="bi bi-stop-circle me-1"></i>Stop Exam
                            </button>
                            <button
                                type="button"
                                class="btn btn-success w-50"
                                data-bs-toggle="modal"
                                data-bs-target="#submit_exam_confirm_modal"
                            >
                                <i class="bi bi-send me-1"></i>Submit Answers
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Exam Summary --}}
            <div class="col-lg-4">

                {{-- Exam Summary --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-clipboard-data me-2"></i>Exam Summary
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted"><i class="bi bi-book me-2"></i>Course</span>
                                <span class="fw-semibold text-end text-dark">
                                    <h6 class="mb-0 text-truncate">{{ $exam->course->course_title }}</h6>
                                    <small class="text-muted fw-normal">[{{ $exam->course->course_code }}]</small>
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted"><i class="bi bi-tag me-2"></i>Exam Type</span>
                                <span class="fw-semibold text-dark">{{ $exam->exam_type ?? 'General' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted"><i class="bi bi-bookmark me-2"></i>Status</span>
                                <span class="badge rounded-pill {{ $statusClass }} px-3">
                                    <i class="bi {{ $statusIconClass }} me-1"></i>{{ $status }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted"><i class="bi bi-calendar3 me-2"></i>Exam Date</span>
                                <span class="fw-semibold text-dark">{{ $exam->exam_date->format('d M Y') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted"><i class="bi bi-clock me-2"></i>Time</span>
                                <span class="fw-semibold text-dark">
                                    {{ $exam->start_time?->format('h:i A') ?? 'N/A' }}
                                    <span class="text-muted fw-normal">–</span>
                                    {{ $exam->end_time?->format('h:i A') ?? 'N/A' }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted"><i class="bi bi-stopwatch me-2"></i>Duration</span>
                                <span class="fw-semibold text-dark">{{ $exam->exam_duration_min ?? 0 }} mins</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted"><i class="bi bi-question-circle me-2"></i>Questions</span>
                                <span class="fw-semibold text-dark">{{ $exam->total_questions ?? 0 }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted"><i class="bi bi-patch-check me-2"></i>Total Marks</span>
                                <span class="fw-semibold text-dark">{{ intval($exam->total_marks) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted"><i class="bi bi-check2-circle me-2"></i>Passing Marks</span>
                                <span class="fw-semibold text-dark">{{ intval($exam->passing_marks) ?? 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Sticky Action Buttons --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-grid gap-2 p-3">
                        <button
                            type="button"
                            class="btn btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#submit_exam_confirm_modal"
                        >
                            <i class="bi bi-send me-1"></i>Submit Answers
                        </button>
                        <button
                            type="button"
                            class="btn btn-outline-danger stopExamBtn"
                            data-bs-toggle="modal"
                            data-bs-target="#stop_exam_confirm_modal"
                        >
                            <i class="bi bi-stop-circle me-1"></i>Stop Exam
                        </button>
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

<script>
document.addEventListener('DOMContentLoaded', function () {

    const totalQuestions = {{ $exam->questions->count() }};
    let timeLeft = {{ $remainingSeconds ?? 0 }};
    const timerEl     = document.getElementById('exam_timer');
    const answeredEl  = document.getElementById('answeredBadge');
    const progressBar = document.getElementById('progressBar');
    const progressLbl = document.getElementById('progressLabel');

    // --- Timer ---
    function updateTimer() {
        if (timeLeft <= 0) {
            timerEl.innerHTML = '<i class="bi bi-stopwatch me-1"></i>Time Up!';
            timerEl.classList.remove('bg-success');
            timerEl.classList.add('bg-danger');
            submitExam(false, 'timer_expired');
            return;
        }

        const h = Math.floor(timeLeft / 3600);
        const m = Math.floor((timeLeft % 3600) / 60);
        const s = timeLeft % 60;

        timerEl.innerHTML = `<i class="bi bi-stopwatch me-1"></i>${String(h).padStart(2,'0')}h : ${String(m).padStart(2,'0')}m : ${String(s).padStart(2,'0')}s`;

        if (timeLeft <= 300) {
            timerEl.classList.remove('bg-success');
            timerEl.classList.add('bg-danger');
        }

        timeLeft--;
    }

    updateTimer();
    setInterval(updateTimer, 1000);

    // --- Answered Counter + Progress ---
    function updateAnsweredCount() {
        let answered = 0;
        document.querySelectorAll('.question-item').forEach(function (item) {
            const radios   = item.querySelectorAll('input[type="radio"]');
            const textarea = item.querySelector('textarea');
            if (radios.length > 0) {
                if (item.querySelector('input[type="radio"]:checked')) answered++;
            } else if (textarea && textarea.value.trim() !== '') {
                answered++;
            }
        });

        answeredEl.innerHTML  = `<i class="bi bi-ui-checks me-1"></i>${answered} / ${totalQuestions} Answered`;

        const pct = totalQuestions > 0 ? Math.round((answered / totalQuestions) * 100) : 0;
        progressBar.style.width    = pct + '%';
        progressBar.setAttribute('aria-valuenow', pct);
        progressLbl.textContent    = pct + '%';
    }

    document.querySelectorAll('input[type="radio"]').forEach(r => r.addEventListener('change', updateAnsweredCount));
    document.querySelectorAll('textarea').forEach(t => t.addEventListener('input', updateAnsweredCount));
    updateAnsweredCount();

    // --- MCQ label highlight on select ---
    document.querySelectorAll('input[type="radio"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            const group = document.querySelectorAll(`input[name="${this.name}"]`);
            group.forEach(function (r) {
                const lbl = r.closest('label');
                if (lbl) {
                    lbl.classList.remove('border-success', 'bg-success', 'bg-opacity-10', 'text-success');
                }
            });
            const selectedLabel = this.closest('label');
            if (selectedLabel) {
                selectedLabel.classList.add('border-success', 'bg-success', 'bg-opacity-10', 'text-success');
            }
        });
    });

    // --- Submit / Stop Modals ---
    document.getElementById('confirmSubmitExam').addEventListener('click', function () {
        submitExam(false, '');
    });

    document.getElementById('confirmStopExam').addEventListener('click', function () {
        submitExam(true, document.getElementById('stopReasonFlag').value || 'manual_stop');
    });

});
</script>

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
            if (link && link.href && !link.href.startsWith('javascript') && !link.getAttribute('data-bs-toggle') && !formSubmitting) {
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
            return window.outerWidth >= screenWidth * 0.95 && window.outerHeight >= screenHeight * 0.95;
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
                if (!checkMaximized()) submitExam(true, 'browser_maximized');
            }, 300);
        });
    })();
    @endif












    @if($ruleKey === 'webcam_required')
    (function () {
        let stream = null;
        let webcamCheckInterval = null;

        const overlay = document.createElement('div');
        overlay.id = 'webcam_overlay';
        overlay.style.cssText = `
            position: fixed; bottom: 16px; left: 16px; z-index: 9999;
            background: #000; border-radius: 10px; overflow: hidden;
            width: 160px; height: 120px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);
            border: 2px solid #198754;
        `;

        const video = document.createElement('video');
        video.autoplay = true;
        video.muted = true;
        video.playsInline = true;
        video.style.cssText = 'width:100%;height:100%;object-fit:cover;';

        const badge = document.createElement('div');
        badge.style.cssText = `
            position: absolute; top: 6px; left: 6px;
            background: #198754; color: #fff;
            font-size: 11px; padding: 2px 7px; border-radius: 20px;
            font-family: inherit; font-weight: 500;
        `;
        badge.innerHTML = '&#128247; Live';

        overlay.appendChild(video);
        overlay.appendChild(badge);
        document.body.appendChild(overlay);

        function onDenied(reason) {
            badge.style.background = '#dc3545';
            badge.textContent = 'Webcam Off';
            submitExam(true, reason || 'webcam_denied');
        }

        function startWebcam() {
            navigator.mediaDevices.getUserMedia({ video: true, audio: false })
                .then(function (s) {
                    stream = s;
                    video.srcObject = s;
                    badge.innerHTML = '&#128247; Live';
                    badge.style.background = '#198754';

                    webcamCheckInterval = setInterval(function () {
                        if (!stream || !stream.active) {
                            clearInterval(webcamCheckInterval);
                            onDenied('webcam_disconnected');
                        }
                        const tracks = stream.getVideoTracks();
                        if (!tracks.length || tracks[0].readyState === 'ended') {
                            clearInterval(webcamCheckInterval);
                            onDenied('webcam_disconnected');
                        }
                    }, 3000);
                })
                .catch(function () {
                    onDenied('webcam_denied');
                });
        }

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            onDenied('webcam_not_supported');
        } else {
            startWebcam();
        }
    })();
    @endif












    @endforeach

});
</script>







@endsection
