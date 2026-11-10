@extends('admin.layouts.app')
@section('title', 'Exams')
@section('title2', 'Question Paper')

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
                                <i class="bi bi-plus-square"></i>
                                <span class="ms-1">@yield('title2')</span>
                            </h1>
                            <nav style="--bs-breadcrumb-divider: '•';">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                    <li class="breadcrumb-item "><a href="{{ route('admin.academic.exams.index') }}">@yield('title')</a></li>
                                    <li class="breadcrumb-item active">@yield('title2')</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="card-header-right">
                            <a href="{{ route('admin.academic.exams.index') }}" class="btn btn-outline-theme btn-sm">
                                <i class="bi bi-arrow-left-square"></i>
                                <span class="ms-1">Back to List</span>
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

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="exam-card-left">
                            <h5 class="fw-bold text-theme mb-1">
                                <span>{{ $exam->exam_title }} - <small>[{{ $exam->exam_code }}]</small></span>
                            </h5>
                            <h6 class="mb-0">
                                <span class="badge bg-dark">Total Questions: {{ $exam->questions->count() }}</span>
                            </h6>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="exam-card-right d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-theme btn-sm" data-bs-toggle="modal" data-bs-target="#addQuestionFromLibraryModal">
                                <i class="bi bi-journal-plus me-1"></i> From Question Bank
                            </button>
                            <button type="button" class="btn btn-theme btn-sm" data-bs-toggle="modal" data-bs-target="#addCustomQuestionModal">
                                <i class="bi bi-plus-square me-1"></i> Add Custom Question
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="question-list-div">
                        {{-- Question List --}}
                        @if ($exam->questions->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach ($exam->questions as $question)
                            <div class="border shadow-sm overflow-hidden">
                                <div class="row g-0">

                                    {{-- Sidebar --}}
                                    <div class="col-2 bg-light border-end d-flex flex-column align-items-center gap-2 p-3">

                                        <h6 class="text-muted fw-bold mb-1">Q{{ $serialNo++ }}.</h6>

                                        {{-- Difficulty --}}
                                        <div class="difficulty-level w-100 small">
                                            @if($question->difficulty_level == 'easy')
                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle w-100 text-center">Easy</span>
                                            @elseif($question->difficulty_level == 'medium')
                                            <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle w-100 text-center">Medium</span>
                                            @else
                                            <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle w-100 text-center">Hard</span>
                                            @endif
                                        </div>

                                        {{-- Question Type --}}
                                        <div class="question-type w-100 small">
                                            @if($question->question_type == 'mcq_2')
                                            <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle w-100 text-center">MCQ 2</span>
                                            @elseif($question->question_type == 'mcq_4')
                                            <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle w-100 text-center">MCQ 4</span>
                                            @elseif($question->question_type == 'short_question')
                                            <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle w-100 text-center">Short Q.</span>
                                            @else
                                            <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle w-100 text-center">Long Q.</span>
                                            @endif
                                        </div>

                                        {{-- Evaluation Type --}}
                                        <div class="evaluation-type w-100 small">
                                            @if($question->evaluation_type == 'automatic')
                                            <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle w-100 text-center">Automatic</span>
                                            @else
                                            <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle w-100 text-center">Manual</span>
                                            @endif
                                        </div>

                                        {{-- Action Buttons --}}
                                        <div class="d-flex gap-1 mt-2">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-warning editQuestionBtn"
                                            data-id="{{ $question->id }}"
                                            data-exam_id="{{ $exam->id }}"
                                            data-question_type="{{ $question->question_type }}"
                                            data-question_text="{{ $question->question_text }}"
                                            data-difficulty_level="{{ $question->difficulty_level }}"
                                            data-marks="{{ $question->marks }}"
                                            data-evaluation_type="{{ $question->evaluation_type }}"
                                            data-option_a="{{ $question->option_a }}"
                                            data-option_b="{{ $question->option_b }}"
                                            data-option_c="{{ $question->option_c }}"
                                            data-option_d="{{ $question->option_d }}"
                                            data-correct_answer="{{ $question->correct_answer }}"
                                            data-question_order="{{ $question->question_order }}"
                                            data-question_figure="{{ $question->question_figure ? asset('storage/question_figure/' . $question->question_figure) : '' }}"
                                            data-bs-toggle="modal" data-bs-target="#addCustomQuestionModal" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm deleteBtn" data-id="{{ $question->id }}" data-exam="{{ $exam->id }}" data-bs-toggle="modal" data-bs-target="#delete_modal" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>

                                    </div>
                                    {{-- end sidebar --}}

                                    {{-- Question Body --}}
                                    <div class="col-10 p-3">

                                        {{-- Question Text --}}
                                        <p class="fw-semibold mb-3">
                                            {{ $question->question_text }}
                                        </p>

                                        {{-- Question Figure --}}
                                        @if($question->question_figure)
                                        <div class="border rounded text-center p-2 mb-3 mx-auto w-50">
                                            <img src="{{ asset('storage/question_figure/' . $question->question_figure) }}" alt="Question Figure" class="img-fluid" style="max-height: 140px; object-fit: contain;">
                                        </div>
                                        @endif

                                        {{-- MCQ Options --}}
                                        @if(in_array($question->question_type, ['mcq_2', 'mcq_4']))
                                        <div class="row g-2 mb-3">

                                            @if($question->option_a)
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-center gap-2 rounded px-3 py-2 border {{ $question->correct_answer == $question->option_a ? 'bg-success-subtle border-success-subtle' : 'bg-light' }}">
                                                    <p class="badge rounded-1 mb-0 {{ $question->correct_answer == $question->option_a ? 'bg-success' : 'bg-secondary' }}">A</p>
                                                    <p class="mb-0 {{ $question->correct_answer == $question->option_a ? 'text-success fw-medium' : '' }}">{{ $question->option_a }}</p>
                                                </div>
                                            </div>
                                            @endif

                                            @if($question->option_b)
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-center gap-2 rounded px-3 py-2 border {{ $question->correct_answer == $question->option_b ? 'bg-success-subtle border-success-subtle' : 'bg-light' }}">
                                                    <p class="badge rounded-1 mb-0 {{ $question->correct_answer == $question->option_b ? 'bg-success' : 'bg-secondary' }}">B</p>
                                                    <p class="mb-0 {{ $question->correct_answer == $question->option_b ? 'text-success fw-medium' : '' }}">{{ $question->option_b }}</p>
                                                </div>
                                            </div>
                                            @endif

                                            @if($question->option_c)
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-center gap-2 rounded px-3 py-2 border {{ $question->correct_answer == $question->option_c ? 'bg-success-subtle border-success-subtle' : 'bg-light' }}">
                                                    <p class="badge rounded-1 mb-0 {{ $question->correct_answer == $question->option_c ? 'bg-success' : 'bg-secondary' }}">C</p>
                                                    <p class="mb-0 {{ $question->correct_answer == $question->option_c ? 'text-success fw-medium' : '' }}">{{ $question->option_c }}</p>
                                                </div>
                                            </div>
                                            @endif

                                            @if($question->option_d)
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-center gap-2 rounded px-3 py-2 border {{ $question->correct_answer == $question->option_d ? 'bg-success-subtle border-success-subtle' : 'bg-light' }}">
                                                    <p class="badge rounded-1 mb-0 {{ $question->correct_answer == $question->option_d ? 'bg-success' : 'bg-secondary' }}">D</p>
                                                    <p class="mb-0 {{ $question->correct_answer == $question->option_d ? 'text-success fw-medium' : '' }}">{{ $question->option_d }}</p>
                                                </div>
                                            </div>
                                            @endif

                                        </div>
                                        @endif

                                        {{-- Footer: Answer + Marks --}}
                                        <div class="d-flex align-items-baseline justify-content-between pt-2 border-top">
                                            <div class="answer-col">
                                                @if($question->correct_answer)
                                                <p class="mb-0 text-muted fw-bold small">Correct Answer</p>
                                                <p class="mb-0 small">
                                                    {{ $question->correct_answer }}
                                                </p>
                                                @else
                                                <span class="text-muted fst-italic small">Requires manual evaluation</span>
                                                @endif
                                            </div>
                                            <div class="marks-col d-flex align-items-baseline gap-1">
                                                <p class="mb-0 text-muted fw-bold fs-6">{{ intval($question->marks) }}</p>
                                                <p class="mb-0 small">mark{{ intval($question->marks) != 1 ? 's' : '' }}</p>
                                            </div>
                                        </div>

                                    </div>
                                    {{-- end col --}}
                                </div>
                                {{-- end row g-0 --}}
                            </div>
                            {{-- end card --}}
                            @endforeach
                        </div>
                        @else
                        <div class="alert alert-info d-flex align-items-center gap-2" role="alert">
                            <i class="bi bi-info-circle-fill"></i>
                            No questions have been added to this exam yet.
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>

</section>

@include('admin.layouts.common.deleteModal')
@include('admin.academic.exams.questionModal.addCustomQuestionModel')
@include('admin.academic.exams.questionModal.addQuestionFromLibraryModel')

@endsection


@section('scripts')

{{-- Figure image preview --}}
<script>
    document.getElementById('question_figure')?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => document.getElementById('figure_preview_img').src = e.target.result;
        reader.readAsDataURL(file);
    });
