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
                            <a href="{{ route('admin.academic.proctoring.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Back
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
    $webcamCount = $attempt->webcamLogs->where('ai_flag', '!=', 'clear')->count();
    $highCount = $attempt->proctoringEvents->where('severity', 'high')->count();

    $score = ($tabCount * 5) + ($clipCount * 10) + ($webcamCount * 20) + ($highCount * 15);
    $risk = $score >= 60 ? 'high' : ($score >= 30 ? 'medium' : 'low');

    $riskColor = match($risk) {
    'high' => 'danger',
    'medium' => 'warning',
    default => 'success',
    };
    @endphp

    {{-- Student & Risk Summary --}}
    <div class="row g-3 mb-3">

        {{-- Student Info --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-center gap-3">




                        {{-- <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                            <i class="bi bi-person fs-4 text-primary"></i>
                        </div> --}}

                        <div class="avatar_sec d-flex align-items-center gap-3">

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
                                <img id="nav-photo-preview" src="{{ $studentInfo?->profile_photo ? asset('storage/profile_photo/student/' . $studentInfo->profile_photo) : '' }}" alt="Profile Photo" style="{{ $studentInfo?->profile_photo ? '' : 'display:none;' }} width: 60px; height:60px; max-height:60px;">

                                {{-- Initials fallback --}}
                                @if(!$studentInfo?->profile_photo)
                                <div class="photo-initials" style="background-color:{{ $bgColor }}; width: 60px; height:60px;">
                                    <span style="font-size: 20px;">{{ $initials ?: '?' }}</span>
                                </div>
                                @endif
                            </div>
                            
                        </div>

                        <div>
                            <h5 class="fw-bold mb-0">{{ $attempt->student->first_name.' '.$attempt->student->last_name ?? 'N/A' }}</h5>
                            <small class="text-muted">{{ $attempt->student->email ?? '' }}</small> <br>
                            <small class="text-muted">ID # {{ $attempt->student->info->student_id_no ?? '' }}</small>
                        </div>
                        <div class="ms-auto text-end">
                            <div class="fw-semibold text-dark">{{ $attempt->exam->exam_title ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $attempt->exam->exam_code ?? '' }}</small>
                            <div class="mt-1">
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $attempt->started_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                    –
                                    {{ $attempt->submitted_at?->format('h:i A') ?? 'Ongoing' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Risk Score --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100 border-{{ $riskColor }} border-start border-4">
                <div class="card-body px-4 py-3 d-flex align-items-center gap-3">
                    <div>
                        <small class="text-muted d-block mb-1">Overall Risk Level</small>
                        <span class="badge bg-{{ $riskColor }} fs-6 px-4 py-2 rounded-pill">
                            <i class="bi bi-shield-{{ $risk === 'low' ? 'check' : 'exclamation' }} me-1"></i>
                            {{ ucfirst($risk) }} Risk
                        </span>
                        <div class="mt-2">
                            <small class="text-muted">Score: <strong>{{ $score }}</strong> / 100+</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-warning">{{ $tabCount }}</div>
                <small class="text-muted"><i class="bi bi-arrow-left-right me-1"></i>Tab Switches</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-info">{{ $clipCount }}</div>
                <small class="text-muted"><i class="bi bi-clipboard-x me-1"></i>Clipboard Attempts</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="fs-2 fw-bold text-secondary">{{ $webcamCount }}</div>
                <small class="text-muted"><i class="bi bi-camera-video-off me-1"></i>Webcam Flags</small>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm text-center py-3">
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
            <div class="card border-0 shadow-sm mb-3">
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

        {{-- RIGHT: Webcam Snapshots --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-camera-video me-2 text-secondary"></i>
                        Webcam Snapshots
                        <span class="badge bg-secondary ms-2">{{ $attempt->webcamLogs->count() }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if($attempt->webcamLogs->count())
                    <div class="row g-2">
                        @foreach($attempt->webcamLogs as $snap)
                        <div class="col-6">
                            <div class="position-relative">
                                <img src="{{ asset('storage/proctoring/webcam/' . $snap->image_url) }}" class="img-fluid rounded border w-100" style="height:100px;object-fit:cover;" alt="Snapshot">
                                <span class="position-absolute top-0 start-0 m-1 badge bg-{{ $snap->ai_flag === 'clear' ? 'success' : 'danger' }} rounded-pill" style="font-size:10px;">
                                    {{ ucfirst(str_replace('_', ' ', $snap->ai_flag)) }}
                                </span>
                                <div class="text-center mt-1">
                                    <small class="text-muted" style="font-size:10px;">
                                        {{ $snap->captured_at?->format('h:i:s A') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-camera-video-off fs-1 d-block mb-2 opacity-50"></i>
                        <p class="mb-0 small">No snapshots recorded.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</section>

@endsection

