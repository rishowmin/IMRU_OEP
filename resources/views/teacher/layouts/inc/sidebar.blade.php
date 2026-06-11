<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- Start Dashboard Nav --}}
        <li class="nav-item">
            <a class="nav-link  {{ request()->routeIs('teacher.dashboard') ? '' : 'collapsed' }}" href="{{ route('teacher.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        {{-- End Dashboard Nav --}}

        {{-- Start Students Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.students*') ? '' : 'collapsed' }}" data-bs-target="#students-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i><span>Students</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="students-nav" class="nav-content collapse {{ request()->routeIs('teacher.students*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ request()->routeIs('teacher.students.create') ? 'active' : '' }}" href="{{ route('teacher.students.create') }}">
                        <i class="bi bi-circle"></i><span>Add Student</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('teacher.students.index') ? 'active' : '' }}" href="{{ route('teacher.students.index') }}">
                        <i class="bi bi-circle"></i><span>Manage Students</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Students Nav --}}

        {{-- Start Courses Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.courses*') ? '' : 'collapsed' }}" data-bs-target="#courses-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-book"></i>
                <span>Courses</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="courses-nav"
                class="nav-content collapse {{ request()->routeIs('teacher.courses*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ request()->routeIs('teacher.courses.create') ? 'active' : '' }}" href="{{ route('teacher.courses.create') }}">
                        <i class="bi bi-circle"></i><span>Add Course</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('teacher.courses.index') ? 'active' : '' }}" href="{{ route('teacher.courses.index') }}">
                        <i class="bi bi-circle"></i><span>Manage Courses</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Courses Nav --}}

        {{-- Start Enrollments Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.enrollments*') ? '' : 'collapsed' }}" href="{{ route('teacher.enrollments.index') }}">
                <i class="bi bi-bookmark-plus"></i>
                <span>Enrollments</span>
            </a>
        </li>
        {{-- End Enrollments Nav --}}

        {{-- Start Exams Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.exams*', 'teacher.examRules*', 'teacher.examAttempts*') ? '' : 'collapsed' }}" data-bs-target="#exam-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-clipboard"></i>
                <span>Exams</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="exam-nav" class="nav-content collapse {{ request()->routeIs('teacher.exams*', 'teacher.examRules*', 'teacher.examAttempts*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ request()->routeIs('teacher.exams.create') ? 'active' : '' }}" href="{{ route('teacher.exams.create') }}">
                        <i class="bi bi-circle"></i><span>Add Exam</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('teacher.exams.index') ? 'active' : '' }}" href="{{ route('teacher.exams.index') }}">
                        <i class="bi bi-circle"></i><span>Manage Exams</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('teacher.examAttempts*') ? 'active' : '' }}" href="{{ route('teacher.examAttempts.index') }}">
                        <i class="bi bi-circle"></i><span>Exam Attempts</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Exams Nav --}}

        {{-- Start Exams by AI Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.aiExamSets*') ? '' : 'collapsed' }}" href="{{ route('teacher.aiExamSets.index') }}">
                <i class="bi bi-stars"></i>
                <span>Exams By AI</span>
            </a>
        </li>
        {{-- End Exams by AI Nav --}}

        {{-- Start Questions Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.questions*', 'teacher.questions.library*') ? '' : 'collapsed' }}" data-bs-target="#questions-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-earmark-text"></i><span>Questions</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="questions-nav" class="nav-content collapse {{ request()->routeIs('teacher.questions*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="{{ request()->routeIs('teacher.questions.create') ? 'active' : '' }}" href="{{ route('teacher.questions.create') }}">
                        <i class="bi bi-circle"></i><span>Add Question</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('teacher.questions.index') ? 'active' : '' }}" href="{{ route('teacher.questions.index') }}">
                        <i class="bi bi-circle"></i><span>Manage Questions</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('teacher.questions.library.index') ? 'active' : '' }}" href="{{ route('teacher.questions.library.index') }}">
                        <i class="bi bi-circle"></i><span>Question Bank</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Questions Nav --}}

        {{-- Start Review Answer Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.reviewAnswer*') ? '' : 'collapsed' }}" href="{{ route('teacher.reviewAnswer.index') }}">
                <i class="bi bi-chat-left-text"></i>
                <span>Review Answer</span>
            </a>
        </li>
        {{-- End Review Answer Nav --}}

        {{-- Start Performance Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.performance*') ? '' : 'collapsed' }}" href="{{ route('teacher.performance.index') }}">
                <i class="bi bi-graph-up"></i>
                <span>Performance</span>
            </a>
        </li>
        {{-- End Performance Nav --}}

        {{-- Start Proctoring Monitor Nav --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.proctoring*') ? '' : 'collapsed' }}" href="{{ route('teacher.proctoring.index') }}">
                <i class="bi bi-shield-exclamation"></i>
                <span>Proctoring Monitor</span>
            </a>
        </li>
        {{-- End Proctoring Monitor Nav --}}

    </ul>

</aside>
<!-- End Sidebar-->

