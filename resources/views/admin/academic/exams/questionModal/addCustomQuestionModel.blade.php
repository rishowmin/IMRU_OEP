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
                            <div class="row g-2 mb-2">
                                <div class="col-sm-3">
                                    <label class="form-label fw-bold small">Question Type <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('question_type') is-invalid @enderror" id="question_type" name="question_type" required>
                                        <option value="mcq_4" {{ old('question_type') == 'mcq_4' ? 'selected' : '' }}>MCQ (4 Options)</option>
                                        <option value="mcq_2" {{ old('question_type') == 'mcq_2' ? 'selected' : '' }}>MCQ (2 Options)</option>
                                        <option value="short_question" {{ old('question_type') == 'short_question' ? 'selected' : '' }}>Short Question</option>
                                        <option value="long_question" {{ old('question_type') == 'long_question' ? 'selected' : '' }}>Long Question</option>
                                    </select>
                                    @error('question_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-3">
                                    <label class="form-label fw-bold small">Difficulty <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('difficulty_level') is-invalid @enderror" id="difficulty_level" name="difficulty_level" required>
                                        <option value="easy" {{ old('difficulty_level') == 'easy' ? 'selected' : '' }}>Easy</option>
                                        <option value="medium" {{ old('difficulty_level', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="hard" {{ old('difficulty_level') == 'hard' ? 'selected' : '' }}>Hard</option>
                                    </select>
                                    @error('difficulty_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-3">
                                    <label class="form-label fw-bold small">Evaluation <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('evaluation_type') is-invalid @enderror" id="evaluation_type" name="evaluation_type" required>
                                        <option value="automatic" {{ old('evaluation_type', 'automatic') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                                        <option value="manual" {{ old('evaluation_type') == 'manual' ? 'selected' : '' }}>Manual</option>
                                    </select>
                                    @error('evaluation_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-3">
                                    <label class="form-label fw-bold small">Marks <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control form-control-sm @error('marks') is-invalid @enderror" id="marks" name="marks" value="{{ old('marks', 1) }}" min="0" step="0.5" required>
                                    @error('marks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Question Text --}}
                            <div class="mb-2">
                                <label class="form-label fw-bold small">Question Text <span class="text-danger">*</span></label>
                                <textarea class="form-control form-control-sm @error('question_text') is-invalid @enderror" id="question_text" name="question_text" rows="3" placeholder="Enter question text..." required>{{ old('question_text') }}</textarea>
                                @error('question_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- MCQ Options --}}
                            <div class="mb-2" id="option_row">
                                <label class="form-label fw-bold small">Options</label>
                                <div class="row g-2">
                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroupPrepend" style="font-size: 14px; padding: 4px 10px;">A</span>
                                            <input type="text" class="form-control form-control-sm" id="option_a" name="option_a" placeholder="Option A" value="{{ old('option_a') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroupPrepend" style="font-size: 14px; padding: 4px 10px;">B</span>
                                            <input type="text" class="form-control form-control-sm" id="option_b" name="option_b" placeholder="Option B" value="{{ old('option_b') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6" id="option_c_wrap">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroupPrepend" style="font-size: 14px; padding: 4px 10px;">C</span>
                                            <input type="text" class="form-control form-control-sm" id="option_c" name="option_c" placeholder="Option C" value="{{ old('option_c') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6" id="option_d_wrap">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="inputGroupPrepend" style="font-size: 14px; padding: 4px 10px;">D</span>
                                            <input type="text" class="form-control form-control-sm" id="option_d" name="option_d" placeholder="Option D" value="{{ old('option_d') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Correct Answer --}}
                            <div class="mb-2">
                                <label class="form-label fw-bold small">Correct Answer</label>
                                <input type="text" class="form-control form-control-sm" id="correct_answer" name="correct_answer" placeholder="e.g. Dhaka or A" value="{{ old('correct_answer') }}">
                            </div>

                        </div>

                        {{-- Right Column --}}
                        <div class="col-lg-3">

                            {{-- Question Order --}}
                            <div class="mb-2">
                                <label class="form-label fw-bold small">Question Order</label>
                                <input type="number" class="form-control form-control-sm" id="question_order" name="question_order" placeholder="Auto" value="{{ old('question_order') }}">
                            </div>

                            {{-- Question Figure --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Question Figure</label>
                                <input type="file" class="form-control form-control-sm" id="question_figure" name="question_figure" accept="image/*">
                                <div class="mt-2 border rounded p-1 text-center">
                                    <img id="figure_preview_img" src="{{ asset('assets/admin/img/img-prev.png') }}" alt="Preview" class="img-fluid" style="max-height: 150px; object-fit: contain;">
                                </div>
                                <small class="text-muted">Max 2MB. JPG, PNG.</small>
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
