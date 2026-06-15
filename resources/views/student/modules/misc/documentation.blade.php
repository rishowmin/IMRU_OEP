@extends('student.layouts.app')
@section('title', 'Documentation')

@section('content')

<div class="pagetitle mb-0">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-header-left">
                            <h1>
                                <i class="bi bi-question-circle"></i>
                                <span class="ms-1">@yield('title')</span>
                            </h1>
                            <nav style="--bs-breadcrumb-divider: '•';">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                    <li class="breadcrumb-item active">@yield('title')</li>
                                </ol>
                            </nav>
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
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <h5 class="card-title fw-semibold text-theme mb-2 p-0">How to use the student portal</h5>
                    <p class="text-muted small mb-4">This guide explains the main student features available after signing in.</p>

                    <div class="list-group list-group-flush border rounded">

                        {{-- 1. Sign In --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-box-arrow-in-right fw-bold text-primary me-2"></i>Student Login
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">Go to the student login page and login with your student email and password.</p>
                            <ul class="small text-muted mb-0 ps-5">
                                <li>Visit <code>/academic/login</code> to enter your credentials.</li>
                                <li>If you forgot your password, use the <strong>Forgot password</strong> link to reset it.</li>
                            </ul>
                        </div>

                        {{-- 2. Dashboard --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-grid-fill fw-bold text-success me-2"></i>Dashboard
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">After login, the dashboard shows your key student statistics and quick navigation links.</p>
                            <div class="row g-2 ps-4">
                                <div class="col-sm-6 col-md-3">
                                    <div class="card border border-theme p-2 mb-2 shadow-sm">
                                        <small class="text-muted mb-1" style="font-size: 11px;">Enrolled courses</small>
                                        <p class="fw-semibold small mb-0">Active programs</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="card border border-theme p-2 mb-2 shadow-sm">
                                        <small class="text-muted mb-1" style="font-size: 11px;">Upcoming exams</small>
                                        <p class="fw-semibold small mb-0">Scheduled soon</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="card border border-theme p-2 mb-2 shadow-sm">
                                        <small class="text-muted mb-1" style="font-size: 11px;">Exams attempted</small>
                                        <p class="fw-semibold small mb-0">Completed tests</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="card border border-theme p-2 mb-2 shadow-sm">
                                        <small class="text-muted mb-1" style="font-size: 11px;">Average score</small>
                                        <p class="fw-semibold small mb-0">Performance rate</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. My Exams --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-clipboard-fill fw-bold text-warning me-2"></i>My Exams
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">Use the My exams section to view available exams, read exam details, and start tests.</p>
                            <ul class="small text-muted mb-0 ps-5">
                                <li>Select an exam to see the description, date, and time limit.</li>
                                <li>Read the exam rules carefully before starting.</li>
                                <li>Start the exam from the <strong>Answer sheet</strong> button.</li>
                                <li>Answer all questions and submit your responses when finished.</li>
                            </ul>
                        </div>

                        {{-- 4. Proctoring --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-shield-fill-check fw-bold text-danger me-2"></i>Exam Proctoring
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">While an exam is in progress, the system monitors activity to preserve exam integrity.</p>
                            <div class="alert alert-danger d-flex align-items-start gap-2 py-2 ms-4 mb-0">
                                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                                <span class="small">Avoid switching tabs or copying content during the exam. The portal captures tab changes, clipboard events, and webcam activity.</span>
                            </div>
                        </div>

                        {{-- 5. My Results --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-trophy-fill fw-bold text-primary me-2"></i>My Results
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">After exams are graded, visit My results to review your scores and feedback.</p>
                            <ul class="small text-muted mb-0 ps-5">
                                <li>Open a completed exam to see the full result details.</li>
                                <li>Compare your performance across all attempted exams.</li>
                            </ul>
                        </div>

                        {{-- 6. My Profile --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-person-circle fw-bold text-info me-2"></i>My Profile
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">Access My profile to view and update your personal information.</p>
                            <ul class="small text-muted mb-0 ps-5">
                                <li>Update your name, email, phone, and other details.</li>
                                <li>Save changes to keep your profile information current.</li>
                            </ul>
                        </div>

                        {{-- 7. Help & Support --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-question-circle-fill fw-bold text-secondary me-2"></i>Help & Support
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">Use the profile menu to access this documentation or contact your institution administrator.</p>
                            <ul class="small text-muted mb-0 ps-5">
                                <li>The documentation link is available from your profile dropdown menu.</li>
                                <li>Use <strong>Logout</strong> when you finish your session.</li>
                            </ul>
                        </div>

                    </div>

                    <div class="alert alert-info d-flex align-items-start gap-2 mt-3 mb-0">
                        <i class="bi bi-info-circle-fill mt-1"></i>
                        <span class="small"><strong>Tip:</strong> Always complete exams in one session and follow the exam rules to avoid proctoring flags.</span>
                    </div>

                </div>
            </div>
        </div>
    </div>

</section>

@endsection
