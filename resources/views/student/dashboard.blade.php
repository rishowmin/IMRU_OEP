@extends('student.layouts.app')
@section('title', 'Dashboard')

@section('styles')
<style>
    .stat-card {
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        transition: box-shadow .2s;
    }
    .stat-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,.10);
    }
    .stat-card .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        line-height: 1.1;
    }
    .stat-label {
        font-size: 0.78rem;
        margin-top: 2px;
    }
    .opacity-40 { opacity: 0.4; }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        25%      { opacity: 0.25; }
        50%      { opacity: 0.5; }
        75%      { opacity: 0.75; }
    }
    .blink-badge {
        animation: blink 1.2s ease-in-out infinite;
    }
</style>
@endsection

@section('content')

@if(session('status'))
@include('layouts.inc.common.messages.status')
@endif

<div class="pagetitle mb-0">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-header-left">
                            <h1>
                                <i class="bi bi-grid"></i>
                                <span class="ms-1">@yield('title')</span>
                            </h1>
                            <nav style="--bs-breadcrumb-divider: '•';">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item active"><i class="bi bi-house"></i> Dashboard</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="section dashboard">

    {{-- ── Stat Cards ── --}}
    <div class="row g-3 mb-3">

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary border border-primary">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value text-theme">{{ $totalCourses }}</div>
                        <div class="stat-label text-muted">Enrolled Courses</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning border border-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="stat-value text-theme">{{ $upcomingExams }}</div>
                        <div class="stat-label text-muted">Upcoming Exams</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success border border-success">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <div class="stat-value text-theme">{{ $totalAttempts }}</div>
                        <div class="stat-label text-muted">Exams Attempted</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info border border-info">
                        <i class="bi bi-bar-chart-line-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value text-theme">{{ $averageScore }}%</div>
                        <div class="stat-label text-muted">Average Score</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-3">

        {{-- ── Upcoming Exams ── --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="text-theme fw-bold mb-0"><i class="bi bi-calendar-event me-2 text-warning"></i>Upcoming Exams</h6>
                    <a href="{{ route('student.myExams') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($upcomingExamsList->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Exam</th>
                                    <th>Course</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingExamsList as $exam)
                                @php
                                    $now = now();
                                    $startDT = \Carbon\Carbon::parse($exam->exam_date->toDateString() . ' ' . \Carbon\Carbon::parse($exam->start_time)->format('H:i:s'));
                                    $endDT   = \Carbon\Carbon::parse($exam->exam_date->toDateString() . ' ' . \Carbon\Carbon::parse($exam->end_time)->format('H:i:s'));
                                    $isOngoing = $now->between($startDT, $endDT);
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-semibold text-dark">{{ Str::limit($exam->exam_title, 24) }}</td>
                                    <td class="text-muted">{{ Str::limit($exam->course->course_title ?? '—', 20) }}</td>
                                    <td class="text-center">
                                        @if($isOngoing)
                                        <span class="badge bg-success border border-success blink-badge">
                                            <i class="bi bi-play-circle me-1" style="font-size: 10px;"></i>Ongoing
                                        </span>
                                        @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">
                                            {{ $exam->exam_date->format('d M Y') }}
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-muted text-center">{{ $exam->exam_duration_min }} mins</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x fs-2 d-block mb-2 opacity-40"></i>
                        <p class="mb-0 small">No upcoming exams</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Enrolled Courses ── --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="text-theme fw-bold mb-0"><i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Enrolled Courses</h6>
                    <a href="#" class="btn btn-sm btn-outline-secondary">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($enrolledCourses->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Course</th>
                                    <th class="text-center">Code</th>
                                    <th class="text-center">Instructor</th>
                                    <th class="text-center">Exams</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enrolledCourses as $course)
                                <tr>
                                    <td class="ps-3 fw-semibold text-dark">{{ Str::limit($course->course_title, 24) }}</td>
                                    <td class="text-center"><span class="badge bg-primary bg-opacity-10 text-primary border border-primary">{{ $course->course_code }}</span></td>
                                    {{-- <td class="text-muted">{{ Str::limit($course->instructor->name ?? '—', 18) }}</td> --}}
                                    <td class="text-muted text-center">{{ Str::limit($course->instructor->full_name ?? '—', 18) }}</td>
                                    <td class="text-muted text-center">{{ $course->exams_count ?? 0 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-journal-x fs-2 d-block mb-2 opacity-40"></i>
                        <p class="mb-0 small">No courses enrolled</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Recent Results ── --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="text-theme fw-bold mb-0"><i class="bi bi-trophy-fill me-2 text-success"></i>Recent Results</h6>
                    <a href="{{ route('student.myResults') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($recentResults->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Exam</th>
                                    <th class="text-center">Marks</th>
                                    <th class="text-center">Score</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentResults as $result)
                                @php
                                    $pct    = intval($result->percentage ?? 0);
                                    $passed = $result->is_pass;
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-semibold text-dark">{{ Str::limit($result->exam->exam_title, 24) }}</td>
                                    <td class="text-muted text-center">{{ intval($result->total_marks_obtained) }} / {{ intval($result->total_marks) }}</td>
                                    <td class="text-center">
                                        <span class="text-muted small" style="min-width:32px;">{{ $pct }}%</span>
                                        <div class="progress flex-grow-1 {{ $passed ? 'border border-success' : 'border border-danger' }}" style="height:6px;">
                                            <div class="progress-bar {{ $passed ? 'bg-success' : 'bg-danger' }}" style="width:{{ $pct }}%"></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($passed)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success">Pass</span>
                                        @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Fail</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-trophy fs-2 d-block mb-2 opacity-40"></i>
                        <p class="mb-0 small">No results yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Exam Attempt History ── --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="text-theme fw-bold mb-0"><i class="bi bi-clock-history me-2 text-info"></i>Attempt History</h6>
                    <a href="{{ route('student.myExams') }}" class="btn btn-sm btn-outline-secondary">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($attemptHistory->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Exam</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Duration</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attemptHistory as $attempt)
                                @php
                                    $duration = $attempt->started_at && $attempt->submitted_at
                                        ? $attempt->started_at->diffInMinutes($attempt->submitted_at) . ' mins'
                                        : '—';
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-semibold text-dark">{{ Str::limit($attempt->exam->exam_title, 24) }}</td>
                                    <td class="text-muted text-center">{{ $attempt->created_at->format('d M Y') }}</td>
                                    <td class="text-muted text-center">{{ $duration }}</td>
                                    <td class="text-center">
                                        @if($attempt->stopped)
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Stopped</span>
                                        @elseif($attempt->submitted_at)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success">Submitted</span>
                                        @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">In Progress</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-clock-history fs-2 d-block mb-2 opacity-40"></i>
                        <p class="mb-0 small">No attempts yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</section>

@endsection
