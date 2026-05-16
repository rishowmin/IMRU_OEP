@extends('admin.layouts.app')
@section('title', 'Exam Analytics')

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
                                <i class="bi bi-graph-up"></i>
                                <span class="ms-1">@yield('title')</span>
                            </h1>
                            <nav style="--bs-breadcrumb-divider: '•';">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.academic.performance.index') }}">Performance</a></li>
                                    <li class="breadcrumb-item active">@yield('title')</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="card-header-right">
                            {{-- Re-trigger grading --}}
                            <form method="POST" action="{{ route('admin.academic.performance.retriggerGrading', $exam) }}" onsubmit="return confirm('Re-grade all students for this exam?')">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <span class="ms-1">Re-Grade All</span>
                                </button>

                                <a href="{{ route('admin.academic.performance.index') }}" class="btn btn-outline-theme btn-sm">
                                    <i class="bi bi-arrow-left-square"></i>
                                    <span class="ms-1">Back to List</span>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="section">

    {{-- STAT CARDS --}}
    <div class="row g-2 mb-3">
        <div class="col-12 col-md-4">
            <div class="card h-100 mb-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <span>
                            <div class="fs-3 fw-bold text-theme">{{ $exam->exam_title }}</div>
                            <small class="text-muted">{{ $exam->exam_code ?? 'N/A' }}</small>
                        </span>

                        {{-- Re-trigger grading --}}
                        <form method="POST" action="{{ route('admin.academic.performance.retriggerGrading', $exam) }}" onsubmit="return confirm('Re-grade all students for this exam?')" title="Re-Grade All">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                        </form>
                    </div>

                    <ul class="list-group list-group-flush small mt-2 mb-0">
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-1">
                            <span class="fw-semibold">Course</span>
                            <span>{{ $exam->course->course_title }} <small class="text-muted">[{{ $exam->course->course_code ?? 'N/A' }}]</small></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-1">
                            <span class="fw-semibold">Exam Date</span>
                            <span>{{ $exam->exam_date->format('d M Y') ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-1">
                            <span class="fw-semibold">Duration</span>
                            <span>{{ $exam->exam_duration_min ?? 'N/A' }} mins</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-1">
                            <span class="fw-semibold">Marks</span>
                            <span>{{ intval($exam->total_marks) ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-1">
                            <span class="fw-semibold">Total Questions</span>
                            <span>{{ $exam->total_questions ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="row g-2 mb-2">
                <div class="col-4 col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted small">Total Students</div>
                            <div class="fs-3 fw-bold text-primary">{{ $stats->total_students ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted small">Passed</div>
                            <div class="fs-3 fw-bold text-success">{{ $stats->passed ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted small">Failed</div>
                            <div class="fs-3 fw-bold text-danger">{{ $stats->failed ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-2">
                <div class="col-4 col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted small">Avg Score</div>
                            <div class="fs-3 fw-bold text-primary">{{ $stats->avg_percentage ?? 0 }}%</div>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted small">Highest</div>
                            <div class="fs-3 fw-bold text-success">{{ intval($stats->max_percentage) ?? 0 }}%</div>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-muted small">Lowest</div>
                            <div class="fs-3 fw-bold text-danger">{{ intval($stats->min_percentage) ?? 0 }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>

    {{-- CHARTS ROW --}}
    <div class="row g-2 mb-3">

        {{-- Grade Distribution --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold text-theme">
                        <i class="bi bi-pie-chart me-1"></i>
                        Grade Distribution
                    </h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="gradeChart" height="220"></canvas>
                </div>
            </div>
        </div>

        {{-- Pass / Fail --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold text-theme">
                        <i class="bi bi-check2-circle me-1"></i>
                        Pass / Fail Ratio
                    </h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="passFailChart" height="220"></canvas>
                </div>
            </div>
        </div>

        {{-- Score Distribution --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold text-theme">
                        <i class="bi bi-bar-chart me-1"></i>
                        Score Distribution
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="scoreDistChart" height="220"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- QUESTION DIFFICULTY TABLE --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card mb-0">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold text-theme">
                        <i class="bi bi-lightbulb me-1"></i>
                        Question-wise Difficulty Analysis
                    </h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0 small" id="qWiseDifficultTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Marks</th>
                                    <th class="text-center">Received</th>
                                    <th class="text-center">Attempted</th>
                                    <th class="text-center">Correct</th>
                                    <th class="text-center">Correct Rate</th>
                                    <th class="text-center">Difficulty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($questionDifficulty as $i => $q)
                                <tr>
                                    <td class="text-muted">{{ $i + 1 }}</td>
                                    <td>{{ $q['question_text'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border">{{ str_replace('_', ' ', $q['question_type']) }}</span>
                                    </td>
                                    <td class="text-center">{{ $q['marks'] }}</td>
                                    <td class="text-center text-muted">{{ $q['received'] }}</td>
                                    <td class="text-center">{{ $q['attempted'] }} ({{ $q['attempted_rate'] }}%)</td>
                                    <td class="text-center">{{ $q['correct'] }}</td>
                                    <td class="text-center">
                                        <div class="progress" style="height:8px;">
                                            <div class="progress-bar bg-{{ $q['correct_rate'] >= 50 ? 'success' : 'danger' }}"
                                                style="width: {{ $q['correct_rate'] }}%"></div>
                                        </div>
                                        <small>{{ $q['correct_rate'] }}%</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="difficulty-badge {{ str_replace(' ', '.', $q['difficulty']) }}">
                                            {{ $q['difficulty'] }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">No question data available.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STUDENT RESULTS TABLE --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-0">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold text-theme">
                        <i class="bi bi-trophy me-1"></i>
                        Students Leaderboard
                    </h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0 small" id="studentLeaderboardTable">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Student</th>
                                    <th class="text-center">MCQ Marks</th>
                                    <th class="text-center">Subj. Marks</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">%</th>
                                    <th class="text-center">Grade</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Grading</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $result)
                                @php
                                    $rowClass = match($result->rank) {
                                        1       => 'table-first', // gold
                                        2       => 'table-second', // silver
                                        3       => 'table-third', // bronze
                                        default => '',
                                    };
                                    $rankIcon = match($result->rank) {
                                        1       => '🥇',
                                        2       => '🥈',
                                        3       => '🥉',
                                        default => '',
                                    };
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td class="text-center">
                                        @if($result->rank <= 3)
                                            <span class="fw-bold">{{ $rankIcon }} #{{ $result->rank }}</span>
                                        @else
                                            <span class="text-muted">#{{ $result->rank }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $result->student->first_name ?? '' }} {{ $result->student->last_name ?? 'N/A' }}
                                        </div>
                                        <small class="text-muted">
                                            ID # {{ $result->student->info->student_id_no ?? $result->student->id }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        {{ $result->mcq_marks_obtained }}/{{ $result->mcq_total_marks }}
                                        <small class="d-block text-muted">
                                            <span class="text-success me-1">✓{{ $result->mcq_correct }}</span>
                                            <span class="text-danger me-1">✗{{ $result->mcq_wrong }}</span>
                                            @if($result->mcq_unanswered > 0)
                                            <span class="text-secondery">–{{ $result->mcq_unanswered }}</span>
                                            @else
                                            <span class="text-secondery">–0</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        {{ $result->subjective_marks_obtained }}/{{ $result->subjective_total_marks }}
                                        <small class="d-block text-muted">
                                            <span class="text-success me-1">✓{{ $result->subjective_reviewed }}</span>
                                            <span class="text-danger">✗{{ $result->subjective_total - $result->subjective_reviewed }}</span>
                                        </small>
                                    </td>
                                    <td class="text-center fw-bold">
                                        {{ $result->total_marks_obtained }}/{{ $result->total_marks }}
                                    </td>
                                    <td class="text-center fw-bold text-{{ $result->percentage >= 40 ? 'success' : 'danger' }}">
                                        {{ intval($result->percentage) }}%
                                    </td>
                                    <td class="text-center">
                                        <span class="{{ $result->grade_badge_class }}">{{ $result->grade }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($result->is_pass)
                                            <span class="badge bg-success">Pass</span>
                                        @else
                                            <span class="badge bg-danger">Fail</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($result->grading_status === 'complete')
                                            <span class="badge bg-success">Complete</span>
                                        @elseif($result->grading_status === 'partial')
                                            <span class="badge bg-secondary text-dark">Partial</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            @if ($result->grading_status === 'pending')
                                            <a href="{{ route('admin.academic.reviewAnswer.studentAnswers', [$exam->id, $result->student_id]) }}" class="btn btn-xs btn-outline-warning btn-sm" title="Review Answer">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endif
                                            <a href="{{ route('admin.academic.performance.studentReport', [$exam->id, $result->student_id]) }}"
                                            class="btn btn-xs btn-outline-primary btn-sm" title="View Result">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        No results yet. Click "Re-Grade All" to compute results.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection

@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── Grade Distribution (Doughnut) ───────────────────────────────────
        const gradeLabels = @json($gradeDistribution->keys());
        const gradeCounts = @json($gradeDistribution->values());
        const gradeColors = {
            'A+': '#198754', 'A': '#00BFFF', 'A-': '#9370DB',
            'B+': '#00BCD4', 'B': '#4169E1', 'B-': '#8E44AD',
            'C+': '#ffce3c', 'C': '#FF7F11', 'D': '#dc1489', 'F': '#dc3545'
        };

        new Chart(document.getElementById('gradeChart'), {
            type: 'doughnut',
            data: {
                labels: gradeLabels,
                datasets: [{
                    data: gradeCounts,
                    backgroundColor: gradeLabels.map(g => gradeColors[g] ?? '#adb5bd'),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 12 } } }
                }
            }
        });

        // ── Pass / Fail (Doughnut) ───────────────────────────────────────────
        const passFailData = @json($passFailData);
        const passed = passFailData[1] ?? 0;
        const failed  = passFailData[0] ?? 0;

        new Chart(document.getElementById('passFailChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pass', 'Fail'],
                datasets: [{
                    data: [passed, failed],
                    backgroundColor: ['#198754', '#dc3545'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // ── Score Distribution (Bar) ─────────────────────────────────────────
        const scoreLabels = @json($scoreDistribution->keys());
        const scoreCounts = @json($scoreDistribution->values());

        new Chart(document.getElementById('scoreDistChart'), {
            type: 'bar',
            data: {
                labels: scoreLabels,
                datasets: [{
                    label: 'Students',
                    data: scoreCounts,
                    backgroundColor: 'rgba(13, 110, 253, 0.6)',
                    borderColor: '#0d6efd',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { title: { display: true, text: 'Score Range (%)' } }
                },
                plugins: { legend: { display: false } }
            }
        });

    });
</script>

{{-- DataTable Script --}}
@if ($questionDifficulty->count())
<script>
    const qwdTable = new DataTable('#qWiseDifficultTable', {
        paging: true,
        pageLength: 10,
        lengthChange: false,
        searching: false,
        scrollX: false,
    });
</script>
@endif

@if ($results->count())
<script>
    const slTable = new DataTable('#studentLeaderboardTable', {
        paging: true,
        pageLength: 10,
        lengthChange: false,
        searching: false,
        scrollX: false,
    });
</script>
@endif

@endsection
