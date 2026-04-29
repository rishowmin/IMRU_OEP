@extends('student.layouts.app')
@section('title', 'My Exam Details')

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

        if ($isSubmitted) {
            $status = 'Submitted';
            $statusClass = 'bg-primary';
            $statusIconClass = 'bi-check-circle';
            $canStart = false;
        } elseif ($now->lt($startDT)) {
            $status = 'Upcoming';
            $statusClass = 'bg-warning';
            $statusIconClass = 'bi-hourglass-split';
            $canStart = false;
        } elseif ($now->between($startDT, $endDT)) {
            $status = 'Ongoing';
            $statusClass = 'bg-success';
            $statusIconClass = 'bi-play-circle';
            $canStart = true;
        } else {
            $status = 'Ended';
            $statusClass = 'bg-secondary';
            $statusIconClass = 'bi-slash-circle';
            $canStart = false;
        }

        $noQuestions = ($exam->questions_count ?? 0) === 0;
    @endphp

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

                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-book text-muted mt-1"></i>
                                <div>
                                    <div class="small text-muted">Course</div>
                                    <div class="fw-semibold">
                                        {{ $exam->course->course_title }}
                                        <span class="text-muted small">[{{ $exam->course->course_code }}]</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-tag text-muted mt-1"></i>
                                <div>
                                    <div class="small text-muted">Exam Type</div>
                                    <div class="fw-semibold">{{ $exam->exam_type ?? 'General' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-2 mb-3">
                                <i class="bi bi-calendar3 text-muted mt-1"></i>
                                <div>
                                    <div class="small text-muted">Exam Date</div>
                                    <div class="fw-semibold">{{ $exam->exam_date->format('d M Y') }}</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-2 mb-3">
                                <i class="bi bi-clock text-muted mt-1"></i>
                                <div>
                                    <div class="small text-muted">Exam Time</div>
                                    <div class="fw-semibold">
                                        {{ $exam->start_time?->format('h:i A') ?? 'N/A' }}
                                        <span class="text-muted">to</span>
                                        {{ $exam->end_time?->format('h:i A') ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-stopwatch text-muted mt-1"></i>
                                <div>
                                    <div class="small text-muted">Duration</div>
                                    <div class="fw-semibold">{{ $exam->exam_duration_min ?? 0 }} minutes</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-start gap-2 mb-3">
                                <i class="bi bi-patch-check text-muted mt-1"></i>
                                <div>
                                    <div class="small text-muted">Total Marks</div>
                                    <div class="fw-semibold">{{ intval($exam->total_marks) }}</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-2 mb-3">
                                <i class="bi bi-trophy text-muted mt-1"></i>
                                <div>
                                    <div class="small text-muted">Passing Marks</div>
                                    <div class="fw-semibold">{{ intval($exam->passing_marks) ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-question-circle text-muted mt-1"></i>
                                <div>
                                    <div class="small text-muted">Total Questions</div>
                                    <div class="fw-semibold">
                                        {{ $exam->total_questions ?? 0 }}
                                        @if($noQuestions)
                                        <span class="badge bg-warning text-dark ms-1">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Not Set Yet
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        {{-- Right: Action Panel --}}
        <div class="col-lg-4 mb-3">

            <div class="rounded-top {{ $statusClass }}" style="height: 5px;"></div>

            <div class="card border-0 shadow-sm h-100">
                <div class="card-header">
                    <h6 class="card-title fw-semibold mb-0 p-0">Exam Summary</h6>
                </div>

                <div class="card-body d-flex flex-column justify-content-between">

                    <div>

                        <ul class="list-group small">
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Status</span>
                                <span class="badge rounded-pill {{ $statusClass }}">
                                    <i class="bi {{ $statusIconClass }} me-1"></i>{{ $status }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Questions</span>
                                <strong>{{ $exam->total_questions ?? 0 }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Total Marks</span>
                                <strong>{{ intval($exam->total_marks) }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Passing Marks</span>
                                <strong>{{ intval($exam->passing_marks) ?? 'N/A' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Duration</span>
                                <strong>{{ $exam->exam_duration_min ?? 0 }} mins</strong>
                            </li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        @if($noQuestions)
                        <button class="btn btn-outline-danger" disabled>
                            <i class="bi bi-slash-circle me-1"></i>Not Available
                        </button>
                        @elseif($isSubmitted)
                        <a href="{{ route('student.myExams.result', $exam->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-bar-chart me-1"></i>View Result
                        </a>
                        @elseif($canStart)
                        <a href="{{ route('student.myExams.rule', $exam->id) }}" class="btn btn-success">
                            <i class="bi bi-play-fill me-1"></i>Start Exam
                        </a>
                        @elseif($status === 'Upcoming')
                        <button class="btn btn-outline-primary" disabled>
                            <i class="bi bi-hourglass-split me-1"></i>Not Yet Open
                        </button>
                        @else
                        <a href="{{ route('student.myExams.result', $exam->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-bar-chart me-1"></i>View Result
                        </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>

</section>

@endsection

@section('scripts')
@endsection
