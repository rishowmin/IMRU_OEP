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

        {{-- Left: Questions & Answers --}}
        <div class="col-lg-8 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title fw-semibold mb-0 p-0">
                        {{ $exam->exam_title }}
                        <small class="text-muted">[{{ $exam->exam_code }}]</small>
                    </h5>
                </div>
                <div class="card-body">

                    @forelse($exam->questions as $question)
                    @php
                        $studentAnswer = $answers[$question->id]->answer ?? null;
                        $correctAnswer = $question->correct_answer ?? null;
                        $isCorrect = $studentAnswer && $correctAnswer && strtoupper($studentAnswer) === strtoupper($correctAnswer);
                        $isUnanswered = is_null($studentAnswer);
                    @endphp

                    <div class="question-item mb-4 p-3 border rounded
                        {{ $isUnanswered ? 'border-secondary' : ($isCorrect ? 'border-success' : 'border-danger') }}">

                        {{-- Question Header --}}
                        <div class="d-flex align-items-center gap-2 mb-2">
                            @if($question->difficulty_level == 'easy')
                            <span class="badge bg-light border border-success text-success">Easy</span>
                            @elseif($question->difficulty_level == 'medium')
                            <span class="badge bg-light border border-warning text-warning">Medium</span>
                            @else
                            <span class="badge bg-light border border-danger text-danger">Hard</span>
                            @endif

                            @if($question->question_type == 'mcq_2')
                            <span class="badge bg-dark">MCQ (2 options)</span>
                            @elseif($question->question_type == 'mcq_4')
                            <span class="badge bg-dark">MCQ (4 options)</span>
                            @elseif($question->question_type == 'short_question')
                            <span class="badge bg-dark">Short Question</span>
                            @else
                            <span class="badge bg-dark">Long Question</span>
                            @endif

                            {{-- Result badge --}}
                            @if($isUnanswered)
                            <span class="badge bg-secondary ms-auto">Not Answered</span>
                            @elseif(in_array($question->question_type, ['mcq_2', 'mcq_4']))
                            <span class="badge {{ $isCorrect ? 'bg-success' : 'bg-danger' }} ms-auto">
                                <i class="bi {{ $isCorrect ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                {{ $isCorrect ? 'Correct' : 'Wrong' }}
                            </span>
                            @else
                            <span class="badge bg-warning text-dark ms-auto"><i class="bi bi-pause-circle me-1"></i>Pending Review</span>
                            @endif
                        </div>

                        {{-- Question Text --}}
                        <div class="d-flex align-items-baseline justify-content-between mb-2">
                            <p class="question mb-0" style="width: 95%;">
                                <strong>Q{{ $question->question_order }}:</strong> {{ $question->question_text }}
                            </p>
                            <p class="fw-bold text-end mb-0" style="width: 5%;">
                                {{ intval($question->marks) }}
                            </p>
                        </div>

                        {{-- Question Figure --}}
                        @if($question->question_figure)
                        <div class="p-2 border rounded w-50 m-auto mb-2">
                            <img src="{{ asset('storage/question_figure/' . $question->question_figure) }}"
                                 alt="Question Figure" class="img-fluid"
                                 style="width:100%; max-height: 200px">
                        </div>
                        @endif

                        {{-- MCQ Options --}}
                        @if(in_array($question->question_type, ['mcq_2', 'mcq_4']))
                        <div class="options mb-2">

                                @php
                                $optionMap = [
                                    'A' => $question->option_a,
                                    'B' => $question->option_b,
                                    'C' => $question->option_c,
                                    'D' => $question->option_d,
                                ];

                                $studentAnswer = $answers[$question->id]->answer ?? null;
                                $correctAnswer = $question->correct_answer ?? null;

                                // Find correct key — handles both letter (B) and full text (Dhaka)
                                $correctKey = null;
                                if ($correctAnswer) {
                                    $foundKey = array_search($correctAnswer, $optionMap);
                                    $correctKey = $foundKey !== false ? strtoupper($foundKey) : strtoupper($correctAnswer);
                                }

                                // Find student key — handles both letter (A) and full text (Chattagram)
                                $studentKey = null;
                                if ($studentAnswer) {
                                    $foundKey = array_search($studentAnswer, $optionMap);
                                    $studentKey = $foundKey !== false ? strtoupper($foundKey) : strtoupper($studentAnswer);
                                }
                            @endphp

                            @foreach($optionMap as $key => $option)
                            @if($option)
                            @php
                                $isStudentChoice = !is_null($studentKey) && $studentKey === $key;
                                $isCorrectOption = !is_null($correctKey) && $correctKey === $key;
                            @endphp
                            <div class="mb-2 px-3 py-2 rounded d-flex align-items-center gap-2 option-item small {{ $isCorrectOption ? 'option-correct' : '' }} {{ $isStudentChoice && !$isCorrectOption ? 'option-wrong' : '' }}">

                                @if($isCorrectOption)
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                @elseif($isStudentChoice && !$isCorrectOption)
                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                @else
                                    <i class="bi bi-circle text-muted"></i>
                                @endif

                                <span class="{{ $isCorrectOption ? 'text-success fw-bold' : '' }} {{ $isStudentChoice && !$isCorrectOption ? 'text-danger fw-bold' : '' }}">
                                    <strong>{{ $key }}.</strong> {{ $option }}
                                </span>

                                @if($isCorrectOption && $isStudentChoice)
                                    <span class="badge bg-success ms-auto">Your Answer</span>
                                @elseif($isCorrectOption)
                                    <span class="badge bg-success ms-auto">Correct Answer</span>
                                @elseif($isStudentChoice)
                                    <span class="badge bg-danger ms-auto">Your Answer</span>
                                @endif

                            </div>
                            @endif
                            @endforeach
                        </div>

                        {{-- Short/Long Answer --}}
                        @else
                        <div class="mt-2">
                            <p class="small text-muted mb-1">Your Answer:</p>
                            <div class="p-2 bg-light rounded border">
                                {{ $studentAnswer ?? 'No answer provided.' }}
                            </div>
                        </div>
                        @endif

                    </div>
                    @empty
                    <p class="text-center text-muted">No questions found for this exam.</p>
                    @endforelse

                </div>
            </div>
        </div>

        {{-- Right: Result Summary --}}
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h6 class="card-title fw-semibold mb-0 p-0">Result Summary</h6>
                </div>
                <div class="card-body">

                    @php
                        $mcqQuestions = $exam->questions->filter(fn($q) => in_array($q->question_type, ['mcq_2', 'mcq_4']));
                        $subjectiveQuestions = $exam->questions->filter(fn($q) => in_array($q->question_type, ['short_question', 'long_question']));

                        $correctCount = 0;
                        $wrongCount = 0;
                        $unansweredCount = 0;
                        $obtainedMarks = 0;

                        foreach ($exam->questions as $question) {
                            $studentAnswer = $answers[$question->id]->answer ?? null;
                            $correctAnswer = $question->correct_answer ?? null;

                            if (is_null($studentAnswer)) {
                                $unansweredCount++;
                            } elseif (in_array($question->question_type, ['mcq_2', 'mcq_4'])) {
                                if ($correctAnswer && strtoupper($studentAnswer) === strtoupper($correctAnswer)) {
                                    $correctCount++;
                                    $obtainedMarks += $question->marks;
                                } else {
                                    $wrongCount++;
                                }
                            }
                        }

                        $totalMcq = $mcqQuestions->count();
                        $percentage = $exam->total_marks > 0 ? round(($obtainedMarks / $exam->total_marks) * 100, 1) : 0;
                        $isPassed = $obtainedMarks >= ($exam->passing_marks ?? 0);
                    @endphp

                    <ul class="list-group small mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Course</span>
                            <strong>{{ $exam->course->course_title }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Exam</span>
                            <strong>{{ $exam->exam_title }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Total Marks</span>
                            <strong>{{ intval($exam->total_marks) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Passing Marks</span>
                            <strong>{{ intval($exam->passing_marks) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">MCQ Correct</span>
                            <span class="badge bg-success rounded-pill">{{ $correctCount }} / {{ $totalMcq }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">MCQ Wrong</span>
                            <span class="badge bg-danger rounded-pill">{{ $wrongCount }} / {{ $totalMcq }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Not Answered</span>
                            <span class="badge bg-secondary rounded-pill">{{ $unansweredCount }} / {{ $totalMcq }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Subjective Questions</span>
                            <span class="badge bg-warning text-dark rounded-pill">{{ $subjectiveQuestions->count() }} Pending</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">MCQ Marks Obtained</span>
                            <strong>{{ $obtainedMarks }} / {{ intval($exam->total_marks) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Percentage</span>
                            <strong>{{ $percentage }}%</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Result</span>
                            @if($subjectiveQuestions->count() > 0)
                            <span class="badge bg-warning text-dark">Pending Review</span>
                            @elseif($isPassed)
                            <span class="badge bg-success">Passed</span>
                            @else
                            <span class="badge bg-danger">Failed</span>
                            @endif
                        </li>
                    </ul>

                    {{-- Progress Bar --}}
                    <div class="mb-2">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Score</span>
                            <span>{{ $percentage }}%</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar {{ $isPassed ? 'bg-success' : 'bg-danger' }}"
                                 role="progressbar"
                                 style="width: {{ $percentage }}%"
                                 aria-valuenow="{{ $percentage }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

</section>

@endsection

@section('scripts')
@endsection
