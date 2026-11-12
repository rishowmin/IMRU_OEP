@extends('admin.layouts.app')
@section('title', 'Proctoring Report')

@section('content')

<div class="pagetitle mb-0">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-header-left">
                            <h1>
                                <i class="bi bi-file-earmark-bar-graph"></i>
                                <span class="ms-1">@yield('title')</span>
                            </h1>
                            <nav style="--bs-breadcrumb-divider: '•';">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.academic.proctoring.index') }}">Proctoring</a>
                                    </li>
                                    <li class="breadcrumb-item active">Report</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="card-header-right">
                            <a href="{{ route('admin.academic.proctoring.index') }}" class="btn btn-sm btn-outline-theme">
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
    @php
    $tabCount = $attempt->tabSwitchLogs->count();
    $clipCount = $attempt->clipboardLogs->count();
    $highCount = $attempt->proctoringEvents->where('severity', 'high')->count();

    $score = ($tabCount * 5) + ($clipCount * 10) + ($highCount * 15);
    $risk = $score >= 60 ? 'high' : ($score >= 30 ? 'medium' : 'low');

    $riskColor = match($risk) {
    'high' => 'danger',
    'medium' => 'warning',
    default => 'success',
    };
    @endphp

    <div class="row g-3 mb-3">

        {{-- Student Info --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 px-4 py-1">

                        <div class="avatar_sec d-flex gap-3">

                            @php
                            $student = $attempt->student;
                            $studentInfo = $student->info;
                            $firstName = $student->first_name ?? '';
                            $lastName = $student->last_name ?? '';
                            $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                            $colors = ['#4e73df', '#1cc88a', '#36b9cc', '#e74a3b', '#f6c23e', '#6f42c1', '#fd7e14', '#20c9a6'];
                            $bgColor = $colors[abs(crc32($firstName . $lastName)) % count($colors)];
                            @endphp

                            <div class="img-sec">
                                {{-- Preview image --}}
                                <img id="nav-photo-preview" src="{{ $studentInfo?->profile_photo ? asset('assets/storage/profile_photo/student/' . $studentInfo->profile_photo) : '' }}" alt="Profile Photo" style="{{ $studentInfo?->profile_photo ? '' : 'display:none;' }} width: 60px; height:60px; max-height:60px;">

                                {{-- Initials fallback --}}
                                @if(!$studentInfo?->profile_photo)
                                <div class="photo-initials" style="background-color:{{ $bgColor }}; width: 60px; height:60px;">
                                    <span style="font-size: 20px;">{{ $initials ?: '?' }}</span>
                                </div>
                                @endif
                            </div>

                            <div>
                                <h5 class="fw-bold mb-0">{{ $attempt->student->first_name.' '.$attempt->student->last_name ?? 'N/A' }}</h5>
                                {{-- <small class="text-muted">{{ $attempt->student->email ?? '' }}</small> <br>
                                <small class="text-muted">ID # {{ $attempt->student->info->student_id_no ?? '' }}</small> --}}
                                <ul class="small text-muted list-unstyled mb-0">
                                    <li>{{ $attempt->student->email ?? '' }}</li>
                                    <li class="mb-2">ID # {{ $attempt->student->info->student_id_no ?? '' }}</li>
                                    <li>
                                        <span class="me-1"><i class="bi bi-laptop"></i></span>
                                        {{ $attempt->device_name ?? 'Not recorded' }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="ms-auto text-end">
                            <div class="fw-semibold text-dark">
                                <span>{{ $attempt->exam->exam_title ?? 'N/A' }}</span>
                                [<span class="text-muted small">{{ $attempt->exam->exam_code ?? '' }}</span>]
                            </div>

                            <ul class="small text-muted list-unstyled mb-0">
                                <li>
                                    <span class="me-1"><i class="bi bi-calendar3"></i></span>
                                    {{ $attempt->started_at?->format('d M Y') ?? 'N/A' }}
                                </li>
                                <li class="mb-2">
                                    <span class="me-1"><i class="bi bi-clock"></i></span>
                                    {{ $attempt->submitted_at?->format('h:i A') ?? 'Ongoing' }}
                                </li>
                                <li>
                                    <span class="me-1"><i class="bi bi-globe"></i></span>
                                    {{ $attempt->ip_address ?? 'Not recorded' }}
                                </li>
                            </ul>
                        </div>
                </div>
            </div>
        </div>

        {{-- Risk Score --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100 border-{{ $riskColor }} border-start border-4">
                <div class="card-body px-4 py-1 d-flex align-items-center justify-content-between gap-2">
                    <div>
                        <small class="text-muted fw-bold d-block mb-2">Overall Risk Level</small>
                        <span class="badge bg-{{ $riskColor }} px-3 py-2 rounded-pill">
                            {{ ucfirst($risk) }} Risk
                        </span>
                        <div>
                            <small class="text-muted">Score: <strong>{{ $score }}</strong> / 100+</small>
                        </div>
                    </div>
                    <div>
                        <h1 class="display-3 mb-0"><i class="bi bi-shield-{{ $risk === 'low' ? 'check' : 'exclamation' }} text-{{ $riskColor }}"></i></h1>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="col-4 col-lg-4">
            <div class="card border-0 shadow-sm text-center py-3 mb-0">
                <div class="fs-2 fw-bold text-warning">{{ $tabCount }}</div>
                <small class="text-muted"><i class="bi bi-arrow-left-right me-1"></i>Tab Switches</small>
            </div>
        </div>
        <div class="col-4 col-lg-4">
            <div class="card border-0 shadow-sm text-center py-3 mb-0">
                <div class="fs-2 fw-bold text-info">{{ $clipCount }}</div>
                <small class="text-muted"><i class="bi bi-clipboard-x me-1"></i>Clipboard Attempts</small>
            </div>
        </div>
        <div class="col-4 col-lg-4">
            <div class="card border-0 shadow-sm text-center py-3 mb-0">
                <div class="fs-2 fw-bold text-danger">{{ $highCount }}</div>
                <small class="text-muted"><i class="bi bi-exclamation-triangle me-1"></i>High Severity</small>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- LEFT: Event Timeline --}}
        <div class="col-lg-8">

            {{-- Tab Switch Logs --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-arrow-left-right me-2 text-warning"></i>
                        Tab Switch Logs
                        <span class="badge bg-warning text-dark ms-2">{{ $tabCount }}</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($attempt->tabSwitchLogs->count())
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">#</th>
                                    <th>Switched At</th>
                                    <th>Returned At</th>
                                    <th>Duration</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attempt->tabSwitchLogs as $i => $log)
                                <tr>
                                    <td class="px-4">{{ $i + 1 }}</td>
                                    <td><small>{{ $log->switched_at?->format('h:i:s A') }}</small></td>
                                    <td><small>{{ $log->returned_at?->format('h:i:s A') ?? '—' }}</small></td>
                                    <td>
                                        @if($log->duration_ms)
                                        <span class="badge bg-{{ $log->duration_ms > 30000 ? 'danger' : 'secondary' }} bg-opacity-10 text-{{ $log->duration_ms > 30000 ? 'danger' : 'secondary' }} border border-{{ $log->duration_ms > 30000 ? 'danger' : 'secondary' }}">
                                            {{ round($log->duration_ms / 1000, 1) }}s
                                        </span>
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-warning text-dark">{{ $log->switch_count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center py-3 mb-0">No tab switches recorded.</p>
                    @endif
                </div>
            </div>

            {{-- Clipboard Logs --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-clipboard-x me-2 text-info"></i>
                        Clipboard Activity
                        <span class="badge bg-info text-dark ms-2">{{ $clipCount }}</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($attempt->clipboardLogs->count())
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">#</th>
                                    <th>Action</th>
                                    <th>Attempted At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attempt->clipboardLogs as $i => $log)
                                <tr>
                                    <td class="px-4">{{ $i + 1 }}</td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                            <i class="bi bi-{{ $log->action_type === 'copy' ? 'files' : ($log->action_type === 'paste' ? 'clipboard' : 'scissors') }} me-1"></i>
                                            {{ ucfirst($log->action_type) }}
                                        </span>
                                    </td>
                                    <td><small>{{ $log->attempted_at?->format('h:i:s A') }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center py-3 mb-0">No clipboard activity recorded.</p>
                    @endif
                </div>
            </div>

            {{-- Proctoring Events --}}
            <div class="card border-0 shadow-sm mb-0">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-exclamation-triangle me-2 text-danger"></i>
                        All Proctoring Events
                        <span class="badge bg-danger ms-2">{{ $attempt->proctoringEvents->count() }}</span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($attempt->proctoringEvents->count())
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">#</th>
                                    <th>Event</th>
                                    <th>Severity</th>
                                    <th>Detected At</th>
                                    <th>Metadata</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attempt->proctoringEvents as $i => $event)
                                @php
                                $sevColor = match($event->severity) {
                                'high' => 'danger',
                                'medium' => 'warning',
                                default => 'secondary',
                                };
                                @endphp
                                <tr>
                                    <td class="px-4">{{ $i + 1 }}</td>
                                    <td>
                                        <span class="badge bg-dark bg-opacity-10 text-dark border border-secondary">
                                            {{ str_replace('_', ' ', ucfirst($event->event_type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $sevColor }} bg-opacity-10 text-{{ $sevColor }} border border-{{ $sevColor }}">
                                            {{ ucfirst($event->severity) }}
                                        </span>
                                    </td>
                                    <td><small>{{ $event->detected_at?->format('h:i:s A') }}</small></td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $event->metadata ? json_encode($event->metadata) : '—' }}
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center py-3 mb-0">No events recorded.</p>
                    @endif
                </div>
            </div>

        </div>

        {{-- Webcam Video --}}
        <div class="col-lg-4">
            @if($attempt->webcamVideo)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold mb-0"><i class="bi bi-record-circle me-2 text-danger"></i>Webcam Recording</h6>
                </div>
                <div class="card-body">
                    <video class="w-100 rounded" controls preload="metadata" src="{{ route('admin.academic.proctoring.video', $attempt) }}">
                        Your browser does not support video playback.
                    </video>
                    <small class="badge badge-secondary text-muted d-block mt-2">
                        {{ $attempt->webcamVideo->started_at?->format('d M Y, h:i:s A') }}
                        · {{ number_format($attempt->webcamVideo->size_bytes / 1048576, 1) }} MB
                        @if(!$attempt->webcamVideo->ended_at)
                        · Recording may be incomplete
                        @endif
                    </small>
                </div>
            </div>
            @endif
        </div>

    </div>
</section>

@endsection
