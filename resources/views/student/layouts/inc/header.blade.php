<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
            <img src="{{ asset('assets/admin/img/brand/logo.png') }}" alt="">
            {{-- <span class="d-none d-lg-block">IMRU Online Examination Portal</span> --}}
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item dropdown pe-3">



                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    @php
                    $authStudent = Auth::guard('student')->user();
                    $studentInfo = $authStudent->info;
                    $firstName = $authStudent->first_name ?? '';
                    $lastName = $authStudent->last_name ?? '';
                    $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                    $colors = ['#4e73df', '#1cc88a', '#36b9cc', '#e74a3b', '#f6c23e', '#6f42c1', '#fd7e14', '#20c9a6'];
                    $bgColor = $colors[abs(crc32($firstName . $lastName)) % count($colors)];
                    @endphp

                    {{-- Preview image --}}
                    <img id="nav-photo-preview" src="{{ $studentInfo?->profile_photo ? asset('storage/profile_photo/student/' . $studentInfo->profile_photo) : '' }}" alt="Profile Photo" style="{{ $studentInfo?->profile_photo ? '' : 'display:none;' }}">

                    {{-- Initials fallback --}}
                    @if(!$studentInfo?->profile_photo)
                    <div class="photo-initials" style="background-color:{{ $bgColor }};">
                        <span>{{ $initials ?: '?' }}</span>
                    </div>
                    @endif
                </a>
                <!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{ $firstName }} {{ $lastName }}</h6>
                        <span>
                            @if ($studentInfo?->student_id_no != null)
                            Student ID: {{ $studentInfo->student_id_no }}
                            @else
                            Student
                            @endif
                        </span>

                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <i class="bi bi-person"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <i class="bi bi-gear"></i>
                            <span>Account Settings</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <i class="bi bi-question-circle"></i>
                            <span>Documentation</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form method="POST" action="{{ route('student.logout') }}">
                            @csrf
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('student.logout') }}" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </form>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header>
<!-- End Header -->
