<div class="modal delete-modal fade" id="delete_modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="bi bi-trash me-1"></i> Delete A @yield('title')</h5>
                        <button type="button" class="btn-close btn-close-white btn-close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-icon">
                            <i class="bi bi-trash3"></i>
                        </div>
                        <div class="modal-query">
                            <h5>Are you sure you want to delete this @yield('title') record?</h5>
                            <p>Once deleted, it cannot be restored. If you need to recover the data, please contact the Administrator.</p>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="reset" class="btn btn-dark btn-sm w-25 me-1" data-bs-dismiss="modal">
                            <i class="bi bi-x-square"></i>
                            <span class="ms-1">Cancel</span>
                        </button>
                        <button type="submit" class="btn btn-outline-danger btn-sm w-25 ms-1">
                            <i class="bi bi-trash"></i>
                            <span class="ms-1">Delete</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
