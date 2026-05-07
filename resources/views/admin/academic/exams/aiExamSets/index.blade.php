@extends('admin.layouts.app')
@section('title', 'Exam Sets by AI')

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
                                <i class="bi bi-stars"></i>
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
                            <a href="{{ route('admin.academic.aiExamSets.create') }}" class="btn btn-sm btn-outline-theme">
                                <i class="bi bi-stars"></i>
                                <span class="ms-1">Generate New Exam</span>
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

            <div class="accordion mb-3" id="accordionAiExamSets">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingAiExamSets">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAiExamSets" aria-expanded="true" aria-controls="collapseAiExamSets">
                            <h6 class="card-title p-0 m-0">
                                <i class="bi bi-table"></i>
                                @yield('title') List
                            </h6>
                        </button>
                    </h2>
                    <div id="collapseAiExamSets" class="accordion-collapse collapse show" aria-labelledby="headingAiExamSets" data-bs-parent="#accordionAiExamSets">
                        <div class="accordion-body px-0">

                            <table class="table table-sm small" id="generateExamByAiTable">
                                <thead>
                                    <tr>
                                        <th width="7%">#</th>
                                        <th width="18%">Exam Title</th>
                                        <th width="17%">Topic</th>
                                        <th width="10%">Questions</th>
                                        <th width="13%">Difficulty</th>
                                        <th width="12%">Duration</th>
                                        <th width="10%">Status</th>
                                        <th width="13%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($examSets as $set)

                                    <tr>
                                        <th class="text-start">{{ $serialNo++ }}</th>
                                        <td>{{ $set->title }}</td>
                                        <td>{{ $set->topic }}</td>
                                        <td>{{ $set->total_questions }}</td>
                                        <td>
                                            <div style="display:flex;gap:3px;align-items:center">
                                                <span style="width:{{ $set->easy_count / max($set->total_questions,1) * 60 }}px;height:8px;background:#198754;border-radius:2px" title="Easy: {{ $set->easy_count }}"></span>
                                                <span style="width:{{ $set->medium_count / max($set->total_questions,1) * 60 }}px;height:8px;background:#ffc107;border-radius:2px" title="Medium: {{ $set->medium_count }}"></span>
                                                <span style="width:{{ $set->hard_count / max($set->total_questions,1) * 60 }}px;height:8px;background:#dc3545;border-radius:2px" title="Hard: {{ $set->hard_count }}"></span>
                                            </div>
                                            <small class="text-muted" style="font-size:.75rem">{{ $set->easy_count }}E · {{ $set->medium_count }}M · {{ $set->hard_count }}H</small>
                                        </td>
                                        <td>{{ $set->duration_minutes }}m</td>
                                        <td>
                                            @php $colors = ['draft'=>'secondary','active'=>'success','archived'=>'danger']; @endphp
                                            <span class="badge bg-{{ $colors[$set->status] ?? 'secondary' }}">{{ ucfirst($set->status) }}</span>
                                        </td>
                                        <td>
                                            {{-- <a href="{{ route('admin.academic.aiExamSets.show', $set) }}" class="btn btn-sm btn-outline-primary">View</a> --}}

                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="View">
                                                <a href="{{ route('admin.academic.aiExamSets.show', $set) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </span>

                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger deleteBtn" data-id="{{ $set->id }}" data-bs-toggle="modal" data-bs-target="#delete_modal">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                                @include('admin.layouts.common.deleteModal')
                                            </span>
                                        </td>
                                    </tr>

                                    @empty

                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <strong>
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                <span>No Exam Sets Available</span>
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
@if ($examSets->count())
<script>
    const table = new DataTable('#generateExamByAiTable', {
        paging: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 100, 200],
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
        let examSet = $(this).data("id");
        let deleteRoute = "{{ route('admin.academic.aiExamSets.destroy', ['examSet' => ':id']) }}";
        $("#deleteForm").attr("action", deleteRoute.replace(':id', examSet));
    });
</script>

@endsection
