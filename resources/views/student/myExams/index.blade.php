@extends('student.layouts.app')
@section('title', 'My Exams')

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
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <button type="button" class="btn btn-outline-theme btn-sm active" id="gridViewBtn" title="Grid View">
                                <i class="bi bi-grid"></i>
                            </button>
                            <button type="button" class="btn btn-outline-theme btn-sm" id="listViewBtn" title="List View">
                                <i class="bi bi-list-ul"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            {{-- ==================== GRID VIEW ==================== --}}
            <div id="gridView">
                <div class="row">
                    @forelse ($myExamList as $exam)

                    @php
                    $now = now();
                    $startDT = \Carbon\Carbon::parse(
                    $exam->exam_date->toDateString() . ' ' . \Carbon\Carbon::parse($exam->start_time)->format('H:i:s')
                    );
                    $endDT = \Carbon\Carbon::parse(
                    $exam->exam_date->toDateString() . ' ' . \Carbon\Carbon::parse($exam->end_time)->format('H:i:s')
                    );

                    $isSubmitted = in_array($exam->id, $submittedExamIds);

                    if ($isSubmitted) {
                    $status = 'Submitted';
                    $statusClass = 'bg-primary';
                    $statusIconClass = 'bi-check2-circle';
                    $canStart = false;
                    } elseif ($now->lt($startDT)) {
                    $status = 'Upcoming';
                    $statusClass = 'bg-warning';
                    $statusIconClass = 'bi-hourglass-split';
                    $canStart = false;
                    } elseif ($now->between($startDT, $endDT)) {
                    $status = 'Ongoing';
                    $statusClass = 'bg-success';
                    $statusIconClass = 'bi-play-circle';
                    $canStart = true;
                    } else {
                    $status = 'Ended';
                    $statusClass = 'bg-secondary';
                    $statusIconClass = 'bi-slash-circle';
                    $canStart = false;
                    }

                    $noQuestions = ($exam->questions_count ?? 0) === 0;
                    @endphp

                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-0 shadow-sm">

                            {{-- Colored top accent bar by status --}}
                            <div class="rounded-top {{ $statusClass }}" style="height: 5px;"></div>

                            <div class="card-body d-flex flex-column">

                                <div class="status mb-3">
                                    <span class="badge rounded-pill {{ $statusClass }}">
                                        <i class="bi {{ $statusIconClass }} me-1"></i>{{ $status }}
                                    </span>
                                </div>

                                <div class="title mb-3">
                                    <h5 class="card-title mb-0 fw-semibold p-0">{{ $exam->course->course_title }} <small>[{{ $exam->course->course_code }}]</small></h5>
                                    <small class="text-muted">{{ $exam->exam_title }} - [{{ $exam->exam_code }}]</small>
                                </div>

                                {{-- Exam Type --}}
                                <div class="exam-type mb-3 d-flex flex-wrap gap-2">
                                    @if($exam->exam_type)
                                    <span class="badge bg-light text-dark border align-self-start">
                                        <i class="bi bi-tag me-1"></i>{{ $exam->exam_type }}
                                    </span>
                                    @else
                                    <span class="badge bg-light text-dark border align-self-start">
                                        <i class="bi bi-tag me-1"></i>General
                                    </span>
                                    @endif

                                    @if($noQuestions)
                                    <span class="badge bg-warning text-dark align-self-start">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Questions Not Ready
                                    </span>
                                    @endif
                                </div>

                                {{-- Meta Info --}}
                                <ul class="list-unstyled small text-muted mb-3 flex-grow-1">
                                    <li class="mb-1">
                                        <i class="bi bi-calendar3 me-2"></i>
                                        {{ $exam->exam_date->format('d F Y') }}
                                    </li>
                                    <li class="mb-1">
                                        <i class="bi bi-clock me-2"></i>
                                        {{ $exam->start_time?->format('h:i A') ?? 'N/A' }} - {{ $exam->end_time?->format('h:i A') ?? 'N/A' }}
                                    </li>
                                    <li class="mb-1">
                                        <i class="bi bi-stopwatch me-2"></i>
                                        {{ $exam->exam_duration_min ?? 0 }} mins
                                    </li>
                                    <li class="mb-1">
                                        <i class="bi bi-patch-check me-2"></i>
                                        <strong>{{ intval($exam->total_marks) }}</strong> marks
                                    </li>
                                    <li>
                                        <i class="bi bi-question-circle me-2"></i>
                                        <strong>{{ $exam->total_questions ?? 0 }}</strong> questions
                                    </li>
                                </ul>

                                {{-- Action Buttons --}}
                                <div class="d-flex gap-2 mt-auto">
                                    <a href="{{ route('student.myExams.show', $exam->id) }}" class="btn btn-sm btn-outline-theme flex-fill w-50">
                                        <i class="bi bi-eye me-1"></i>Details
                                    </a>
                                    @if($noQuestions)
                                    <button class="btn btn-sm btn-outline-danger flex-fill w-50" disabled>
                                        <i class="bi bi-slash-circle me-1"></i>Not Available
                                    </button>
                                    @elseif($isSubmitted)
                                    <a href="{{ route('student.myExams.result', $exam->id) }}" class="btn btn-sm btn-outline-primary flex-fill w-50">
                                        <i class="bi bi-bar-chart me-1"></i>View Result
                                    </a>
                                    @elseif($canStart)
                                    <a href="{{ route('student.myExams.rule', $exam->id) }}" class="btn btn-sm btn-success flex-fill w-50">
                                        <i class="bi bi-play-fill me-1"></i>Start Exam
                                    </a>
                                    @elseif($status === 'Upcoming')
                                    <button class="btn btn-sm btn-outline-warning flex-fill w-50" disabled>
                                        <i class="bi bi-hourglass-split me-1"></i>Not yet open
                                    </button>
                                    @else
                                    <a href="{{ route('student.myExams.result', $exam->id) }}" class="btn btn-sm btn-outline-primary flex-fill w-50">
                                        <i class="bi bi-bar-chart me-1"></i>View Result
                                    </a>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    @empty
                    <div class="col-12">
                        <div class="alert alert-info d-flex align-items-center gap-2" role="alert">
                            <i class="bi bi-info-circle-fill"></i>
                            <span>You have no exams assigned yet. Please check back later.</span>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- ==================== TABLE VIEW ==================== --}}
            <div id="listView" style="display: none;">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <table class="table table-sm small mb-0" id="myExamTable">
                            <thead class="small">
                                <tr>
                                    <th>Course</th>
                                    <th>Exam</th>
                                    <th>Type</th>
                                    <th>Date & Time</th>
                                    <th>Marks</th>
                                    <th>Status</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @forelse ($myExamList as $index => $exam)

                                @php
                                $now = now();
                                $startDT = \Carbon\Carbon::parse(
                                $exam->exam_date->toDateString() . ' ' . \Carbon\Carbon::parse($exam->start_time)->format('H:i:s')
                                );
                                $endDT = \Carbon\Carbon::parse(
                                $exam->exam_date->toDateString() . ' ' . \Carbon\Carbon::parse($exam->end_time)->format('H:i:s')
                                );

                                $isSubmitted = in_array($exam->id, $submittedExamIds);

                                if ($isSubmitted) {
                                $status = 'Submitted';
                                $statusClass = 'bg-primary';
                                $statusIconClass = 'bi-check2-circle';
                                $canStart = false;
                                } elseif ($now->lt($startDT)) {
                                $status = 'Upcoming';
                                $statusClass = 'bg-warning';
                                $statusIconClass = 'bi-hourglass-split';
                                $canStart = false;
                                } elseif ($now->between($startDT, $endDT)) {
                                $status = 'Ongoing';
                                $statusClass = 'bg-success';
                                $statusIconClass = 'bi-play-circle';
                                $canStart = true;
                                } else {
                                $status = 'Ended';
                                $statusClass = 'bg-secondary';
                                $statusIconClass = 'bi-slash-circle';
                                $canStart = false;
                                }

                                $noQuestions = ($exam->questions_count ?? 0) === 0;
                                @endphp

                                <tr>
                                    <td style="border-left: 4px solid var(--bs-{{ $isSubmitted ? 'primary' : ($status === 'Upcoming' ? 'warning' : ($status === 'Ongoing' ? 'success' : 'secondary')) }});">
                                        <div class="fw-bold">{{ $exam->course->course_title }}</div>
                                        <small class="text-muted">[{{ $exam->course->course_code }}]</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $exam->exam_title }}</div>
                                        <small class="text-muted">[{{ $exam->exam_code }}]</small>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge bg-light text-dark border align-self-start">
                                                <i class="bi bi-tag me-1"></i>{{ $exam->exam_type ?? 'General' }}
                                            </span>
                                            @if($noQuestions)
                                            <span class="badge bg-warning text-dark align-self-start">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Questions Not Ready
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="fw-semibold"><i class="bi bi-calendar3 me-1 text-muted"></i>{{ $exam->exam_date->format('d M Y') }}</div>
                                        <div class="text-muted small">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $exam->start_time?->format('h:i A') ?? 'N/A' }} - {{ $exam->end_time?->format('h:i A') ?? 'N/A' }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bi bi-stopwatch me-1"></i>
                                            {{ $exam->exam_duration_min ?? 0 }} mins
                                        </div>
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="fw-semibold"><i class="bi bi-patch-check me-1 text-muted"></i>{{ intval($exam->total_marks) }} marks</div>
                                        <div class="text-muted small">
                                            <i class="bi bi-question-circle me-1"></i>
                                            {{ $exam->total_questions ?? 0 }} questions
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ $statusClass }}">
                                            <i class="bi {{ $statusIconClass }} me-1"></i>{{ $status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('student.myExams.show', $exam->id) }}" class="btn btn-sm btn-outline-theme" title="Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($noQuestions)
                                            <button class="btn btn-sm btn-outline-danger" disabled title="Not Available">
                                                <i class="bi bi-slash-circle"></i>
                                            </button>
                                            @elseif($isSubmitted)
                                            <a href="{{ route('student.myExams.result', $exam->id) }}" class="btn btn-sm btn-outline-primary" title="View Result">
                                                <i class="bi bi-bar-chart"></i>
                                            </a>
                                            @elseif($canStart)
                                            <a href="{{ route('student.myExams.rule', $exam->id) }}" class="btn btn-sm btn-success" title="Start Exam">
                                                <i class="bi bi-play-fill"></i>
                                            </a>
                                            @elseif($status === 'Upcoming')
                                            <button class="btn btn-sm btn-outline-warning" disabled title="Not yet open">
                                                <i class="bi bi-hourglass-split"></i>
                                            </button>
                                            @else
                                            <a href="{{ route('student.myExams.result', $exam->id) }}" class="btn btn-sm btn-outline-primary" title="View Result">
                                                <i class="bi bi-bar-chart"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="alert alert-info d-flex align-items-center gap-2 mb-0">
                                            <i class="bi bi-info-circle-fill"></i>
                                            <span>You have no exams assigned yet. Please check back later.</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>

        </div>
    </div>

