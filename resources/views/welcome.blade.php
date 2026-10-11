<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>
    <meta content="Secure online examination portal for academic admissions and professional recruitment assessments." name="description">
    <meta content="online examination, proctored exams, recruitment assessment, admission test, IMRU" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/admin/img/brand/icon_wh.png') }}" rel="icon">
    <link href="{{ asset('assets/admin/img/branding/favicons/apple-icon.png') }}" rel="apple-touch-icon">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/admin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <!-- Main CSS Files -->
    <link href="{{ asset('assets/frontend/css/style.css') }}" rel="stylesheet">
</head>

<body class="antialiased">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('assets/admin/img/brand/logo_wh.png') }}" alt="IMRU" onerror="this.style.display='none'">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-4 gap-2 py-3 py-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how">How it works</a></li>
                    <li class="nav-item"><a class="nav-link" href="#roles">Roles</a></li>
                    <li class="nav-item"><a class="nav-link" href="#security">Security</a></li>

                    @if (Route::has('login'))
                    <li class="nav-item d-flex flex-wrap gap-2 mt-2 mt-lg-0">

                        @auth('student')
                            <a href="{{ route('student.dashboard') }}" class="btn btn-imru btn-sm px-3 rounded-2">
                                <i class="bi bi-person me-1"></i>Student Dashboard
                            </a>
                        @endauth

                        @auth('teacher')
                            <a href="{{ route('teacher.dashboard') }}" class="btn btn-imru btn-sm px-3 rounded-2">
                                <i class="bi bi-person-badge me-1"></i>Teacher Dashboard
                            </a>
                        @endauth

                        @guest('student')
                            @guest('teacher')
                                <a href="{{ route('academic.login') }}" class="btn btn-imru-outline btn-sm px-3 rounded-2">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Academic Login
                                </a>

                                @if (Route::has('register'))
                                <a href="{{ route('corporate.login') }}" class="btn btn-imru btn-sm px-3 rounded-2">
                                    <i class="bi bi-building me-1"></i>Corporate Login
                                </a>
                                @endif
                            @endguest
                        @endguest

                    </li>
                    @endif

                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-section text-center">
        <div class="container hero-content">
            <span class="hero-eyebrow"><span class="dot"></span>Online Examination Portal</span>
            <h1 class="mb-0">Run exams that are <span class="accent">fair</span>, <span class="accent">secure</span>, and effortless to grade</h1>
            <p class="lead">A proctored, scalable assessment platform for admissions and recruitment — live monitoring, automated scoring, and results your candidates can trust.</p>
            <div class="hero-actions d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('academic.login') }}" class="btn btn-imru-outline px-4 py-2 rounded-2">Academic Login</a>
                <a href="{{ route('corporate.login') }}" class="btn btn-imru px-4 py-2 rounded-2">Corporate Login</a>
            </div>

            <!-- Mock exam interface signature element -->
            <div class="hero-panel">
                <div class="exam-card">
                    <div class="exam-card-head">
                        <div class="exam-dots">
                            <span style="background:#ff5f57;"></span>
                            <span style="background:#febc2e;"></span>
                            <span style="background:#28c840;"></span>
                        </div>
                        <span class="timer-pill"><i class="bi bi-stopwatch me-1"></i>00:18:42 remaining</span>
                    </div>
                    <p class="exam-q">Q14. Which data structure provides O(1) average-time lookups?</p>
                    <div class="exam-option"><span class="bubble"></span> Linked list</div>
                    <div class="exam-option selected"><span class="bubble"></span> Hash table</div>
                    <div class="exam-option"><span class="bubble"></span> Binary search tree</div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS BAR -->
    <div class="stats-bar">
        <div class="container">
            <div class="row text-center gy-4">
                <div class="col-6 col-md-3 stat-col">
                    <div class="stat-num">50k+</div>
                    <div class="stat-lbl">Exams conducted</div>
                </div>
                <div class="col-6 col-md-3 stat-col">
                    <div class="stat-num">99.9%</div>
                    <div class="stat-lbl">Uptime guarantee</div>
                </div>
                <div class="col-6 col-md-3 stat-col">
                    <div class="stat-num">3</div>
                    <div class="stat-lbl">User roles</div>
                </div>
                <div class="col-6 col-md-3 stat-col">
                    <div class="stat-num">100%</div>
                    <div class="stat-lbl">Cloud scalable</div>
                </div>
            </div>
        </div>
    </div>

    <!-- FEATURES -->
    <section class="py-5 bg-white" id="features" style="padding-top:80px;padding-bottom:80px;">
        <div class="container">
            <div class="section-label mb-2">Platform features</div>
            <h2 class="section-heading mb-2">Everything you need for fair, modern exams</h2>
            <p class="section-sub mb-5" style="max-width:540px;">From question creation to result publication — IMRU handles the full examination lifecycle.</p>
            <div class="row g-4">
                <div class="col-sm-6 col-lg-4">
                    <div class="feature-card card h-100 p-4">
                        <div class="feature-icon-wrap mb-3"><i class="bi bi-camera-video"></i></div>
                        <h5>Video proctoring</h5>
                        <p class="mb-0">Real-time webcam monitoring flags suspicious activity and ensures candidate integrity throughout.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="feature-card card h-100 p-4">
                        <div class="feature-icon-wrap mb-3"><i class="bi bi-lock-fill"></i></div>
                        <h5>Tab-lock enforcement</h5>
                        <p class="mb-0">Prevents candidates from switching browser tabs or windows during active examination sessions.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="feature-card card h-100 p-4">
                        <div class="feature-icon-wrap mb-3"><i class="bi bi-stopwatch"></i></div>
                        <h5>Timer controls</h5>
                        <p class="mb-0">Per-section and per-question timers with auto-submit on expiry — fully configurable per exam.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="feature-card card h-100 p-4">
                        <div class="feature-icon-wrap mb-3"><i class="bi bi-check2-circle"></i></div>
                        <h5>Automated grading</h5>
                        <p class="mb-0">Instant scoring for MCQ, true/false, and fill-in-the-blank with manual override for descriptive answers.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="feature-card card h-100 p-4">
                        <div class="feature-icon-wrap mb-3"><i class="bi bi-bar-chart-line"></i></div>
                        <h5>Result dashboards</h5>
                        <p class="mb-0">Visual analytics — score distributions, pass rates, question difficulty, and candidate comparisons.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="feature-card card h-100 p-4">
                        <div class="feature-icon-wrap mb-3"><i class="bi bi-file-earmark-text"></i></div>
                        <h5>Multiple exam formats</h5>
                        <p class="mb-0">MCQ, short answer, descriptive, coding challenges, and mixed-format papers for any assessment type.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="py-5" id="how" style="padding-top:80px;padding-bottom:80px;background:var(--paper);">
        <div class="container">
            <div class="section-label mb-2">How it works</div>
            <h2 class="section-heading mb-5">From setup to results in four steps</h2>
            <div class="row text-center gy-4">
                <div class="col-6 col-md-3 step-item">
                    <div class="step-circle">01</div>
                    <div class="step-connector d-none d-md-block"></div>
                    <h6>Create exam</h6>
                    <p>Admin builds question bank, sets timer and rules</p>
                </div>
                <div class="col-6 col-md-3 step-item">
                    <div class="step-circle">02</div>
                    <div class="step-connector d-none d-md-block"></div>
                    <h6>Invite candidates</h6>
                    <p>Send secure access links with credentials</p>
                </div>
                <div class="col-6 col-md-3 step-item">
                    <div class="step-circle">03</div>
                    <div class="step-connector d-none d-md-block"></div>
                    <h6>Conduct exam</h6>
                    <p>Live proctoring and tab-lock keeps it fair</p>
                </div>
                <div class="col-6 col-md-3 step-item">
                    <div class="step-circle">04</div>
                    <h6>View results</h6>
                    <p>Auto-graded reports published instantly</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ROLES -->
    <section class="py-5 bg-white" id="roles" style="padding-top:80px;padding-bottom:80px;">
        <div class="container">
            <div class="section-label mb-2">User roles</div>
            <h2 class="section-heading mb-5">Built for every stakeholder</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="role-card card h-100 p-4">
                        <span class="badge badge-blue-custom mb-3 rounded-pill px-3 py-1" style="font-size:11px;">SUPER ADMIN</span>
                        <h5 class="mb-3">Platform administrator</h5>
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                            <li class="small text-secondary"><span class="role-dot"></span>Manage organizations and users</li>
                            <li class="small text-secondary"><span class="role-dot"></span>System configuration and billing</li>
                            <li class="small text-secondary"><span class="role-dot"></span>Platform-wide analytics</li>
                            <li class="small text-secondary"><span class="role-dot"></span>Access control and permissions</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="role-card card h-100 p-4">
                        <span class="badge mb-3 rounded-pill px-3 py-1" style="font-size:11px;background:#e1f5ee;color:#0f6e56;font-weight:600;">EXAMINER</span>
                        <h5 class="mb-3">University / HR team</h5>
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                            <li class="small text-secondary"><span class="role-dot"></span>Create and schedule exams</li>
                            <li class="small text-secondary"><span class="role-dot"></span>Build and manage question bank</li>
                            <li class="small text-secondary"><span class="role-dot"></span>Monitor live sessions</li>
                            <li class="small text-secondary"><span class="role-dot"></span>Review and publish results</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="role-card card h-100 p-4">
                        <span class="badge mb-3 rounded-pill px-3 py-1" style="font-size:11px;background:#faeeda;color:#854f0b;font-weight:600;">CANDIDATE</span>
                        <h5 class="mb-3">Student / Applicant</h5>
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                            <li class="small text-secondary"><span class="role-dot"></span>Register and verify identity</li>
                            <li class="small text-secondary"><span class="role-dot"></span>Attempt assigned exams</li>
                            <li class="small text-secondary"><span class="role-dot"></span>View personal scorecards</li>
                            <li class="small text-secondary"><span class="role-dot"></span>Download result certificates</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECURITY -->
    <section class="security-section py-5" id="security" style="padding-top:80px;padding-bottom:80px;">
        <div class="container">
            <div class="section-label mb-2">Security &amp; integrity</div>
            <h2 class="text-white mb-2" style="font-weight:600;">Enterprise-grade trust built in</h2>
            <p class="mb-5" style="color:rgba(255,255,255,0.55);max-width:540px;">Every feature is designed with exam integrity at its core — because results matter.</p>
            <div class="row g-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="sec-card p-4 h-100">
                        <div class="sec-icon"><i class="bi bi-shield-lock"></i></div>
                        <h5 class="mb-2">End-to-end encryption</h5>
                        <p class="mb-0">All exam data — questions, answers, and results — is encrypted in transit and at rest.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="sec-card p-4 h-100">
                        <div class="sec-icon"><i class="bi bi-cpu"></i></div>
                        <h5 class="mb-2">AI anomaly detection</h5>
                        <p class="mb-0">Machine learning flags unusual answer patterns, copy-paste events, and behaviour anomalies.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="sec-card p-4 h-100">
                        <div class="sec-icon"><i class="bi bi-shuffle"></i></div>
                        <h5 class="mb-2">Question randomisation</h5>
                        <p class="mb-0">Each candidate receives a unique question and answer order to prevent collusion.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="sec-card p-4 h-100">
                        <div class="sec-icon"><i class="bi bi-journal-text"></i></div>
                        <h5 class="mb-2">Audit trail</h5>
                        <p class="mb-0">Every action — login, submission, review — is logged with timestamp and IP for compliance.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section text-center text-white py-5" style="padding-top:90px;padding-bottom:90px;">
        <div class="container py-3">
            <h2 class="mb-3">Ready to modernise your assessments?</h2>
            <p class="mb-4" style="opacity:0.92;max-width:520px;margin:0 auto 28px;">Join universities and companies using IMRU to run fair, scalable, and trusted exams.</p>
            <button class="btn btn-light fw-500 px-4 py-2 rounded-2" style="color:#185fa5;font-weight:600;" onclick="sendPrompt('How do I get started with IMRU Online Examination Portal?')">Request a demo ↗</button>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="py-4 text-center">
        <div class="container">
            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                <img src="{{ asset('assets/admin/img/brand/logo_wh.png') }}" height="64" alt="" onerror="this.style.display='none'">
            </div>
            <p class="mb-2" style="color:rgba(255,255,255,0.45);font-size:13px;">Online Examination Portal — Built for academic and recruitment excellence.</p>
            <div class="d-flex justify-content-center gap-3" style="font-size:13px;">
                <a href="#features" style="color:rgba(255,255,255,0.4);">Features</a>
                <a href="#how" style="color:rgba(255,255,255,0.4);">How it works</a>
                <a href="#roles" style="color:rgba(255,255,255,0.4);">Roles</a>
                <a href="#security" style="color:rgba(255,255,255,0.4);">Security</a>
            </div>
            <p class="mt-3 mb-0" style="color:rgba(255,255,255,0.3);font-size:12px;">&copy; {{ date('Y') }} IMRU. All rights reserved.</p>
        </div>
    </footer>

    <script src="{{ asset('assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
