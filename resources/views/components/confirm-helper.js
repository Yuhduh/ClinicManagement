/**
 * Show confirmation modal and handle form submission
 * Usage: confirmDelete(event, modalId, formId)
 */
function confirmDelete(event, modalId = "delete-confirm", formId = null) {
    event.preventDefault();

    // If no form ID provided, get it from the current form
    if (!formId && event.target.closest("form")) {
        formId = event.target.closest("form").id;
    }

    // Store the form reference for later submission
    window.pendingFormSubmit = {
        formId: formId,
        form: event.target.closest("form"),
    };

    // Show the confirmation modal
    window.dispatchEvent(
        new CustomEvent("open-modal", {
            detail: modalId,
        }),
    );
}

/**
 * Submit the pending form after confirmation
 */
function submitPendingForm(modalId = "delete-confirm") {
    if (window.pendingFormSubmit && window.pendingFormSubmit.form) {
        window.pendingFormSubmit.form.submit();
    }

    // Close modal
    window.dispatchEvent(
        new CustomEvent("close-modal", {
            detail: modalId,
        }),
    );
}

/**
 * Handle generic confirmation action
 */
function handleConfirmation(confirmCallback, cancelCallback = null) {
    return function (modalId = "confirm-modal") {
        // Close modal first
        window.dispatchEvent(
            new CustomEvent("close-modal", {
                detail: modalId,
            }),
        );

        // Execute the callback
        if (typeof confirmCallback === "function") {
            confirmCallback();
        }
    };
}

// Initialize all data-confirm-delete buttons
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[data-confirm-delete]").forEach((element) => {
        element.addEventListener("click", function (e) {
            const modalId =
                this.getAttribute("data-confirm-modal") || "delete-confirm";
            const message =
                this.getAttribute("data-confirm-message") ||
                "Are you sure you want to delete this item?";

            // Store form for submission
            const form = this.closest("form");
            if (form) {
                form.id = form.id || "temp-form-" + Date.now();
                window.pendingFormSubmit = { form: form };
            }

            // Show modal
            window.dispatchEvent(
                new CustomEvent("open-modal", { detail: modalId }),
            );
            e.preventDefault();
        });
    });
});