</script>

<script>
    function handleOptions() {
        const type = $('#question_type').val();
        const isMcq = type === 'mcq_2' || type === 'mcq_4';
        const is4 = type === 'mcq_4';

        $('#option_row').toggle(isMcq);
        $('#option_col_c, #option_col_d').toggle(is4);

        if (!isMcq) {
            $('#option_a, #option_b, #option_c, #option_d').val('');
            $('input[name="correct_option"]').prop('checked', false);
        } else if (!is4) {
            $('#option_c, #option_d').val('');
            $('input[name="correct_option"][value="c"], input[name="correct_option"][value="d"]').prop('checked', false);
        }

        $('#correct_answer_wrap').toggle(!isMcq);
        $('#correct_answer').prop('disabled', isMcq);
        $('#correct_answer_mcq').prop('disabled', !isMcq);

        highlightCorrectOption();
    }

    function highlightCorrectOption() {
        $('#option_row .input-group-div').removeClass('option-correct');
        $('input[name="correct_option"]:checked').closest('.input-group-div').addClass('option-correct');
    }

    $(document).ready(function () {
        const $modal = $('#addCustomQuestionModal');

        $('#question_type').select2({ dropdownParent: $modal, width: '100%' }).on('change', handleOptions);
        $('#difficulty_level').select2({ dropdownParent: $modal, width: '100%' });
        $('#evaluation_type').select2({ dropdownParent: $modal, width: '100%' });

        handleOptions();

        $(document).on('change', 'input[name="correct_option"]', highlightCorrectOption);

        // Copy the ticked option's text into correct_answer before submitting
        $('#customQuestionFormEl').on('submit', function (e) {
            const type = $('#question_type').val();
            if (type !== 'mcq_2' && type !== 'mcq_4') return;

            const key = $('input[name="correct_option"]:checked').val();
            if (!key) {
                e.preventDefault();
                alert('Please tick the correct option.');
                return;
            }

            const text = $('#option_' + key).val().trim();
            if (!text) {
                e.preventDefault();
                alert('The option you marked as correct is empty.');
                return;
            }

            $('#correct_answer_mcq').val(text);
        });
    });
