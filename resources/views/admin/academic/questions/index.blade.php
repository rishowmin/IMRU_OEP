@extends('admin.layouts.app')
@section('title', 'Questions')

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
                        <a href="{{ route('admin.academic.questions.create') }}" class="btn btn-sm btn-outline-theme">
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

            <div class="accordion mb-3" id="accordionAcademicquestions">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingquestion">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsequestion" aria-expanded="true" aria-controls="collapsequestion">
                            <h6 class="card-title p-0 m-0">
                                <i class="bi bi-table"></i>
                                @yield('title') List
                            </h6>
                        </button>
                    </h2>
                    <div id="collapsequestion" class="accordion-collapse collapse show" aria-labelledby="headingquestion" data-bs-parent="#accordionAcademicquestions">
                        <div class="accordion-body">

                            <table class="table table-sm small" id="questionTable">
                                <thead>
                                    <tr>
                                        <th width="7%">#</th>
                                        <th width="25%">Question</th>
                                        <th width="8%">Type</th>
                                        <th width="20%">Exam</th>
                                        <th width="10%">Difficulty</th>
                                        <th width="7%">Marks</th>
                                        <th width="10%">Status</th>
                                        <th width="13%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($questionList as $question)

                                    <tr>
                                        <th class="text-start">
                                            <a href="javascript:void(0)" class="toggle-icon me-1">
                                                <i class="bi bi-plus-square"></i>
                                            </a>
                                            {{ $serialNo++ }}
                                        </th>
                                        <td>{{ $question->question_text ?? 'N/A' }}</td>
                                        <td>
                                            @if($question->question_type == 'mcq_2')
                                            MCQ (2)
                                            @elseif($question->question_type == 'mcq_4')
                                            MCQ (4)
                                            @elseif ($question->question_type == 'short_question')
                                            Short Q.
                                            @elseif ($question->question_type == 'long_question')
                                            Long Q.
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <td>{{ $question->exam->exam_title ?? 'N/A' }} [{{ $question->exam->exam_code ?? 'N/A' }}]</td>
                                        <td>
                                            @if($question->difficulty_level == 'easy')
                                            <h6 class="mb-0"><span class="badge rounded-pill bg-success">Easy</span></h6>
                                            @elseif($question->difficulty_level == 'medium')
                                            <h6 class="mb-0"><span class="badge rounded-pill bg-warning">Medium</span></h6>
                                            @else
                                            <h6 class="mb-0"><span class="badge rounded-pill bg-danger">Hard</span></h6>
                                            @endif

                                        </td>
                                        <td>{{ $question->marks ?? 'N/A' }}</td>
                                        <td>
                                            @if($question->is_active == '1')
                                            <h6 class="mb-0"><span class="badge border-success border-1 text-success"><i class="bi bi-check-circle me-1"></i> ACTIVE</span></h6>
                                            @else
                                            <h6 class="mb-0"><span class="badge border-danger border-1 text-danger"><i class="bi bi-x-circle me-1"></i> DEACTIVE</span></h6>
                                            @endif
                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit">
                                                <a href="{{ route('admin.academic.questions.edit', $question->id) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </span>

                                            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger deleteBtn" data-id="{{ $question->id }}" data-bs-toggle="modal" data-bs-target="#delete_modal">
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
                                                        <th width="22%">Evaluation Type</th>
                                                        <td width="70%">
                                                            @if($question->evaluation_type == 'automatic')
                                                            Automatic
                                                            @elseif($question->evaluation_type == 'manual')
                                                            Manual
                                                            @else
                                                            N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Options</th>
                                                        <td width="70%">
                                                            @if ($question->question_type == 'mcq_2')
                                                            <div class="d-flex align-items-center gap-4">
                                                                <span><b>A:</b> {{ $question->option_a ?? 'N/A' }}</span>
                                                                <span><b>B:</b> {{ $question->option_b ?? 'N/A' }}</span>
                                                            </div>
                                                            @elseif ($question->question_type == 'mcq_4')
                                                            <div class="d-flex align-items-center gap-4">
                                                                <span><b>A:</b> {{ $question->option_a ?? 'N/A' }}</span>
                                                                <span><b>B:</b> {{ $question->option_b ?? 'N/A' }}</span>
                                                                <span><b>C:</b> {{ $question->option_c ?? 'N/A' }}</span>
                                                                <span><b>D:</b> {{ $question->option_d ?? 'N/A' }}</span>
                                                            </div>
                                                            @elseif ($question->question_type == 'short_question')
                                                            <span class="text-info">No options for short question.</span>
                                                            @elseif ($question->question_type == 'long_question')
                                                            <span class="text-info">No options for long question.</span>
                                                            @else
                                                            N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="8%" class="text-end"><i class="bi bi-arrow-return-right"></i></td>
                                                        <th width="22%">Correct Answer</th>
                                                        <td width="70%">
                                                            @if ($question->correct_answer == null)
                                                            N/A
                                                            @else
                                                            {{ $question->correct_answer }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </template>
                                    </tr>


                                    @empty

                                    <tr>
                                        <td colspan="8" class="text-center">
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
@if ($questionList->count())
<script>
    const table = new DataTable('#questionTable', {
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
        let question = $(this).data("id");
        let deleteRoute = "{{ route('admin.academic.questions.destroy', ['question' => ':id']) }}";
        $("#deleteForm").attr("action", deleteRoute.replace(':id', question));
    });

</script>

@endsection
