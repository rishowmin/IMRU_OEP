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
                                Create @yield('title')
                            </h6>
                        </button>
                    </h2>
                    <div id="collapsecourse" class="accordion-collapse collapse show" aria-labelledby="headingcourse" data-bs-parent="#accordionAcademinCourses">
                        <div class="accordion-body">

                            <form action="{{ route('admin.academic.enrollments.store') }}" method="POST">
                                @csrf

                                @php $isActive = old('is_active', 1); @endphp

                                <div class="row">
                                    <div class="col-sm-12">

                                        {{-- Course ID --}}
                                        <div class="row align-items-baseline mb-2">
                                            <div class="col-sm-12">
                                                <label for="course_id" class="form-label fw-bold">
                                                    <small>Course Title & Code</small>
                                                    <small class="text-danger">*</small>
                                                </label>
                                                <div class="input-group">
                                                    <select class="form-select form-select-sm @error('course_id') is-invalid @elseif(old('course_id')) is-valid @enderror" name="course_id" id="course_id">
                                                        <option selected disabled>Select Course</option>
                                                        @foreach($courses as $course)
                                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
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
                                                    @if(old('course_id'))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

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
                                                        'photo' => $s->info?->profile_photo ? asset('assets/storage/profile_photo/student/' . $s->info->profile_photo) : null,
                                                    ])) }}"
                                                    data-selected="{{ json_encode(array_map('intval', (array) old('student_id', []))) }}"
                                                    data-single="false">
                                                </div>

                                                {{-- Footer --}}
                                                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top bg-light">
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
                                        <i class="bi bi-person-plus"></i>
                                        <span class="ms-1">Enroll</span>
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
                                        <th class="text-center">#</th>
                                        <th>Course Title & Code</th>
                                        <th class="text-center">Total Enrolled</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse ($enrollments as $courseId => $courseEnrollments)

                                    <tr>
                                        <th class="text-start">
                                            <a href="javascript:void(0)" class="toggle-icon me-1">
                                                <i class="bi bi-plus-square"></i>
                                            </a>
                                            {{ $serialNo++ }}
                                        </th>
                                        <td>
                                            {{ $courseEnrollments->first()->course->course_title }}
                                            <small class="d-block text-muted">[{{ $courseEnrollments->first()->course->course_code }}]</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $courseEnrollments->count() }} students
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($courseEnrollments->first()->course->is_active == '1')
                                                <span class="badge border-success border-1 text-success"><i class="bi bi-check-circle me-1"></i>ACTIVE</span>
                                            @else
                                                <span class="badge border-danger border-1 text-danger"><i class="bi bi-x-circle me-1"></i>INACTIVE</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)"
                                                class="btn btn-sm btn-outline-danger deleteCourseBtn"
                                                data-id="{{ $courseEnrollments->first()->course_id }}"
                                                data-label="{{ $courseEnrollments->first()->course->course_title }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#delete_bulk_modal"
                                                title="Remove all enrollments for this course">
                                                <i class="bi bi-trash3"></i>
                                            </a>
                                        </td>

                                        <template class="child-template">
                                            <table class="table table-sm mb-0 w-100 small">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th>Student's Name</th>
                                                        <th class="text-center">Email</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($courseEnrollments as $studentEnroll)
                                                    <tr>
                                                        <th class="text-center">{{ $loop->iteration }}</th>
                                                        <td>{{ $studentEnroll->student->first_name }} {{ $studentEnroll->student->last_name }}</td>
                                                        <td class="text-center">{{ $studentEnroll->student->email }}</td>
                                                        <td class="text-center">
                                                            @if($studentEnroll->is_active)
                                                                <span class="badge bg-success-subtle text-success">Active</span>
                                                            @else
                                                                <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-sm btn-outline-danger deleteBtn"
                                                                data-id="{{ $studentEnroll->id }}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#delete_modal"
                                                                title="Delete">
                                                                <i class="bi bi-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
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

@include('admin.layouts.common.deleteBulkModal')
@include('admin.layouts.common.deleteModal')

@endsection


@section('scripts')

{{-- Status: Active / Inactive --}}
<script>
    function updateLabelText(checkbox) {
        const label = document.getElementById("isActiveLabel");
        const span  = label.querySelector("span");

        if (checkbox.checked) {
            span.className = 'badge bg-success';
            span.innerHTML = '<i class="bi bi-check-square me-1"></i> Active';
        } else {
            span.className = 'badge bg-danger';
            span.innerHTML = '<i class="bi bi-x-square me-1"></i> Inactive';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('is_active');
        if (checkbox) updateLabelText(checkbox);
    });
</script>

{{-- DataTable --}}
<script>
    let table = null;

    @if ($enrollments->count())
    table = new DataTable('#enrollmentTable', {
        paging: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        lengthChange: true,
        scrollX: true
    });
    @endif
</script>

{{-- Toggle child row --}}
<script>
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.toggle-icon');
        if (!btn || !table) return;

        const tr       = btn.closest('tr');
        const row      = table.row(tr);
        const icon     = btn.querySelector('i');
        const template = tr.querySelector('.child-template');

        if (row.child.isShown()) {
            row.child.hide();
            icon.classList.replace('bi-dash-square', 'bi-plus-square');
        } else {
            row.child(template.innerHTML).show();
            icon.classList.replace('bi-plus-square', 'bi-dash-square');
        }
    });
</script>

{{-- Single delete --}}
<script>
    $(document).on("click", ".deleteBtn", function () {
        const id          = $(this).data("id");
        const deleteRoute = "{{ route('admin.academic.enrollments.destroy', ['enroll' => ':id']) }}";
        $("#deleteForm").attr("action", deleteRoute.replace(':id', id));
    });
</script>

{{-- Bulk course delete --}}
<script>
    $(document).on("click", ".deleteCourseBtn", function () {
        const courseId    = $(this).data("id");
        const label       = $(this).data("label");
        const deleteRoute = "{{ route('admin.academic.enrollments.destroyCourse', ['course' => ':id']) }}";
        $("#deleteBulkForm").attr("action", deleteRoute.replace(':id', courseId));
        $("#delete_bulk_label").text(label);
    });
</script>

{{-- Student picker --}}
<script>
(function () {
    const listEl   = document.getElementById('student-list');
    if (!listEl) return;

    const students = JSON.parse(listEl.dataset.students);
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
        countEl.className   = 'fw-semibold small ' + (n > 0 ? 'text-primary' : 'text-muted');
    }

    function renderHidden() {
        hiddenEl.innerHTML = '';
        [...selected].forEach(id => {
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

            const avatarHtml = s.photo
                ? `<img src="${s.photo}" alt="${s.name}"
                        class="rounded-circle flex-shrink-0 object-fit-cover"
                        style="width:32px;height:32px;"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex';">
                   <span class="rounded-circle d-none align-items-center justify-content-center fw-semibold flex-shrink-0"
                        style="width:32px;height:32px;font-size:11px;
                               background:${isChecked ? '#0d6efd' : 'var(--bs-secondary-bg)'};
                               color:${isChecked ? '#fff' : 'var(--bs-secondary-color)'};">
                        ${initials(s.name)}
                   </span>`
                : `<span class="rounded-circle d-inline-flex align-items-center justify-content-center fw-semibold flex-shrink-0"
                        style="width:32px;height:32px;font-size:11px;
                               background:${isChecked ? '#0d6efd' : 'var(--bs-secondary-bg)'};
                               color:${isChecked ? '#fff' : 'var(--bs-secondary-color)'};">
                        ${initials(s.name)}
                   </span>`;

            row.innerHTML = `
                <input type="checkbox" class="form-check-input mt-0 flex-shrink-0"
                    id="${uid}" ${isChecked ? 'checked' : ''}>
                ${avatarHtml}
                <span class="d-flex flex-column overflow-hidden">
                    <span class="small fw-semibold text-truncate">${s.name}</span>
                    <span class="text-muted text-truncate" style="font-size:11px;">${s.email}</span>
                </span>
                ${isChecked ? '<i class="bi bi-check2 ms-auto text-primary"></i>' : '<span class="ms-auto"></span>'}
            `;

            row.querySelector('input').addEventListener('change', e => {
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

    if (searchEl) {
        searchEl.addEventListener('input', e => { query = e.target.value; renderList(); });
    }

    sync();
})();
</script>

@endsection
