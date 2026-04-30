@extends('admin.layouts.app')
@section('title', 'Review Answers')

@section('content')

@if(session('success') || session('status') || session('error'))
@include('admin.layouts.common.status')
@endif

<div class="pagetitle mb-0">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-3">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="card-header-left">
                            <h1>
                                <i class="bi bi-chat-left-text"></i>
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
                        <div class="accordion-body">


                            <table class="table table-sm small" id="reviewAnsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Course</th>
                                        <th>Exam</th>
                                        <th>Date</th>
                                        <th>Submissions</th>
                                        <th>Review Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($exams as $index => $exam)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $exam->course->course_title }}</div>
                                            <small class="text-muted">{{ $exam->course->course_code }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $exam->exam_title }}</div>
                                            <small class="text-muted">{{ $exam->exam_code }}</small>
                                        </td>
                                        <td>{{ $exam->exam_date->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $exam->total_submissions }} Students
                                            </span>
                                        </td>
                                        <td>
                                            @if($exam->reviewed_count >= $exam->total_submissions && $exam->total_submissions > 0)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Fully Reviewed
                                            </span>
                                            @elseif($exam->reviewed_count > 0)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-hourglass-split me-1"></i>Partially Reviewed
                                            </span>
                                            @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-clock me-1"></i>Pending
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.academic.reviewAnswer.show', $exam->id) }}" class="btn btn-sm btn-outline-theme" title="View Students">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <strong>
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                <span>No @yield('title') Available</span>
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
@endsection

@section('scripts')


{{-- DataTable Script --}}
@if ($exams->count())
<script>
    const table = new DataTable('#reviewAnsTable', {
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

