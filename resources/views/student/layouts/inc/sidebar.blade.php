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

        {{-- My Results --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.myResults*') ? '' : 'collapsed' }}" href="{{ route('student.myResults') }}">
                <i class="bi bi-trophy"></i>
                <span>My Results</span>
            </a>
        </li>

        {{-- My Profile --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.myProfile*') ? '' : 'collapsed' }}" href="{{ route('student.myProfile', auth('student')->user()->id) }}">
                <i class="bi bi-person-circle"></i>
                <span>My Profile</span>
            </a>
        </li>

    </ul>

</aside>
<!-- End Sidebar-->

