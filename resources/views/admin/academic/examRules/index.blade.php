@extends('admin.layouts.app')
@section('title', 'Exam Rules')

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
                                <i class="bi bi-gear"></i>
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
                            <a href="{{ route('admin.academic.examRules.index') }}" class="btn btn-sm btn-outline-theme">
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
                                {{ isset($examRule) ? 'Edit' : 'Create' }} @yield('title')
                            </h6>
                        </button>
                    </h2>
                    <div id="collapsecourse" class="accordion-collapse collapse show" aria-labelledby="headingcourse" data-bs-parent="#accordionAcademinCourses">
                        <div class="accordion-body">


                            <form action="{{ isset($examRule) ? route('admin.academic.examRules.update', $examRule->id) : route('admin.academic.examRules.store') }}" method="POST">
                                @csrf
                                @if(isset($examRule))
                                @method('PUT')
                                @endif

                                @php $isActive = old('is_active', isset($examRule) ? $examRule->is_active : 1); @endphp

                                <div class="row">

                                    <div class="col-sm-12">

                                        {{-- Type ID --}}
                                        <div class="row align-items-baseline mb-2">
                                            <div class="col-sm-12">
                                                {{-- <label for="type" class="form-label fw-bold"><small>Type</small> <small class="text-danger">*</small></label> --}}
                                                <div class="input-group">
                                                    <select class="form-select form-select-sm @error('type') is-invalid @elseif(old('type', $examRule->type ?? false)) is-valid @enderror" name="type" id="type" class="form-control">
                                                        <option value="instruction" {{ old('type', $examRule->type ?? '') == 'instruction' ? 'selected' : '' }}>Instruction</option>
                                                        <option value="rule" {{ old('type', $examRule->type ?? '') == 'rule' ? 'selected' : '' }}>Rule</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('type')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('type', $examRule->type ?? false))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Title --}}
                                        <div class="row align-items-baseline mb-2">
                                            <div class="col-sm-12">
                                                {{-- <label for="title" class="form-label fw-bold"><small>Title</small> <small class="text-danger">*</small></label> --}}
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-control-sm @error('title') is-invalid @elseif(old('title', $examRule->title ?? false)) is-valid @enderror" id="title" name="title" placeholder="Title*" value="{{ old('title', $examRule->title ?? '') }}">
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    @error('title')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('title', $examRule->title ?? false))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Key --}}
                                        <div class="row align-items-baseline mb-2">
                                            <div class="col-sm-12">
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-control-sm @error('key') is-invalid @elseif(old('key', $examRule->key ?? false)) is-valid @enderror" id="key" name="key" placeholder="Key (e.g. browser_minimized)" value="{{ old('key', $examRule->key ?? '') }}">
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    @error('key')
                                                    <div class="invalid-feedback d-block">
                                                        <i class="bi bi-exclamation-circle"></i>
                                                        {{ $message }}
                                                    </div>
                                                    @else
                                                    @if(old('key', $examRule->key ?? false))
                                                    <div class="valid-feedback d-block">
                                                        <i class="bi bi-check-circle"></i>
                                                        Looks good!
                                                    </div>
                                                    @endif
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Description --}}
                                        <div class="row align-items-baseline mb-2">
                                            <div class="col-sm-12">
                                                {{-- <label for="description" class="form-label fw-bold"><small>Description</small></label> --}}
                                                <div class="input-group">
                                                    <textarea class="form-control form-control-sm" id="description" name="description" placeholder="Description" rows="3">{{ old('description', $examRule->description ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Order --}}
                                        <div class="row align-items-baseline mb-2">
                                            <div class="col-sm-12">
                                                {{-- <label for="order" class="form-label fw-bold"><small>Order</small></label> --}}
                                                <div class="input-group">
                                                    <input type="number" class="form-control form-control-sm" id="order" name="order" placeholder="Order" value="{{ old('order', $examRule->order ?? '') }}">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Status --}}
                                        <div class="row align-items-baseline">
                                            <div class="col-sm-12">
                                                {{-- <label for="is_active" class="form-label fw-bold"><small>Status</small></label> --}}
                                                <div class="input-group">
                                                    <div class="form-check form-switch">
                                                        <input type="hidden" name="is_active" value="0">
                                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $isActive ? 'checked' : '' }} onchange="updateLabelText(this)">
                                                        <label class="form-check-label ms-2" for="is_active" id="isActiveLabel">
                                                            <span class="badge {{ $isActive ? 'bg-success' : 'bg-danger' }}">
                                                                <i class="bi {{ $isActive ? 'bi-check-square' : 'bi-x-square' }} me-1"></i>
                                                                {{ $isActive ? 'Active' : 'Deactive' }}
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
                                        <span class="ms-1">{{ isset($examRule) ? 'Update' : 'Save' }}</span>
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-8">

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

                            <table class="table table-sm small" id="ruleTable">
                                <thead>
                                    <tr>
                                        <th width="10%">#</th>
                                        <th width="20%">Type</th>
                                        <th width="40%">Title</th>
                                        <th width="15%">Status</th>
                                        <th width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ruleList as $rule)

                                    <tr>
                                        <th class="text-start">
                                            <a href="javascript:void(0)" class="toggle-icon me-1">
                                                <i class="bi bi-plus-square"></i>
                                            </a>
                                            {{ $serialNo++ }}
                                        </th>
                                        <td>
                                            @if ($rule->type == 'rule')
                                            Rule
                                            @else
                                            Instruction
                                            @endif
                                        </td>
                                        <td>{{ $rule->title }}</td>
                                        <td>
                                            @if($rule->is_active == '1')
                                            <h6 class="mb-0"><span class="badge border-success border-1 text-success"><i class="bi bi-check-circle me-1"></i> ACTIVE</span></h6>
                                            @else
                                            <h6 class="mb-0"><span class="badge border-danger border-1 text-danger"><i class="bi bi-x-circle me-1"></i> DEACTIVE</span></h6>
                                            @endif

                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit">
                                                <a href="{{ route('admin.academic.examRules.edit', $rule->id) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </span>

                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger deleteBtn" data-id="{{ $rule->id }}" data-bs-toggle="modal" data-bs-target="#delete_modal">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                                @include('admin.layouts.common.deleteModal')
                                            </span>
                                        </td>

                                        <!-- hidden child content -->
                                        <template class="child-template">
                                            <table class="table table-sm mb-0 w-100 small">
                                                <tbody>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Key</th>
                                                        <td width="70%">{{ $rule->key ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Description</th>
                                                        <td width="70%">{{ $rule->description ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Order</th>
                                                        <td width="70%">{{ $rule->order ?? 'N/A' }}</td>
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
@if ($ruleList->count())
<script>
    const table = new DataTable('#ruleTable', {
        paging: true
        , pageLength: 10
        , lengthMenu: [5, 10, 25, 50, 100]
        , lengthChange: true
        , scrollX: true
    , });

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
        let examRule = $(this).data("id");
        let deleteRoute = "{{ route('admin.academic.examRules.destroy', ['examRule' => ':id']) }}";
        $("#deleteForm").attr("action", deleteRoute.replace(':id', examRule));
    });

</script>

{{-- Auto-generate key from title --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const keyInput = document.getElementById('key');

        titleInput.addEventListener('input', function() {
            // Only auto-fill if key is empty or was previously auto-generated
            if (keyInput.dataset.manuallyEdited !== 'true') {
                keyInput.value = titleInput.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s_]/g, '')
                    .replace(/\s+/g, '_');
            }
        });

        // Mark as manually edited if user types in key field
        keyInput.addEventListener('input', function() {
            keyInput.dataset.manuallyEdited = 'true';
        });

        // If key is cleared, allow auto-generation again
        keyInput.addEventListener('blur', function() {
            if (keyInput.value === '') {
                keyInput.dataset.manuallyEdited = 'false';
            }
        });
    });

</script>

@endsection

