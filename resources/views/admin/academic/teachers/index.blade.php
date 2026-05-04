@extends('admin.layouts.app')
@section('title', 'Teachers')
@section('title2', 'Manage Teachers')

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
                                <i class="bi bi-person-workspace"></i>
                                <span class="ms-1">@yield('title')</span>
                            </h1>
                            <nav style="--bs-breadcrumb-divider: '•';">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                    <li class="breadcrumb-item active">@yield('title2')</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="card-header-right">
                            <a href="{{ route('admin.academic.teachers.create') }}" class="btn btn-sm btn-outline-theme">
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

            <div class="accordion mb-3" id="accordionAcademicteachers">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingteacher">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseteacher" aria-expanded="true" aria-controls="collapseteacher">
                            <h6 class="card-title p-0 m-0">
                                <i class="bi bi-table"></i>
                                @yield('title') List
                            </h6>
                        </button>
                    </h2>
                    <div id="collapseteacher" class="accordion-collapse collapse show" aria-labelledby="headingteacher" data-bs-parent="#accordionAcademicteachers">
                        <div class="accordion-body px-0">

                            <table class="table table-sm small" id="teacherTable">
                                <thead>
                                    <tr>
                                        <th width="7%">#</th>
                                        <th width="30%">Teacher</th>
                                        <th width="20%">Email</th>
                                        <th width="15%">Phone</th>
                                        <th width="10%">Status</th>
                                        <th width="13%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($teacherList as $teacher)

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
                                                    $teacher;
                                                    $teacherInfo = $teacher->info;
                                                    $firstName = $teacher->first_name ?? '';
                                                    $lastName = $teacher->last_name ?? '';
                                                    $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                                    $colors = ['#4e73df', '#1cc88a', '#36b9cc', '#e74a3b', '#f6c23e', '#6f42c1', '#fd7e14', '#20c9a6'];
                                                    $bgColor = $colors[abs(crc32($firstName . $lastName)) % count($colors)];
                                                    @endphp

                                                    {{-- Preview image --}}
                                                    <img id="nav-photo-preview" src="{{ $teacherInfo?->profile_photo ? asset('storage/profile_photo/teacher/' . $teacherInfo->profile_photo) : '' }}" alt="Profile Photo" style="{{ $teacherInfo?->profile_photo ? '' : 'display:none;' }}">

                                                    {{-- Initials fallback --}}
                                                    @if(!$teacherInfo?->profile_photo)
                                                    <div class="photo-initials" style="background-color:{{ $bgColor }};">
                                                        <span>{{ $initials ?: '?' }}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="name-sec">
                                                    <p class="mb-0 fw-semibold">{{ $teacher->first_name }} {{ $teacher->last_name }}</p>
                                                    <small class="text-muted"><strong>ID: </strong>{{ $teacher->info->teacher_id_no ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $teacher->email }}</td>
                                        <td>{{ $teacher->info->phone ?? 'N/A' }}</td>
                                        <td>
                                            @if($teacher->is_active == '1')
                                            <h6 class="mb-0"><span class="badge border-success border-1 text-success"><i class="bi bi-check-circle me-1"></i> ACTIVE</span></h6>
                                            @else
                                            <h6 class="mb-0"><span class="badge border-danger border-1 text-danger"><i class="bi bi-x-circle me-1"></i> INACTIVE</span></h6>
                                            @endif

                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Profile">
                                                <a href="{{ route('admin.academic.teachers.profile', $teacher->id) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-person-square"></i>
                                                </a>
                                            </span>

                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit">
                                                <a href="{{ route('admin.academic.teachers.edit', $teacher->id) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </span>

                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger deleteBtn" data-id="{{ $teacher->id }}" data-bs-toggle="modal" data-bs-target="#delete_modal">
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
                                                        <th width="22%">Designation</th>
                                                        <td width="70%">{{ $teacher->info->designation ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Department</th>
                                                        <td width="70%">{{ $teacher->info->department ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Specialization</th>
                                                        <td width="70%">{{ $teacher->info->specialization ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Qualification</th>
                                                        <td width="70%">{{ $teacher->info->qualification ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Experience</th>
                                                        <td width="70%">
                                                            @if ($teacher->info?->experience_years != null)
                                                            {{ $teacher->info->experience_years .' years' }}
                                                            @else
                                                            N/A
                                                            @endif

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Joining Date</th>
                                                        <td width="70%">{{ $teacher->info?->joining_date->format('d F Y') ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Gender</th>
                                                        <td width="70%">
                                                            @if ($teacher->info?->gender == 'male')
                                                            Male
                                                            @elseif ($teacher->info?->gender == 'female')
                                                            Female
                                                            @elseif ($teacher->info?->gender == 'other')
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
                                                            @if ($teacher->info?->dob != null)
                                                            <span class="me-3">{{ $teacher->info?->dob->format('d F Y') }}</span>
                                                            <span class="fw-semibold"> ({{ $teacher->info?->age }} years old)</span>
                                                            @else
                                                            N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Blood Group</th>
                                                        <td width="70%">{{ $teacher->info->blood_group ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Nationality</th>
                                                        <td width="70%">{{ $teacher->info->nationality ?? 'N/A' }}</td>
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
@if ($teacherList->count())
<script>
    const table = new DataTable('#teacherTable', {
        paging: true
        , pageLength: 10
        , lengthMenu: [5, 10, 25, 50, 100]
        , lengthChange: true
        , scrollX: true
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
        let teacher = $(this).data("id");
        let deleteRoute = "{{ route('admin.academic.teachers.destroy', ['teacher' => ':id']) }}";
        $("#deleteForm").attr("action", deleteRoute.replace(':id', teacher));
    });

</script>

@endsection
