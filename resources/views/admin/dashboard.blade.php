@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('styles')
<style>
    .stat-card {
        box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
        transition: box-shadow .2s;
    }

    .stat-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, .10);
    }

    .stat-icon {
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

    .opacity-40 {
        opacity: 0.4;
    }

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

        <div class="col-6 col-xl-3">
            <div class="card stat-card border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary border border-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value text-theme">{{ $totalStudents }}</div>
                        <div class="stat-label text-muted">Total Students</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card stat-card border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info border border-info">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value text-theme">{{ $totalTeachers }}</div>
                        <div class="stat-label text-muted">Total Teachers</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card stat-card border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning border border-warning">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value text-theme">{{ $totalCourses }}</div>
                        <div class="stat-label text-muted">Total Courses</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card stat-card border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success border border-success">
                        <i class="bi bi-clipboard-check-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value text-theme">{{ $totalExams }}</div>
                        <div class="stat-label text-muted">Total Exams</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Results Overview Cards ── --}}
    <div class="row g-3 mb-3">

        <div class="col-6 col-xl-3">
            <div class="card stat-card border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-secondary bg-opacity-10 text-secondary border border-secondary">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value text-theme">{{ $totalResults }}</div>
                        <div class="stat-label text-muted">Total Results</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card stat-card border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success border border-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value text-theme">{{ $passCount }}</div>
                        <div class="stat-label text-muted">Passed</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card stat-card border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger border border-danger">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value text-theme">{{ $failCount }}</div>
                        <div class="stat-label text-muted">Failed</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="card stat-card border-0 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary border border-primary">
                        <i class="bi bi-bar-chart-line-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value text-theme">{{ $avgScore }}%</div>
                        <div class="stat-label text-muted">Avg Score · {{ $passRate }}% Pass Rate</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Charts Row ── --}}
    <div class="row g-3 mb-3">

        {{-- Pass vs Fail Doughnut --}}
        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="fw-bold mb-0 text-theme"><i class="bi bi-pie-chart-fill me-2 text-success"></i>Pass vs Fail</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="passFailChart" height="220"></canvas>
                </div>
            </div>
        </div>

        {{-- Monthly Pass/Fail Trend Line --}}
        <div class="col-lg-5 col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="fw-bold mb-0 text-theme"><i class="bi bi-graph-up me-2 text-primary"></i>Monthly Pass / Fail Trend</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyTrendChart" height="160"></canvas>
                </div>
            </div>
        </div>

        {{-- Exams per Course Bar --}}
        <div class="col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="fw-bold mb-0 text-theme"><i class="bi bi-bar-chart-fill me-2 text-warning"></i>Exams per Course</h6>
                </div>
                <div class="card-body">
                    <canvas id="examsPerCourseChart" height="160"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Tables Row 1 ── --}}
    <div class="row g-3 mb-3">

        {{-- Upcoming Exams --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0 text-theme"><i class="bi bi-calendar-event me-2 text-warning"></i>Upcoming Exams</h6>
                    <a href="{{ route('admin.academic.exams.index') }}" class="text-theme small text-decoration-underline">View All<i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                <div class="card-body p-0">
                    @if($upcomingExams->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-warning">
                                <tr>
                                    <th class="ps-3">Exam</th>
                                    <th class="text-center">Course</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingExams as $exam)
                                @php
                                    $now = now();
                                    $startDT   = \Carbon\Carbon::parse($exam->exam_date->toDateString() . ' ' . \Carbon\Carbon::parse($exam->start_time)->format('H:i:s'));
                                    $endDT     = \Carbon\Carbon::parse($exam->exam_date->toDateString() . ' ' . \Carbon\Carbon::parse($exam->end_time)->format('H:i:s'));
                                    $isOngoing = $now->between($startDT, $endDT);
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-semibold text-dark">{{ Str::limit($exam->exam_title, 22) }}</td>
                                    <td class="text-center"><span class="badge bg-primary bg-opacity-10 text-primary border border-primary">{{ $exam->course->course_code }}</span></td>
                                    <td class="text-center">
                                        @if($isOngoing)
                                        <span class="badge bg-success border border-success blink-badge">
                                            <i class="bi bi-play-circle me-1"></i>Ongoing
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

        {{-- Recent Enrollments --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0 text-theme"><i class="bi bi-person-plus-fill me-2 text-primary"></i>Recent Enrollments</h6>
                    <a href="{{ route('admin.academic.enrollments.index') }}" class="text-theme small text-decoration-underline">View All<i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                <div class="card-body p-0">
                    @if($recentEnrollments->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-primary">
                                <tr>
                                    <th class="ps-3">Student</th>
                                    <th class="text-center">Course</th>
                                    <th class="text-center">Enrolled On</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentEnrollments as $enrollment)
                                <tr>
                                    <td class="ps-3 fw-semibold text-dark">{{ Str::limit($enrollment->student->first_name. ' '.$enrollment->student->last_name ?? '—', 20) }}</td>
                                    <td class="text-center"><span class="badge bg-primary bg-opacity-10 text-primary border border-primary">{{ $enrollment->course->course_code }}</span></td>
                                    <td class="text-muted text-center">{{ $enrollment->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        @if($enrollment->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success">Active</span>
                                        @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-person-x fs-2 d-block mb-2 opacity-40"></i>
                        <p class="mb-0 small">No enrollments yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- ── Tables Row 2 ── --}}
    <div class="row g-3">

        {{-- Recent Exam Attempts --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0 text-theme"><i class="bi bi-clock-history me-2 text-info"></i>Recent Exam Attempts</h6>
                    <a href="{{ route('admin.academic.examAttempts.index') }}" class="text-theme small text-decoration-underline">View All<i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                <div class="card-body p-0">
                    @if($recentAttempts->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-info">
                                <tr>
                                    <th class="ps-3">Student</th>
                                    <th class="text-center">Exam</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttempts as $attempt)
                                <tr>
                                    <td class="ps-3 fw-semibold text-dark">{{ Str::limit($attempt->student->first_name.' '.$attempt->student->last_name ?? '—', 18) }}</td>
                                    {{-- <td class="text-muted">{{ Str::limit($attempt->exam->exam_title ?? '—', 18) }}</td> --}}
                                    <td class="text-center"><span class="badge bg-info bg-opacity-10 text-info border border-info">{{ Str::limit($attempt->exam->exam_code , 18) ?? '—' }}</span></td>
                                    <td class="text-muted text-center">{{ $attempt->created_at->format('d M Y') }}</td>
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

        {{-- Per-Course Stats --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0 text-theme"><i class="bi bi-journal-text me-2 text-warning"></i>Per-Course Stats</h6>
                    <a href="{{ route('admin.academic.courses.index') }}" class="text-theme small text-decoration-underline">View All<i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                <div class="card-body p-0">
                    @if($courseStats->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="table-warning">
                                <tr>
                                    <th class="ps-3">Course</th>
                                    <th class="text-center">Code</th>
                                    <th class="text-center">Enrolled</th>
                                    <th class="text-center">Exams</th>
                                    <th class="text-center">Pass Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courseStats as $course)
                                <tr>
                                    <td class="ps-3 fw-semibold text-dark">{{ Str::limit($course->course_title, 15) }}</td>
                                    <td class="text-center"><span class="badge bg-primary bg-opacity-10 text-primary border border-primary">{{ $course->course_code }}</span></td>
                                    <td class="text-muted text-center">{{ $course->enrollments_count }}</td>
                                    <td class="text-muted text-center">{{ $course->exams_count }}</td>
                                    <td class="text-center">
                                        @if($course->pass_rate !== null)
                                            @php $pr = $course->pass_rate; @endphp
                                            <div class="">
                                                <span class="text-muted small">{{ $pr }}%</span>
                                                <div class="progress flex-grow-1 border border-success" style="height:5px;">
                                                    <div class="progress-bar {{ $pr >= 50 ? 'bg-success' : 'bg-danger' }}" style="width:{{ $pr }}%"></div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-journal-x fs-2 d-block mb-2 opacity-40"></i>
                        <p class="mb-0 small">No course data</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</section>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const gridColor  = 'rgba(0,0,0,0.06)';
    const fontColor  = '#6c757d';
    Chart.defaults.color       = fontColor;
    Chart.defaults.font.family = "'Nunito','Segoe UI',sans-serif";

    // ── 1. Pass vs Fail Doughnut ──
    new Chart(document.getElementById('passFailChart'), {
        type: 'doughnut',
        data: {
            labels  : ['Pass', 'Fail'],
            datasets: [{
                data           : [{{ $passCount }}, {{ $failCount }}],
                backgroundColor: ['#198754', '#dc3545'],
                borderColor    : ['#fff', '#fff'],
                borderWidth    : 3,
                hoverOffset    : 6,
            }]
        },
        options: {
            cutout : '68%',
            plugins: {
                legend : { position: 'bottom', labels: { padding: 16, usePointStyle: true } },
                tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed}` } },
            }
        }
    });

    // ── 2. Monthly Pass / Fail Trend Line ──
    const monthly = @json($monthlyResults);
    new Chart(document.getElementById('monthlyTrendChart'), {
        type: 'line',
        data: {
            labels  : monthly.map(d => d.month),
            datasets: [
                {
                    label          : 'Pass',
                    data           : monthly.map(d => d.pass),
                    borderColor    : '#198754',
                    backgroundColor: 'rgba(25,135,84,0.08)',
                    borderWidth    : 2.5,
                    tension        : 0.4,
                    fill           : true,
                    pointRadius    : 4,
                    pointBackgroundColor: '#198754',
                },
                {
                    label          : 'Fail',
                    data           : monthly.map(d => d.fail),
                    borderColor    : '#dc3545',
                    backgroundColor: 'rgba(220,53,69,0.08)',
                    borderWidth    : 2.5,
                    tension        : 0.4,
                    fill           : true,
                    pointRadius    : 4,
                    pointBackgroundColor: '#dc3545',
                },
            ]
        },
        options: {
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } },
            },
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } },
            }
        }
    });

    // ── 3. Exams per Course Bar ──
    const epc = @json($examsPerCourse);
    new Chart(document.getElementById('examsPerCourseChart'), {
        type: 'bar',
        data: {
            labels  : epc.map(d => d.label),
            datasets: [{
                label          : 'Exams',
                data           : epc.map(d => d.count),
                backgroundColor: 'rgba(255,193,7,0.25)',
                borderColor    : '#ffc107',
                borderWidth    : 2,
                borderRadius   : 6,
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } },
            },
            plugins: { legend: { display: false } },
        }
    });

});
</script>
@endsection
