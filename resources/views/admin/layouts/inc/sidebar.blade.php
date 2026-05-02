<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- Start Dashboard Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? '' : 'collapsed' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        {{-- End Dashboard Nav --}}

        <li class="nav-heading d-none">
            <span>Academic</span>
        </li>

        {{-- Start Academic Dashboard Nav --}}
        <li class="nav-item d-none">
            <a class="nav-link {{ request()->routeIs('admin.academic.dashboard') ? '' : 'collapsed' }}" href="{{ route('admin.academic.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Academic Dashboard</span>
            </a>
        </li>
        {{-- End Academic Dashboard Nav --}}

        {{-- Start Teachers Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.academic.teachers*') ? '' : 'collapsed' }}" data-bs-target="#teachers-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person-workspace"></i><span>Teachers</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="teachers-nav" class="nav-content collapse {{ request()->routeIs('admin.academic.teachers*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ request()->routeIs('admin.academic.teachers.create') ? 'active' : '' }}" href="{{ route('admin.academic.teachers.create') }}">
                        <i class="bi bi-circle"></i><span>Add Teacher</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.academic.teachers.index') ? 'active' : '' }}" href="{{ route('admin.academic.teachers.index') }}">
                        <i class="bi bi-circle"></i><span>Manage Teachers</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Teachers Nav --}}

        {{-- Start Students Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.academic.students*') ? '' : 'collapsed' }}" data-bs-target="#students-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i><span>Students</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="students-nav" class="nav-content collapse {{ request()->routeIs('admin.academic.students*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ request()->routeIs('admin.academic.students.create') ? 'active' : '' }}" href="{{ route('admin.academic.students.create') }}">
                        <i class="bi bi-circle"></i><span>Add Student</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.academic.students.index') ? 'active' : '' }}" href="{{ route('admin.academic.students.index') }}">
                        <i class="bi bi-circle"></i><span>Manage Students</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Students Nav --}}

        {{-- Start Courses Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.academic.courses*') ? '' : 'collapsed' }}" data-bs-target="#courses-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-book"></i>
                <span>Courses</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="courses-nav"
                class="nav-content collapse {{ request()->routeIs('admin.academic.courses*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ request()->routeIs('admin.academic.courses.create') ? 'active' : '' }}" href="{{ route('admin.academic.courses.create') }}">
                        <i class="bi bi-circle"></i><span>Add Course</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.academic.courses.index') ? 'active' : '' }}" href="{{ route('admin.academic.courses.index') }}">
                        <i class="bi bi-circle"></i><span>Manage Courses</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Courses Nav --}}

        {{-- Start Exams Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.academic.exams*', 'admin.academic.examRules*', 'admin.academic.examAttempts*') ? '' : 'collapsed' }}" data-bs-target="#exam-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-clipboard"></i>
                <span>Exams</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="exam-nav" class="nav-content collapse {{ request()->routeIs('admin.academic.exams*', 'admin.academic.examRules*', 'admin.academic.examAttempts*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ request()->routeIs('admin.academic.exams.create') ? 'active' : '' }}" href="{{ route('admin.academic.exams.create') }}">
                        <i class="bi bi-circle"></i><span>Add Exam</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.academic.exams.index') ? 'active' : '' }}" href="{{ route('admin.academic.exams.index') }}">
                        <i class="bi bi-circle"></i><span>Manage Exams</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.academic.examRules*') ? 'active' : '' }}" href="{{ route('admin.academic.examRules.index') }}">
                        <i class="bi bi-circle"></i><span>Exam Rules</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.academic.examAttempts*') ? 'active' : '' }}" href="{{ route('admin.academic.examAttempts.index') }}">
                        <i class="bi bi-circle"></i><span>Exam Attempts</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Exams Nav --}}

        {{-- Start Questions Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.academic.questions*') ? '' : 'collapsed' }}" data-bs-target="#questions-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-earmark-text"></i><span>Questions</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="questions-nav" class="nav-content collapse {{ request()->routeIs('admin.academic.questions*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ request()->routeIs('admin.academic.questions.create') ? 'active' : '' }}" href="{{ route('admin.academic.questions.create') }}">
                        <i class="bi bi-circle"></i><span>Add Question</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.academic.questions.index') ? 'active' : '' }}" href="{{ route('admin.academic.questions.index') }}">
                        <i class="bi bi-circle"></i><span>Manage Questions</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Questions Nav --}}

        {{-- Start Questions Library Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.academic.questions.library*') ? '' : 'collapsed' }}" data-bs-target="#questions-library-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-earmark-text"></i><span>Questions Bank</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="questions-library-nav" class="nav-content collapse {{ request()->routeIs('admin.academic.questions.library*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ request()->routeIs('admin.academic.questions.library.create') ? 'active' : '' }}" href="{{ route('admin.academic.questions.library.create') }}">
                        <i class="bi bi-circle"></i><span>Add Question</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.academic.questions.library.index') ? 'active' : '' }}" href="{{ route('admin.academic.questions.library.index') }}">
                        <i class="bi bi-circle"></i><span>Manage Questions</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Questions Library Nav --}}

        {{-- Start Enrollments Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.academic.enrollments.index') ? '' : 'collapsed' }}" href="{{ route('admin.academic.enrollments.index') }}">
                <i class="bi bi-bookmark-plus"></i>
                <span>Enrollments</span>
            </a>
        </li>
        {{-- End Enrollments Nav --}}

        {{-- Start Review Answer Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.academic.reviewAnswer.index') ? '' : 'collapsed' }}" href="{{ route('admin.academic.reviewAnswer.index') }}">
                <i class="bi bi-chat-left-text"></i>
                <span>Review Answer</span>
            </a>
        </li>
        {{-- End Review Answer Nav --}}

        <li class="nav-heading d-none">
            <span>Corporate</span>
        </li>

        {{-- Start Professional Dashboard Nav --}}
        <li class="nav-item d-none">
            <a class="nav-link {{ request()->routeIs('admin.professional.dashboard') ? '' : 'collapsed' }}" href="{{ route('admin.professional.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Professional Dashboard</span>
            </a>
        </li>
        {{-- End Professional Dashboard Nav --}}

    </ul>

</aside>
<!-- End Sidebar-->

