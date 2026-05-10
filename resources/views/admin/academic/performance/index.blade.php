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
                        <div class="card-header-right">

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
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExamPerformance" aria-expanded="true" aria-controls="collapseExamPerformance">
                            <h6 class="card-title p-0 m-0">
                                <i class="bi bi-table"></i>
                                @yield('title') List
                            </h6>
                        </button>
                    </h2>
                    <div id="collapseExamPerformance" class="accordion-collapse collapse show" aria-labelledby="headingExamPerformance" data-bs-parent="#accordionExamPerformance">
                        <div class="accordion-body px-0">

                            <table class="table table-sm small" id="examPerformanceTable">
                                <thead>
                                    <tr>
                                        <th width="8%">#</th>
                                        <th width="15%">Exam</th>
                                        <th width="15%">Course</th>
                                        <th width="10%">Date</th>
                                        <th width="10%" class="text-center">Submissions</th>
                                        <th width="10%" class="text-center">Graded</th>
                                        <th width="10%" class="text-center">Avg Score</th>
                                        <th width="10%" class="text-center">Pass Rate</th>
                                        <th width="12%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @forelse($exams as $index => $exam)
                                    @php
                                    $summary = $exam->result_summary;
                                    $totalGraded = (int) ($summary->total_graded ?? 0);
                                    $avgPct = (float) ($summary->avg_percentage ?? 0);
                                    $totalSubs = (int) ($exam->total_submissions ?? 0);
                                    $passCount = $totalGraded > 0
                                    ? \App\Models\Academic\AcaExamResult::where('exam_id', $exam->id)
                                    ->where('is_pass', true)->count()
                                    : 0;
                                    $passRatePct = $totalGraded > 0
                                    ? round(($passCount / $totalGraded) * 100)
                                    : 0;
                                    @endphp
                                    <tr>
                                        <td class="text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="text-truncate fw-semibold">{{ $exam->exam_title }}</div>
                                            <small class="text-muted">
                                                {{ $exam->exam_code ?? '' }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="text-truncate">{{ $exam->course->course_title ?? '—' }}</div>
                                            <small class="text-muted">
                                                {{ $exam->course->course_code ?? '' }}
                                            </small>
                                        </td>
                                        <td>

                                            <div class="">{{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') ?? '_'}}</div>
                                            <small class="text-muted">
                                                {{ $exam->exam_duration_min ?? '-' }} min
                                            </small>

                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $totalSubs }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($totalGraded === 0)
                                            <span class="badge bg-warning text-dark">Not Graded</span>
                                            @elseif($totalGraded < $totalSubs) <span class="badge bg-info text-dark">{{ $totalGraded }}/{{ $totalSubs }}</span>
                                                @else
                                                <span class="badge bg-success">All Graded</span>
                                                @endif
                                        </td>
                                        <td class="text-center">
                                            @if($totalGraded > 0)
                                            <span class="fw-bold text-{{ $avgPct >= 50 ? 'success' : 'danger' }}">
                                                {{ number_format($avgPct, 1) }}%
                                            </span>
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($totalGraded > 0)
                                            <span class="badge bg-{{ $passRatePct >= 50 ? 'success' : 'danger' }}">
                                                {{ $passRatePct }}%
                                            </span>
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.academic.performance.examAnalytics', $exam->id) }}" class="btn btn-sm btn-outline-primary" title="Analytics">
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

{{-- DataTable Script --}}
@if ($exams->count())
<script>
    const table = new DataTable('#examPerformanceTable', {
        paging: true
        , pageLength: 10
        , lengthMenu: [5, 10, 25, 50, 100]
        , lengthChange: true
        , scrollX: true
    });
</script>
@endif

{{-- Toggle Child Row Script --}}
<script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.toggle-icon');
        if (!btn) return;

        const tr = btn.closest('tr');
        const row = table.row(tr);
        const icon = btn.querySelector('i');

        if (row.child.isShown()) {
            row.child.hide();
            icon.classList.replace('bi-dash-square', 'bi-plus-square');
        } else {
            const template = tr.querySelector('.child-template');
            row.child(template.innerHTML).show();
            icon.classList.replace('bi-plus-square', 'bi-dash-square');
        }
    });
</script>

@endsection
