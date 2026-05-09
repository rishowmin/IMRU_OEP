@extends('admin.layouts.app')
@section('title', 'Candidate Paper — ' . $examSet->title)

@push('styles')
<style>
    .question-block {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 20px 24px;
        margin-bottom: 16px;
        background: #fff;
    }
    .question-block:hover { border-color: #0d6efd22; }
    .q-number {
        width: 32px; height: 32px; border-radius: 50%;
        background: #0d6efd; color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem; font-weight: 700; flex-shrink: 0;
    }
    .diff-badge { padding: 2px 9px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
    .diff-badge.easy   { background: #d1e7dd; color: #0a3622; }
    .diff-badge.medium { background: #fff3cd; color: #664d03; }
    .diff-badge.hard   { background: #f8d7da; color: #58151c; }
    .option-row {
        display: flex; align-items: center; gap: 10px;
        padding: 8px 12px; border-radius: 6px;
        border: 1px solid #dee2e6; margin-bottom: 6px;
        font-size: .9rem;
    }
    .option-label {
        width: 26px; height: 26px; border-radius: 50%;
        border: 2px solid #dee2e6;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .78rem; flex-shrink: 0;
        color: #495057;
    }
    .paper-header {
        background: linear-gradient(135deg, #1e3a5f, #0d6efd);
        color: #fff; border-radius: 12px; padding: 24px 28px; margin-bottom: 28px;
    }
    @media print {
        .no-print { display: none !important; }
        .paper-header { background: #1e3a5f !important; -webkit-print-color-adjust: exact; }
        body { padding: 0 !important; }
        .container { max-width: 100% !important; padding: 0 !important; }
    }
</style>
@endpush

@section('content')
<div class="container py-4">

    {{-- Top actions --}}
    <div class="d-flex align-items-center gap-2 mb-4 no-print">
        <a href="{{ route('admin.academic.aiExamSets.show', $examSet) }}"
           class="btn btn-outline-secondary btn-sm">← Back to Exam Set</a>
        <button onclick="window.print()" class="btn btn-outline-primary btn-sm ms-auto">
            🖨 Print Paper
        </button>
    </div>

    {{-- Paper header --}}
    <div class="paper-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="fw-bold mb-1">{{ $examSet->title }}</h3>
                <div class="opacity-75" style="font-size:.9rem">
                    Topic: {{ $examSet->topic }} &nbsp;·&nbsp;
                    Total Questions: {{ $examSet->total_questions }} &nbsp;·&nbsp;
                    Total Marks: {{ $examSet->total_marks }} &nbsp;·&nbsp;
                    Duration: {{ $examSet->duration_minutes }} mins
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div style="font-size:.8rem;opacity:.75">Candidate ID</div>
                <div class="fw-bold fs-5">{{ $instance->candidate_id }}</div>
                <div style="font-size:.75rem;opacity:.6;font-family:monospace">
                    Paper #{{ $instance->id }}
                </div>
            </div>
        </div>
    </div>

    {{-- Questions --}}
    @forelse($questions as $index => $question)
        <div class="question-block">
            <div class="d-flex align-items-start gap-3">

                {{-- Number bubble --}}
                <div class="q-number">{{ $serialNo++ }}</div>

                <div class="flex-grow-1">
                    {{-- Question text --}}
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <p class="mb-0 fw-semibold" style="font-size:.97rem; flex:1">
                            {!! $question->question_text !!}
                        </p>
                        <div class="d-flex align-items-center gap-1 flex-shrink-0">
                            <span class="diff-badge {{ $question->difficulty_level }}">
                                {{ ucfirst($question->difficulty_level) }}
                            </span>
                            <span class="text-muted small">
                                [{{ $question->marks ?? 1 }} mark{{ ($question->marks ?? 1) != 1 ? 's' : '' }}]
                            </span>
                        </div>
                    </div>

                    {{-- Question figure --}}
                    @if($question->question_figure)
                        <div class="mb-3">
                            <img src="{{ asset('storage/question_figure/' . $question->question_figure) }}"
                                 alt="Question figure"
                                 class="img-fluid rounded"
                                 style="max-height:200px">
                        </div>
                    @endif

                    {{-- MCQ Options --}}
                    @if($question->option_a)
                        @php
                            // Use shuffled option order if available, otherwise default
                            $optionMap = ['a' => $question->option_a, 'b' => $question->option_b,
                                          'c' => $question->option_c, 'd' => $question->option_d];
                            $order     = $question->shuffled_options ?? ['a', 'b', 'c', 'd'];
                            $labels    = ['A', 'B', 'C', 'D'];
                        @endphp
                        <div class="mt-2">
                            @foreach($order as $i => $key)
                                @if(!empty($optionMap[$key]))
                                    <div class="option-row">
                                        <div class="option-label">{{ $labels[$i] }}</div>
                                        <span>{{ $optionMap[$key] }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                    {{-- Short answer / Essay blank --}}
                    @else
                        <div class="mt-3" style="border-bottom: 1px dashed #ccc; min-height: 60px;">
                            &nbsp;
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-5">No questions found for this candidate paper.</div>
    @endforelse

    {{-- Footer --}}
    <div class="text-center text-muted mt-4 pt-3 border-top" style="font-size:.8rem">
        <strong>— End of Paper —</strong> &nbsp;·&nbsp;
        Candidate: {{ $instance->candidate_id }} &nbsp;·&nbsp;
        {{ $examSet->title }} &nbsp;·&nbsp;
        Generated: {{ $instance->created_at->format('d M Y, h:i A') }}
    </div>

</div>
@endsection
