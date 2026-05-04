@extends('admin.layouts.app')
@section('title', 'Proctoring Monitor')

@section('content')

<div class="pagetitle mb-0">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-3">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="card-header-left">
                            <h1>
                                <i class="bi bi-camera-video"></i>
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

<section class="pagetitle mb-0">
    <div class="row">
        <div class="col-lg-12">

            <div class="accordion mb-3" id="accordionAcademicReviewAns">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingReviewAns">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReviewAns" aria-expanded="true" aria-controls="collapseReviewAns">
                            <h6 class="card-title p-0 m-0">
                                <i class="bi bi-table"></i>
                                @yield('title') List
                            </h6>
                        </button>
                    </h2>
                    <div id="collapseReviewAns" class="accordion-collapse collapse show" aria-labelledby="headingReviewAns" data-bs-parent="#accordionAcademicReviewAns">
                        <div class="accordion-body px-0">

                            <table class="table table-sm small" id="proctoringTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student</th>
                                        <th>Exam</th>
                                        <th>Tab Switches</th>
                                        <th>Clipboard</th>
                                        <th>Webcam Flags</th>
                                        <th>High Severity</th>
                                        <th>Risk</th>
                                        <th>Started At</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attempts as $index => $attempt)
                                    @php
                                    $tabCount = $attempt->tabSwitchLogs->count();
                                    $clipCount = $attempt->clipboardLogs->count();
                                    $webcamCount = $attempt->webcamLogs->where('ai_flag', '!=', 'clear')->count();
                                    $highCount = $attempt->proctoringEvents->where('severity', 'high')->count();

                                    $score = ($tabCount * 5) + ($clipCount * 10) + ($webcamCount * 20) + ($highCount * 15);
                                    $risk = $score >= 60 ? 'high' : ($score >= 30 ? 'medium' : 'low');

                                    $riskBadge = match($risk) {
                                    'high' => 'danger',
                                    'medium' => 'warning',
                                    default => 'success',
                                    };
                                    @endphp
                                    <tr>
                                        <th class="text-start">
                                            <a href="javascript:void(0)" class="toggle-icon me-1">
                                                <i class="bi bi-plus-square"></i>
                                            </a>
                                            {{ $serialNo++ }}
                                        </th>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $attempt->student->first_name.' '.$attempt->student->last_name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $attempt->student->email ?? '' }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $attempt->exam->exam_title ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $attempt->exam->exam_code ?? '' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2">
                                                <i class="bi bi-arrow-left-right me-1"></i>{{ $tabCount }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info px-2">
                                                <i class="bi bi-clipboard-x me-1"></i>{{ $clipCount }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-purple bg-opacity-10 text-secondary border border-secondary px-2">
                                                <i class="bi bi-camera-video-off me-1"></i>{{ $webcamCount }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2">
                                                <i class="bi bi-exclamation-triangle me-1"></i>{{ $highCount }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-{{ $riskBadge }} px-3">
                                                {{ ucfirst($risk) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $attempt->started_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.academic.proctoring.report', $attempt->id) }}" class="btn btn-sm btn-outline-primary" title="View Report">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5 text-muted">
                                            <i class="bi bi-shield-check fs-1 d-block mb-2 opacity-50"></i>
                                            No flagged attempts found.
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
@if ($attempts->count())
<script>
    const table = new DataTable('#proctoringTable', {
        paging: true
        , pageLength: 10
        , lengthMenu: [5, 10, 25, 50, 100]
        , lengthChange: true
        , scrollX: true
    , });

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

