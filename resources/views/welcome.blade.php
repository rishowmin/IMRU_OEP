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

    <link href="{{ asset('assets/admin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

    <!-- Type system: Sora for display, Inter for body, JetBrains Mono for figures/scores -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --imru-blue: #29abe2;
            --imru-cyan: #6fe3ff;
            --imru-dark: #101d2c;
            --imru-darker: #0a141f;
            --imru-navy-card: #16263a;
            --ink: #1c2733;
            --ink-soft: #5b6b7c;
            --hairline: #e6eaee;
            --paper: #f6f8fa;

            --font-display: 'Sora', system-ui, sans-serif;
            --font-sans: 'Inter', system-ui, sans-serif;
            --font-mono: 'JetBrains Mono', ui-monospace, monospace;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-sans);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-display);
            letter-spacing: -0.01em;
        }

        ::selection {
            background: rgba(41, 171, 226, 0.25);
        }

        a {
            text-decoration: none;
        }

        /* Subtle keyboard focus visibility */
        a:focus-visible,
        button:focus-visible {
            outline: 2px solid var(--imru-blue);
            outline-offset: 2px;
        }

        /* ============ NAVBAR ============ */
        .navbar {
            background: rgba(16, 29, 44, 0.92) !important;
            backdrop-filter: blur(10px);
            min-height: 64px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            transition: background 0.25s ease;
        }

        .navbar-brand img {
            height: 56px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.7) !important;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.01em;
            position: relative;
            transition: color 0.2s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -4px;
            width: 0;
            height: 2px;
            background: var(--imru-blue);
            transition: width 0.25s ease;
        }

        .nav-link:hover {
            color: #fff !important;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.3);
        }

        .navbar-toggler-icon {
            filter: invert(1);
        }

        /* ============ HERO ============ */
        .hero-section {
            position: relative;
            background: radial-gradient(ellipse 80% 60% at 50% -10%, rgba(41, 171, 226, 0.22), transparent 60%), var(--imru-dark);
            padding: 108px 0 0;
            overflow: hidden;
        }

        /* OMR-sheet inspired bubble grid, faint, behind content */
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1.5px, transparent 1.5px);
            background-size: 28px 28px;
            mask-image: linear-gradient(to bottom, rgba(0,0,0,0.9) 0%, transparent 75%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-mono);
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--imru-cyan);
            background: rgba(41, 171, 226, 0.1);
            border: 1px solid rgba(111, 227, 255, 0.25);
            border-radius: 999px;
            padding: 6px 16px;
            margin-bottom: 24px;
        }

        .hero-eyebrow .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--imru-cyan);
            box-shadow: 0 0 0 3px rgba(111, 227, 255, 0.18);
        }

        .hero-section h1 {
            font-size: 3rem;
            font-weight: 600;
            color: #fff;
            line-height: 1.18;
            max-width: 720px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-section h1 .accent {
            background: linear-gradient(100deg, var(--imru-cyan) 0%, var(--imru-blue) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero-section p.lead {
            color: rgba(255, 255, 255, 0.62);
            font-size: 1.05rem;
            line-height: 1.7;
            max-width: 560px;
            margin: 22px auto 36px;
        }

        .hero-actions .btn {
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.01em;
        }

        /* Mock exam panel illustration */
        .hero-panel {
            margin-top: 64px;
            position: relative;
            z-index: 2;
        }

        .exam-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px 16px 0 0;
            backdrop-filter: blur(6px);
            padding: 22px 28px;
            max-width: 760px;
            margin: 0 auto;
            text-align: left;
            box-shadow: 0 30px 80px -30px rgba(0, 0, 0, 0.6);
        }

        .exam-card .exam-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .exam-card .exam-dots span {
            display: inline-block;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            margin-right: 6px;
        }

        .exam-card .timer-pill {
            font-family: var(--font-mono);
            font-size: 12px;
            color: var(--imru-cyan);
            background: rgba(111, 227, 255, 0.08);
            border: 1px solid rgba(111, 227, 255, 0.2);
            border-radius: 999px;
            padding: 4px 12px;
        }

        .exam-q {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            margin-bottom: 14px;
        }

        .exam-option {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.55);
            font-size: 13px;
            padding: 9px 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            margin-bottom: 8px;
        }

        .exam-option .bubble {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            flex-shrink: 0;
        }

        .exam-option.selected {
            color: #fff;
            border-color: rgba(41, 171, 226, 0.4);
            background: rgba(41, 171, 226, 0.08);
        }

        .exam-option.selected .bubble {
            border-color: var(--imru-cyan);
            background: var(--imru-cyan);
            box-shadow: 0 0 0 3px rgba(111, 227, 255, 0.15);
        }

        /* ============ STATS BAR ============ */
        .stats-bar {
            background: var(--imru-darker);
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding: 36px 0;
        }

        .stats-bar .stat-num {
            font-family: var(--font-mono);
            font-size: 1.9rem;
            font-weight: 600;
            color: #fff;
            line-height: 1;
        }

        .stats-bar .stat-lbl {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 6px;
            letter-spacing: 0.02em;
        }

        .stats-bar .stat-col {
            border-right: 1px solid rgba(255, 255, 255, 0.07);
        }

        .stats-bar .stat-col:last-child,
        .stats-bar .col-6.stat-col:nth-child(even) {
            border-right: none;
        }

        @media (max-width: 767px) {
            .stats-bar .stat-col:nth-child(odd) {
                border-right: 1px solid rgba(255, 255, 255, 0.07);
            }
            .stats-bar .stat-col:nth-child(even) {
                border-right: none;
            }
        }

        /* ============ SECTION SHARED ============ */
        .section-label {
            font-family: var(--font-mono);
            font-size: 12px;
            color: var(--imru-blue);
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .section-heading {
            font-weight: 600;
            font-size: clamp(1.6rem, 2.4vw, 2.1rem);
            color: var(--ink);
        }

        .section-sub {
            color: var(--ink-soft);
            font-size: 1rem;
            line-height: 1.7;
        }

        /* ============ FEATURES ============ */
        .feature-card {
            border-radius: 14px;
            border: 1px solid var(--hairline);
            background: #fff;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            border-color: transparent;
            box-shadow: 0 16px 40px -20px rgba(16, 29, 44, 0.25);
        }

        .feature-icon-wrap {
            width: 46px;
            height: 46px;
            background: rgba(41, 171, 226, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-icon-wrap i {
            font-size: 21px;
            color: var(--imru-blue);
        }

        .feature-card h5 {
            font-weight: 600;
            font-size: 15px;
            color: var(--ink);
            margin-top: 4px;
        }

        .feature-card p {
            color: var(--ink-soft);
            font-size: 13.5px;
            line-height: 1.65;
        }

        /* ============ HOW IT WORKS ============ */
        .step-item {
            position: relative;
        }

        .step-circle {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: var(--imru-dark);
            color: var(--imru-cyan);
            font-family: var(--font-mono);
            font-size: 1.05rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            position: relative;
            z-index: 2;
            box-shadow: 0 10px 24px -12px rgba(16, 29, 44, 0.4);
        }

        .step-connector {
            position: absolute;
            top: 26px;
            left: calc(50% + 26px);
            right: calc(-50% + 26px);
            height: 1px;
            background: repeating-linear-gradient(to right, var(--hairline) 0, var(--hairline) 6px, transparent 6px, transparent 12px);
            z-index: 1;
        }

        .step-item h6 {
            font-weight: 600;
            font-size: 15px;
        }

        .step-item p {
            color: var(--ink-soft);
            font-size: 13.5px;
        }

        /* ============ ROLES ============ */
        .role-card {
            border-radius: 14px;
            border: 1px solid var(--hairline);
            background: #fff;
            transition: box-shadow 0.25s ease, transform 0.25s ease;
        }

        .role-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px -20px rgba(16, 29, 44, 0.2);
        }

        .role-card h5 {
            font-weight: 600;
        }

        .role-card li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .badge-blue-custom {
            background: rgba(41, 171, 226, 0.12);
            color: #1578a8;
            font-weight: 600;
            letter-spacing: 0.04em;
        }

        .role-dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--imru-blue);
            margin-top: 7px;
            flex-shrink: 0;
        }

        /* ============ SECURITY ============ */
        .security-section {
            background: var(--imru-dark);
            position: relative;
            overflow: hidden;
        }

        .security-section::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -120px;
            width: 360px;
            height: 360px;
            background: radial-gradient(circle, rgba(41, 171, 226, 0.18), transparent 70%);
            pointer-events: none;
        }

        .sec-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            position: relative;
            z-index: 1;
            transition: border-color 0.25s ease, background 0.25s ease;
        }

        .sec-card:hover {
            border-color: rgba(111, 227, 255, 0.3);
            background: rgba(255, 255, 255, 0.05);
        }

        .sec-card .sec-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(41, 171, 226, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .sec-card .sec-icon i {
            color: var(--imru-cyan);
            font-size: 17px;
        }

        .sec-card h5 {
            color: #fff;
            font-weight: 600;
            font-size: 15px;
        }

        .sec-card p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 13px;
            line-height: 1.65;
        }

        /* ============ CTA ============ */
        .cta-section {
            background: linear-gradient(120deg, #1f7cb4 0%, var(--imru-blue) 45%, #1ec6c6 100%);
            position: relative;
            overflow: hidden;
        }

        .cta-section::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1.5px, transparent 1.5px);
            background-size: 24px 24px;
            opacity: 0.5;
        }

        .cta-section .container {
            position: relative;
            z-index: 1;
        }

        .cta-section h2 {
            color: #fff;
            font-weight: 600;
        }

        /* ============ FOOTER ============ */
        footer {
            background: var(--imru-darker);
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        footer a {
            transition: color 0.2s ease;
        }

        footer a:hover {
            color: var(--imru-cyan) !important;
        }

        /* ============ BUTTONS ============ */
        .btn-imru {
            background: var(--imru-blue);
            color: #fff;
            border: none;
            box-shadow: 0 8px 20px -8px rgba(41, 171, 226, 0.6);
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-imru:hover {
            background: #1e8fc0;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 10px 24px -10px rgba(41, 171, 226, 0.7);
        }

        .btn-imru-outline {
            border: 1.5px solid rgba(255, 255, 255, 0.25);
            color: #fff;
            background: rgba(255, 255, 255, 0.03);
            transition: border-color 0.2s ease, background 0.2s ease, transform 0.2s ease;
        }

        .btn-imru-outline:hover {
            border-color: var(--imru-cyan);
            color: #fff;
            background: rgba(111, 227, 255, 0.08);
            transform: translateY(-1px);
        }

        .btn-light.fw-500:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 28px -12px rgba(16, 29, 44, 0.4);
        }

        @media (max-width: 767px) {
            .hero-section h1 {
                font-size: 2.1rem;
            }
            .step-connector {
                display: none;
            }
            .stats-bar .stat-col {
                border-right: none !important;
            }
        }
    </style>
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
                                <a href="#" class="btn btn-imru btn-sm px-3 rounded-2">
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
                <a href="#" class="btn btn-imru px-4 py-2 rounded-2">Corporate Login</a>
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
