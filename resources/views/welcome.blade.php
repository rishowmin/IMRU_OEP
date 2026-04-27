<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/admin/img/brand/icon_wh.png') }}" rel="icon">
    <link href="{{ asset('assets/admin/img/branding/favicons/apple-icon.png') }}" rel="apple-touch-icon">

    <link href="{{ asset('assets/admin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <style>
        :root {
            --imru-blue: #29abe2;
            --imru-dark: #1e2d3d;
            --imru-darker: #131f2b;
        }

        body {
            font-family: var(--font-sans, system-ui, sans-serif);
        }

        /* NAV */
        .navbar {
            background: var(--imru-dark) !important;
            min-height: 64px;
        }

        .navbar-brand img {
            height: 60px;
        }

        .navbar-brand span {
            color: #fff;
            font-size: 18px;
            font-weight: 500;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.75) !important;
            font-size: 14px;
        }

        .nav-link:hover {
            color: var(--imru-blue) !important;
        }

        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.3);
        }

        .navbar-toggler-icon {
            filter: invert(1);
        }

        /* HERO */
        .hero-section {
            /* background: linear-gradient(135deg, #1e2d3d 55%, #184060 100%); */
            background-image: url('../../assets/admin/img/hero_banner.png');
            background-size: cover;
            padding: 90px 0 72px;
        }

        .hero-section h1 {
            font-size: 2.2rem;
            font-weight: 500;
            color: #fff;
            line-height: 1.25;
        }

        .hero-section h1 span {
            color: var(--imru-blue);
        }

        .hero-section p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1rem;
            max-width: 540px;
            margin: 0 auto;
        }

        .hero-logo {
            height: 80px;
        }

        /* STATS */
        .stats-bar {
            background: var(--imru-blue);
        }

        .stats-bar .stat-num {
            font-size: 1.7rem;
            font-weight: 500;
            color: #fff;
        }

        .stats-bar .stat-lbl {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.85);
        }

        /* FEATURES */
        .feature-card {
            border-radius: 12px;
            border: 0.5px solid #dee2e6;
            transition: border-color 0.2s;
        }

        .feature-card:hover {
            border-color: var(--imru-blue);
        }

        .feature-icon-wrap {
            width: 44px;
            height: 44px;
            background: rgba(41, 171, 226, 0.12);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-icon-wrap i {
            font-size: 20px;
            color: var(--imru-blue);
        }

        /* STEPS */
        .step-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--imru-blue);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            position: relative;
            z-index: 2;
        }

        .step-connector {
            position: absolute;
            top: 24px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: rgba(41, 171, 226, 0.3);
            z-index: 1;
        }

        /* ROLE CARDS */
        .role-card {
            border-radius: 12px;
            border: 0.5px solid #dee2e6;
        }

        .badge-blue-custom {
            background: rgba(41, 171, 226, 0.15);
            color: #1578a8;
            font-weight: 500;
        }

        .role-dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--imru-blue);
            margin-right: 8px;
        }

        /* SECURITY */
        .security-section {
            background: var(--imru-dark);
        }

        .sec-card {
            background: rgba(255, 255, 255, 0.05);
            border: 0.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }

        .sec-card h5 {
            color: var(--imru-blue);
            font-weight: 500;
        }

        .sec-card p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 13px;
        }

        /* CTA */
        .cta-section {
            background: linear-gradient(135deg, var(--imru-blue) 0%, #185fa5 100%);
        }

        /* FOOTER */
        footer {
            background: var(--imru-darker);
        }

        .btn-imru {
            background: var(--imru-blue);
            color: #fff;
            border: none;
        }

        .btn-imru:hover {
            background: #1e8fc0;
            color: #fff;
        }

        .btn-imru-outline {
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            color: #fff;
            background: transparent;
        }

        .btn-imru-outline:hover {
            border-color: var(--imru-blue);
            color: var(--imru-blue);
            background: transparent;
        }

        .section-label {
            font-size: 12px;
            color: var(--imru-blue);
            font-weight: 500;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

    </style>
</head>
<body class="antialiased">
    {{-- <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
        @if (Route::has('login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            @auth
            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
    @else
    <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Login</a>

    @if (Route::has('register'))
    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
    @endif
    @endauth
    </div>
    @endif

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        @if (Route::has('admin.login'))
        <div class="">
            @auth('admin')
            <a href="{{ url('/admin/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Admin Dashboard</a>
            @else
            <a href="{{ route('admin.login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Admin Login</a>

            @if (Route::has('admin.register'))
            <a href="{{ route('admin.register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Admin Register</a>
            @endif
            @endauth
        </div>
        @endif
    </div>
    </div> --}}




    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('assets/admin/img/brand/logo_wh.png') }}" alt="IMRU icon" onerror="this.style.display='none'">
                {{-- <span>IMRU</span> --}}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3 gap-2 py-2 py-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how">How it works</a></li>
                    <li class="nav-item"><a class="nav-link" href="#roles">Roles</a></li>
                    <li class="nav-item"><a class="nav-link" href="#security">Security</a></li>

                    @if (Route::has('login'))
                    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                        @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-imru btn-sm px-3 rounded-2">Dashboard</a>
                        @else
                        <a href="{{ url('/academic/login') }}" class="btn btn-outline-info btn-sm px-3 rounded-2 ">Academic Login</a>

                        @if (Route::has('register'))
                        <a href="#" class="ml-4 btn btn-info btn-sm px-3 rounded-2">Corporate Login</a>
                        @endif
                        @endauth
                    </div>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-section text-center">
        <div class="container">
            <img class="hero-logo mb-4" src="{{ asset('assets/admin/img/brand/logo_wh.png') }}" alt="IMRU Online Examination Portal" onerror="this.style.display='none'">
            <h1 class="mb-3">Smarter examination system for <br><span>academic</span> and <span>corporate</span></h1>
            <p class="mb-4 mx-auto">A secure, scalable online examination portal for admission and recruitment assessments — with automated grading, live proctoring, and real-time analytics.</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ url('/academic/login') }}" class="btn btn-imru-outline px-4 py-2 rounded-2">Academic Login</a>
                <a href="#" class="btn btn-imru px-4 py-2 rounded-2">Corporate Login</a>
            </div>
        </div>
    </section>

    <!-- STATS BAR -->
    <div class="stats-bar py-4">
        <div class="container">
            <div class="row text-center gy-3">
                <div class="col-6 col-md-3">
                    <div class="stat-num">50k+</div>
                    <div class="stat-lbl">Exams conducted</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-num">99.9%</div>
                    <div class="stat-lbl">Uptime guarantee</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-num">3</div>
                    <div class="stat-lbl">User roles</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-num">100%</div>
                    <div class="stat-lbl">Cloud scalable</div>
                </div>
            </div>
        </div>
    </div>

    <!-- FEATURES -->
    <section class="py-5 bg-white" id="features">
        <div class="container">
            <div class="section-label mb-1">Platform features</div>
            <h2 class="fw-500 mb-2" style="font-weight:500;">Everything you need for fair, modern exams</h2>
            <p class="text-secondary mb-5" style="max-width:520px;">From question creation to result publication — IMRU handles the full examination lifecycle.</p>
            <div class="row g-4">
                <div class="col-sm-6 col-lg-4">
                    <div class="feature-card card h-100 border p-4">
                        <div class="feature-icon-wrap mb-3"><i class="bi bi-camera-video"></i></div>
                        <h5 class="fw-500" style="font-weight:500;font-size:15px;">Video proctoring</h5>
                        <p class="text-secondary small mb-0">Real-time webcam monitoring flags suspicious activity and ensures candidate integrity throughout.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="feature-card card h-100 border p-4">
                        <div class="feature-icon-wrap mb-3"><i class="bi bi-lock-fill"></i></div>
                        <h5 class="fw-500" style="font-weight:500;font-size:15px;">Tab-lock enforcement</h5>
                        <p class="text-secondary small mb-0">Prevents candidates from switching browser tabs or windows during active examination sessions.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="feature-card card h-100 border p-4">
                        <div class="feature-icon-wrap mb-3"><i class="bi bi-stopwatch"></i></div>
                        <h5 class="fw-500" style="font-weight:500;font-size:15px;">Timer controls</h5>
                        <p class="text-secondary small mb-0">Per-section and per-question timers with auto-submit on expiry — fully configurable per exam.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="feature-card card h-100 border p-4">
                        <div class="feature-icon-wrap mb-3"><i class="bi bi-check2-circle"></i></div>
                        <h5 class="fw-500" style="font-weight:500;font-size:15px;">Automated grading</h5>
                        <p class="text-secondary small mb-0">Instant scoring for MCQ, true/false, and fill-in-the-blank with manual override for descriptive answers.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="feature-card card h-100 border p-4">
                        <div class="feature-icon-wrap mb-3"><i class="bi bi-bar-chart-line"></i></div>
                        <h5 class="fw-500" style="font-weight:500;font-size:15px;">Result dashboards</h5>
                        <p class="text-secondary small mb-0">Visual analytics — score distributions, pass rates, question difficulty, and candidate comparisons.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="feature-card card h-100 border p-4">
                        <div class="feature-icon-wrap mb-3"><i class="bi bi-file-earmark-text"></i></div>
                        <h5 class="fw-500" style="font-weight:500;font-size:15px;">Multiple exam formats</h5>
                        <p class="text-secondary small mb-0">MCQ, short answer, descriptive, coding challenges, and mixed-format papers for any assessment type.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="py-5 bg-light" id="how">
        <div class="container">
            <div class="section-label mb-1">How it works</div>
            <h2 style="font-weight:500;" class="mb-5">From setup to results in four steps</h2>
            <div class="row text-center gy-4 position-relative">
                <div class="col-6 col-md-3 position-relative">
                    <div class="step-circle">1</div>
                    <div class="step-connector d-none d-md-block"></div>
                    <h6 style="font-weight:500;">Create exam</h6>
                    <p class="text-secondary small">Admin builds question bank, sets timer and rules</p>
                </div>
                <div class="col-6 col-md-3 position-relative">
                    <div class="step-circle">2</div>
                    <div class="step-connector d-none d-md-block"></div>
                    <h6 style="font-weight:500;">Invite candidates</h6>
                    <p class="text-secondary small">Send secure access links with credentials</p>
                </div>
                <div class="col-6 col-md-3 position-relative">
                    <div class="step-circle">3</div>
                    <div class="step-connector d-none d-md-block"></div>
                    <h6 style="font-weight:500;">Conduct exam</h6>
                    <p class="text-secondary small">Live proctoring and tab-lock keeps it fair</p>
                </div>
                <div class="col-6 col-md-3 position-relative">
                    <div class="step-circle">4</div>
                    <h6 style="font-weight:500;">View results</h6>
                    <p class="text-secondary small">Auto-graded reports published instantly</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ROLES -->
    <section class="py-5 bg-white" id="roles">
        <div class="container">
            <div class="section-label mb-1">User roles</div>
            <h2 style="font-weight:500;" class="mb-5">Built for every stakeholder</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="role-card card h-100 p-4">
                        <span class="badge badge-blue-custom mb-3 rounded-pill px-3 py-1" style="font-size:11px;">Super Admin</span>
                        <h5 style="font-weight:500;">Platform administrator</h5>
                        <ul class="list-unstyled mt-3 mb-0">
                            <li class="mb-2 small text-secondary"><span class="role-dot"></span>Manage organizations and users</li>
                            <li class="mb-2 small text-secondary"><span class="role-dot"></span>System configuration and billing</li>
                            <li class="mb-2 small text-secondary"><span class="role-dot"></span>Platform-wide analytics</li>
                            <li class="small text-secondary"><span class="role-dot"></span>Access control and permissions</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="role-card card h-100 p-4">
                        <span class="badge mb-3 rounded-pill px-3 py-1" style="font-size:11px;background:#e1f5ee;color:#0f6e56;font-weight:500;">Examiner</span>
                        <h5 style="font-weight:500;">University / HR team</h5>
                        <ul class="list-unstyled mt-3 mb-0">
                            <li class="mb-2 small text-secondary"><span class="role-dot"></span>Create and schedule exams</li>
                            <li class="mb-2 small text-secondary"><span class="role-dot"></span>Build and manage question bank</li>
                            <li class="mb-2 small text-secondary"><span class="role-dot"></span>Monitor live sessions</li>
                            <li class="small text-secondary"><span class="role-dot"></span>Review and publish results</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="role-card card h-100 p-4">
                        <span class="badge mb-3 rounded-pill px-3 py-1" style="font-size:11px;background:#faeeda;color:#854f0b;font-weight:500;">Candidate</span>
                        <h5 style="font-weight:500;">Student / Applicant</h5>
                        <ul class="list-unstyled mt-3 mb-0">
                            <li class="mb-2 small text-secondary"><span class="role-dot"></span>Register and verify identity</li>
                            <li class="mb-2 small text-secondary"><span class="role-dot"></span>Attempt assigned exams</li>
                            <li class="mb-2 small text-secondary"><span class="role-dot"></span>View personal scorecards</li>
                            <li class="small text-secondary"><span class="role-dot"></span>Download result certificates</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECURITY -->
    <section class="security-section py-5" id="security">
        <div class="container">
            <div class="section-label mb-1">Security & integrity</div>
            <h2 class="text-white mb-2" style="font-weight:500;">Enterprise-grade trust built in</h2>
            <p class="mb-5" style="color:rgba(255,255,255,0.6);max-width:520px;">Every feature is designed with exam integrity at its core — because results matter.</p>
            <div class="row g-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="sec-card p-4 h-100">
                        <h5 class="mb-2"><i class="bi bi-shield-lock me-2"></i>End-to-end encryption</h5>
                        <p class="mb-0">All exam data — questions, answers, and results — is encrypted in transit and at rest.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="sec-card p-4 h-100">
                        <h5 class="mb-2"><i class="bi bi-cpu me-2"></i>AI anomaly detection</h5>
                        <p class="mb-0">Machine learning flags unusual answer patterns, copy-paste events, and behaviour anomalies.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="sec-card p-4 h-100">
                        <h5 class="mb-2"><i class="bi bi-shuffle me-2"></i>Question randomisation</h5>
                        <p class="mb-0">Each candidate receives a unique question and answer order to prevent collusion.</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="sec-card p-4 h-100">
                        <h5 class="mb-2"><i class="bi bi-journal-text me-2"></i>Audit trail</h5>
                        <p class="mb-0">Every action — login, submission, review — is logged with timestamp and IP for compliance.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section text-center text-white py-5">
        <div class="container py-3">
            <h2 style="font-weight:500;" class="mb-3">Ready to modernise your assessments?</h2>
            <p class="mb-4" style="opacity:0.85;">Join universities and companies using IMRU to run fair, scalable, and trusted exams.</p>
            <button class="btn btn-light fw-500 px-4 py-2 rounded-2" style="color:#185fa5;font-weight:500;" onclick="sendPrompt('How do I get started with IMRU Online Examination Portal?')">Request a demo ↗</button>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="py-4 text-center">
        <div class="container">
            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                <img src="{{ asset('assets/admin/img/brand/logo_wh.png') }}" height="80" alt="" onerror="this.style.display='none'">
            </div>
            <p class="mb-2" style="color:rgba(255,255,255,0.45);font-size:13px;">Online Examination Portal — Built for academic and recruitment excellence.</p>
            <div class="d-flex justify-content-center gap-3" style="font-size:13px;">
                <a href="#features" style="color:rgba(255,255,255,0.4);">Features</a>
                <a href="#how" style="color:rgba(255,255,255,0.4);">How it works</a>
                <a href="#roles" style="color:rgba(255,255,255,0.4);">Roles</a>
                <a href="#security" style="color:rgba(255,255,255,0.4);">Security</a>
            </div>
            <p class="mt-3 mb-0" style="color:rgba(255,255,255,0.3);font-size:12px;">&copy; 2025 IMRU. All rights reserved.</p>
        </div>
    </footer>

    <script src="{{ asset('assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>

