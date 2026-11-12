@extends('teacher.layouts.app')
@section('title', 'Generate Exam Set by AI')

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

        <div class="col-lg-12">

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

                            <form method="POST" action="{{ route('teacher.aiExamSets.store') }}" id="exam-form">
                                @csrf

                                <div class="row g-4">
                                    <div class="col-md-8">
                                        <div class="row g-2">

                                            {{-- Exam Title --}}
                                            <div class="col-md-12">
                                                <label for="title" class="form-label fw-bold mb-1"><small>Exam Title</small> <small class="text-danger">*</small></label>
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

                                            {{-- Total Questions --}}
                                            <div class="col-md-4">
                                                <label for="total_questions" class="form-label fw-bold mb-1"><small>Total Questions</small> <small class="text-danger">*</small></label>
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
                                            <div class="col-md-4">
                                                <label for="duration_minutes" class="form-label fw-bold mb-1"><small>Duration (min)</small> <small class="text-danger">*</small></label>
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

                                            {{-- Question Type --}}
                                            <div class="col-md-4">
                                                <label for="question_type" class="form-label fw-bold mb-1"><small>Question Type</small> <small class="text-danger">*</small></label>
                                                <div class="input-group">
                                                    <select name="question_type" class="form-select @error('question_type') is-invalid @enderror" id="question-type-select">
                                                        <option value="All">All</option>
                                                        <option value="objective" {{ old('question_type') === 'objective' ? 'selected' : '' }}>Objective (4 & 2 Options)</option>
                                                        <option value="subjective" {{ old('question_type') === 'subjective' ? 'selected' : '' }}>Subjective (Short & Long)</option>
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
                                            <div class="col-md-12">
                                                <label for="topic" class="form-label fw-bold mb-1"><small>Topic / Subject</small> <small class="text-danger">*</small></label>
                                                <div class="input-group">
                                                    <select name="topic" class="form-select @error('topic') is-invalid @enderror" id="topic-select">
                                                        <option selected disabled>Select Topic</option>
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

                                            {{-- Extra Instructions --}}
                                            <div class="col-md-12">
                                                <label for="extra_instructions" class="form-label fw-bold mb-1"><small>Extra Instructions <span class="text-muted fw-normal">(optional)</span></small></label>
                                                <textarea name="extra_instructions" id="extra_instructions" class="form-control" rows="3" maxlength="1000" placeholder="e.g. Focus on chapter 3, use practical scenarios">{{ old('extra_instructions') }}</textarea>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card balance-mix-card mb-0">
                                            <div class="card-header border-2 border-theme">
                                                <h6 class="mb-0 text-theme fw-bold">
                                                    <i class="bi bi-sliders me-1"></i>
                                                    Balance Mix
                                                </h6>
                                            </div>

                                            <div class="card-body pt-2">

                                                <div class="row mb-2">
                                                    {{-- Question Type --}}
                                                    <div class="col-md-12">
                                                        <label for="qt-range" class="form-label fw-bold mb-1"><small>Question Type</small></label>

                                                        <div>
                                                            {{-- hidden fields: these are what the controller receives --}}
                                                            <input type="hidden" name="qtype1_percent" id="qt1_pct" value="{{ old('qtype1_percent', 50) }}">
                                                            <input type="hidden" name="qtype2_percent" id="qt2_pct" value="{{ old('qtype2_percent', 50) }}">

                                                            <div class="qt-range" id="qt-range">
                                                                <input type="range" class="form-range qt-range-input" id="qt-range-1" min="0" max="100" step="5" value="{{ old('qtype1_percent', 50) }}">
                                                            </div>

                                                            <div class="row g-0 small mt-1">
                                                                <div class="col-6 text-start">
                                                                    <div class="text-start">
                                                                        <h6 class="mb-0"><span id="qt1-value" class="badge bg-info">50%</span></h6>
                                                                        <span id="qtype1-label" class="fw-bold small">Objective</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 text-end">
                                                                    <div class="text-end">
                                                                        <h6 class="mb-0"><span id="qt2-value" class="badge bg-primary">50%</span></h6>
                                                                        <span id="qtype2-label" class="fw-bold small">Subjective</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr class="border-1 border-theme my-3 font-light opacity-100">

                                                    {{-- Difficulty Mix --}}
                                                    <div class="col-md-12">
                                                        <label for="diff-range-1" class="form-label fw-bold mb-1"><small>Question Difficulty</small></label>

                                                        <div>
                                                            {{-- hidden fields: these are what the controller receives --}}
                                                            <input type="hidden" name="easy_percent"   id="easy_pct"   value="{{ old('easy_percent', 50) }}">
                                                            <input type="hidden" name="medium_percent" id="medium_pct" value="{{ old('medium_percent', 30) }}">
                                                            <input type="hidden" name="hard_percent"   id="hard_pct"   value="{{ old('hard_percent', 20) }}">

                                                            <div class="diff-range" id="diff-range">
                                                                <input type="range" class="diff-range-input" id="diff-range-1" min="0" max="100" step="5" value="{{ (int) old('easy_percent', 50) }}" aria-label="Easy and Medium boundary">
                                                                <input type="range" class="diff-range-input" id="diff-range-2" min="0" max="100" step="5" value="{{ (int) old('easy_percent', 50) + (int) old('medium_percent', 30) }}" aria-label="Medium and Hard boundary">
                                                            </div>

                                                            <div class="row g-0 small mt-1">
                                                                <div class="col-4 text-start">
                                                                    <h6 class="mb-0"><span id="easy-value" class="badge bg-success">50%</span></h6>
                                                                    <span class="fw-bold small">Easy</span>
                                                                </div>
                                                                <div class="col-4 text-center">
                                                                    <h6 class="mb-0"><span id="medium-value" class="badge bg-warning text-dark">30%</span></h6>
                                                                    <span class="fw-bold small">Medium</span>
                                                                </div>
                                                                <div class="col-4 text-end">
                                                                    <h6 class="mb-0"><span id="hard-value" class="badge bg-danger">20%</span></h6>
                                                                    <span class="fw-bold small">Hard</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <hr>

                                    <div class="col-md-12 mt-0">
                                        <div class="form-submit-button">
                                            <button type="submit" class="btn btn-primary btn-lg w-100" id="submit-btn">
                                                <i class="bi bi-lightning-charge me-2"></i>
                                                Generate with AI
                                            </button>
                                            <p class="text-center text-muted small mt-2 mb-0">This may take 5–10 seconds while AI selects questions.</p>
                                        </div>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-12">
            <div class="card border-0 bg-primary-light shadow-sm mb-0" style="border-left: 5px solid #0d6efd !important;">
                <div class="card-body">
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

    </div>

