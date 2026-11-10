<form method="POST" action="{{ route('admin.academic.exams.questionPaper.store', $exam->id) }}" enctype="multipart/form-data" id="customQuestionFormEl">
    @csrf

    <div class="modal fade" id="addCustomQuestionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="exam_id" value="{{ $exam->id }}">
            <input type="hidden" name="question_id" id="question_id" value="">

            <div class="modal-content">
                <div class="modal-header bg-theme text-white">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="bi bi-file-earmark-plus me-1"></i>Add Custom Question
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Left Column --}}
                        <div class="col-lg-9">

                            {{-- Row 1: Question Type, Difficulty, Evaluation, Marks --}}
                            <div class="row g-2">
                                {{-- Question Type --}}
                                <div class="col-sm-3">
                                    <label class="form-label fw-bold small mb-1">Question Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('question_type') is-invalid @enderror" id="question_type" name="question_type" required>
                                        <option value="mcq_4" {{ old('question_type') == 'mcq_4' ? 'selected' : '' }}>MCQ (4 Options)</option>
                                        <option value="mcq_2" {{ old('question_type') == 'mcq_2' ? 'selected' : '' }}>MCQ (2 Options)</option>
                                        <option value="short_question" {{ old('question_type') == 'short_question' ? 'selected' : '' }}>Short Question</option>
                                        <option value="long_question" {{ old('question_type') == 'long_question' ? 'selected' : '' }}>Long Question</option>
                                    </select>
                                    @error('question_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Difficulty  --}}
                                <div class="col-sm-3">
                                    <label class="form-label fw-bold small mb-1">Difficulty <span class="text-danger">*</span></label>
                                    <select class="form-select @error('difficulty_level') is-invalid @enderror" id="difficulty_level" name="difficulty_level" required>
                                        <option value="easy" {{ old('difficulty_level') == 'easy' ? 'selected' : '' }}>Easy</option>
                                        <option value="medium" {{ old('difficulty_level', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="hard" {{ old('difficulty_level') == 'hard' ? 'selected' : '' }}>Hard</option>
                                    </select>
                                    @error('difficulty_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Evaluation  --}}
                                <div class="col-sm-3">
                                    <label class="form-label fw-bold small mb-1">Evaluation <span class="text-danger">*</span></label>
                                    <select class="form-select @error('evaluation_type') is-invalid @enderror" id="evaluation_type" name="evaluation_type" required>
                                        <option value="automatic" {{ old('evaluation_type', 'automatic') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                                        <option value="manual" {{ old('evaluation_type') == 'manual' ? 'selected' : '' }}>Manual</option>
                                    </select>
                                    @error('evaluation_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Marks  --}}
                                <div class="col-sm-3">
                                    <label class="form-label fw-bold small mb-1">Marks <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control  @error('marks') is-invalid @enderror" id="marks" name="marks" value="{{ old('marks', 1) }}" min="0" step="0.5" required>
                                    @error('marks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Question Text --}}
                                <div class="col-sm-12">
                                    <label class="form-label fw-bold small mb-1">Question Text <span class="text-danger">*</span></label>
                                    <textarea class="form-control  @error('question_text') is-invalid @enderror" id="question_text" name="question_text" rows="3" placeholder="Enter question text..." required>{{ old('question_text') }}</textarea>
                                    @error('question_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- MCQ Options --}}
                                <div class="col-sm-12" id="option_row">
                                    <label class="form-label fw-bold small mb-1">
                                        Options
                                        <span class="small"><small class="text-muted fw-normal ms-1">[Tick the correct one]</small></span>
                                    </label>
                                    <div class="row g-2">
                                        @foreach(['a', 'b', 'c', 'd'] as $k)
                                        <div class="col-6 input-group-div" id="option_col_{{ $k }}">
                                            <div class="input-group flex-nowrap">
                                                <div class="input-group-text border-theme">
                                                    <input class="form-check-input option-checkbox mt-0" type="radio" name="correct_option" value="{{ $k }}" title="Mark as correct answer" {{ old('correct_option') === $k ? 'checked' : '' }}>
                                                </div>
                                                <input type="text" class="form-control border-theme" style="min-width: 0;" id="option_{{ $k }}" name="option_{{ $k }}" placeholder="Option {{ strtoupper($k) }}" value="{{ old('option_' . $k) }}">
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" id="correct_answer_mcq" name="correct_answer" disabled>
                                </div>

                                {{-- Correct Answer (Short / Long only) --}}
                                <div class="col-sm-12" id="correct_answer_wrap">
                                    <label class="form-label fw-bold small mb-1">Correct Answer</label>
                                    <textarea class="form-control" id="correct_answer" name="correct_answer" rows="3" placeholder="Enter the correct answer...">{{ old('correct_answer') }}</textarea>
                                </div>

                            </div>

                        </div>

                        {{-- Right Column --}}
                        <div class="col-lg-3">

                            <div class="row g-2">
                                {{-- Question Order --}}
                                <div class="col-sm-12">
                                    <label class="form-label fw-bold small mb-1">Question Order</label>
                                    <input type="number" class="form-control " id="question_order" name="question_order" placeholder="Auto" value="{{ old('question_order') }}">
                                </div>

                                {{-- Question Figure --}}
                                <div class="col-sm-12">
                                    <label class="form-label fw-bold small mb-1">Question Figure</label>
                                    <input type="file" class="form-control " id="question_figure" name="question_figure" accept="image/*">
                                    <div class="mt-2 border rounded p-1 text-center">
                                        <img id="figure_preview_img" src="{{ asset('assets/admin/img/img-prev.png') }}" alt="Preview" class="img-fluid" style="max-height: 150px; object-fit: contain;">
                                    </div>
                                    <small class="text-muted">Max 2MB. JPG, PNG.</small>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-sm btn-theme" id="modalSubmitBtn">
                        <i class="bi bi-save me-1"></i>Add Question
                    </button>
                </div>
            </div>
        </div>
    </div>

</form>
