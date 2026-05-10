@extends('admin.layouts.app')
@section('title', 'Student Report')

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
                                <i class="bi bi-graph-up"></i>
                                <span class="ms-1">@yield('title')</span>
                            </h1>
                            <nav style="--bs-breadcrumb-divider: '•';">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.academic.performance.index') }}">Performance</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.academic.performance.examAnalytics', $exam->id) }}">Exam Analytics</a></li>
                                    <li class="breadcrumb-item active">@yield('title')</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="card-header-right">
                            {{-- Re-trigger grading --}}
                            <form method="POST" action="{{ route('admin.academic.performance.retriggerStudentGrading', [$exam->id, $student->id]) }}" onsubmit="return confirm('Re-grade this student?')">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <span class="ms-1">Re-Grade Student</span>
                                </button>

                                <a href="{{ route('admin.academic.performance.examAnalytics', $exam->id) }}" class="btn btn-outline-theme btn-sm">
                                    <i class="bi bi-arrow-left-square"></i>
                                    <span class="ms-1">Back to List</span>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="section">

    {{-- RESULT SUMMARY --}}
    <div class="row g-3 mb-3">
        <div class="col-md-8">
            <div class="card mb-0 h-100">
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6 col-md-3">
                            <div class="h-75 display-6 fw-bold text-{{ $result->percentage >= 40 ? 'success' : 'danger' }}">
                                {{ intval($result->percentage) }}%
                            </div>
                            <div class="text-muted small">Final Score</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="d-flex align-items-center justify-content-center h-75">
                                <span class="{{ $result->grade_badge_class }} fs-5 px-3 py-2">{{ $result->grade }}</span>
                            </div>
                            <div class="text-muted small">Grade</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="d-flex align-items-center justify-content-center h-75">
                                @if($result->is_pass)
                                    <span class="badge bg-success fs-5 px-3 py-2">PASS</span>
                                @else
                                    <span class="badge bg-danger fs-5 px-3 py-2">FAIL</span>
                                @endif
                            </div>
                            <div class="text-muted small">Result</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="h-75 display-6 fw-bold text-primary">#{{ $rank }}</div>
                            <div class="text-muted small">of {{ $totalStudents }}</div>
                        </div>
                    </div>

                    <hr>

                    <div class="row g-3 text-center">
                        <div class="col-md-4">
                            <div class="p-2 rounded bg-light h-100">
                                <div class="fw-bold">{{ $result->total_marks_obtained }} / {{ $result->total_marks }}</div>
                                <div class="text-muted small">Total Marks</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 rounded bg-light h-100">
                                <div class="fw-bold text-primary">
                                    {{ $result->mcq_marks_obtained }} / {{ $result->mcq_total_marks }}
                                </div>
                                <div class="text-muted small">MCQ Marks</div>
                                <div class="text-muted" style="font-size:0.72rem">
                                    ✓ {{ $result->mcq_correct }}
                                    &nbsp;✗ {{ $result->mcq_wrong }}
                                    @if($result->mcq_unanswered > 0)
                                        &nbsp;– {{ $result->mcq_unanswered }} skipped
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 rounded bg-light h-100">
                                <div class="fw-bold text-secondary">
                                    {{ $result->subjective_marks_obtained }} / {{ $result->subjective_total_marks }}
                                </div>
                                <div class="text-muted small">Subjective Marks</div>
                                <div class="text-muted" style="font-size:0.72rem">
                                    {{ $result->subjective_reviewed }}/{{ $result->subjective_total }} reviewed
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Stacked progress bar --}}
                    <div class="mt-3">
                        <div class="progress" style="height: 12px; border-radius: 6px;">
                            @php
                                $mcqPct  = $result->total_marks > 0
                                    ? ($result->mcq_marks_obtained / $result->total_marks * 100) : 0;
                                $subjPct = $result->total_marks > 0
                                    ? ($result->subjective_marks_obtained / $result->total_marks * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-primary" style="width: {{ $mcqPct }}%" title="MCQ"></div>
                            <div class="progress-bar bg-secondary" style="width: {{ $subjPct }}%" title="Subjective"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted"><span class="badge bg-primary me-1">&nbsp;</span>MCQ</small>
                            <small class="text-muted"><span class="badge bg-secondary me-1">&nbsp;</span>Subjective</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-0 h-100">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold text-theme">
                        <i class="bi bi-person me-1"></i>
                        Student Info
                    </h6>
                </div>
                <div class="card-body">
                    <div class="avatar_sec text-center mb-2">

                        @php
                        $student;
                        $studentInfo = $student->info;
                        $firstName = $student->first_name ?? '';
                        $lastName = $student->last_name ?? '';
                        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                        $colors = ['#4e73df', '#1cc88a', '#36b9cc', '#e74a3b', '#f6c23e', '#6f42c1', '#fd7e14', '#20c9a6'];
                        $bgColor = $colors[abs(crc32($firstName . $lastName)) % count($colors)];
                        @endphp

                        <div class="img-sec d-flex justify-content-center">
                            {{-- Preview image --}}
                            <img id="nav-photo-preview" src="{{ $studentInfo?->profile_photo ? asset('storage/profile_photo/student/' . $studentInfo->profile_photo) : '' }}" alt="Profile Photo" style="{{ $studentInfo?->profile_photo ? '' : 'display:none;' }} width: 80px; height:80px; max-height:80px;">

                            {{-- Initials fallback --}}
                            @if(!$studentInfo?->profile_photo)
                            <div class="photo-initials" style="background-color:{{ $bgColor }}; width: 80px; height:80px;">
                                <span style="font-size: 20px;">{{ $initials ?: '?' }}</span>
                            </div>
                            @endif
                        </div>

                    </div>

                    <table class="table table-sm table-borderless mb-0 small">
                        <tr>
                            <td class="text-muted fw-semibold">Name</td>
                            <td class="fw-semibold text-end">{{ $student->first_name }} {{ $student->last_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Student ID</td>
                            <td class="text-end">{{ $student->info->student_id_no ?? $student->id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Email</td>
                            <td class="text-end">{{ $student->email ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Exam</td>
                            <td class="text-end">{{ $exam->exam_title ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Course</td>
                            <td class="text-end">{{ $exam->course->course_title ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Graded At</td>
                            <td class="text-end">{{ $result->graded_at ? $result->graded_at->format('d M Y H:i A') : '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Grading</td>
                            <td class="text-end">
                                @if($result->grading_status === 'complete')
                                <span class="badge bg-success">Complete</span>
                                @elseif($result->grading_status === 'partial')
                                <span class="badge bg-warning text-dark">Partial</span>
                                @else
                                <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ANSWER-BY-ANSWER BREAKDOWN --}}
    <div class="card shadow-sm border-0">
        <div class="card-header">
            <h5 class="mb-0 fw-bold text-theme">
                <i class="bi bi-list-check me-1"></i>
                Answers Breakdown
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0 small" id="answerBreakdownTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Question</th>
                            <th class="text-center">Type</th>
                            <th>Student's Answer</th>
                            <th>Correct Answer</th>
                            <th class="text-center">Marks</th>
                            <th class="text-center">Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($answers as $i => $answer)
                        @php
                            $question    = $answer->question;
                            $review      = $answer->reviewAnswer;
                            $isObjective = in_array($question?->question_type, ['mcq_4', 'mcq_2']) || $question?->evaluation_type === 'automatic';
                        @endphp
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td style="max-width: 260px;">
                                {{ \Str::limit($question?->question_text ?? '—', 100) }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">
                                    {{ str_replace('_', ' ', $question?->question_type ?? '') }}
                                </span>
                            </td>
                            <td style="max-width: 180px;">{{ $answer->answer ?? '—' }}</td>
                            <td>
                                @if($isObjective)
                                    <span class="text-success fw-semibold">
                                        {{ $question?->correct_answer ?? '—' }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic">Teacher-reviewed</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($review)
                                    {{ $review->marks_awarded }} / {{ $question?->marks ?? 0 }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($review)
                                    @if($review->review)
                                        <span class="badge bg-success">Correct</span>
                                    @else
                                        <span class="badge bg-danger">Wrong</span>
                                    @endif
                                @elseif(!$answer->answer)
                                    <span class="badge bg-secondary">Skipped</span>
                                @else
                                    <span class="badge bg-light text-dark border">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>
@endsection

@section('scripts')

{{-- DataTable Script --}}
@if ($answers->count())
<script>
    const qwdTable = new DataTable('#answerBreakdownTable', {
        paging: true,
        pageLength: 50,
        lengthChange: false,
        searching: false,
        scrollX: false,
    });
</script>
@endif

@endsection
