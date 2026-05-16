@extends('admin.layouts.app')
@section('title', 'Generate Exam Set by AI')

@section('content')

@if(session('success') || session('status') || session('error'))
@include('admin.layouts.common.status')
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
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                    <li class="breadcrumb-item active">@yield('title')</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="card-header-right">
                            <a href="{{ route('admin.academic.aiExamSets.index') }}" class="btn btn-outline-theme btn-sm">
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

    <div class="row">

        <div class="col-lg-8">

            <div class="accordion mb-3" id="accordionGenerateExamSetsByAi">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingGenerateExamSetsByAi">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGenerateExamSetsByAi" aria-expanded="true" aria-controls="collapseGenerateExamSetsByAi">
                            <h6 class="card-title p-0 m-0">
                                <i class="bi bi-pencil-square"></i>
                                Create Exam Set
                            </h6>
                        </button>
                    </h2>
                    <div id="collapseGenerateExamSetsByAi" class="accordion-collapse collapse show" aria-labelledby="headingGenerateExamSetsByAi" data-bs-parent="#accordionGenerateExamSetsByAi">
                        <div class="accordion-body">

                            <form method="POST" action="{{ route('admin.academic.aiExamSets.store') }}" id="exam-form">
                                @csrf

                                <div class="row mb-3">

                                    {{-- Exam Title --}}
                                    <div class="col-md-8">
                                        <label for="title" class="form-label fw-bold"><small>Exam Title</small> <small class="text-danger">*</small></label>
                                        <div class="input-group">
                                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. Midterm Exam — Computer Networks">
                                        </div>

                                        <div class="d-flex align-items-center">
                                            @error('title')
                                            <div class="invalid-feedback d-block">
                                                <i class="bi bi-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Question Type --}}
                                    <div class="col-md-4">
                                        <label for="question_type" class="form-label fw-bold"><small>Question Type</small> <small class="text-danger">*</small></label>
                                        <div class="input-group">
                                            <select name="question_type" class="form-select @error('question_type') is-invalid @enderror" id="question-type-select">
                                                <option value="All">All</option>
                                                <option value="objective" {{ old('question_type') === 'objective' ? 'selected' : '' }}>Objective (MCQ 4 & 2 Options)</option>
                                                <option value="subjective" {{ old('question_type') === 'subjective' ? 'selected' : '' }}>Subjective (Short & Long Questions)</option>
                                                <option value="mcq_4" {{ old('question_type') === 'mcq_4' ? 'selected' : '' }}>MCQ (4 Options)</option>
                                                <option value="mcq_2" {{ old('question_type') === 'mcq_2' ? 'selected' : '' }}>MCQ (2 Options)</option>
                                                <option value="short_question" {{ old('question_type') === 'short_question' ? 'selected' : '' }}>Short Question</option>
                                                <option value="long_question" {{ old('question_type') === 'long_question' ? 'selected' : '' }}>Long Question</option>

                                            </select>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            @error('question_type')
                                            <div class="invalid-feedback d-block">
                                                <i class="bi bi-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Topic / Subject --}}
                                    <div class="col-md-6">
                                        <label for="topic" class="form-label fw-bold"><small>Topic / Subject</small> <small class="text-danger">*</small></label>
                                        <div class="input-group">
                                            <select name="topic" class="form-select @error('topic') is-invalid @enderror" id="topic-select">
                                                <option value="All">All Topics</option>
                                                @foreach($topics as $topic)
                                                <option value="{{ $topic }}" {{ old('topic') === $topic ? 'selected' : '' }}>{{ $topic }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="d-flex align-items-center">
                                            @error('topic')
                                            <div class="invalid-feedback d-block">
                                                <i class="bi bi-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Total Questions --}}
                                    <div class="col-md-3">
                                        <label for="total_questions" class="form-label fw-bold"><small>Total Questions</small> <small class="text-danger">*</small></label>
                                        <div class="input-group">
                                            <input type="number" name="total_questions" class="form-control @error('total_questions') is-invalid @enderror" value="{{ old('total_questions', 30) }}" min="5" max="200">
                                        </div>

                                        <div class="d-flex align-items-center">
                                            @error('total_questions')
                                            <div class="invalid-feedback d-block">
                                                <i class="bi bi-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Duration (min) --}}
                                    <div class="col-md-3">
                                        <label for="duration_minutes" class="form-label fw-bold"><small>Duration (min)</small> <small class="text-danger">*</small></label>
                                        <div class="input-group">
                                            <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', 60) }}" min="10" max="300">
                                        </div>

                                        <div class="d-flex align-items-center">
                                            @error('duration_minutes')
                                            <div class="invalid-feedback d-block">
                                                <i class="bi bi-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                <hr class="my-4">

                                <div class="row mb-4">
                                    {{-- Question Type Mix --}}
                                    <div class="col-md-6">
                                        <div class="card border border-theme mb-0">
                                            <div class="card-header">
                                                <h6 class="mb-0 text-theme fw-semibold">Question Type Balance</h6>
                                            </div>

                                            <div class="card-body pt-2">

                                                <div class="question-type-row mb-2">
                                                    <div class="form-group">
                                                        <label class="form-label fw-bold text-info mb-1 small"><small id="qtype1-label">Objective (%)</small></label>
                                                        <input type="number" name="qtype1_percent" id="qt1_pct" class="form-control form-control-sm border-info" value="{{ old('qtype1_percent', 50) }}" min="0" max="100" oninput="updateQuestionTypeTotal()">
                                                    </div>
                                                    <div class="form-group" id="qtype2-group">
                                                        <label class="form-label fw-bold text-primary mb-1 small"><small id="qtype2-label">Subjective (%)</small></label>
                                                        <input type="number" name="qtype2_percent" id="qt2_pct" class="form-control form-control-sm border-primary" value="{{ old('qtype2_percent', 50) }}" min="0" max="100" oninput="updateQuestionTypeTotal()">
                                                    </div>
                                                </div>

                                                <div class="mb-2">
                                                    <span id="qt-pct-total" class="qt-pct-total ok">✓ Total: 100%</span>
                                                    <small class="text-muted ms-2">Must equal 100%</small>
                                                </div>

                                                <div id="qt-bar" style="height:10px;border-radius:6px;overflow:hidden;display:flex">
                                                    <div id="bar-qt-1" style="background:#0dcaf0;width:50%;transition:width .3s"></div>
                                                    <div id="bar-qt-2" style="background:#0d6efd;width:50%;transition:width .3s"></div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    {{-- Difficulty Mix --}}
                                    <div class="col-md-6">
                                        <div class="card border border-theme mb-0">
                                            <div class="card-header">
                                                <h6 class="mb-0 text-theme fw-semibold">Difficulty Balance</h6>
                                            </div>

                                            <div class="card-body pt-2">

                                                <div class="difficulty-row mb-2">
                                                    <div class="form-group">
                                                        <label for="easy_percent" class="form-label fw-bold text-success mb-1 small"><small>Easy (%)</small></label>
                                                        <input type="number" name="easy_percent" id="easy_pct" class="form-control form-control-sm border-success" value="{{ old('easy_percent', 30) }}" min="0" max="100" oninput="updateDifficultyTotal()">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="medium_percent" class="form-label fw-bold text-warning mb-1 small"><small>Medium (%)</small></label>
                                                        <input type="number" name="medium_percent" id="medium_pct" class="form-control form-control-sm border-warning" value="{{ old('medium_percent', 50) }}" min="0" max="100" oninput="updateDifficultyTotal()">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="hard_percent" class="form-label fw-bold text-danger mb-1 small"><small>Hard (%)</small></label>
                                                        <input type="number" name="hard_percent" id="hard_pct" class="form-control form-control-sm border-danger" value="{{ old('hard_percent', 20) }}" min="0" max="100" oninput="updateDifficultyTotal()">
                                                    </div>
                                                </div>

                                                <div class="mb-2">
                                                    <span id="difclt-pct-total" class="difclt-pct-total ok">✓ Total: 100%</span>
                                                    <small class="text-muted ms-2">Must equal 100%</small>
                                                </div>

                                                <div id="diff-bar" style="height:10px;border-radius:6px;overflow:hidden;display:flex">
                                                    <div id="bar-easy" style="background:#198754;width:30%;transition:width .3s"></div>
                                                    <div id="bar-medium" style="background:#ffc107;width:50%;transition:width .3s"></div>
                                                    <div id="bar-hard" style="background:#dc3545;width:20%;transition:width .3s"></div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-submit-button">
                                    <button type="submit" class="btn btn-primary btn-lg w-100" id="submit-btn">
                                        <i class="bi bi-lightning-charge me-2"></i>
                                        Generate with AI
                                    </button>
                                    <p class="text-center text-muted small mt-2 mb-0">This may take 5–10 seconds while AI selects questions.</p>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3" style="border-left: 5px solid #0d6efd !important;">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-2" style="font-size:.85rem"><i class="bi bi-robot me-1"></i> How AI works here</h6>
                    <ul class="text-muted small mb-0 ps-4">
                        <li>Reads your entire question bank for the selected topic</li>
                        <li>Selects questions matching your difficulty targets</li>
                        <li>Avoids similar/duplicate questions</li>
                        <li>Ensures variety in question types</li>
                        <li>Each candidate gets a uniquely shuffled version</li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="col-lg-4">

            <div class="accordion mb-3" id="accordionAvailableQuestionsInBank">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingAvailableQuestionsInBank">
                        <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAvailableQuestionsInBank" aria-expanded="true" aria-controls="collapseAvailableQuestionsInBank">
                            <h6 class="card-title p-0 m-0">
                                <i class="bi bi-bank2"></i>
                                Question Bank
                            </h6>
                        </button>
                    </h2>
                    <div id="collapseAvailableQuestionsInBank"
                        class="accordion-collapse collapse show" aria-labelledby="headingAvailableQuestionsInBank" data-bs-parent="#accordionAvailableQuestionsInBank">
                        <div class="accordion-body">

                            @forelse($questionStats as $topic => $stats)
                                @php
                                    $easy   = $stats->firstWhere('difficulty_level', 'easy');
                                    $medium = $stats->firstWhere('difficulty_level', 'medium');
                                    $hard   = $stats->firstWhere('difficulty_level', 'hard');
                                    $total  = $stats->sum('count');
                                @endphp
                                <div class="qbank-topic-card rounded-3 p-3 mb-2 bg-light">

                                    {{-- Topic name --}}
                                    <div class="fw-semibold text-truncate mb-2 small" title="{{ $topic }}">
                                        <i class="bi bi-folder2 me-1 text-secondary"></i>
                                        {{ $topic }}
                                    </div>

                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                        <span class="badge rounded-pill bg-success" title="Easy">
                                            E <strong>{{ $easy->count ?? 0 }}</strong>
                                        </span>
                                        <span class="qbank-divider">·</span>
                                        <span class="badge rounded-pill bg-warning" title="Medium">
                                            M <strong>{{ $medium->count ?? 0 }}</strong>
                                        </span>
                                        <span class="qbank-divider">·</span>
                                        <span class="badge rounded-pill bg-danger" title="Hard">
                                            H <strong>{{ $hard->count ?? 0 }}</strong>
                                        </span>
                                        <span class="qbank-divider ms-auto">|</span>
                                        <span class="badge rounded-pill bg-primary" title="Total">
                                            Total <strong>{{ $total }}</strong>
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4 small">
                                    <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                    No active questions found.<br>Please add questions to the bank first.
                                </div>
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</section>

@endsection


@section('scripts')

<script>
    const SINGLE_TYPES = ['mcq_4', 'mcq_2', 'short_question', 'long_question'];

    const SINGLE_TYPE_LABELS = {
        'mcq_4':          'MCQ 4 (%)',
        'mcq_2':          'MCQ 2 (%)',
        'short_question': 'Short Q. (%)',
        'long_question':  'Long Q. (%)',
    };

    const QTYPE_LABELS = {
        'All':            ['Objective (%)',      'Subjective (%)'],
        'objective':      ['MCQ 4 (%)', 'MCQ 2 (%)'],
        'subjective':     ['Short Q. (%)', 'Long Q. (%)'],
    };

    function syncQtypeLabels() {
        const val      = document.getElementById('question-type-select').value;
        const qt1Input = document.getElementById('qt1_pct');
        const qt2Input = document.getElementById('qt2_pct');
        const qt2Group = document.getElementById('qtype2-group');
        const qtBar2   = document.getElementById('bar-qt-2');

        if (SINGLE_TYPES.includes(val)) {
            document.getElementById('qtype1-label').textContent = SINGLE_TYPE_LABELS[val];
            qt1Input.value    = 100;
            qt1Input.readOnly = true;

            qt2Input.value    = 0;
            qt2Input.readOnly = true;          // ✅ readOnly submits, disabled doesn't
            qt2Input.type     = 'hidden';      // ✅ hide visually but still submits
            qt2Group.style.display = 'none';

            document.getElementById('bar-qt-1').style.width = '100%';
            document.getElementById('bar-qt-2').style.width = '0%';

            const el = document.getElementById('qt-pct-total');
            el.textContent = '✓ Total: 100%';
            el.className   = 'fw-semibold text-success';

        } else {
            const labels = QTYPE_LABELS[val] ?? ['Type 1 (%)', 'Type 2 (%)'];
            document.getElementById('qtype1-label').textContent = labels[0];
            document.getElementById('qtype2-label').textContent = labels[1];

            qt2Input.type     = 'number';      // ✅ restore type
            qt2Input.readOnly = false;
            qt2Group.style.display = '';

            updateQuestionTypeTotal();
        }
    }

    function updateQuestionTypeTotal() {
        const val   = document.getElementById('question-type-select').value;
        const isSingle = SINGLE_TYPES.includes(val);
        const qt1   = isSingle ? 100 : (parseInt(document.getElementById('qt1_pct').value) || 0);
        const qt2   = isSingle ? 0   : (parseInt(document.getElementById('qt2_pct').value) || 0);
        const total = qt1 + qt2;
        const el = document.getElementById('qt-pct-total');
        el.textContent = (total === 100 ? '✓' : '✗') + ' Total: ' + total + '%';
        el.className   = 'qt-pct-total ' + (total === 100 ? 'ok' : 'fail');
        document.getElementById('submit-btn').disabled = !isSingle && (total !== 100);
        document.getElementById('bar-qt-1').style.width = qt1 + '%';
        document.getElementById('bar-qt-2').style.width = qt2 + '%';
    }

    document.getElementById('question-type-select').addEventListener('change', syncQtypeLabels);

    function updateDifficultyTotal() {
        const e     = parseInt(document.getElementById('easy_pct').value)   || 0;
        const m     = parseInt(document.getElementById('medium_pct').value) || 0;
        const h     = parseInt(document.getElementById('hard_pct').value)   || 0;
        const total = e + m + h;
        const el    = document.getElementById('difclt-pct-total');
        el.textContent = (total === 100 ? '✓' : '✗') + ' Total: ' + total + '%';
        el.className   = 'difclt-pct-total ' + (total === 100 ? 'ok' : 'fail');
        document.getElementById('submit-btn').disabled = (total !== 100);
        document.getElementById('bar-easy').style.width   = e + '%';
        document.getElementById('bar-medium').style.width = m + '%';
        document.getElementById('bar-hard').style.width   = h + '%';
    }

    document.getElementById('exam-form').addEventListener('submit', function () {
        const btn = document.getElementById('submit-btn');
        btn.disabled  = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> AI is selecting questions...';
    });

    // Init on page load
    syncQtypeLabels();
    updateQuestionTypeTotal();
    updateDifficultyTotal();
</script>

@endsection
