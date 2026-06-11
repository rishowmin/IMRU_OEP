<div class="modal delete-modal fade" id="delete_bulk_modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="deleteBulkForm" method="POST">
                @csrf
                @method('DELETE')
                    <div class="modal-body p-5">
                        <div class="modal-icon">
                            <i class="bi bi-trash3 text-danger"></i>
                        </div>
                        <div class="modal-query">
                            <h3 class="text-danger fw-bold">Are You Sure?</h3>
                            <h6 class="mb-1 fw-semibold">You are about to delete all data of <span id="delete_bulk_label" class="fw-bold text-danger"></span>.</h6>
                            <p>Once deleted, it cannot be restored. If you need to recover the data, please contact the Administrator.</p>
                        </div>

                        <div class="modal-button text-center mt-4">
                            <button type="reset" class="btn btn-outline-dark btn-sm me-1" data-bs-dismiss="modal">
                                <i class="bi bi-x-square"></i>
                                <span class="ms-1">Cancel</span>
                            </button>
                            <button type="submit" class="btn btn-danger btn-sm ms-1">
                                <i class="bi bi-trash"></i>
                                <span class="ms-1">Yes, Delete All!</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
