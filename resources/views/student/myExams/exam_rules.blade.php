@extends('student.layouts.app')
@section('title', 'Exam Rules')

@section('content')

@if(session('success') || session('status') || session('error'))
@include('student.layouts.common.status')
@endif

<section class="section">

    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Exam Info Bar --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body py-2">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="fw-bold">{{ $exam->exam_title }}</span>
                            <small class="text-muted ms-2">{{ $exam->course->course_title }} [{{ $exam->course->course_code }}]</small>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <small class="text-muted"><i class="bi bi-question-circle me-1"></i>{{ $exam->total_questions ?? 0 }} Questions</small>
                            <small class="text-muted"><i class="bi bi-stopwatch me-1"></i>{{ $exam->exam_duration_min ?? 0 }} mins</small>
                            <small class="text-muted"><i class="bi bi-patch-check me-1"></i>{{ intval($exam->total_marks) }} Marks</small>
                            <small class="text-muted"><i class="bi bi-award me-1"></i>Pass: {{ intval($exam->passing_marks) }}</small>
                            <span class="badge bg-success py-2"><i class="bi bi-play-circle me-1"></i>Ongoing</span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-0">
                    <div class="row g-0">

                        {{-- Instructions --}}
                        <div class="col-lg-6 border-end">
                            <div class="px-3 py-2 border-bottom bg-light">
                                <small class="fw-semibold text-primary">
                                    <i class="bi bi-info-circle me-1"></i>Instructions
                                </small>
                            </div>
                            @if($instructions->count())
                            <ul class="list-group list-group-flush rounded">
                                @foreach($instructions->sortBy(fn($m) => $m->rule->order) as $map)
                                <li class="list-group-item d-flex align-items-start gap-2 px-3 py-2 border-0 border-bottom-0">
                                    <i class="bi bi-check-circle-fill text-primary flex-shrink-0 mt-1" style="font-size: 0.75rem;"></i>
                                    <div>
                                        <span class="small fw-semibold">{{ $map->rule->title }}</span>
                                        @if($map->rule->description)
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">{{ $map->rule->description }}</small>
                                        @endif
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <div class="px-3 py-3 text-center">
                                <small class="text-muted">No instructions assigned.</small>
                            </div>
                            @endif
                        </div>

                        {{-- Rules --}}
                        <div class="col-lg-6">
                            <div class="px-3 py-2 border-bottom bg-light">
                                <small class="fw-semibold text-danger">
                                    <i class="bi bi-shield-exclamation me-1"></i>Rules
                                </small>
                            </div>
                            @if($rules->count())
                            <ul class="list-group list-group-flush rounded">
                                @foreach($rules->sortBy(fn($m) => $m->rule->order) as $map)
                                <li class="list-group-item d-flex align-items-start gap-2 px-3 py-2 border-0 border-bottom-0">
                                    <i class="bi bi-exclamation-circle-fill text-danger flex-shrink-0 mt-1" style="font-size: 0.75rem;"></i>
                                    <div>
                                        <span class="small fw-semibold">{{ $map->rule->title }}</span>
                                        @if($map->rule->description)
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">{{ $map->rule->description }}</small>
                                        @endif
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <div class="px-3 py-3 text-center">
                                <small class="text-muted">No rules assigned. Enjoy your exam!</small>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>



            {{-- Agree & Start --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="agreeRules">
                        <label class="form-check-label small" for="agreeRules">
                            I have read and agree to all the exam rules and instructions above.
                        </label>
                    </div>
                    <a href="{{ route('student.myExams.start', $exam->id) }}" class="btn btn-success btn-sm px-4" id="startExamBtn" style="pointer-events: none; opacity: 0.6;">
                        <i class="bi bi-play-fill me-1"></i>I Agree & Start Exam Now
                    </a>
                </div>
            </div>

        </div>
    </div>

</section>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const agreeCheckbox = document.getElementById('agreeRules');
        const startBtn = document.getElementById('startExamBtn');

        agreeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                startBtn.style.pointerEvents = 'auto';
                startBtn.style.opacity = '1';
            } else {
                startBtn.style.pointerEvents = 'none';
                startBtn.style.opacity = '0.6';
            }
        });
    });

</script>
@endsection

