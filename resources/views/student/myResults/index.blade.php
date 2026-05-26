@extends('student.layouts.app')
@section('title', 'My Results')

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
                                <i class="bi bi-trophy"></i>
                                <span class="ms-1">@yield('title')</span>
                            </h1>
                            <nav style="--bs-breadcrumb-divider: '•';">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}"><i class="bi bi-house"></i></a></li>
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

            <div class="card shadow-sm">
                <div class="card-body p-0">

                    <table class="table table-sm small mb-0" id="myResultTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Exam</th>
                                <th>Course</th>
                                <th>Total Marks</th>
                                <th>Grade</th>
                                <th>Percentage</th>
                                <th>Rank</th>
                                <th>Status</th>
                                <th>Gradeing Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @forelse($myResults as $result)
                            <tr>
                                <td class="text-muted">{{ $serialNo++ }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $result->exam->exam_title }}</div>
                                    <small class="text-muted">{{ $result->exam->exam_code }}</small>
                                </td>
                                <td>
                                    <div>{{ $result->exam->course->course_title }}</div>
                                    <small class="text-muted">{{ $result->exam->course->course_code }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="fw-semibold">{{ intval($result->total_marks_obtained) }}</span>
                                    <small class="d-block text-muted">out of {{ intval($result->total_marks) }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="{{ $result->grade_badge_class }}">{{ $result->grade }}</span>
                                </td>
                                <td class="text-center fw-bold text-{{ $result->percentage >= 40 ? 'success' : 'danger' }}">
                                    {{ intval($result->percentage) }}%
                                </td>
                                <td class="text-center">
                                    <span class="fw-semibold"><i class="bi bi-hash"></i>{{ $result->rank }}</span>
                                    <small class="d-block text-muted">out of {{ $result->total_students }}</small>
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
                                        <span class="badge bg-warning text-dark">Partial</span>
                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('student.myExams.result', $result->exam_id) }}"
                                       class="btn btn-sm btn-outline-theme" title="View Result">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12">
                                    <div class="alert alert-info d-flex align-items-center gap-2 mb-0">
                                        <i class="bi bi-info-circle-fill"></i>
                                        <span>No results yet. Complete an exam to see your results here.</span>
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

</section>

@endsection



@section('scripts')

{{-- DataTable Script --}}
@if ($myResults->count())
<script>
    const table = new DataTable('#myResultTable', {
        paging: true
        , pageLength: 10
        , lengthMenu: [5, 10, 25, 50, 100]
        , lengthChange: true
        , scrollX: true
    });

</script>
@endif

@endsection
