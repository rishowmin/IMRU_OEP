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

    @php
        // ── Question collections from the student's actual answer sheet ──────
        $allQuestions        = $answers->map(fn($a) => $a->question)->filter();
        $mcqQuestions        = $allQuestions->filter(fn($q) => in_array($q->question_type, ['mcq_2', 'mcq_4']));
        $subjectiveQuestions = $allQuestions->filter(fn($q) => in_array($q->question_type, ['short_question', 'long_question']));

        // ── Unanswered count from actual answer rows ──────────────────────────
        $unansweredCount = $answers->filter(
            fn($a) => is_null($a->answer) || trim($a->answer) === ''
        )->count();

        // ── Use aca_exam_results when available (correct denominator) ─────────
        if ($result) {
            $totalPossible        = $result->total_marks;
            $totalObtained        = $result->total_marks_obtained;
            $mcqMarks             = $result->mcq_marks_obtained;
            $totalMcqMarks        = $result->mcq_total_marks;
            $totalSubjectiveMarks = $result->subjective_total_marks;
            $correctCount         = $result->mcq_correct;
            $wrongCount           = $result->mcq_wrong;
            $pendingReviewCount   = $result->subjective_total - $result->subjective_reviewed;
            $percentage           = $result->percentage;
            $isPassed             = $result->is_pass;
        } else {
            // Fallback: compute on the fly (result row not yet created)
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
            $totalPossible        = $allQuestions->sum('marks'); // assigned questions only
            $percentage           = $totalPossible > 0 ? round(($totalObtained / $totalPossible) * 100, 1) : 0;
            $isPassed             = $totalObtained >= ($exam->passing_marks ?? 0);
        }
    @endphp

    <div class="row g-3">

        {{-- Left: Score Card --}}
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mt-4 mb-2">
                        <span class="display-4 fw-bold text-{{ $percentage >= 40 ? 'success' : 'danger' }}">
                            {{ intval($percentage) }}%
                        </span>
                    </div>

                    @if($result)
                    <div class="mb-3">
                        <span class="{{ $result->grade_badge_class }} fs-5 px-4 py-2">
                            Grade: {{ $result->grade }}
                        </span>
                    </div>
                    @endif

                    @if($isPassed)
                    <div class="alert alert-success d-inline-block px-5 mb-0">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <strong>Congratulations! You passed this exam.</strong>
                    </div>
                    @else
                    <div class="alert alert-danger d-inline-block px-5 mb-0">
                        <i class="bi bi-x-circle-fill me-2 fs-5"></i>
                        <strong>You did not pass this exam. Keep working hard!</strong>
                    </div>
                    @endif

                    {{-- Stats row --}}
                    <div class="row justify-content-center mt-4 g-2">
                        <div class="col-6 col-md-3">
                            <div class="bg-light rounded p-3">
                                <div class="fw-bold fs-5 text-theme">
                                    {{ intval($totalObtained) }}/{{ intval($totalPossible) }}
                                </div>
                                <div class="text-muted small">Total Marks</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="bg-light rounded p-3">
                                <div class="fw-bold fs-5 text-info">
                                    {{ intval($mcqMarks) }}/{{ intval($totalMcqMarks) }}
                                </div>
                                <div class="text-muted small">Objective Marks</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="bg-light rounded p-3">
                                <div class="fw-bold fs-5 text-primary">
                                    {{ intval($result->subjective_marks_obtained ?? $subjectiveMarksObtained) }}/{{ intval($totalSubjectiveMarks) }}
                                </div>
                                <div class="text-muted small">Subjective Marks</div>
                            </div>
                        </div>
                        @if($rank)
                        <div class="col-6 col-md-3">
                            <div class="bg-light rounded p-3">
                                <div class="fw-bold fs-5">
                                    <i class="bi bi-trophy text-warning"></i> #{{ $rank }}
                                </div>
                                <div class="text-muted small">of {{ $totalStudents }} students</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Grading pending notice --}}
                    @if(!$allReviewed && $subjectiveQuestions->count() > 0)
                    <div class="alert alert-info mt-4 mb-0 d-inline-block small">
                        <i class="bi bi-info-circle me-1"></i>
                        Your subjective answers are still being reviewed. This result may update once fully graded.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Result Summary --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title fw-semibold mb-0 p-0">Result Summary</h6>
                </div>

                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small mb-0">
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
                            <strong>{{ intval($totalPossible) }}</strong>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Passing Marks</span>
                            <strong>{{ intval($exam->passing_marks) }}</strong>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Questions</span>
                            <span class="badge bg-dark rounded-pill">{{ $allQuestions->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Objective</span>
                            <span class="badge bg-dark rounded-pill">{{ $mcqQuestions->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span class="text-muted">Subjective</span>
                            <span class="badge bg-dark rounded-pill">{{ $subjectiveQuestions->count() }}</span>
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
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted fw-bold">Score</span>
                                <span class="text-muted fw-bold">{{ intval($percentage) }}%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar {{ $allReviewed ? ($isPassed ? 'bg-success' : 'bg-danger') : 'bg-warning' }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            @if(!$allReviewed && $subjectiveQuestions->count() > 0)
                            <small class="text-muted mt-1 d-block">
                                <i class="bi bi-info-circle me-1"></i>
                                Final score may change after subjective review is completed.
                            </small>
                            @endif
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        {{-- Answers Breakdown --}}
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title fw-semibold text-theme mb-0 p-0">
                        <i class="bi bi-list-check me-1"></i>
                        Your Answer Breakdown
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
                        $isSubjectivePending = !$isObjective && empty($answer->reviewAnswer);

                        $borderClass = $isUnanswered
                            ? 'border-secondary'
                            : ($isSubjectivePending
                                ? 'border-warning'
                                : ($isCorrect ? 'border-success' : 'border-danger'));
                    @endphp

                    <div class="question-item mb-4 p-3 border rounded {{ $borderClass }}">

                        {{-- Question Header --}}
                        <div class="d-flex align-items-center gap-2 mb-2">
                            @if($question->difficulty_level == 'easy')
                                <span class="badge bg-success">Easy</span>
                            @elseif($question->difficulty_level == 'medium')
                                <span class="badge bg-warning text-dark">Medium</span>
                            @else
                                <span class="badge bg-danger">Hard</span>
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
                            @elseif($answer->reviewAnswer)
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
                                 alt="Question Figure" class="img-fluid"
                                 style="width:100%; max-height: 200px">
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

                                // Match by full option text (both correct_answer and student answer store text)
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
                                    <span class="badge bg-success ms-auto">Correct Answer ✓</span>
                                @elseif($isStudentChoice)
                                    <span class="badge bg-danger ms-auto">Your Answer ✗</span>
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
                            <div class="p-2 rounded border {{ $ra->review ? 'border-success bg-success bg-opacity-10' : ($isPartial ? 'border-warning bg-warning bg-opacity-10' : 'border-danger bg-danger bg-opacity-10') }}">
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

    </div>

</section>

@endsection

@section('scripts')

@endsection
