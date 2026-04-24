@extends('admin.layouts.app')
@section('title', 'Students')

@section('content')

@if(session('success') || session('status') || session('error'))
@include('admin.layouts.common.status')
@endif

<section class="section">

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-header-left">
                        <h5>
                            <i class="bi bi-question-square"></i>
                            <span class="ms-1">@yield('title')</span>
                        </h5>
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
                        <div class="accordion-body">

                            <table class="table table-sm small" id="studentTable">
                                <thead>
                                    <tr>
                                        <th width="7%">#</th>
                                        <th width="25%">Student Name</th>
                                        <th width="20%">Username</th>
                                        <th width="25%">Email</th>
                                        <th width="10%">Status</th>
                                        <th width="13%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($studentList as $student)

                                    <tr>
                                        <th class="text-start">
                                            <a href="javascript:void(0)" class="toggle-icon me-1">
                                                <i class="bi bi-plus-square"></i>
                                            </a>
                                            {{ $serialNo++ }}
                                        </th>
                                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                        <td>{{ $student->username }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>
                                            @if($student->is_active == '1')
                                            <h6 class="mb-0"><span class="badge border-success border-1 text-success"><i class="bi bi-check-circle me-1"></i> ACTIVE</span></h6>
                                            @else
                                            <h6 class="mb-0"><span class="badge border-danger border-1 text-danger"><i class="bi bi-x-circle me-1"></i> INACTIVE</span></h6>
                                            @endif

                                        </td>
                                        <td>
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
                                                        <th width="22%">Phone Number</th>
                                                        <td width="70%">

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </template>
                                    </tr>


                                    @empty

                                    <tr>
                                        <td colspan="6" class="text-center">
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
{{-- @if ($studentList->count())
<script>
    const table = new DataTable('#questionTable', {
        paging: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        lengthChange: true,
        scrollX: true
    });
</script>
@endif --}}

{{-- Toggle Child Row Script --}}
<script>
    document.addEventListener('click', function (e) {
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
