@extends('admin.layouts.app')
@section('title', 'Review Answers')
@section('title2', 'Students')

@section('content')

@if(session('success') || session('status') || session('error'))
@include('admin.layouts.common.status')
@endif

<section class="section">

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-header-left">
                        <h5>
                            <i class="bi bi-people"></i>
                            <span class="ms-1">{{ $exam->exam_title }} — Student Submissions</span>
                        </h5>
                        <nav style="--bs-breadcrumb-divider: '•';">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.academic.reviewAnswer.index') }}">@yield('title')</a></li>
                                <li class="breadcrumb-item active">@yield('title2')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        <a href="{{ route('admin.academic.reviewAnswer.index') }}" class="btn btn-outline-theme btn-sm">
                            <i class="bi bi-arrow-left-square"></i>
                            <span class="ms-1">Back</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

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



                            <table class="table table-sm small" id="reviewStudentTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student</th>
                                        <th>Total Answers</th>
                                        <th>Subjective</th>
                                        <th>Reviewed</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $index => $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $data['student']->name }}</div>
                                            <small class="text-muted">{{ $data['student']->email }}</small>
                                        </td>
                                        <td>{{ $data['total_answers'] }}</td>
                                        <td>{{ $data['total_subjective'] }}</td>
                                        <td>{{ $data['reviewed'] }}</td>
                                        <td>
                                            @if($data['is_fully_reviewed'])
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Reviewed
                                            </span>
                                            @elseif($data['reviewed'] > 0)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-hourglass-split me-1"></i>Partial
                                            </span>
                                            @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-clock me-1"></i>Pending
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.academic.reviewAnswer.studentAnswers', [$exam->id, $data['student']->id]) }}"
                                            class="btn btn-sm btn-outline-theme">
                                                <i class="bi bi-pencil-square me-1"></i>Review
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="alert alert-info d-flex align-items-center gap-2 mb-0">
                                                <i class="bi bi-info-circle-fill"></i>
                                                <span>No student submissions found.</span>
                                            </div>
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
@if ($students->count())
<script>
    const table = new DataTable('#reviewStudentTable', {
        paging: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        lengthChange: true,
        scrollX: true
    });
</script>
@endif

{{-- Toggle Child Row Script --}}
<script>
    document.addEventListener('click', function (e) {
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
