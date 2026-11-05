@extends('student.layouts.app')
@section('title', 'Enrolled Courses')

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
                                <i class="bi bi-journal-bookmark-fill"></i>
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

            <div class="card shadow-sm mb-0">
                <div class="card-body">

                    @if($enrolledCourses->count())
                    <table class="table table-sm table-bordered table-hover small mb-0" id="myCourseTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Course</th>
                                <th>Teacher</th>
                                <th>Credit</th>
                                <th>Total Exams</th>
                                <th>Ongoing</th>
                                <th>Upcoming</th>
                                <th>Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrolledCourses as $enrolled)
                            <tr>
                                <td class="text-muted">{{ $serialNo++ }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $enrolled->course_title }}</div>
                                    <small class="text-muted">{{ $enrolled->course_code ?: '—' }}</small>
                                </td>
                                <td>{{ $enrolled->teacher ? $enrolled->teacher->first_name . ' ' . $enrolled->teacher->last_name : '—' }}</td>
                                <td>{{ $enrolled->credits ?: '—' }}</td>
                                <td>
                                    <span class="badge bg-theme border border-theme">
                                        {{ $enrolled->exams_count ?: '0' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success-light border border-success text-dark">
                                        {{ $enrolled->ongoing_exams_count }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-warning-light border border-warning text-dark">
                                        {{ $enrolled->upcoming_exams_count }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-light border border-primary text-dark">
                                        {{ $enrolled->submitted_exams_count }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="text-center text-muted py-3">
                        <h1 class="mb-2"><i class="bi bi-journal-x"></i></h1>
                        <h6 class="mb-0">No courses enrolled!</h6>
                    </div>
                    @endif

                </div>
            </div>

        </div>

    </div>

</section>

@endsection



@section('scripts')

{{-- DataTable Script --}}
@if ($enrolledCourses->count())
<script>
    const table = new DataTable('#myCourseTable', {
        paging: true
        , pageLength: 10
        , lengthMenu: [5, 10, 25, 50, 100]
        , lengthChange: true
        , scrollX: true
    });

</script>
@endif

@endsection
