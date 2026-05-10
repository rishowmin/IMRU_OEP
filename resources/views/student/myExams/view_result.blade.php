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
</section>

<section class="section">
    
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

                    @forelse($answers as $answer)
                    @php
                        $index         = $loop->index;
                        $question      = $answer->question;
                        $studentAnswer = $answer->answer ?? null;
                        $correctAnswer = $question->correct_answer ?? null;
                        $isObjective   = in_array($question->question_type, ['mcq_2', 'mcq_4']);
                        $isUnanswered  = is_null($studentAnswer) || trim($studentAnswer) === '';
                        $isCorrect     = $isObjective && !$isUnanswered && $correctAnswer
                                         && strtolower(trim($studentAnswer)) === strtolower(trim($correctAnswer));
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
                            @elseif($isObjective)
                                <span class="badge {{ $isCorrect ? 'bg-success' : 'bg-danger' }} ms-auto">
                                    <i class="bi {{ $isCorrect ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                                    {{ $isCorrect ? 'Correct' : 'Wrong' }}
                                </span>
                            @elseif(!empty($answer->reviewAnswer))
                                <span class="badge bg-success ms-auto">
                                    <i class="bi bi-check-circle me-1"></i>Reviewed
                                </span>
                            @else
                                <span class="badge bg-warning text-dark ms-auto">
                                    <i class="bi bi-pause-circle me-1"></i>Pending Review
                                </span>
                            @endif
                        </div>

                        {{-- Question Text --}}
                        <div class="d-flex align-items-baseline justify-content-between mb-2">
                            <p class="question mb-0" style="width: 95%;">
                                <strong>Q{{ $index + 1 }}:</strong> {{ $question->question_text }}
                            </p>
                            <p class="fw-bold text-end mb-0" style="width: 5%;">
                                {{ intval($question->marks) }}
                            </p>
                        </div>

                        {{-- Question Figure --}}
                        @if($question->question_figure)
                        <div class="p-2 border rounded w-50 m-auto mb-2">
                            <img src="{{ asset('storage/question_figure/' . $question->question_figure) }}"
                                 alt="Question Figure" class="img-fluid" style="width:100%; max-height: 200px">
                        </div>
                        @endif

                        {{-- MCQ Options --}}
                        @if($isObjective)
                        <div class="options mb-2">
                            @php
                                $optionMap = [
                                    'A' => $question->option_a,
                                    'B' => $question->option_b,
                                    'C' => $question->option_c,
                                    'D' => $question->option_d,
                                ];

                                // correct_answer and student answer both store full option TEXT
                                // Find the matching key (A/B/C/D) by comparing text values
                                $correctKey = null;
                                $studentKey = null;

                                foreach ($optionMap as $k => $v) {
                                    if ($v && $correctAnswer && strtolower(trim($v)) === strtolower(trim($correctAnswer))) {
                                        $correctKey = $k;
                                    }
                                    if ($v && $studentAnswer && strtolower(trim($v)) === strtolower(trim($studentAnswer))) {
                                        $studentKey = $k;
                                    }
                                }
                            @endphp

                            @foreach($optionMap as $key => $option)
                            @if($option)
                            @php
                                $isStudentChoice = $studentKey === $key;
                                $isCorrectOption = $correctKey === $key;
                            @endphp
                            <div class="mb-2 px-3 py-2 rounded d-flex align-items-center gap-2 option-item small
                                {{ $isCorrectOption ? 'option-correct' : '' }}
                                {{ $isStudentChoice && !$isCorrectOption ? 'option-wrong' : '' }}">

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
                                    <span class="badge bg-success ms-auto">Your Answer ✓</span>
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
                            <div class="p-2 bg-light rounded border mb-2">
                                {{ $studentAnswer ?? 'No answer provided.' }}
                            </div>

                            @if($answer->reviewAnswer)
                            @php
                                $ra        = $answer->reviewAnswer;
                                $qMarks    = intval($answer->question->marks);
                                $isPartial = !$ra->review && $ra->marks_awarded > 0 && $ra->marks_awarded < $qMarks;
                            @endphp
                            <div class="p-2 rounded border
                                {{ $ra->review ? 'border-success bg-success bg-opacity-10' : ($isPartial ? 'border-warning bg-warning bg-opacity-10' : 'border-danger bg-danger bg-opacity-10') }}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($ra->review)
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                            <span class="small fw-semibold text-success">Correct</span>
                                        @elseif($isPartial)
                                            <i class="bi bi-check-circle text-warning"></i>
                                            <span class="small fw-semibold text-warning">Partial Marks</span>
                                        @else
                                            <i class="bi bi-x-circle-fill text-danger"></i>
                                            <span class="small fw-semibold text-danger">Wrong</span>
                                        @endif
                                    </div>
                                    <span class="badge {{ $ra->review ? 'bg-success' : ($isPartial ? 'bg-warning' : 'bg-danger') }} ms-auto">
                                        {{ $ra->marks_awarded }} / {{ $qMarks }} marks
                                    </span>
                                </div>
                            </div>
                            @else
                            <div class="p-2 rounded border border-warning bg-warning bg-opacity-10">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-hourglass-split text-warning"></i>
                                    <span class="small fw-semibold text-warning">Pending Review by Teacher</span>
                                </div>
                            </div>
                            @endif
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
                        $allQuestions        = $answers->map(fn($a) => $a->question);
                        $mcqQuestions        = $allQuestions->filter(fn($q) => in_array($q->question_type, ['mcq_2', 'mcq_4']));
                        $subjectiveQuestions = $allQuestions->filter(fn($q) => in_array($q->question_type, ['short_question', 'long_question']));

                        if ($result) {
                            // ✅ Use pre-computed values from aca_exam_results.
                            // total_marks = sum of ASSIGNED questions only (e.g. 26), NOT $exam->total_marks (43).
                            $correctCount         = $result->mcq_correct;
                            $wrongCount           = $result->mcq_wrong;
                            $mcqMarks             = $result->mcq_marks_obtained;
                            $totalMcqMarks        = $result->mcq_total_marks;
                            $totalSubjectiveMarks = $result->subjective_total_marks;
                            $pendingReviewCount   = $result->subjective_total - $result->subjective_reviewed;
                            $totalObtained        = $result->total_marks_obtained;
                            $totalPossible        = $result->total_marks;   // ✅ correct denominator
                            $percentage           = $result->percentage;
                            $isPassed             = $result->is_pass;
                        } else {
                            // Fallback: compute on the fly if result row not yet created
                            $correctCount = $wrongCount = $mcqMarks = $totalMcqMarks = 0;

                            foreach ($answers as $answer) {
                                $q       = $answer->question;
                                $ans     = $answer->answer ?? null;
                                $correct = $q->correct_answer ?? null;
                                $isEmpty = is_null($ans) || trim($ans) === '';

                                if (in_array($q->question_type, ['mcq_2', 'mcq_4'])) {
                                    $totalMcqMarks += $q->marks;
                                    if (!$isEmpty && $correct && strtolower(trim($ans)) === strtolower(trim($correct))) {
                                        $correctCount++;
                                        $mcqMarks += $q->marks;
                                    } elseif (!$isEmpty) {
                                        $wrongCount++;
                                    }
                                }
                            }

                            $totalSubjectiveMarks = $subjectiveQuestions->sum('marks');
                            $pendingReviewCount   = $subjectiveAnswers->count() - $reviewedAnswers->count();
                            $totalObtained        = $mcqMarks + $subjectiveMarksObtained;
                            // ✅ Sum marks of ASSIGNED questions — never $exam->total_marks
                            $totalPossible        = $allQuestions->sum('marks');
                            $percentage           = $totalPossible > 0
                                ? round(($totalObtained / $totalPossible) * 100, 1) : 0;
                            $isPassed             = $totalObtained >= ($exam->passing_marks ?? 0);
                        }

                        // Unanswered count — count from actual answer rows (null or blank)
                        $unansweredCount = $answers->filter(
                            fn($a) => is_null($a->answer) || trim($a->answer) === ''
                        )->count();
                    @endphp

                    <ul class="list-group small mb-3">

                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Course</span>
                            <strong class="text-end">{{ $exam->course->course_title }}</strong>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Exam</span>
                            <strong class="text-end">{{ $exam->exam_title }}</strong>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Total Marks</span>
                            {{-- ✅ $totalPossible = assigned questions sum, e.g. 26 not 43 --}}
                            <strong>{{ intval($totalPossible) }}</strong>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Passing Marks</span>
                            <strong>{{ intval($exam->passing_marks) }}</strong>
                        </li>

                        <li class="list-group-item bg-light py-1">
                            <small class="fw-semibold text-muted">Questions</small>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Total Questions</span>
                            <span class="badge bg-dark rounded-pill">{{ $allQuestions->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Correct Answered</span>
                            <span class="badge bg-success rounded-pill">{{ $correctCount }}</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Wrong Answered</span>
                            <span class="badge bg-danger rounded-pill">{{ $wrongCount }}</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Not Answered</span>
                            <span class="badge bg-secondary rounded-pill">{{ $unansweredCount }}</span>
                        </li>

                        <li class="list-group-item bg-light py-1">
                            <small class="fw-semibold text-muted">Breakdown</small>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Total MCQ</span>
                            <span class="badge bg-dark rounded-pill">{{ $mcqQuestions->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Total Subjective</span>
                            <span class="badge bg-dark rounded-pill">{{ $subjectiveQuestions->count() }}</span>
                        </li>

                        <li class="list-group-item bg-light py-1">
                            <small class="fw-semibold text-muted">Marks</small>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">MCQ Marks</span>
                            <strong>{{ $mcqMarks }} / {{ $totalMcqMarks }}</strong>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Subjective Marks</span>
                            @if($subjectiveQuestions->count() === 0)
                                <strong>N/A</strong>
                            @elseif($allReviewed)
                                <strong>{{ $subjectiveMarksObtained }} / {{ $totalSubjectiveMarks }}</strong>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-hourglass-split me-1"></i>{{ $pendingReviewCount }} Pending
                                </span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Total Obtained</span>
                            {{-- ✅ $totalPossible = assigned questions marks sum --}}
                            <strong>{{ number_format($totalObtained, 2) }} / {{ number_format($totalPossible, 2) }}</strong>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Percentage</span>
                            <strong>{{ $percentage }}%</strong>
                        </li>

                        <li class="list-group-item bg-light py-1">
                            <small class="fw-semibold text-muted">Result</small>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Status</span>
                            @if(!$allReviewed && $subjectiveQuestions->count() > 0)
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-hourglass-split me-1"></i>Pending Review
                                </span>
                            @elseif($isPassed)
                                <span class="badge bg-success">
                                    <i class="bi bi-trophy me-1"></i>Passed
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i>Failed
                                </span>
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
                            <div class="progress-bar {{ $allReviewed ? ($isPassed ? 'bg-success' : 'bg-danger') : 'bg-warning' }}"
                                 role="progressbar" style="width: {{ $percentage }}%"
                                 aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        @if(!$allReviewed && $subjectiveQuestions->count() > 0)
                        <small class="text-muted mt-1 d-block">
                            <i class="bi bi-info-circle me-1"></i>
                            Final score may change after subjective review is completed.
                        </small>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>

</section>

@endsection

@section('scripts')
@endsection
