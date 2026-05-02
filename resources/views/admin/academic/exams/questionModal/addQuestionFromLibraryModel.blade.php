<form method="POST" action="{{ route('admin.academic.exams.questionPaper.library', $exam->id) }}" id="libraryQuestionFormEl">
    @csrf
    <div class="modal fade" id="addQuestionFromLibraryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                {{-- Header --}}
                <div class="modal-header bg-theme text-white py-3">
                    <h5 class="modal-title fw-semibold">
                        <i class="bi bi-journal-bookmark-fill me-2"></i>Add Questions From Library
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                @if($questionLibrary->isEmpty())

                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center gap-2 mb-0">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                        <span>No active questions found in the library.</span>
                    </div>
                </div>

                @else

                {{-- Toolbar --}}
                <div class="bg-light border-bottom px-3 py-2">
                    <div class="row g-2 align-items-center">

                        <div class="col-md-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-theme border-theme text-light border-end-0">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" id="librarySearch" class="form-control form-control-sm" placeholder="Search by question or topic...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <select id="libraryTypeFilter" class="form-select form-select-sm">
                                <option value="">All Question Types</option>
                                <option value="mcq_4">MCQ (4 Options)</option>
                                <option value="mcq_2">MCQ (2 Options)</option>
                                <option value="short_question">Short Question</option>
                                <option value="long_question">Long Question</option>
                            </select>
                        </div>

                        <div class="col-md-4 d-flex align-items-center justify-content-end gap-2">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="selectAllLibrary">
                                <label class="form-check-label small fw-semibold text-secondary" for="selectAllLibrary">
                                    Select All Visible
                                </label>
                            </div>
                            <span class="badge rounded-pill text-bg-secondary" id="selectedCount">0 selected</span>
                        </div>

                    </div>
                </div>

                {{-- Question List --}}
                <div class="modal-body p-0" style="max-height: 480px; overflow-y: auto;">

                    {{-- No Results --}}
                    <div id="libraryNoResults" class="d-none text-center py-5 text-muted">
                        <i class="bi bi-search d-block mb-2" style="font-size: 2rem;"></i>
                        <p class="mb-0 small">No questions match your search or filter.</p>
                    </div>

                    {{-- Accordion --}}
                    <div class="accordion" id="libraryAccordion">

                        @foreach($questionLibrary as $topic => $questions)
                        @php $topicId = 'topic_' . Str::slug($topic); @endphp

                        <div class="accordion-item library-topic-group" data-topic="{{ strtolower($topic) }}">

                            {{-- Accordion Header --}}
                            <h2 class="accordion-header" id="heading_{{ $topicId }}">
                                <button class="accordion-button collapsed py-2 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $topicId }}" aria-expanded="false" aria-controls="{{ $topicId }}">

                                    <div class="d-flex align-items-center gap-2 me-3 w-100">
                                        <i class="bi bi-folder2-open text-secondary" style="font-size: 13px;"></i>
                                        <span class="fw-bold text-secondary text-uppercase" style="font-size: 11px; letter-spacing: .05em;">
                                            {{ $topic }}
                                        </span>
                                        <span class="badge text-bg-secondary rounded-pill" style="font-size: 10px;" id="totalBadge_{{ $topicId }}">
                                            {{ $questions->count() }}
                                        </span>
                                        <span class="badge rounded-pill bg-theme d-none" style="font-size: 10px;" id="selectedBadge_{{ $topicId }}">
                                        </span>
                                    </div>

                                </button>
                            </h2>

                            {{-- Accordion Body --}}
                            <div id="{{ $topicId }}" class="accordion-collapse collapse" aria-labelledby="heading_{{ $topicId }}">

                                <div class="accordion-body p-0">

                                    @foreach($questions as $lib)
                                    <div class="library-question-item border-bottom" data-question-type="{{ $lib->question_type }}" data-question-text="{{ strtolower($lib->question_text) }}" data-topic="{{ strtolower($topic) }}" data-topic-id="{{ $topicId }}">

                                        <label class="d-flex align-items-start gap-3 px-3 py-3 w-100" for="lib_{{ $lib->id }}" style="cursor: pointer;">

                                            {{-- Checkbox --}}
                                            <input class="form-check-input flex-shrink-0 mt-1 library-checkbox" type="checkbox" name="library_question_ids[]" id="lib_{{ $lib->id }}" value="{{ $lib->id }}" data-topic-id="{{ $topicId }}">

                                            {{-- Body --}}
                                            <div class="w-100">

                                                {{-- Question Text --}}
                                                <p class="mb-2 fw-semibold text-dark" style="font-size: 13px; line-height: 1.5;">
                                                    {{ $lib->question_text }}
                                                </p>

                                                {{-- Meta Badges --}}
                                                <div class="d-flex flex-wrap gap-1">

                                                    <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle" style="font-size: 11px;">
                                                        @if($lib->question_type == 'mcq_4') <i class="bi bi-ui-checks me-1"></i>MCQ 4
                                                        @elseif($lib->question_type == 'mcq_2') <i class="bi bi-ui-checks me-1"></i>MCQ 2
                                                        @elseif($lib->question_type == 'short_question') <i class="bi bi-chat-left-text me-1"></i>Short Q.
                                                        @else <i class="bi bi-text-paragraph me-1"></i>Long Q.
                                                        @endif
                                                    </span>

                                                    @if($lib->correct_answer)
                                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle" style="font-size: 11px;">
                                                        <i class="bi bi-check-circle me-1"></i>{{ $lib->correct_answer }}
                                                    </span>
                                                    @else
                                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 11px;">
                                                        <i class="bi bi-pencil me-1"></i>Manual Eval.
                                                    </span>
                                                    @endif

                                                    @if($lib->question_figure)
                                                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle" style="font-size: 11px;">
                                                        <i class="bi bi-image me-1"></i>Has Figure
                                                    </span>
                                                    @endif

                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach

                                </div>
                            </div>

                        </div>
                        @endforeach

                    </div>
                    {{-- end accordion --}}

                </div>
                {{-- end modal-body --}}

                {{-- Footer --}}
                <div class="modal-footer justify-content-between border-top bg-light py-2">
                    <small class="text-muted">
                        <i class="bi bi-list-ul me-1"></i>
                        <span id="visibleCount">{{ $questionLibrary->flatten()->count() }}</span> question(s) shown
                    </small>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-sm btn-theme" id="librarySubmitBtn" disabled>
                            <i class="bi bi-plus-circle me-1"></i>
                            Add Selected &nbsp;<span class="badge text-bg-light" id="submitCount">0</span>
                        </button>
                    </div>
                </div>

                @endif

            </div>
        </div>
    </div>

</form>

