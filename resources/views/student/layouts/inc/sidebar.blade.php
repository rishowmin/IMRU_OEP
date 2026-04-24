<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- Dashboard --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.dashboard') ? '' : 'collapsed' }}" href="{{ route('student.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- My Exams --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.myExams*') ? '' : 'collapsed' }}" href="{{ route('student.myExams') }}">
                <i class="bi bi-clipboard"></i>
                <span>My Exams</span>
            </a>
        </li>

    </ul>

</aside>
<!-- End Sidebar-->

