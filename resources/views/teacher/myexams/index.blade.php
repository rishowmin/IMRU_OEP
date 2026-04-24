@extends('student.layouts.app')
@section('title', 'My Exams')

@section('content')

@if(session('success') || session('status') || session('error'))
@include('student.layouts.common.status')
@endif

<section class="section">

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-header-left">
                        <h5>
                            <i class="bi bi-person-badge"></i>
                            <span class="ms-1">@yield('title')</span>
                        </h5>
                        <nav style="--bs-breadcrumb-divider: '•';">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        {{-- <a href="{{ route('student.myExams.create') }}" class="btn btn-sm btn-outline-theme">
                            <i class="bi bi-plus-lg"></i>
                            <span class="ms-1">Add @yield('title')</span>
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <div class="row">
                @forelse ($myExamList as $exam)
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-0 pt-1 pb-1">{{ $exam->exam_title }} [{{ $exam->exam_code }}]</h5>
                            <h6 class="text-dark fw-bold mb-2">{{ $exam->exam_type ?? 'N/A' }}</h6>

                            <p class="card-text mb-0"><strong>Date:</strong> {{ $exam->exam_date->format('d-M-Y') }}</p>

                            <div class="d-flex align-items-center justify-content-between">
                                <p class="card-text mb-0"><strong>Time:</strong> {{ $exam->start_time?->format('h:i A') ?? 'N/A' }} - {{ $exam->end_time?->format('h:i A') ?? 'N/A' }}</p>
                                <p class="card-text mb-0">
                                    <span class="badge rounded-pill bg-dark">{{ $exam->exam_duration_min ?? '0' }} mins</span>
                                </p>
                            </div>

                            <p class="card-text mb-0"><strong>Marks:</strong> {{ intval($exam->total_marks) ?? 'N/A' }}</p>
                            <p class="card-text mb-0"><strong>No. Of Questions:</strong> {{ $exam->total_questions ?? 'N/A' }}</p>

                            {{-- <a href="{{ route('student.myExams.show', ['myExam' => $exam->id]) }}" class="btn btn-sm btn-outline-primary">View Details</a> --}}
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        No exams found.
                    </div>
                </div>
                @endforelse
            </div>

        </div>
    </div>

</section>

@endsection




@section('scripts')


@endsection
