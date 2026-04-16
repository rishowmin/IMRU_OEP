<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- Start Dashboard Nav --}}
        <li class="nav-item">
            <a class="nav-link " href="index.html">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        {{-- End Dashboard Nav --}}

        <li class="nav-heading">
            <span>Academic</span>
        </li>

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
                <i class="bi bi-file-earmark-text"></i><span>Exams</span><i class="bi bi-chevron-down ms-auto"></i>
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

        {{-- Start Contact Page Nav --}}
        <li class="nav-item">
            <a class="nav-link collapsed" href="pages-contact.html">
                <i class="bi bi-envelope"></i>
                <span>Contact</span>
            </a>
        </li>
        {{-- End Contact Page Nav --}}

        <li class="nav-heading">
            <span>Recruitment</span>
        </li>

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

