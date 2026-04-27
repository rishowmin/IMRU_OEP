@extends('admin.layouts.app')
@section('title', 'Review Answers')

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
                            <i class="bi bi-question-square"></i>
                            <span class="ms-1">@yield('title')</span>
                        </h5>
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

    <div class="row">
        <div class="col-lg-12">

            {{-- Exams with pending subjective reviews --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h6 class="card-title fw-semibold mb-0 p-0">
                        <i class="bi bi-pencil-square me-1"></i>Exams Pending Review
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Course</th>
                                    <th>Exam</th>
                                    <th>Exam Date</th>
                                    <th>Submissions</th>
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
        <span class="badge bg-primary rounded-pill">{{ $exam->total_submissions }} Students</span>
        @if($exam->reviewed_count > 0)
        <span class="badge bg-success rounded-pill ms-1">{{ $exam->reviewed_count }} Reviewed</span>
        @endif
    </td>
    <td>
        {{-- {{ route('admin.academic.reviewAnswer.show', $exam->id) }} --}}
        <a href="#"
           class="btn btn-sm btn-outline-theme">
            <i class="bi bi-eye me-1"></i>Review
        </a>
    </td>
</tr>
@empty
<tr>
    <td colspan="6">
        <div class="alert alert-info d-flex align-items-center gap-2 mb-0">
            <i class="bi bi-info-circle-fill"></i>
            <span>No exams with pending subjective answers found.</span>
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

</section>

@endsection

@section('scripts')
@endsection
