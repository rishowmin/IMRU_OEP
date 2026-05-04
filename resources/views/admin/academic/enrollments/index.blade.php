@extends('admin.layouts.app')
@section('title', 'Enrollments')

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
                                <i class="bi bi-bookmark-plus"></i>
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
                            <a href="{{ route('admin.academic.enrollments.index') }}" class="btn btn-sm btn-outline-theme">
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
        <div class="col-lg-4">

            <div class="accordion mb-3" id="accordionAcademinCourses">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingcourse">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsecourse" aria-expanded="true" aria-controls="collapsecourse">
                            <h6 class="card-title p-0 m-0">
                                <i class="bi bi-pencil-square"></i>
                                {{ isset($enroll) ? 'Edit' : 'Create' }} @yield('title')
                            </h6>
                        </button>
                    </h2>
                    <div id="collapsecourse" class="accordion-collapse collapse show" aria-labelledby="headingcourse" data-bs-parent="#accordionAcademinCourses">
                        <div class="accordion-body">


                            <form action="{{ isset($enroll) ? route('admin.academic.enrollments.update', $enroll->id) : route('admin.academic.enrollments.store') }}" method="POST">
                                @csrf
                                @if(isset($enroll))
                                @method('PUT')
                                @endif

                                @php $isActive = old('is_active', isset($enroll) ? $enroll->is_active : 1); @endphp

                                <div class="row">

                                    <div class="col-sm-12">

                                        {{-- Course ID --}}
                                        <div class="row align-items-baseline mb-2">
                                            <div class="col-sm-12">
                                                <label for="course_id" class="form-label fw-bold"><small>Course Title & Code</small> <small class="text-danger">*</small></label>
                                                <div class="input-group">
                                                    <select class="form-select form-select-sm @error('course_id') is-invalid @elseif(old('course_id', $enroll->course_id ?? false)) is-valid @enderror" name="course_id" id="course_id" class="form-control">
                                                        <option selected disabled>Select Course</option>
                                                        @foreach($courses as $course)
                                                        <option value="{{ $course->id }}" {{ old('course_id', $enroll->course_id ?? '') == $course->id ? 'selected' : '' }}>
                                                            {{ $course->course_title }} - [{{ $course->course_code }}]
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('course_id')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('course_id', $enroll->course_id ?? false))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Student ID --}}
                                        {{-- <div class="row align-items-baseline mb-2">
                                            <div class="col-sm-12">
                                                <label for="student_id" class="form-label fw-bold"><small>Student's Name</small> <small class="text-danger">*</small></label>
                                                <div class="input-group">
                                                    <select class="form-select form-select-sm @error('student_id') is-invalid @elseif(old('student_id', $enroll->student_id ?? false)) is-valid @enderror" name="student_id" id="student_id" class="form-control">
                                                        <option selected disabled>Select Student</option>
                                                        @foreach($students as $student)
                                                        <option value="{{ $student->id }}" {{ old('student_id', $enroll->student_id ?? '') == $student->id ? 'selected' : '' }}>
                                                            {{ $student->first_name }} {{ $student->last_name }} - [{{ $student->email }}]
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('student_id')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('student_id', $enroll->student_id ?? false))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @enderror
                                                </div>
                                            </div>
                                        </div> --}}

                                        {{-- Student --}}
<div class="mb-3">
    <label class="form-label fw-semibold small">
        Student's Name
        <span class="text-danger ms-1">*</span>
    </label>

    @error('student_id')
        <div class="alert alert-danger py-1 px-2 small mb-2">
            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
        </div>
    @enderror

    {{-- Hidden inputs (populated by JS) --}}
    <div id="student-hidden-inputs"></div>

    <div class="border rounded-3 overflow-hidden">

        {{-- Search bar --}}
        <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom bg-white">
            <i class="bi bi-search text-muted" style="font-size:13px;"></i>
            <input type="text"
                id="student-search"
                class="form-control form-control-sm border-0 shadow-none p-0"
                placeholder="Search students by name or email…"
                autocomplete="off">
        </div>

        {{-- Checkbox list --}}
        <div id="student-list"
            class="overflow-auto"
            style="max-height: 220px;"
            data-students="{{ json_encode($students->map(fn($s) => [
                'id'    => $s->id,
                'name'  => $s->first_name . ' ' . $s->last_name,
                'email' => $s->email,
            ])) }}"
            data-selected="{{ json_encode(
                isset($enroll)
                    ? [$enroll->student_id]
                    : array_map('intval', (array) old('student_id', []))
            ) }}"
            data-single="{{ isset($enroll) ? 'true' : 'false' }}">
        </div>

        {{-- Footer --}}
        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top bg-light">
            @if(!isset($enroll))
                <div class="d-flex align-items-center gap-2">
                    <button type="button" id="btn-select-all"
                        class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:12px;">
                        Select all
                    </button>
                    <button type="button" id="btn-clear-all"
                        class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size:12px;">
                        Clear
                    </button>
                    <span class="vr"></span>
                    <small class="text-muted fw-semibold" id="student-count"></small>
                </div>
            @else
                <small class="text-muted fw-semibold" id="student-count"></small>
            @endif
        </div>

    </div>