</script>

<script>
    // Reset modal for Add
    document.getElementById('addCustomQuestionModal').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('customQuestionFormEl');
        form.reset();
        $('input[name="correct_option"]').prop('checked', false);
        form.action = "{{ route('admin.academic.exams.questionPaper.store', $exam->id) }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('question_id').value = '';
        document.getElementById('modalTitle').innerHTML = '<i class="bi bi-file-earmark-plus me-1"></i>Add Custom Question';
        document.getElementById('modalSubmitBtn').innerHTML = '<i class="bi bi-save me-1"></i>Add Question';
        document.getElementById('figure_preview_img').src = "{{ asset('assets/admin/img/img-prev.png') }}";

        // refresh Select2 UI (this also runs handleOptions via the change listener)
        $('#question_type, #difficulty_level, #evaluation_type').trigger('change');
    });

    // Populate modal for Edit
    document.querySelectorAll('.editQuestionBtn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const data = this.dataset;
            const form = document.getElementById('customQuestionFormEl');

            form.action = "{{ route('admin.academic.exams.questionPaper.update', ['exam' => $exam->id, 'question' => ':id']) }}".replace(':id', data.id);
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('question_id').value = data.id;
            document.getElementById('question_text').value = data.question_text;
            document.getElementById('marks').value = data.marks;
            document.getElementById('option_a').value = data.option_a;
            document.getElementById('option_b').value = data.option_b;
            document.getElementById('option_c').value = data.option_c;
            document.getElementById('option_d').value = data.option_d;
            document.getElementById('correct_answer').value = data.correct_answer;
            document.getElementById('question_order').value = data.question_order;

            document.getElementById('figure_preview_img').src =
                data.question_figure || "{{ asset('assets/admin/img/img-prev.png') }}";

            document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil-square me-1"></i>Edit Question';
            document.getElementById('modalSubmitBtn').innerHTML = '<i class="bi bi-save me-1"></i>Update Question';

            // Selects last (this runs handleOptions)
            $('#difficulty_level').val(data.difficulty_level).trigger('change');
            $('#evaluation_type').val(data.evaluation_type).trigger('change');
            $('#question_type').val(data.question_type).trigger('change');

            // Tick the radio whose text matches the saved answer
            $('input[name="correct_option"]').prop('checked', false);
            ['a', 'b', 'c', 'd'].forEach(function (k) {
                if (data['option_' + k] && data['option_' + k] === data.correct_answer) {
                    $('input[name="correct_option"][value="' + k + '"]').prop('checked', true);
                }
            });
            highlightCorrectOption();
        });
    });
