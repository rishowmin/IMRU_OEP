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

    </ul>

</aside>
<!-- End Sidebar-->

