@extends('student.layouts.app')
@section('title', 'View Result')

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
                                <li class="breadcrumb-item"><a href="{{ route('student.myExams') }}">My Exams</a></li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        <a href="{{ route('student.myExams') }}" class="btn btn-outline-theme btn-sm">
                            <i class="bi bi-arrow-left-square"></i>
                            <span class="ms-1">Back to My Exams</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <h4>{{ $exam->title }}</h4>
                    <p>Total Questions: {{ $exam->questions->count() }}</p>

                    <hr>

                    <h5>Your Answers:</h5>

                    @foreach($answers as $answer)
                        <div class="mb-3">
                            <strong>Question:</strong> {{ $answer->question->question_text }} <br>
                            <strong>Your Answer:</strong> {{ $answer->answer }} <br>
                            <strong>Correct Answer:</strong> {{ $answer->question->correct_answer }}
                        </div>
                        <hr>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

</section>

@endsection

@section('scripts')
@endsection
