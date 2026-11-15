(function () {
    "use strict";

    const storageKey = "imru-auth-transition-started-at";
    const transitionLifetime = 60000;
    const root = document.documentElement;

    function handleStorageError(error) {
        if (error && (error.name === "SecurityError" || error.name === "QuotaExceededError")) {
            return;
        }

        throw error;
    }

    function clearPendingTransition() {
        try {
            window.sessionStorage.removeItem(storageKey);
        } catch (error) {
            handleStorageError(error);
        }
    }

    function readPendingTransition() {
        try {
            const timestamp = Number(window.sessionStorage.getItem(storageKey));
            const age = Date.now() - timestamp;

            if (timestamp > 0 && age >= 0 && age < transitionLifetime) {
                return true;
            }

            window.sessionStorage.removeItem(storageKey);
        } catch (error) {
            handleStorageError(error);
        }

        return false;
    }

    function savePendingTransition() {
        try {
            window.sessionStorage.setItem(storageKey, String(Date.now()));
        } catch (error) {
            handleStorageError(error);
        }
    }

    if (root.dataset.authTransition === "auth") {
        clearPendingTransition();
    } else if (root.dataset.authTransition === "dashboard" && readPendingTransition()) {
        root.classList.add("auth-transition-pending");
    }

    function initializePreloader() {
        const preloader = document.querySelector(".auth-preloader");
        const transitionPending = root.classList.contains("auth-transition-pending");
        let submissionTimer;
        let authSubmissionInProgress = false;

        if (!preloader) {
            return;
        }

        function resetFormSubmission(form) {
            authSubmissionInProgress = false;
            form.dataset.authSubmitting = "false";
            form.removeAttribute("aria-busy");
            form.querySelectorAll("button[type='submit'], input[type='submit']").forEach(function (button) {
                button.disabled = false;
            });
            preloader.classList.remove("is-visible");
            preloader.setAttribute("aria-hidden", "true");
            clearPendingTransition();
        }

        if (transitionPending) {
            preloader.setAttribute("aria-hidden", "false");

            let timeout;
            const hidePreloader = function () {
                window.clearTimeout(timeout);
                root.classList.remove("auth-transition-pending");
                preloader.classList.remove("is-visible");
                preloader.setAttribute("aria-hidden", "true");
                clearPendingTransition();
            };

            timeout = window.setTimeout(hidePreloader, transitionLifetime);
            window.addEventListener("load", hidePreloader, { once: true });

            if (document.readyState === "complete") {
                hidePreloader();
            }
        }

        const authForms = Array.from(document.querySelectorAll("form[method='post'], form[method='POST']"))
            .filter(function (form) {
                const action = new URL(form.action, window.location.href);
                return /\/(?:login|register)\/?$/i.test(action.pathname);
            });

        authForms.forEach(function (form) {
            form.addEventListener("submit", function (event) {
                if (authSubmissionInProgress || form.dataset.authSubmitting === "true") {
                    event.preventDefault();
                    return;
                }

                if (event.defaultPrevented || !form.checkValidity()) {
                    return;
                }

                authSubmissionInProgress = true;
                form.dataset.authSubmitting = "true";
                form.setAttribute("aria-busy", "true");
                form.querySelectorAll("button[type='submit'], input[type='submit']").forEach(function (button) {
                    button.disabled = true;
                });

                savePendingTransition();
                preloader.classList.add("is-visible");
                preloader.setAttribute("aria-hidden", "false");
                submissionTimer = window.setTimeout(function () {
                    resetFormSubmission(form);
                }, transitionLifetime);
            });
        });

        window.addEventListener("pageshow", function () {
            authForms.forEach(function (form) {
                if (form.dataset.authSubmitting !== "true") {
                    return;
                }

                window.clearTimeout(submissionTimer);
                resetFormSubmission(form);
            });
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initializePreloader);
    } else {
        initializePreloader();
    }
})();
