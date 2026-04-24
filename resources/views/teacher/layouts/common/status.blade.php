@php
    $message = session('success') ?: session('status') ?: session('error');
    $type = session('success') ? 'success' : (session('error') ? 'danger' : 'success');
@endphp

@if ($message)
    <div class="toast-container position-fixed bottom-0 start-0 p-3" style="z-index: 1080;">
        <div id="statusToast" class="toast align-items-center text-bg-{{ $type }} border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-{{ $type === 'success' ? 'check-circle' : 'exclamation-triangle' }} me-2"></i>
                    {{ $message }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif
