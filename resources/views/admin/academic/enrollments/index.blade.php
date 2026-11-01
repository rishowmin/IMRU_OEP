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
                            <button type="button" class="btn btn-sm btn-outline-theme" data-bs-toggle="modal" data-bs-target="#enrollModal">
                                <i class="bi bi-plus-lg"></i>
                                <span class="ms-1">Add @yield('title')</span>
                            </button>
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
                                        <th>Course Title & Code</th>
                                        <th>Teacher</th>
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
                                        <td>
                                            {{ $courseEnrollments->first()->course->teacher->first_name ?? 'N/A' }} {{ $courseEnrollments->first()->course->teacher->last_name ?? 'N/A' }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $courseEnrollments->count() }} students
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $activeCount   = $courseEnrollments->where('is_active', 1)->count();
                                                $inactiveCount = $courseEnrollments->count() - $activeCount;
                                            @endphp
                                            @if($activeCount)
                                            <span class="badge bg-success-subtle text-success rounded-pill">{{ $activeCount }} Active</span>
                                            @endif
                                            @if($inactiveCount)
                                                <span class="badge bg-danger-subtle text-danger rounded-pill">{{ $inactiveCount }} Deactive</span>
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
                                                                <span class="badge bg-danger-subtle text-danger">Deactive</span>
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

@include('admin.academic.enrollments.includes.enrollModal')
@include('admin.layouts.common.deleteBulkModal')
@include('admin.layouts.common.deleteModal')

@endsection


@section('scripts')

{{-- Status: Active / Inactive --}}
<script>
    function updateLabelText(checkbox, labelId = 'isActiveLabel') {
        const span = document.getElementById(labelId).querySelector('span');

        if (checkbox.checked) {
            span.className = 'badge bg-success';
            span.innerHTML = '<i class="bi bi-check-square me-1"></i> Active';
        } else {
            span.className = 'badge bg-danger';
            span.innerHTML = '<i class="bi bi-x-square me-1"></i> Deactive';
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
    document.querySelectorAll('.student-picker').forEach(initStudentPicker);

    function initStudentPicker(root) {
        const listEl   = root.querySelector('.student-list');
        const hiddenEl = root.querySelector('.student-hidden-inputs');
        const countEl  = root.querySelector('.student-count');
        const searchEl = root.querySelector('.student-search');
        const btnAll   = root.querySelector('.btn-select-all');
        const btnClear = root.querySelector('.btn-clear-all');

        const students = JSON.parse(listEl.dataset.students);
        let selected   = new Set(JSON.parse(listEl.dataset.selected).map(Number));
        let query      = '';

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
                row.className   = 'd-flex align-items-center gap-3 px-3 py-1 border-bottom mb-0'
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
    }
</script>


@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', () => {
        new bootstrap.Modal(document.getElementById('enrollModal')).show();
    });
</script>
@endif

@endsection
