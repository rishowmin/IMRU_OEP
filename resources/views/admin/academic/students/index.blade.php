@extends('admin.layouts.app')
@section('title', 'Students')

@section('content')

@if(session('success') || session('status') || session('error'))
@include('admin.layouts.common.status')
@endif

<div class="pagetitle mb-0">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-header-left">
                            <h1>
                                <i class="bi bi-people"></i>
                                <span class="ms-1">@yield('title')</span>
                            </h1>
                            <nav style="--bs-breadcrumb-divider: '•';">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                    <li class="breadcrumb-item active">@yield('title')</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="card-header-right">
                            <a href="{{ route('admin.academic.students.create') }}" class="btn btn-sm btn-outline-theme">
                                <i class="bi bi-plus-lg"></i>
                                <span class="ms-1">Add @yield('title')</span>
                            </a>
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

            <div class="accordion mb-3" id="accordionAcademicstudents">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingstudent">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsestudent" aria-expanded="true" aria-controls="collapsestudent">
                            <h6 class="card-title p-0 m-0">
                                <i class="bi bi-table"></i>
                                @yield('title') List
                            </h6>
                        </button>
                    </h2>
                    <div id="collapsestudent" class="accordion-collapse collapse show" aria-labelledby="headingstudent" data-bs-parent="#accordionAcademicstudents">
                        <div class="accordion-body px-0">

                            <table class="table table-sm small" id="studentTable">
                                <thead>
                                    <tr>
                                        <th width="7%">#</th>
                                        <th width="30%">Student</th>
                                        <th width="20%">Email</th>
                                        <th width="15%">Phone</th>
                                        <th width="10%">Status</th>
                                        <th width="13%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    @forelse ($studentList as $student)

                                    <tr>
                                        <th class="text-start">
                                            <a href="javascript:void(0)" class="toggle-icon me-1">
                                                <i class="bi bi-plus-square"></i>
                                            </a>
                                            {{ $serialNo++ }}
                                        </th>
                                        <td>
                                            <div class="avatar_sec d-flex align-items-center gap-3">
                                                <div class="img-sec">
                                                    @php
                                                    $student;
                                                    $studentInfo = $student->info;
                                                    $firstName = $student->first_name ?? '';
                                                    $lastName = $student->last_name ?? '';
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
                                                </div>
                                                <div class="name-sec">
                                                    <p class="mb-0 fw-semibold">{{ $student->first_name }} {{ $student->last_name }}</p>
                                                    <small class="text-muted"><strong>ID: </strong>{{ $student->info->student_id_no ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->info->phone ?? 'N/A' }}</td>
                                        <td>
                                            @if($student->is_active == '1')
                                            <h6 class="mb-0"><span class="badge border-success border-1 text-success"><i class="bi bi-check-circle me-1"></i> ACTIVE</span></h6>
                                            @else
                                            <h6 class="mb-0"><span class="badge border-danger border-1 text-danger"><i class="bi bi-x-circle me-1"></i> INACTIVE</span></h6>
                                            @endif

                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Profile">
                                                <a href="{{ route('admin.academic.students.profile', $student->id) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-person-square"></i>
                                                </a>
                                            </span>

                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit">
                                                <a href="{{ route('admin.academic.students.edit', $student->id) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </span>

                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger deleteBtn" data-id="{{ $student->id }}" data-bs-toggle="modal" data-bs-target="#delete_modal">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                                @include('admin.layouts.common.deleteModal')
                                            </span>
                                        </td>

                                        <!-- hidden child content -->
                                        <template class="child-template d-none">
                                            <table class="table table-sm mb-0 w-100 small">
                                                <tbody>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Session</th>
                                                        <td width="70%">{{ $student->info->session ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Batch</th>
                                                        <td width="70%">{{ $student->info->batch ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Semester</th>
                                                        <td width="70%">{{ $student->info->semester ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Department</th>
                                                        <td width="70%">{{ $student->info->department ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Program</th>
                                                        <td width="70%">{{ $student->info->program ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Admission Date</th>
                                                        <td width="70%">{{ $student->info?->admission_date->format('d F Y') ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Gender</th>
                                                        <td width="70%">
                                                            @if ($student->info?->gender == 'male')
                                                            Male
                                                            @elseif ($student->info?->gender == 'female')
                                                            Female
                                                            @elseif ($student->info?->gender == 'other')
                                                            Other
                                                            @else
                                                            N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Date of Birth</th>
                                                        <td width="70%">
                                                            @if ($student->info?->dob != null)
                                                            <span class="me-3">{{ $student->info?->dob->format('d F Y') }}</span>
                                                            <span class="fw-semibold"> ({{ $student->info?->age }} years old)</span>
                                                            @else
                                                            N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Blood Group</th>
                                                        <td width="70%">{{ $student->info->blood_group ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Nationality</th>
                                                        <td width="70%">{{ $student->info->nationality ?? 'N/A' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </template>
                                    </tr>


                                    @empty

                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <strong>
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                <span>No @yield('title') Available</span>
                                                <i class="bi bi-exclamation-triangle ms-1"></i>
                                            </strong>
                                        </td>
                                    </tr>

                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>

@endsection




@section('scripts')

{{-- DataTable Script --}}
@if ($studentList->count())
<script>
    const table = new DataTable('#studentTable', {
        paging: true,
        pageLength: 20,
        lengthMenu: [10, 20, 30, 50, 100],
        lengthChange: true,
        scrollX: true,
    });

</script>
@endif

{{-- Toggle Child Row Script --}}
<script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.toggle-icon');
        if (!btn) return;

        const tr = btn.closest('tr');
        const row = table.row(tr);
        const icon = btn.querySelector('i');

        if (row.child.isShown()) {
            row.child.hide();
            icon.classList.replace('bi-dash-square', 'bi-plus-square');
        } else {
            const template = tr.querySelector('.child-template');
            row.child(template.innerHTML).show();
            icon.classList.replace('bi-plus-square', 'bi-dash-square');
        }
    });

</script>

{{-- Delete Modal Script --}}
<script>
    $(document).on("click", ".deleteBtn", function() {
        let student = $(this).data("id");
        let deleteRoute = "{{ route('admin.academic.students.destroy', ['student' => ':id']) }}";
        $("#deleteForm").attr("action", deleteRoute.replace(':id', student));
    });

</script>

@endsection
