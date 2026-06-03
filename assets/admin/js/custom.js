document.addEventListener('DOMContentLoaded', function () {
    const toastEl = document.getElementById('statusToast');
    if (!toastEl) return;

    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        const toast = bootstrap.Toast.getOrCreateInstance(toastEl, {
            autohide: true,
            delay: 3000
        });
        toast.show();
    } else {
        toastEl.classList.add('show');
    }
});
