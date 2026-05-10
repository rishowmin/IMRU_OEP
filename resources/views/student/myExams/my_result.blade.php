@extends('student.layouts.app')
@section('title', 'My Result')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('student.myExams') }}" class="text-muted text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>My Exams
            </a>
            <h4 class="fw-bold mb-0 mt-1">{{ $exam->exam_title }}</h4>
            <small class="text-muted">
                {{ $exam->course->course_name ?? '' }} &bull;
                {{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }}
            </small>
        </div>
    </div>

    {{-- ── RESULT CARD ──────────────────────────────────────────────── --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-body text-center py-5">
            <div class="mb-2">
                <span class="display-4 fw-bold text-{{ $result->percentage >= 50 ? 'success' : 'danger' }}">
                    {{ $result->percentage }}%
                </span>
            </div>
            <div class="mb-3">
                <span class="{{ $result->grade_badge_class }} fs-5 px-4 py-2">Grade: {{ $result->grade }}</span>
            </div>

            @if($result->is_pass)
                <div class="alert alert-success d-inline-block px-5">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <strong>Congratulations! You passed this exam.</strong>
                </div>
            @else
                <div class="alert alert-danger d-inline-block px-5">
                    <i class="bi bi-x-circle-fill me-2 fs-5"></i>
                    <strong>You did not pass this exam. Keep working hard!</strong>
                </div>
            @endif

            {{-- Stats row --}}
            <div class="row justify-content-center mt-4 g-3">
                <div class="col-6 col-md-2">
                    <div class="bg-light rounded p-3">
                        <div class="fw-bold fs-5">{{ $result->total_marks_obtained }}/{{ $result->total_marks }}</div>
                        <div class="text-muted small">Total Marks</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="bg-light rounded p-3">
                        <div class="fw-bold fs-5 text-primary">
                            {{ $result->mcq_marks_obtained }}/{{ $result->mcq_total_marks }}
                        </div>
                        <div class="text-muted small">MCQ Marks</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="bg-light rounded p-3">
                        <div class="fw-bold fs-5 text-secondary">
                            {{ $result->subjective_marks_obtained }}/{{ $result->subjective_total_marks }}
                        </div>
                        <div class="text-muted small">Subjective</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="bg-light rounded p-3">
                        <div class="fw-bold fs-5">
                            <i class="bi bi-trophy-fill text-warning"></i> #{{ $rank }}
                        </div>
                        <div class="text-muted small">of {{ $totalStudents }} students</div>
                    </div>
                </div>
            </div>

            {{-- MCQ quick summary --}}
            @if($result->mcq_total > 0)
            <div class="mt-4">
                <small class="text-muted">
                    MCQ: &nbsp;
                    <span class="text-success fw-bold">✓ {{ $result->mcq_correct }} correct</span>&nbsp;&nbsp;
                    <span class="text-danger fw-bold">✗ {{ $result->mcq_wrong }} wrong</span>
                    @if($result->mcq_unanswered > 0)
                        &nbsp;&nbsp;<span class="text-secondary">– {{ $result->mcq_unanswered }} skipped</span>
                    @endif
                </small>
            </div>
            @endif

            {{-- Grading pending notice --}}
            @if($result->grading_status !== 'complete')
            <div class="alert alert-info mt-4 mb-0 d-inline-block">
                <i class="bi bi-info-circle me-1"></i>
                Your subjective answers are still being reviewed. This result may update once fully graded.
            </div>
            @endif
        </div>
    </div>

    {{-- ── ANSWER BREAKDOWN ────────────────────────────────────────── --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold border-0">
            <i class="bi bi-list-check me-2"></i>Your Answer Breakdown
        </div>
        <div class="card-body p-0">
            @foreach($answers as $i => $answer)
            @php
                $question    = $answer->question;
                $review      = $answer->reviewAnswer;
                $isObjective = in_array($question?->question_type, ['mcq_4', 'mcq_2']) || $question?->evaluation_type === 'automatic';
                $isCorrect   = $review && $review->review == 1;
                $isWrong     = $review && $review->review == 0;
                $rowBg       = $isCorrect ? 'table-success' : ($isWrong ? 'table-danger' : '');
            @endphp
            <div class="border-bottom p-3 {{ $rowBg }}">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div class="flex-grow-1">
                        <div class="fw-semibold mb-1">
                            Q{{ $i + 1 }}. {{ $question?->question_text ?? 'Question not found' }}
                        </div>

                        {{-- MCQ options with highlight --}}
                        @if($isObjective && $question)
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            @foreach(['a', 'b', 'c', 'd'] as $opt)
                                @if($question->{'option_'.$opt})
                                @php
                                    $correctOpt  = strtolower(trim($question->correct_answer));
                                    $studentOpt  = strtolower(trim($answer->answer ?? ''));
                                    $isThisCorrect  = $correctOpt === $opt;
                                    $isThisSelected = $studentOpt === $opt;
                                    $optClass = $isThisCorrect
                                        ? 'bg-success text-white'
                                        : ($isThisSelected && !$isThisCorrect ? 'bg-danger text-white' : 'bg-light text-dark border');
                                @endphp
                                <span class="badge px-2 py-1 small {{ $optClass }}">
                                    {{ strtoupper($opt) }}. {{ $question->{'option_'.$opt} }}
                                </span>
                                @endif
                            @endforeach
                        </div>
                        @endif

                        {{-- Student answer (subjective) --}}
                        @if(!$isObjective)
                        <div class="mt-2 small">
                            <span class="text-muted">Your answer: </span>
                            @if($answer->answer)
                                <span class="fw-semibold">{{ $answer->answer }}</span>
                            @else
                                <em class="text-muted">Not answered</em>
                            @endif
                        </div>
                        @endif

                        {{-- Correct answer hint if wrong MCQ --}}
                        @if($isObjective && $isWrong)
                        <div class="mt-1 small text-success">
                            Correct answer: <strong>{{ strtoupper($question->correct_answer) }}</strong>
                        </div>
                        @endif
                    </div>

                    {{-- Marks badge --}}
                    <div class="text-end text-nowrap">
                        @if($review)
                            <span class="badge bg-{{ $isCorrect ? 'success' : 'danger' }} fs-6">
                                {{ $review->marks_awarded }}/{{ $question?->marks ?? 0 }}
                            </span>
                            <div class="small text-muted mt-1">{{ $isCorrect ? '✓ Correct' : '✗ Wrong' }}</div>
                        @elseif(!$answer->answer)
                            <span class="badge bg-secondary">Skipped</span>
                        @else
                            <span class="badge bg-light text-dark border">Pending Review</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