</div>

                                        {{-- Status --}}
                                        <div class="row align-items-baseline">
                                            <div class="col-sm-12">
                                                <label for="is_active" class="form-label fw-bold"><small>Status</small></label>
                                                <div class="input-group">
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="is_active" value="0">
                                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $isActive ? 'checked' : '' }} onchange="updateLabelText(this)">
                                                        <label class="form-check-label ms-2" for="is_active" id="isActiveLabel">
                                                            <span class="badge {{ $isActive ? 'bg-success' : 'bg-danger' }}">
                                                                <i class="bi {{ $isActive ? 'bi-check-square' : 'bi-x-square' }} me-1"></i>
                                                                {{ $isActive ? 'Active' : 'Inactive' }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="row d-flex align-items-center justify-content-center mt-4">
                                    <button type="submit" class="btn btn-outline-success btn-sm w-100 ms-1">
                                        <i class="bi bi-floppy"></i>
                                        <span class="ms-1">{{ isset($enroll) ? 'Update' : 'Save' }}</span>
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-8">

            <div class="accordion mb-3" id="accordionAcademicEnrollments">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingEnrollment">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEnrollment" aria-expanded="true" aria-controls="collapseEnrollment">
                            <h6 class="card-title p-0 m-0">
                                <i class="bi bi-table"></i>
                                @yield('title') List
                            </h6>
                        </button>
                    </h2>
                    <div id="collapseEnrollment" class="accordion-collapse collapse show" aria-labelledby="headingEnrollment" data-bs-parent="#accordionAcademicEnrollments">
                        <div class="accordion-body px-0">

                            <table class="table table-sm small" id="enrollmentTable">
                                <thead>
                                    <tr>
                                        <th width="7%">#</th>
                                        <th width="35%">Course Title & Code</th>
                                        <th width="35%">Student's Name</th>
                                        <th width="10%">Status</th>
                                        <th width="13%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($enrollments as $enroll)

                                    <tr>
                                        <th class="text-start">
                                            <a href="javascript:void(0)" class="toggle-icon me-1">
                                                <i class="bi bi-plus-square"></i>
                                            </a>
                                            {{ $serialNo++ }}
                                        </th>
                                        <td>{{ $enroll->course->course_title }}</td>
                                        <td>{{ $enroll->student->first_name }} {{ $enroll->student->last_name }}</td>
                                        <td>
                                            @if($enroll->is_active == '1')
                                            <h6 class="mb-0"><span class="badge border-success border-1 text-success"><i class="bi bi-check-circle me-1"></i> ACTIVE</span></h6>
                                            @else
                                            <h6 class="mb-0"><span class="badge border-danger border-1 text-danger"><i class="bi bi-x-circle me-1"></i> DEACTIVE</span></h6>
                                            @endif

                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit">
                                                <a href="{{ route('admin.academic.enrollments.edit', $enroll->id) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </span>

                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger deleteBtn" data-id="{{ $enroll->id }}" data-bs-toggle="modal" data-bs-target="#delete_modal">
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

{{-- Status: Active / Deactive --}}
<script>
    function updateLabelText(checkbox) {
        const label = document.getElementById("isActiveLabel");
        const span = label.querySelector("span"); // Get the <span> with the badge
        const icon = span.querySelector("i"); // Get the icon element

        if (checkbox.checked) {
            span.classList.remove("bg-danger"); // Remove danger class (Deactive)
            span.classList.add("bg-success"); // Add success class (Active)
            icon.classList.remove("bi-x-square"); // Remove the 'x' icon (Deactive)
            icon.classList.add("bi-check-square"); // Add the 'check' icon (Active)
            span.innerHTML = '<i class="bi bi-check-square me-1"></i> Active'; // Update the text content to Active
        } else {
            span.classList.remove("bg-success"); // Remove success class (Active)
            span.classList.add("bg-danger"); // Add danger class (Deactive)
            icon.classList.remove("bi-check-square"); // Remove the 'check' icon (Active)
            icon.classList.add("bi-x-square"); // Add the 'x' icon (Deactive)
            span.innerHTML = '<i class="bi bi-x-square me-1"></i> Deactive'; // Update the text content to Deactive
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('is_active');
        if (checkbox) {
            updateLabelText(checkbox);
        }
    });

</script>

{{-- DataTable Script --}}
@if ($enrollments->count())
<script>
    const table = new DataTable('#enrollmentTable', {
        paging: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        lengthChange: true,
        scrollX: true
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
        let enroll = $(this).data("id");
        let deleteRoute = "{{ route('admin.academic.enrollments.destroy', ['enroll' => ':id']) }}";
        $("#deleteForm").attr("action", deleteRoute.replace(':id', enroll));
    });

</script>

<script>
(function () {
    const listEl   = document.getElementById('student-list');
    if (!listEl) return;

    const students = JSON.parse(listEl.dataset.students);
    const isSingle = listEl.dataset.single === 'true';
    const hiddenEl = document.getElementById('student-hidden-inputs');
    const countEl  = document.getElementById('student-count');
    const searchEl = document.getElementById('student-search');
    const btnAll   = document.getElementById('btn-select-all');
    const btnClear = document.getElementById('btn-clear-all');

    let selected = new Set(JSON.parse(listEl.dataset.selected).map(Number));
    let query    = '';

    function initials(name) {
        return name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
    }

    function renderCount() {
        if (!countEl) return;
        const n = selected.size;
        countEl.textContent = n + ' selected';
        countEl.className = 'fw-semibold small ' + (n > 0 ? 'text-primary' : 'text-muted');
    }

    function renderHidden() {
        hiddenEl.innerHTML = '';
        const ids = selected.size ? [...selected] : [''];
        ids.forEach(id => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'student_id[]';
            inp.value = id;
            hiddenEl.appendChild(inp);
        });
    }

    function renderList() {
        const q        = query.toLowerCase();
        const filtered = students.filter(s =>
            s.name.toLowerCase().includes(q) || s.email.toLowerCase().includes(q)
        );

        listEl.innerHTML = '';

        if (!filtered.length) {
            listEl.innerHTML = `
                <div class="px-3 py-3 text-center text-muted small">
                    <i class="bi bi-person-x me-1"></i>No students match your search
                </div>`;
            return;
        }

        filtered.forEach(s => {
            const isChecked = selected.has(s.id);
            const uid       = 'sc-' + s.id;

            const row       = document.createElement('label');
            row.htmlFor     = uid;
            row.className   = 'd-flex align-items-center gap-3 px-3 py-2 border-bottom mb-0'
                + (isChecked ? ' bg-primary bg-opacity-10' : '');
            row.style.cssText = 'cursor:pointer;transition:background 0.1s;';

            row.innerHTML = `
                <input type="checkbox" class="form-check-input mt-0 flex-shrink-0"
                    id="${uid}" ${isChecked ? 'checked' : ''}>
                <span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-semibold flex-shrink-0"
                    style="width:32px;height:32px;font-size:11px;
                        background:${isChecked ? '#0d6efd' : 'var(--bs-secondary-bg)'};
                        color:${isChecked ? '#fff' : 'var(--bs-secondary-color)'};">
                    ${initials(s.name)}
                </span>
                <span class="d-flex flex-column overflow-hidden">
                    <span class="small fw-semibold text-truncate">${s.name}</span>
                    <span class="text-muted text-truncate" style="font-size:11px;">${s.email}</span>
                </span>
                ${isChecked ? '<i class="bi bi-check2 ms-auto text-primary"></i>' : '<span class="ms-auto"></span>'}
            `;

            row.querySelector('input').addEventListener('change', e => {
                if (isSingle) selected.clear();
                if (e.target.checked) selected.add(s.id);
                else selected.delete(s.id);
                sync();
            });

            listEl.appendChild(row);
        });
    }

    function sync() {
        renderHidden();
        renderCount();
        renderList();
    }

    if (btnAll) {
        btnAll.addEventListener('click', () => {
            const q = query.toLowerCase();
            students
                .filter(s => s.name.toLowerCase().includes(q) || s.email.toLowerCase().includes(q))
                .forEach(s => selected.add(s.id));
            sync();
        });
    }

    if (btnClear) {
        btnClear.addEventListener('click', () => { selected.clear(); sync(); });
    }

    searchEl.addEventListener('input', e => { query = e.target.value; renderList(); });

    sync();
})();
</script>

@endsection

