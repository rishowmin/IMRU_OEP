@extends('teacher.layouts.app')
@section('title', 'View AI Exam Set')


@section('content')

@if(session('success') || session('status') || session('error'))
@include('teacher.layouts.common.status')
@endif

<div class="pagetitle mb-0">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-header-left">
                            <h1>
                                <i class="bi bi-stars"></i>
                                <span class="ms-1">@yield('title')</span>
                            </h1>
                            <nav style="--bs-breadcrumb-divider: '•';">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('teacher.aiExamSets.index') }}">Exam Sets by AI</a></li>
                                    <li class="breadcrumb-item active">
                                        @yield('title')
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="card-header-right d-flex align-items-center gap-2">
                            @if(!$examSet->published_exam_id)
                            <form action="{{ route('teacher.aiExamSets.destroy', $examSet) }}" method="POST" onsubmit="return confirm('Delete this exam set?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Delete</button>
                            </form>
                            @endif

                            <a href="{{ route('teacher.aiExamSets.index') }}" class="btn btn-outline-theme btn-sm">
                                <i class="bi bi-arrow-left-square"></i>
                                <span class="ms-1">Back to List</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<section class="section">

    <div class="row g-2 mb-3">
        {{-- Exam Title & Details --}}
        <div class="col-md-4">
            <div class="card mb-0 h-100">
                <div class="card-body">
                    <h4 class="text-theme fw-bold mb-0">{{ $examSet->topic }}</h4>
                    <h6 class="text-theme fw-bold mb-0">{{ $examSet->title }}</h6>
                    <span class="text-muted small"><small>Created {{ $examSet->created_at->diffForHumans() }}</small></span>

                    <ul class="list-unstyled mb-0 small text-muted mt-4">
                        <li class="my-2 d-flex align-items-center justify-content-between gap-1">
                            <strong><i class="bi bi-stopwatch me-1"></i>Duration</strong>
                            <span>{{ $examSet->duration_minutes }} minutes</span>
                        </li>
                        <li class="my-2 d-flex align-items-center justify-content-between gap-1">
                            <strong><i class="bi bi-trophy me-1"></i>Total Marks</strong>
                            <span>{{ $examSet->total_marks }}</span>
                        </li>
                        <li class="my-2 d-flex align-items-center justify-content-between gap-1">
                            <strong><i class="bi bi-check-circle me-1"></i>Status</strong>
                            @if ($examSet->status === 'draft')
                            <span class="badge bg-warning text-dark">Draft</span>
                            @elseif ($examSet->status === 'active')
                            <span class="badge bg-success">Active</span>
                            @elseif ($examSet->status === 'archived')
                            <span class="badge bg-secondary">Archived</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="col-md-8">
            <div class="row g-2">
                <div class="col-6 col-md-6">
                    <div class="card text-center mb-0 h-100">
                        <div class="card-body">
                            <div class="fs-2 fw-bold text-theme">{{ $examSet->total_questions }}</div>
                            <div class="text-muted small fw-semibold">Questions</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-6">
                    <div class="card text-center mb-0 h-100">
                        <div class="card-body">
                            @if ($examSet->question_type === 'objective')
                            <span class="fs-2 fw-bold text-info">{{ $examSet->qt1_count }} </span>
                            <span class="fs-2 fw-bold text-muted">& </span>
                            <span class="fs-2 fw-bold text-primary">{{ $examSet->qt2_count }}</span>
                            <div class="text-muted small fw-semibold">MCQ (4 & 2) Questions</div>
                            @elseif ($examSet->question_type === 'subjective')
                            <span class="fs-2 fw-bold text-info">{{ $examSet->qt1_count }} </span>
                            <span class="fs-2 fw-bold text-muted">& </span>
                            <span class="fs-2 fw-bold text-primary">{{ $examSet->qt2_count }}</span>
                            <div class="text-muted small fw-semibold">Short & Long Questions</div>
                            @elseif ($examSet->question_type === 'mcq_4')
                            <span class="fs-2 fw-bold text-info">{{ $examSet->qt1_count }}</span>
                            <div class="text-muted small fw-semibold">MCQ (4) Questions</div>
                            @elseif ($examSet->question_type === 'mcq_2')
                            <span class="fs-2 fw-bold text-info">{{ $examSet->qt1_count }}</span>
                            <div class="text-muted small fw-semibold">MCQ (2) Questions</div>
                            @elseif ($examSet->question_type === 'short_question')
                            <span class="fs-2 fw-bold text-info">{{ $examSet->qt1_count }}</span>
                            <div class="text-muted small fw-semibold">Short Questions</div>
                            @elseif ($examSet->question_type === 'long_question')
                            <span class="fs-2 fw-bold text-info">{{ $examSet->qt1_count }}</span>
                            <div class="text-muted small fw-semibold">Long Questions</div>
                            @else
                            <span class="fs-2 fw-bold text-info">{{ $examSet->qt1_count }} </span>
                            <span class="fs-2 fw-bold text-muted">& </span>
                            <span class="fs-2 fw-bold text-primary">{{ $examSet->qt2_count }}</span>
                            <div class="text-muted small fw-semibold">Obj. & Sub. Questions</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-4 col-md-4">
                    <div class="card text-center py-1 mb-0 h-100">
                        <div class="card-body">
                            <div class="fs-2 fw-bold text-success">{{ $examSet->easy_count }}</div>
                            <div class="text-muted small fw-semibold">Easy</div>
                        </div>
                    </div>
                </div>

                <div class="col-4 col-md-4">
                    <div class="card text-center py-1 mb-0 h-100">
                        <div class="card-body">
                            <div class="fs-2 fw-bold text-warning">{{ $examSet->medium_count }}</div>
                            <div class="text-muted small fw-semibold">Medium</div>
                        </div>
                    </div>
                </div>

                <div class="col-4 col-md-4">
                    <div class="card text-center py-1 mb-0 h-100">
                        <div class="card-body">
                            <div class="fs-2 fw-bold text-danger">{{ $examSet->hard_count }}</div>
                            <div class="text-muted small fw-semibold">Hard</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-lg-12">
            @if($examSet->ai_reasoning)
            <div class="ai-reasoning-box">
                <div class="d-flex align-items-center text-primary gap-2 mb-2">
                    <i class="bi bi-stack"></i>
                    <strong>AI Selection Reasoning</strong>
                </div>
                <p class="mb-0 text-muted" style="font-size:.9rem">{{ $examSet->ai_reasoning }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="row g-3">

        <div class="col-lg-8">
            <div class="card mb-0">

                <div class="card-header border-bottom py-3">
                    <div class="d-flex align-item-center justify-content-between">
                        <h6 class="card-title p-0 m-0">
                            <i class="bi bi-list-check text-theme me-1"></i>
                            Selected Questions
                        </h6>
                    </div>
                </div>

                {{-- Marks edit form (only when active + not published) --}}
                @if($examSet->status === 'active' && !$examSet->published_exam_id)
                <form id="marksForm" action="{{ route('teacher.aiExamSets.updateMarks', $examSet) }}" method="POST">
                    @csrf
                    @endif

                    <div class="card-body p-3">

                        <div class="d-flex align-items-center justify-content-between">
                            <div class="btn-group w-100 mb-3" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary w-25 active" data-group="all">All</button>

                                {{-- Question type --}}
                                @if ($examSet->question_type === 'objective')
                                <button type="button" class="btn btn-sm btn-outline-info w-25" data-group="type" data-value="mcq_4">MCQ 4</button>
                                <button type="button" class="btn btn-sm btn-outline-primary w-25" data-group="type" data-value="mcq_2">MCQ 2</button>
                                @elseif ($examSet->question_type === 'subjective')
                                <button type="button" class="btn btn-sm btn-outline-info w-25" data-group="type" data-value="short_question">Short Q.</button>
                                <button type="button" class="btn btn-sm btn-outline-primary w-25" data-group="type" data-value="long_question">Long Q.</button>
                                @elseif ($examSet->question_type === 'mcq_4')
                                <button type="button" class="btn btn-sm btn-outline-info w-25" data-group="type" data-value="mcq_4">MCQ 4</button>
                                @elseif ($examSet->question_type === 'mcq_2')
                                <button type="button" class="btn btn-sm btn-outline-info w-25" data-group="type" data-value="mcq_2">MCQ 2</button>
                                @elseif ($examSet->question_type === 'short_question')
                                <button type="button" class="btn btn-sm btn-outline-info w-25" data-group="type" data-value="short_question">Short Q.</button>
                                @elseif ($examSet->question_type === 'long_question')
                                <button type="button" class="btn btn-sm btn-outline-info w-25" data-group="type" data-value="long_question">Long Q.</button>
                                @else
                                <button type="button" class="btn btn-sm btn-outline-info w-25" data-group="type" data-value="objective">Objective</button>
                                <button type="button" class="btn btn-sm btn-outline-primary w-25" data-group="type" data-value="subjective">Subjective</button>
                                @endif

                                {{-- Difficulty --}}
                                <button type="button" class="btn btn-sm btn-outline-success w-25" data-group="difficulty" data-value="easy">Easy</button>
                                <button type="button" class="btn btn-sm btn-outline-warning w-25" data-group="difficulty" data-value="medium">Medium</button>
                                <button type="button" class="btn btn-sm btn-outline-danger w-25" data-group="difficulty" data-value="hard">Hard</button>
                            </div>
                        </div>

                        <div id="question-list" class="d-flex flex-column gap-2">
                            @forelse($questions as $index => $question)
                            <div class="question-card border mb-2 overflow-hidden {{ $question->difficulty_level }}" data-difficulty="{{ $question->difficulty_level }}" data-type="{{ $question->question_type }}">

                                {{-- Question header strip --}}
                                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom bg-light">

                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-semibold text-dark small">Q{{ $index + 1 }}.</span>
                                        <span class="badge rounded-pill
                                            @if($question->difficulty_level === 'easy') bg-success
                                            @elseif($question->difficulty_level === 'medium') bg-warning text-dark
                                            @else bg-danger @endif">
                                            {{ ucfirst($question->difficulty_level) }}
                                        </span>
                                        <span class="badge bg-info text-dark rounded-pill small">
                                            @if($question->question_type == 'mcq_2') MCQ (2 options)
                                            @elseif($question->question_type == 'mcq_4') MCQ (4 options)
                                            @elseif($question->question_type == 'short_question') Short Question
                                            @elseif($question->question_type == 'long_question') Long Question
                                            @endif
                                        </span>
                                    </div>

                                    {{-- ✅ MARKS: editable if active, readonly if not --}}
                                    <div class="d-flex align-items-center gap-1">
                                        @if($examSet->status === 'active' && !$examSet->published_exam_id)
                                        {{-- Editable input --}}
                                        <input type="number" name="marks[{{ $question->id }}]" class="form-control form-control-sm marks-input text-end" style="width: 70px;" value="{{ $question->marks ?? 1 }}" min="0" max="100" step="0.5" data-original="{{ $question->marks ?? 1 }}" oninput="recalcTotal()">
                                        @else
                                        {{-- Read-only display --}}
                                        <span class="fw-semibold text-muted small">{{ $question->marks ?? 1 }}</span>
                                        @endif
                                        <span class="text-muted small">mark{{ ($question->marks ?? 1) != 1 ? 's' : '' }}</span>
                                    </div>

                                </div>

                                {{-- Question body --}}
                                <div class="px-3 py-3 bg-body">
                                    <p class="mb-0 fw-semibold lh-base">{!! $question->question_text !!}</p>

                                    @if($question->option_a)
                                    <div class="row g-2 mt-2">
                                        @foreach(['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d] as $key => $opt)
                                        @if($opt)
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start gap-2 bg-body-secondary rounded-2 px-2 py-1 h-100">
                                                <span class="badge bg-secondary-subtle text-secondary fw-bold">{{ strtoupper($key) }}</span>
                                                <span class="small text-muted">{{ $opt }}</span>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    @endif
                                </div>

                                {{-- Question footer (correct answer) --}}
                                @if($question->correct_answer)
                                <div class="px-3 py-2 bg-light border-top small">
                                    <p class="mb-0 small text-muted">
                                        <i class="bi bi-check2-all text-success me-1"></i>
                                        <strong class="text-success">Correct Answer:</strong> <span>{{ $question->correct_answer }}</span>
                                    </p>
                                </div>
                                @endif

                            </div>
                            @empty
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                No questions found in this exam set.
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Save marks button (only when active) --}}
                    @if($examSet->status === 'active' && !$examSet->published_exam_id)
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Adjust marks per question then save before publishing.
                        </small>
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-floppy me-1"></i> Save Marks
                        </button>
                    </div>
                    @endif

                    {{-- Close form if active --}}
                    @if($examSet->status === 'active' && !$examSet->published_exam_id)
                </form>
                @endif

            </div>
        </div>

        <div class="col-lg-4">

            <div class="card mb-3">

                <div class="card-header">
                    <div class="d-flex align-item-center justify-content-between">
                        <h6 class="card-title p-0 m-0">
                            <i class="bi bi-percent text-theme me-1"></i>
                            Question Distribution
                        </h6>
                    </div>
                </div>

                <div class="card-body">

                    {{-- Question type --}}
                    @php
                    $typeCounts = $questions->groupBy('question_type')->map->count();
                    $typeTotal = max($questions->count(), 1);

                    // Each row: label, color, and which real question types it counts
                    $rowDefs = [
                    'objective' => ['label' => 'Objective', 'color' => 'info', 'types' => ['mcq_4', 'mcq_2']],
                    'subjective' => ['label' => 'Subjective', 'color' => 'primary', 'types' => ['short_question', 'long_question']],
                    'mcq_4' => ['label' => 'MCQ (4 options)', 'color' => 'info', 'types' => ['mcq_4']],
                    'mcq_2' => ['label' => 'MCQ (2 options)', 'color' => 'primary', 'types' => ['mcq_2']],
                    'short_question' => ['label' => 'Short Question', 'color' => 'info', 'types' => ['short_question']],
                    'long_question' => ['label' => 'Long Question', 'color' => 'primary', 'types' => ['long_question']],
                    ];

                    // Which rows to show for each exam set type
                    $visibleRows = match ($examSet->question_type) {
                    'objective' => ['mcq_4', 'mcq_2'],
                    'subjective' => ['short_question', 'long_question'],
                    'mcq_4' => ['mcq_4'],
                    'mcq_2' => ['mcq_2'],
                    'short_question' => ['short_question'],
                    'long_question' => ['long_question'],
                    default => ['objective', 'subjective'], // All
                    };
                    @endphp

                    <h6 class="small text-uppercase text-muted fw-bold mb-2">By Question Type</h6>
                    @foreach($visibleRows as $key)
                    @php
                    $row = $rowDefs[$key];
                    $count = collect($row['types'])->sum(fn ($t) => $typeCounts[$t] ?? 0);
                    $pct = round(($count / $typeTotal) * 100);
                    @endphp
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-{{ $row['color'] }} fw-semibold">{{ $row['label'] }}</small>
                            <small class="text-muted">{{ $count }} ({{ $pct }}%)</small>
                        </div>
                        <div class="progress" style="height:6px">
                            <div class="progress-bar bg-{{ $row['color'] }}" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach

                    <hr class="my-3">

                    {{-- Difficulty --}}
                    @php $breakdown = $examSet->difficulty_breakdown; @endphp
                    <h6 class="small text-uppercase text-muted fw-bold mb-2">By Difficulty</h6>
                    @foreach(['easy'=>'success','medium'=>'warning','hard'=>'danger'] as $diff=>$color)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-{{ $color }} fw-semibold">{{ ucfirst($diff) }}</small>
                            <small class="text-muted">{{ $breakdown[$diff]['count'] }} ({{ $breakdown[$diff]['pct'] }}%)</small>
                        </div>
                        <div class="progress" style="height:6px">
                            <div class="progress-bar bg-{{ $color }}" style="width:{{ $breakdown[$diff]['pct'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="card-footer py-2">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Total Marks</small>
                        <small class="fw-semibold">
                            @if($examSet->status === 'active' && !$examSet->published_exam_id)
                            <span id="liveTotalMarks">{{ $examSet->total_marks }}</span> marks
                            @else
                            {{ $examSet->total_marks }} marks
                            @endif
                        </small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Duration</small>
                        <small class="fw-semibold">{{ $examSet->duration_minutes }} mins</small>
                    </div>
                </div>

            </div>

            <div class="card mb-0">

                <div class="card-header">
                    <div class="d-flex align-item-center justify-content-between">
                        <h6 class="card-title p-0 m-0">
                            <i class="bi bi-check-circle text-theme me-1"></i>
                            Exam Status
                        </h6>
                    </div>
                </div>

                @if(!$examSet->published_exam_id)
                <div class="card-body">
                    <form action="{{ route('teacher.aiExamSets.status', $examSet) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="mb-2">
                            <select name="status" class="form-select form-select-sm">
                                @foreach(['draft','active','archived'] as $s)
                                <option value="{{ $s }}" {{ $examSet->status === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-sm btn-outline-primary w-100">Update Status</button>
                    </form>
                </div>
                @endif

            </div>

            {{-- ✅ CASE 1: Active + not published → show Publish form --}}
            @if($examSet->status === 'active' && !$examSet->published_exam_id)
            <div class="card border-0 shadow-sm mt-3 mb-0 publish-card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h6 class="fw-bold mb-0 text-success">
                            <i class="bi bi-check2-circle text-theme"></i>
                            Publish as Exam
                        </h6>
                    </div>
                    <p class="text-muted small mb-3">
                        Select a course. The system will automatically create an exam
                        and copy all <strong>{{ $examSet->total_questions }} questions</strong> into it.
                    </p>
                    <form action="{{ route('teacher.aiExamSets.publish', $examSet) }}" method="POST" id="publishExamForm">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label fw-semibold small">Course <span class="text-danger">*</span></label>
                            <select name="course_id" class="form-select form-select-sm" required>
                                <option value="">— Select Course —</option>
                                @foreach($courseList as $course)
                                <option value="{{ $course->id }}">[{{ $course->course_code }}] - {{ $course->course_title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-semibold small">Exam Date</label>
                            <input type="date" name="exam_date" class="form-control form-control-sm" value="{{ now()->toDateString() }}">
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-semibold small">Exam Time <small class="text-muted ms-1">[Start - End]</small></label>
                            <div class="input-group">
                                <input type="time" name="start_time" class="form-control form-control-sm" value="10:00">
                                <input type="time" name="end_time" class="form-control form-control-sm" value="12:00">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Total Q. for Exam</label>
                            <input type="number" name="total_questions" class="form-control form-control-sm" min="1" max="{{ $examSet->total_questions }}">
                            <small class="text-muted">{{ $examSet->total_questions }} questions generated</small>
                        </div>

                        <button type="button" class="btn btn-success btn-sm w-100 fw-semibold" id="openPublishModal">
                            <i class="bi bi-rocket-takeoff me-1"></i> Publish Exam
                        </button>
                    </form>
                </div>
            </div>

            {{-- ✅ CASE 2: Already published → show link --}}
            @elseif($examSet->published_exam_id)
            <div class="card border-0 shadow-sm mt-3 mb-0 published-card">
                <div class="card-body text-center py-4">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#0d6efd" stroke-width="1.5" class="mb-2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    <p class="fw-bold text-primary mb-1">Published Successfully</p>
                    <p class="text-muted small mb-3">
                        This AI set is live in the exam system with all questions copied.
                    </p>
                    <a href="{{ route('teacher.exams.questionPaper', $examSet->published_exam_id) }}" class="btn btn-sm btn-primary w-100">
                        View Exam Paper →
                    </a>
                </div>
            </div>

            {{-- CASE 3: Draft → prompt to activate --}}
            @else
            <div class="card border-0 shadow-sm bg-light mt-3 mb-0">
                <div class="card-body text-center py-4">
                    <p class="text-muted small mb-0">
                        Set status to <strong>Active</strong> above to publish this exam to a course.
                    </p>
                </div>
            </div>
            @endif

        </div>

    </div>

</section>

@include('teacher.layouts.common.createExamModal')

@endsection



@section('scripts')

<script>
    (function() {
        const state = {
            type: ''
            , difficulty: ''
        };
        const groups = {
            objective: ['mcq_4', 'mcq_2']
            , subjective: ['short_question', 'long_question']
        , };
        const buttons = document.querySelectorAll('[data-group]');

        function matchesType(cardType) {
            if (!state.type) return true;
            return (groups[state.type] || [state.type]).includes(cardType);
        }

        function applyFilters() {
            document.querySelectorAll('#question-list .question-card').forEach(card => {
                const ok = matchesType(card.dataset.type) &&
                    (!state.difficulty || card.dataset.difficulty === state.difficulty);
                card.style.display = ok ? '' : 'none';
            });

            buttons.forEach(btn => {
                const g = btn.dataset.group;
                const active = g === 'all' ?
                    !state.type && !state.difficulty :
                    state[g] === btn.dataset.value;
                btn.classList.toggle('active', active);
            });
        }

        buttons.forEach(btn => btn.addEventListener('click', () => {
            const g = btn.dataset.group;
            if (g === 'all') {
                state.type = state.difficulty = '';
            } else {
                // click again to deselect
                state[g] = state[g] === btn.dataset.value ? '' : btn.dataset.value;
            }
            applyFilters();
        }));
    })();

</script>

<script>
    // Open modal only after basic validation passes
    document.getElementById('openPublishModal').addEventListener('click', function() {
        const form = document.getElementById('publishExamForm');

        // Trigger native HTML5 validation first
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Open the confirmation modal
        const modal = new bootstrap.Modal(document.getElementById('createExam_modal'));
        modal.show();
    });

    // When user confirms in modal — submit the real form
    document.getElementById('confirmPublishBtn').addEventListener('click', function() {
        // Show loading state
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Creating...';

        document.getElementById('publishExamForm').submit();
    });

</script>

<script>
    // Live total marks counter
    function recalcTotal() {
        const inputs = document.querySelectorAll('.marks-input');
        let total = 0;
        inputs.forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        const el = document.getElementById('liveTotalMarks');
        if (el) el.textContent = total.toFixed(total % 1 === 0 ? 0 : 1);
    }

    // Highlight changed marks inputs
    document.querySelectorAll('.marks-input').forEach(input => {
        input.addEventListener('input', function() {
            const original = parseFloat(this.dataset.original);
            const current = parseFloat(this.value);
            this.classList.toggle('border-warning', current !== original);
            this.classList.toggle('text-warning', current !== original);
        });
    });

</script>

@endsection

