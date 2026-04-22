<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- Start Dashboard Nav --}}
        <li class="nav-item">
            <a class="nav-link " href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        {{-- End Dashboard Nav --}}

        <li class="nav-heading">
            <span>Academic</span>
        </li>

        {{-- Start Academic Dashboard Nav --}}
        <li class="nav-item">
            <a class="nav-link " href="{{ route('admin.academic.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Academic Dashboard</span>
            </a>
        </li>
        {{-- End Academic Dashboard Nav --}}

        {{-- Start Courses Nav --}}
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#courses-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-book"></i><span>Courses</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="courses-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('admin.academic.courses.create') }}">
                        <i class="bi bi-circle"></i><span>Add Course</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.academic.courses.index') }}">
                        <i class="bi bi-circle"></i><span>Manage Courses</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Courses Nav --}}

        {{-- Start Exams Nav --}}
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#exams-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-clipboard"></i><span>Exams</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="exams-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('admin.academic.exams.create') }}">
                        <i class="bi bi-circle"></i><span>Add Exam</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.academic.exams.index') }}">
                        <i class="bi bi-circle"></i><span>Manage Exams</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Exams Nav --}}

        {{-- Start Questions Nav --}}
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#questions-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-earmark-text"></i><span>Questions</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="questions-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('admin.academic.questions.create') }}">
                        <i class="bi bi-circle"></i><span>Add Question</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.academic.questions.index') }}">
                        <i class="bi bi-circle"></i><span>Manage Questions</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Questions Nav --}}

        {{-- Start Students Nav --}}
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#students-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i><span>Students</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="students-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('admin.academic.students.create') }}">
                        <i class="bi bi-circle"></i><span>Add Student</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.academic.students.index') }}">
                        <i class="bi bi-circle"></i><span>Manage Students</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Students Nav --}}

        {{-- Start Contact Page Nav --}}
        <li class="nav-item">
            <a class="nav-link collapsed" href="pages-contact.html">
                <i class="bi bi-envelope"></i>
                <span>Contact</span>
            </a>
        </li>
        {{-- End Contact Page Nav --}}

        <li class="nav-heading">
            <span>Corporate</span>
        </li>

        {{-- Start Corporate Dashboard Nav --}}
        <li class="nav-item">
            <a class="nav-link " href="{{ route('admin.corporate.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Corporate Dashboard</span>
            </a>
        </li>
        {{-- End Academic Dashboard Nav --}}

        {{-- Start Components Nav --}}
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Components</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="components-alerts.html">
                        <i class="bi bi-circle"></i><span>Alerts</span>
                    </a>
                </li>
                <li>
                    <a href="components-accordion.html">
                        <i class="bi bi-circle"></i><span>Accordion</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- End Components Nav --}}

        {{-- Start Contact Page Nav --}}
        <li class="nav-item">
            <a class="nav-link collapsed" href="pages-contact.html">
                <i class="bi bi-envelope"></i>
                <span>Contact</span>
            </a>
        </li>
        {{-- End Contact Page Nav --}}

    </ul>

</aside>
<!-- End Sidebar-->

