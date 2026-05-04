@extends('admin.layouts.app')
@section('title', 'Exam Attempts')

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
                                <i class="bi bi-card-checklist"></i>
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

            <div class="accordion mb-3" id="accordionExamAttempts">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingExamAttempts">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExamAttempts" aria-expanded="true" aria-controls="collapseExamAttempts">
                            <h6 class="card-title p-0 m-0">
                                <i class="bi bi-table"></i>
                                @yield('title') List
                            </h6>
                        </button>
                    </h2>
                    <div id="collapseExamAttempts" class="accordion-collapse collapse show" aria-labelledby="headingExamAttempts" data-bs-parent="#accordionExamAttempts">
                        <div class="accordion-body px-0">

                            <table class="table table-sm small" id="examAttemptTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student</th>
                                        <th>Exam</th>
                                        <th>Started At</th>
                                        <th>Submitted At</th>
                                        <th>Status</th>
                                        <th>Active</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="attemptBody">
                                    @forelse($attemptList as $attempt)
                                    <tr>
                                        <th class="text-start">
                                            <a href="javascript:void(0)" class="toggle-icon me-1">
                                                <i class="bi bi-plus-square"></i>
                                            </a>
                                            {{ $serialNo++ }}
                                        </th>

                                        <td>
                                            <div class="student-cell">
                                                <div>
                                                    <div class="student-name">
                                                        {{ $attempt->student->first_name ?? '—' }} {{ $attempt->student->last_name ?? '—' }}
                                                    </div>
                                                    <small class="text-muted">ID#{{ $attempt->student->info->student_id_no ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div style="font-weight:500; color:#1e293b;">
                                                {{ $attempt->exam->exam_title ?? '—' }}
                                            </div>
                                            <small class="text-muted">{{ $attempt->exam->exam_code ?? '—' }}</small>
                                        </td>

                                        <td>
                                            @if($attempt->started_at)
                                            <div class="ts-main">{{ $attempt->started_at->format('d M Y') }}</div>
                                            <div class="ts-label">{{ $attempt->started_at->format('h:i A') }}</div>
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($attempt->submitted_at)
                                            <div class="ts-main">{{ $attempt->submitted_at->format('d M Y') }}</div>
                                            <div class="ts-label">{{ $attempt->submitted_at->format('h:i A') }}</div>
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($attempt->status === 'New')
                                            <span class="status-badge new">
                                                <span class="dot"></span> New
                                            </span>
                                            @else
                                            <span class="status-badge old">
                                                <span class="dot"></span> Old
                                            </span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($attempt->is_active)
                                            <span class="badge rounded-pill bg-success active p-2">
                                                <i class="bi bi-check-circle me-1"></i> Active
                                            </span>
                                            @else
                                            <span class="badge rounded-pill bg-danger inactive">
                                                <i class="bi bi-x-circle me-1"></i> Inactive
                                            </span>
                                            @endif
                                        </td>

                                        <td>
                                            {{-- Only show Reset button if the attempt has been submitted --}}
                                            @if($attempt->status === 'Old')
                                            <button type="button" class="btn btn-sm btn-warning mt-1 reset-attempt-btn" data-attempt-id="{{ $attempt->id }}" data-student="{{ trim(($attempt->student->first_name ?? '') . ' ' . ($attempt->student->last_name ?? '')) }}" data-exam="{{ $attempt->exam->exam_title ?? '' }}" title="Reset Attempt">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                            @else
                                            <span class="badge bg-secondary mt-1">In Progress</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <strong>
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                <span>No Exam Attempts Found</span>
                                                <i class="bi bi-exclamation-triangle ms-1"></i>
                                            </strong>
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


@include('admin.layouts.common.resetAttemptModal')

@endsection

@section('scripts')


{{-- DataTable Script --}}
@if ($attemptList->count())
<script>
    const table = new DataTable('#examAttemptTable', {
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

<script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.reset-attempt-btn');
        if (!btn) return;

        // document.getElementById('modalStudentName').textContent = btn.dataset.student;
        // document.getElementById('modalExamTitle').textContent   = btn.dataset.exam;

        document.getElementById('resetAttemptForm').action =
            '{{ url("admin/academic/exam-attempts/id=") }}' + btn.dataset.attemptId + '/reset';

        new bootstrap.Modal(document.getElementById('resetAttemptModal')).show();
    });

</script>

@endsection
