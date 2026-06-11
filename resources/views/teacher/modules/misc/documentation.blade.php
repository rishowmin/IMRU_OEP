@extends('teacher.layouts.app')
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
                                    <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}"><i class="bi bi-house"></i></a></li>
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

                    <h5 class="card-title fw-semibold text-theme mb-2 p-0">How to use the teacher portal</h5>
                    <p class="text-muted small mb-4">This guide explains the main teacher features available after signing in.</p>

                    <div class="list-group list-group-flush border rounded">

                        {{-- 1. Sign In --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-box-arrow-in-right fw-bold text-primary me-2"></i>Teacher Login
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">Go to the login page, select the <strong>Teacher Login</strong> tab, and sign in with your institutional email and password.</p>
                            <ul class="small text-muted mb-0 ps-5">
                                <li>Visit <code>/academic/login</code> and switch to the Teacher tab to enter your credentials.</li>
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
                            <p class="small text-muted mb-2 ps-4">After login, the dashboard gives a complete overview of your teaching activity with statistics, charts, and quick-access tables.</p>
                            <div class="row g-2 ps-4 mb-2">
                                <div class="col-sm-6 col-md-3">
                                    <div class="card border border-theme p-2 mb-2 shadow-sm">
                                        <small class="text-muted mb-1" style="font-size: 11px;">My Courses</small>
                                        <p class="fw-semibold small mb-0">Courses you teach</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="card border border-theme p-2 mb-2 shadow-sm">
                                        <small class="text-muted mb-1" style="font-size: 11px;">My Exams</small>
                                        <p class="fw-semibold small mb-0">Exams you created</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="card border border-theme p-2 mb-2 shadow-sm">
                                        <small class="text-muted mb-1" style="font-size: 11px;">Enrolled Students</small>
                                        <p class="fw-semibold small mb-0">Students in your courses</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="card border border-theme p-2 mb-2 shadow-sm">
                                        <small class="text-muted mb-1" style="font-size: 11px;">Pending Reviews</small>
                                        <p class="fw-semibold small mb-0">Ungraded subjective answers</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="card border border-theme p-2 mb-2 shadow-sm">
                                        <small class="text-muted mb-1" style="font-size: 11px;">Total Results</small>
                                        <p class="fw-semibold small mb-0">All submitted results</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="card border border-theme p-2 mb-2 shadow-sm">
                                        <small class="text-muted mb-1" style="font-size: 11px;">Passed / Failed</small>
                                        <p class="fw-semibold small mb-0">Pass and fail counts</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="card border border-theme p-2 mb-2 shadow-sm">
                                        <small class="text-muted mb-1" style="font-size: 11px;">Avg Score & Pass Rate</small>
                                        <p class="fw-semibold small mb-0">Overall performance rate</p>
                                    </div>
                                </div>
                            </div>
                            <p class="small text-muted mb-0 ps-4">The dashboard also shows three charts: <strong>Pass vs Fail</strong> doughnut, <strong>Monthly Pass/Fail Trend</strong> line chart (last 6 months), and <strong>Exams per Course</strong> bar chart. Below the charts, four quick-access tables show Upcoming Exams, Pending Reviews, Recent Exam Attempts, and Per-Course Stats.</p>
                        </div>

                        {{-- 3. Courses --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-journal-bookmark-fill fw-bold text-primary me-2"></i>Courses
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">The Courses section lets you view and manage all courses assigned to you.</p>
                            <ul class="small text-muted mb-0 ps-5">
                                <li>Go to <strong>Courses → Manage Courses</strong> to see the full list with course title, code, credits, and status.</li>
                                <li>Use <strong>Courses → Add Course</strong> to create a new course by filling in the title, code, credits, description, and setting its status to Active.</li>
                                <li>Use the <strong>Edit</strong> action button on any row to update an existing course.</li>
                                <li>Each course can be set to <strong>Active</strong> or <strong>Inactive</strong> using the status toggle.</li>
                            </ul>
                        </div>

                        {{-- 4. Exams --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-clipboard-check-fill fw-bold text-warning me-2"></i>Exams
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">The Exams section is the core of the portal. You can create and manage exams, configure rules, and monitor exam status.</p>
                            <ul class="small text-muted mb-2 ps-5">
                                <li>Go to <strong>Exams → Add Exam</strong> to create a new exam. Fill in the course, exam type, title, code, date, start/end time, duration, total marks, passing marks, total questions, instructions, and basic rules.</li>
                                <li>Go to <strong>Exams → Manage Exams</strong> to view all your exams with their status. Each row provides <strong>Exam Settings</strong>, <strong>Clone</strong>, <strong>Edit</strong>, and <strong>Delete</strong> actions.</li>
                                <li>Use the <strong>Exam Settings</strong> (gear icon) to toggle individual instructions and security rules on or off for each exam independently.</li>
                                <li>Go to <strong>Exams → Exam Rules</strong> to manage the global library of reusable instructions and rules.</li>
                            </ul>
                            <div class="alert alert-warning d-flex align-items-start gap-2 py-2 ms-4 mb-0">
                                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                                <span class="small">Set the exam date and time carefully. Students can only access the exam within the configured start and end time window.</span>
                            </div>
                        </div>

                        {{-- 5. Exams By AI --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-stars fw-bold text-info me-2"></i>Exams By AI
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">Use <strong>Exams By AI</strong> to automatically generate a complete exam set using the Gorq AI API, saving time on question creation.</p>
                            <ul class="small text-muted mb-2 ps-5">
                                <li>Provide the exam title, question type (Objective/Subjective), topic, total questions, and duration.</li>
                                <li>The AI engine generates a question bank using the <strong>Question Type Balancer</strong> (objective/subjective mix) and <strong>Difficulty Balancer</strong> (Easy/Medium/Hard).</li>
                                <li>An <strong>Anti-repeat Filter</strong> ensures unique question sets per student to maintain exam integrity.</li>
                                <li>The generated exam set is saved as a <strong>Draft</strong> first. Review and edit the questions before publishing.</li>
                                <li>Once satisfied, <strong>Publish</strong> the exam by assigning a course, date, and time to make it available to students.</li>
                            </ul>
                            <div class="alert alert-info d-flex align-items-start gap-2 py-2 ms-4 mb-0">
                                <i class="bi bi-info-circle-fill mt-1"></i>
                                <span class="small"><strong>Tip:</strong> Always review AI-generated questions before publishing to ensure accuracy and relevance to your course.</span>
                            </div>
                        </div>

                        {{-- 6. Questions --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-patch-question-fill fw-bold text-secondary me-2"></i>Questions
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">The Questions section lets you build and manage the question bank for your exams.</p>
                            <ul class="small text-muted mb-0 ps-5">
                                <li>Go to <strong>Questions → Add Question</strong> to create a new question. Select the exam, question type, write the question text, set the difficulty level, marks, and evaluation type.</li>
                                <li>Supported question types: <strong>MCQ (2 Options)</strong>, <strong>MCQ (4 Options)</strong>, <strong>Short Question</strong>, and <strong>Long Question</strong>.</li>
                                <li>MCQ questions support an <strong>Automatic</strong> evaluation type — the system grades them instantly on submission.</li>
                                <li>Short and Long questions use <strong>Manual</strong> evaluation — you must review and grade them through the Review Answer module.</li>
                                <li>Optionally attach a <strong>Question Figure</strong> (image) to accompany the question text.</li>
                                <li>Use <strong>Questions → Manage Questions</strong> to view, edit, or delete existing questions.</li>
                            </ul>
                        </div>

                        {{-- 7. Enrollments --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-person-check-fill fw-bold text-success me-2"></i>Enrollments
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">Use the Enrollments section to assign students to your courses, giving them access to all exams under that course.</p>
                            <ul class="small text-muted mb-0 ps-5">
                                <li>Select a course and a student from the dropdowns, set the status to Active, and click <strong>Save</strong> to enroll.</li>
                                <li>The Enrollments List on the right shows all existing course-student assignments.</li>
                                <li>Each enrollment can be edited or deleted using the action buttons.</li>
                                <li>A student will only see exams for courses they are actively enrolled in.</li>
                            </ul>
                        </div>

                        {{-- 8. Review Answer --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-pencil-square fw-bold text-danger me-2"></i>Review Answer
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">Use Review Answer to manually grade Short Question and Long Question answers submitted by students. MCQ answers are graded automatically.</p>
                            <ul class="small text-muted mb-0 ps-5">
                                <li>The Review Answers list shows all exams with pending or completed subjective reviews, with a submission count and review status.</li>
                                <li>Click the <strong>view icon</strong> to open the student submissions list for a specific exam.</li>
                                <li>Click <strong>Review</strong> next to a student to open the grading interface, which shows the question, the student's typed answer, and student info on the right.</li>
                                <li>Select a verdict (<strong>Correct</strong>, <strong>Incorrect</strong>, or <strong>Partial</strong>) and enter the marks awarded (up to the question maximum).</li>
                                <li>Click <strong>Save Review</strong> to commit the grade. The student's result page updates automatically after all subjective answers are reviewed.</li>
                            </ul>
                        </div>

                        {{-- 9. Performance --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-bar-chart-line-fill fw-bold text-primary me-2"></i>Performance
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">The Performance section provides detailed analytics on student results across your exams and courses.</p>
                            <ul class="small text-muted mb-0 ps-5">
                                <li>View pass rates, average scores, and score distributions per exam and course.</li>
                                <li>Compare student performance across different exam attempts.</li>
                                <li>Use the data to identify students who may need additional support.</li>
                            </ul>
                        </div>

                        {{-- 10. Proctoring Monitor --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-shield-fill-check fw-bold text-danger me-2"></i>Proctoring Monitor
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">The Proctoring Monitor lets you oversee student activity and flag suspicious behaviour during live exams.</p>
                            <ul class="small text-muted mb-2 ps-5">
                                <li>Monitor tab-switching events, clipboard activity (cut/copy/paste), and webcam captures flagged during an exam.</li>
                                <li>View a per-student log of proctoring events for any exam attempt.</li>
                                <li>Use proctoring flags as supporting evidence when reviewing exam integrity.</li>
                            </ul>
                            <div class="alert alert-danger d-flex align-items-start gap-2 py-2 ms-4 mb-0">
                                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                                <span class="small">Proctoring rules (tab switching, fullscreen required, webcam required, back button restrictions) are configured per exam via <strong>Exam Settings</strong>. Enable only the rules relevant to each exam.</span>
                            </div>
                        </div>

                        {{-- 11. Exam Creation Workflow --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-diagram-3-fill fw-bold text-warning me-2"></i>Exam Creation Workflow
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">There are two paths to create an exam. Both paths converge at the same Exam Generated stage, after which Exam Settings apply.</p>

                            <div class="row g-2 ps-4 mb-3">
                                <div class="col-md-6">
                                    <div class="card border-primary border-opacity-25 p-3 h-100">
                                        <p class="fw-semibold small text-primary mb-2"><i class="bi bi-pencil-fill me-2"></i>Path A — General (Custom Exam)</p>
                                        <ol class="small text-muted mb-0 ps-3">
                                            <li class="mb-1">Go to <strong>Exams → Add Exam</strong> and fill in the course, title, date, duration, marks, and instructions.</li>
                                            <li class="mb-1">Add questions manually by writing <strong>Custom Questions</strong>, or select from the existing <strong>Question Bank</strong>.</li>
                                            <li>Configure <strong>Exam Settings</strong> (instructions and rules) then publish.</li>
                                        </ol>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-info border-opacity-25 p-3 h-100">
                                        <p class="fw-semibold small text-info mb-2"><i class="bi bi-stars me-2"></i>Path B — AI Generated</p>
                                        <ol class="small text-muted mb-0 ps-3">
                                            <li class="mb-1">Go to <strong>Exams By AI</strong> and provide the title, question type, topic, total questions, and duration.</li>
                                            <li class="mb-1">The Gorq API generates questions into the Question Bank, applying the <strong>Question Type Balancer</strong>, <strong>Difficulty Balancer</strong>, and <strong>Anti-repeat Filter</strong>.</li>
                                            <li class="mb-1">The exam set is saved as a <strong>Draft</strong>. Review and edit before publishing.</li>
                                            <li>Assign a course, date, and time, then <strong>Publish</strong>.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <p class="small text-muted ps-4 mb-2">After the exam is generated (via either path), the following <strong>Exam Settings</strong> apply by default and can be customised per exam:</p>
                            <div class="row g-2 ps-4">
                                <div class="col-sm-6 col-md-4">
                                    <div class="card border border-secondary border-opacity-25 p-2 shadow-sm">
                                        <small class="text-muted mb-1" style="font-size: 11px;">Default Rules</small>
                                        <p class="fw-semibold small mb-0">Cut / Copy / Paste Restrictions · Back Button Restrictions</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="card border border-secondary border-opacity-25 p-2 shadow-sm">
                                        <small class="text-muted mb-1" style="font-size: 11px;">Instructions (configurable)</small>
                                        <p class="fw-semibold small mb-0">Timer Policy · Auto Submit · Single Attempt · Internet Connection</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="card border border-secondary border-opacity-25 p-2 shadow-sm">
                                        <small class="text-muted mb-1" style="font-size: 11px;">Rules (configurable)</small>
                                        <p class="fw-semibold small mb-0">Tab Switching · Fullscreen Required · Webcam Required</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 12. Help & Support --}}
                        <div class="list-group-item bg-light py-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h6 class="mb-0 fw-semibold text-theme">
                                    <i class="bi bi-question-circle-fill fw-bold text-secondary me-2"></i>Help & Support
                                </h6>
                            </div>
                            <p class="small text-muted mb-2 ps-4">Access this documentation at any time or contact your institution administrator for further assistance.</p>
                            <ul class="small text-muted mb-0 ps-5">
                                <li>The documentation link is available from your profile dropdown menu.</li>
                                <li>Use <strong>Logout</strong> when you finish your session.</li>
                            </ul>
                        </div>

                    </div>

                    <div class="alert alert-info d-flex align-items-start gap-2 mt-3 mb-0">
                        <i class="bi bi-info-circle-fill mt-1"></i>
                        <span class="small"><strong>Tip:</strong> Review all pending subjective answers promptly — students can only see their final result after all Short and Long Question answers have been graded.</span>
                    </div>

                </div>
            </div>
        </div>
    </div>

</section>

@endsection