</script>

<script>
    // Library modal: search + filter + counter
    (function () {
        const searchInput   = document.getElementById('librarySearch');
        const typeFilter    = document.getElementById('libraryTypeFilter');
        const selectAll     = document.getElementById('selectAllLibrary');
        const selectedCount = document.getElementById('selectedCount');
        const submitCount   = document.getElementById('submitCount');
        const submitBtn     = document.getElementById('librarySubmitBtn');
        const visibleCount  = document.getElementById('visibleCount');
        const noResults     = document.getElementById('libraryNoResults');

        function getItems() {
            return document.querySelectorAll('.library-question-item');
        }

        function updateCounts() {
            const checked = document.querySelectorAll('.library-checkbox:checked').length;
            selectedCount.textContent = checked + ' selected';
            submitCount.textContent   = checked;
            if (submitBtn) submitBtn.disabled = checked === 0;
        }

        function filterItems() {
            const keyword = searchInput ? searchInput.value.toLowerCase() : '';
            const type    = typeFilter  ? typeFilter.value : '';
            let visible   = 0;

            getItems().forEach(function (item) {
                const matchText  = item.dataset.questionText.includes(keyword) || item.dataset.topic.includes(keyword);
                const matchType  = !type || item.dataset.questionType === type;
                const show       = matchText && matchType;
                item.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            // Show/hide topic groups and update totalBadge per topic
            document.querySelectorAll('.library-topic-group').forEach(function (group) {
                const visibleItems = Array.from(group.querySelectorAll('.library-question-item'))
                    .filter(i => i.style.display !== 'none');
                const anyVisible = visibleItems.length > 0;
                group.style.display = anyVisible ? '' : 'none';

                // Update total badge to reflect filtered count
                const topicId   = group.querySelector('.library-question-item')?.dataset.topicId;
                if (topicId) {
                    const totalBadge = document.getElementById('totalBadge_' + topicId);
                    if (totalBadge) totalBadge.textContent = visibleItems.length;
                }
            });

            if (visibleCount) visibleCount.textContent = visible;
            if (noResults) noResults.classList.toggle('d-none', visible > 0);

            // Sync select-all state
            if (selectAll) {
                const visibleCheckboxes = Array.from(document.querySelectorAll('.library-question-item'))
                    .filter(i => i.style.display !== 'none')
                    .map(i => i.querySelector('.library-checkbox'));
                selectAll.checked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => cb.checked);
            }
        }

        if (searchInput) searchInput.addEventListener('input', filterItems);
        if (typeFilter)  typeFilter.addEventListener('change', filterItems);

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                document.querySelectorAll('.library-question-item').forEach(function (item) {
                    if (item.style.display !== 'none') {
                        item.querySelector('.library-checkbox').checked = selectAll.checked;
                    }
                });
                updateCounts();
            });
        }

        document.querySelectorAll('.library-checkbox').forEach(function (cb) {
            cb.addEventListener('change', updateCounts);
        });

        // Reset on modal close
        const modal = document.getElementById('addQuestionFromLibraryModal');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function () {
                if (searchInput) searchInput.value = '';
                if (typeFilter)  typeFilter.value  = '';
                document.querySelectorAll('.library-checkbox').forEach(cb => cb.checked = false);
                if (selectAll) selectAll.checked = false;
                filterItems();
                updateCounts();
            });
        }
    })();
</script>

{{-- Delete modal action binding --}}
<script>
    $(document).on("click", ".deleteBtn", function() {
        let question = $(this).data("id");
        let exam = $(this).data("exam");
        let deleteRoute = "{{ route('admin.academic.exams.questionPaper.destroy', ['exam' => ':exam', 'question' => ':id']) }}";
        $("#deleteForm").attr("action", deleteRoute.replace(':id', question).replace(':exam', exam));
    });

</script>

@endsection