</section>

@endsection




@section('scripts')


{{-- DataTable Script --}}
@if ($myExamList->count())
<script>
    const table = new DataTable('#myExamTable', {
        paging: true
        , pageLength: 10
        , lengthMenu: [5, 10, 25, 50, 100]
        , lengthChange: true
        , scrollX: true
    });

</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gridViewBtn = document.getElementById('gridViewBtn');
        const listViewBtn = document.getElementById('listViewBtn');
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');

        // Restore saved preference
        const savedView = localStorage.getItem('examViewPreference') || 'grid';
        if (savedView === 'list') {
            gridView.style.display = 'none';
            listView.style.display = 'block';
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');
        }

        gridViewBtn.addEventListener('click', function() {
            gridView.style.display = 'block';
            listView.style.display = 'none';
            gridViewBtn.classList.add('active');
            listViewBtn.classList.remove('active');
            localStorage.setItem('examViewPreference', 'grid');
        });

        listViewBtn.addEventListener('click', function() {
            gridView.style.display = 'none';
            listView.style.display = 'block';
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');
            localStorage.setItem('examViewPreference', 'list');
        });
    });

</script>

{{-- Start Exam Modal Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startExamBtns = document.querySelectorAll('.startExamBtn');

        startExamBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const examId = this.dataset.id;
                const startRoute = "{{ route('student.myExams.start', ['exam' => ':id']) }}";
                document.getElementById('startExamForm').action = startRoute.replace(':id', examId);
            });
        });
    });
</script>

@endsection

