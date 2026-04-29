<div class="modal fade" id="resetAttemptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="resetAttemptForm" method="POST" action="">
                @csrf
                <div class="modal-body p-5">
                    <div class="modal-icon">
                        <i class="bi bi-arrow-counterclockwise text-warning"></i>
                    </div>
                    {{-- <p class="mb-2">You are about to reset the attempt for:</p>
                    <ul class="mb-3">
                        <li><strong>Student:</strong> <span id="modalStudentName"></span></li>
                        <li><strong>Exam:</strong> <span id="modalExamTitle"></span></li>
                    </ul> --}}
                    <div class="modal-query">
                        <h3 class="text-warning fw-bold">Are You Sure?</h3>
                        <p>This will permanently remove all <strong>submitted answers</strong> and <strong>review records</strong> for this attempt, and reset the status back to <strong>New</strong>. The student will start the exam fresh.</p>
                    </div>

                    <div class="modal-button text-center mt-4">
                        <button type="reset" class="btn btn-outline-dark btn-sm me-1" data-bs-dismiss="modal">
                            <i class="bi bi-x-square"></i>
                            <span class="ms-1">Cancel</span>
                        </button>
                        <button type="submit" class="btn btn-warning btn-sm ms-1">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            <span class="ms-1">Yes, Reset!</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