</section>

@endsection



@section('scripts')

<script>
(function () {
    'use strict';

    // Labels under the Question Type slider.
    // 2 labels = split slider, 1 label = single type (slider locked at 100%).
    const QTYPE_CONFIG = {
        'All':            ['Objective', 'Subjective'],
        'objective':      ['MCQ 4',     'MCQ 2'],
        'subjective':     ['Short Q.',  'Long Q.'],
        'mcq_4':          ['MCQ 4'],
        'mcq_2':          ['MCQ 2'],
        'short_question': ['Short Q.'],
        'long_question':  ['Long Q.'],
    };

    const byId      = id => document.getElementById(id);
    const setText   = (id, text) => { const el = byId(id); if (el) el.textContent = text; };
    const setHidden = (id, hide) => { const el = byId(id); if (el) el.classList.toggle('d-none', hide); };

    let wasSingle = false;

    // ── Difficulty: ONE track, TWO thumbs ────────────────────────────────────
    // thumb 1 (a) = where Easy ends, thumb 2 (b) = where Medium ends.
    //   Easy = a      Medium = b - a      Hard = 100 - b
    // Independent of the Question Type dropdown.
    function renderDiffRange(changed) {
        const r1 = byId('diff-range-1');
        const r2 = byId('diff-range-2');
        if (!r1 || !r2) return;

        let a = parseInt(r1.value) || 0;
        let b = parseInt(r2.value) || 0;

        // The thumbs may touch but never cross
        if (a > b) {
            if (changed === r1) { a = b; } else { b = a; }
            r1.value = a;
            r2.value = b;
        }

        const easy   = a;
        const medium = b - a;
        const hard   = 100 - b;

        byId('easy_pct').value   = easy;      // hidden fields: these are submitted
        byId('medium_pct').value = medium;
        byId('hard_pct').value   = hard;

        setText('easy-value',   easy + '%');
        setText('medium-value', medium + '%');
        setText('hard-value',   hard + '%');

        const wrap = byId('diff-range');      // paints the green / yellow / red track
        wrap.style.setProperty('--a', a);
        wrap.style.setProperty('--b', b);

        // When both thumbs meet, keep the one that can still move on top
        r1.classList.toggle('is-top', a > 50);
        r2.classList.toggle('is-top', a <= 50);
    }

    // ── Question Type slider ─────────────────────────────────────────────────
    // Slider value = share of the FIRST type (left segment).
    function renderQtSlider() {
        const range = byId('qt-range-1');
        if (!range) return;

        const qt1 = parseInt(range.value) || 0;
        const qt2 = 100 - qt1;

        byId('qt1_pct').value = qt1;          // hidden fields: these are submitted
        byId('qt2_pct').value = qt2;

        setText('qt1-value', qt1 + '%');
        setText('qt2-value', qt2 + '%');

        range.style.setProperty('--split', qt1 + '%');
    }

    // Runs on page load and every time the Question Type dropdown changes
    function syncQtypeLabels() {
        const select = byId('question-type-select');
        const range  = byId('qt-range-1');
        if (!select || !range) return;

        const labels   = QTYPE_CONFIG[select.value] || QTYPE_CONFIG['All'];
        const isSingle = labels.length === 1;

        setText('qtype1-label', labels[0]);

        if (isSingle) {
            range.value    = 100;
            range.disabled = true;
        } else {
            setText('qtype2-label', labels[1]);
            range.disabled = false;

            if (wasSingle) {
                range.value = 50;             // coming back from a single type: reset the split
            }
        }

        setHidden('qtype2-label', isSingle);
        setHidden('qt2-value', isSingle);

        wasSingle = isSingle;
        renderQtSlider();
    }

    // ── Select2 (isolated, so a problem here cannot break the rest of the form) ──
    function initSelect2() {
        if (!window.jQuery || !jQuery.fn || !jQuery.fn.select2) {
            console.warn('[exam form] Select2 is not loaded. Plain dropdowns are used instead. '
                + 'Make sure jQuery and select2.js are included before this section.');
            return;
        }

        const oldTopic = @json(old('topic'));

        jQuery('#topic-select').select2({ tags: true, width: '100%', placeholder: 'Select or type a topic' });
        jQuery('#question-type-select').select2({ width: '100%' });

        // Keep a typed (new) topic after a validation error
        if (oldTopic) {
            const exists = jQuery('#topic-select option').filter((i, o) => o.value === oldTopic).length > 0;

            if (!exists) {
                jQuery('#topic-select').append(new Option(oldTopic, oldTopic, true, true));
            }

            jQuery('#topic-select').val(oldTopic).trigger('change');
        }
    }

    function init() {
        const select = byId('question-type-select');

        // Select2 fires jQuery change events. A jQuery handler also receives native ones,
        // so this one binding covers both a plain select and a Select2 one.
        if (window.jQuery) {
            jQuery(select).on('change', syncQtypeLabels);
        } else {
            select.addEventListener('change', syncQtypeLabels);
        }

        byId('qt-range-1').addEventListener('input', renderQtSlider);
        byId('diff-range-1').addEventListener('input', e => renderDiffRange(e.target));
        byId('diff-range-2').addEventListener('input', e => renderDiffRange(e.target));

        byId('exam-form').addEventListener('submit', function () {
            const btn = byId('submit-btn');
            btn.disabled  = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> AI is writing questions...';
        });

        syncQtypeLabels();
        renderDiffRange(null);

        try {
            initSelect2();
        } catch (e) {
            console.error('[exam form] Select2 init failed:', e);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>

@endsection
