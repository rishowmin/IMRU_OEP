<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- Start Dashboard Nav --}}
        <li class="nav-item">
            <a class="nav-link " href="{{ route('student.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        {{-- End Dashboard Nav --}}

        {{-- Start Contact Page Nav --}}
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('student.myExams') }}">
                <i class="bi bi-clipboard"></i>
                <span>My Exams</span>
            </a>
        </li>
        {{-- End Contact Page Nav --}}

    </ul>

</aside>
<!-- End Sidebar-->

