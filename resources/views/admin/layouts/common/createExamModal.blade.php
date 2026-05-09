<div class="modal createExam-modal fade" id="createExam_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-5">
                <div class="modal-icon">
                    <i class="bi bi-send text-theme"></i>
                </div>
                <div class="modal-query">
                    <h3 class="text-theme fw-bold">Are You Sure?</h3>
                    <p>
                        Create exam with <strong>{{ $examSet->total_questions }}</strong> questions
                        for the selected course?
                    </p>
                </div>

                <div class="modal-button text-center mt-4">
                    <button type="button" class="btn btn-outline-dark btn-sm me-1" data-bs-dismiss="modal">
                        <i class="bi bi-x-square"></i>
                        <span class="ms-1">Cancel</span>
                    </button>

                    <button type="button" class="btn btn-theme btn-sm ms-1" id="confirmPublishBtn">
                        <i class="bi bi-send"></i>
                        <span class="ms-1">Yes, Create!</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
