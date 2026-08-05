@extends('admin.layouts.app')
@section('title', 'Performance')

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
                                    <li class="breadcrumb-item active">@yield('title')</li>
                                </ol>
                            </nav>
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

            <div class="accordion mb-3" id="accordionExamPerformance">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingExamPerformance">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseExamPerformance" aria-expanded="true"
                            aria-controls="collapseExamPerformance">
                            <h6 class="card-title p-0 m-0">
                                <i class="bi bi-table"></i>
                                @yield('title') List
                            </h6>
                        </button>
                    </h2>
                    <div id="collapseExamPerformance" class="accordion-collapse collapse show"
                        aria-labelledby="headingExamPerformance"
                        data-bs-parent="#accordionExamPerformance">
                        <div class="accordion-body px-0">

                            <table class="table table-sm small" id="examPerformanceTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Exam</th>
                                        <th>Course</th>
                                        <th>Date</th>
                                        <th class="text-center">Submissions</th>
                                        <th class="text-center">Graded</th>
                                        <th class="text-center">Avg Score</th>
                                        <th class="text-center">Pass Rate</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @forelse($exams as $index => $exam)
                                    @php
                                        $summary = $exam->result_summary;

                                        // ── Submissions ────────────────────────────────────────
                                        // Total = all enrolled students (submitted + not submitted)
                                        $totalEnrolled  = (int) ($exam->total_enrolled ?? 0);
                                        $totalSubmitted = (int) ($exam->total_submissions ?? 0);
                                        $notSubmitted   = max(0, $totalEnrolled - $totalSubmitted);

                                        // ── Graded breakdown ───────────────────────────────────
                                        // total_result_rows = all students who have ANY result row
                                        // (complete + partial + pending)
                                        $totalResultRows = (int) ($summary->total_result_rows ?? 0);
                                        $totalComplete   = (int) ($summary->total_graded ?? 0);   // complete only
                                        $totalPartial    = (int) ($summary->partial ?? 0);
                                        $totalPending    = (int) ($summary->pending ?? 0);

                                        // ── Avg Score & Pass Rate (complete students only) ─────
                                        $avgPct = (float) ($summary->avg_percentage ?? 0);

                                        $passCount = $totalComplete > 0
                                            ? \App\Models\Academic\AcaExamResult::where('exam_id', $exam->id)
                                                ->where('grading_status', 'complete')
                                                ->where('is_pass', true)
                                                ->count()
                                            : 0;
                                        $passRatePct = $totalComplete > 0
                                            ? round(($passCount / $totalComplete) * 100)
                                            : 0;
                                    @endphp
                                    <tr>
                                        <td class="text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="text-truncate fw-semibold">{{ $exam->exam_title }}</div>
                                            <small class="text-muted">{{ $exam->exam_code ?? '' }}</small>
                                        </td>
                                        <td>
                                            <div class="text-truncate">{{ $exam->course->course_title ?? '—' }}</div>
                                            <small class="text-muted">{{ $exam->course->course_code ?? '' }}</small>
                                        </td>
                                        <td>
                                            <div>{{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }}</div>
                                            <small class="text-muted">{{ $exam->exam_duration_min ?? '-' }} min</small>
                                        </td>

                                        {{-- ── Submissions ──────────────────────────────────────
                                             Shows submitted/total_enrolled.
                                             "Total" = ALL enrolled students, not just submitted. --}}
                                        <td class="text-center">
                                            <span class="badge bg-{{ $totalSubmitted === $totalEnrolled && $totalEnrolled > 0 ? 'success' : 'secondary' }}">
                                                {{ $totalSubmitted }}/{{ $totalEnrolled }}
                                            </span>
                                            <small class="d-block text-muted mt-1">
                                                @if($totalEnrolled === 0)
                                                    No enrolled students
                                                @elseif($notSubmitted === 0)
                                                    All submitted
                                                @else
                                                    {{ $notSubmitted }} not submitted
                                                @endif
                                            </small>
                                        </td>

                                        {{-- ── Graded ───────────────────────────────────────────
                                             Shows complete/total_enrolled.
                                             Includes pending/partial in the "total" count so
                                             admin knows the full picture. --}}
                                        <td class="text-center">
                                            @if($totalSubmitted === 0)
                                                {{-- Nobody submitted yet --}}
                                                <span class="badge bg-light text-dark border">
                                                    No Submissions
                                                </span>

                                            @elseif($totalComplete === $totalEnrolled && $totalEnrolled > 0)
                                                {{-- Every enrolled student is fully graded --}}
                                                <span class="badge bg-success">
                                                    All Graded ({{ $totalComplete }})
                                                </span>

                                            @else
                                                {{-- Show complete/enrolled with pending breakdown --}}
                                                <span class="badge bg-{{ $totalComplete > 0 ? 'info' : 'warning' }} text-dark">
                                                    {{ $totalComplete }}/{{ $totalEnrolled }}
                                                </span>
                                                <small class="d-block text-muted mt-1">
                                                    @if($totalPending > 0 && $totalPartial > 0)
                                                        {{ $totalPending }} pending · {{ $totalPartial }} partial
                                                    @elseif($totalPending > 0)
                                                        {{ $totalPending }} pending
                                                    @elseif($totalPartial > 0)
                                                        {{ $totalPartial }} partial
                                                    @elseif($notSubmitted > 0)
                                                        {{ $notSubmitted }} not submitted
                                                    @endif
                                                </small>
                                            @endif
                                        </td>

                                        {{-- Avg Score — only from complete-graded students --}}
                                        <td class="text-center">
                                            @if($totalComplete > 0)
                                                <span class="fw-bold text-{{ $avgPct >= 40 ? 'success' : 'danger' }}">
                                                    {{ number_format($avgPct, 1) }}%
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- Pass Rate — only from complete-graded students --}}
                                        <td class="text-center">
                                            @if($totalComplete > 0)
                                                <span class="badge bg-{{ $passRatePct >= 50 ? 'success' : 'danger' }}">
                                                    {{ $passRatePct }}%
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <a href="{{ route('admin.academic.performance.examAnalytics', $exam->id) }}"
                                               class="btn btn-sm btn-outline-primary" title="Analytics">
                                                <i class="bi bi-bar-chart-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-5">
                                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                            No exams found.
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
    </div>
</section>

@endsection

@section('scripts')
@if($exams->count())
<script>
    const table = new DataTable('#examPerformanceTable', {
        paging: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        lengthChange: true,
        scrollX: true
    });
</script>
@endif
@endsection
