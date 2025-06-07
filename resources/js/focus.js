function moveFocusOutOfModal(modal) {
    // Try to find the button that opened the modal, via data attributes
    // Fallback to body if not found
    let opener = document.querySelector(`[data-modal-target="${modal.id}"]`);
    if (opener) {
        opener.focus();
    } else {
        document.body.focus();
    }
}

// Attach a listener to ALL modals when they are about to be hidden
function setupGlobalModalFocusTrap() {
    // Use event delegation or your own modal open/close events
    document.querySelectorAll('.my-modal').forEach(modal => {
        // Listen for a custom event, or before hiding via your JS
        // Here, we override hide via MutationObserver as a generic catch-all
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (modal.classList.contains('hidden')) {
                    if (modal.contains(document.activeElement)) {
                        moveFocusOutOfModal(modal);
                    }
                }
            });
        });

        observer.observe(modal, { attributes: true, attributeFilter: ['class'] });
    });
}

// Call this once after the DOM is ready
document.addEventListener('DOMContentLoaded', setupGlobalModalFocusTrap);
