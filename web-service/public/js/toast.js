/**
 * Toast Notification System
 *
 * Creates temporary notification messages that appear in the top-right corner:
 * - Auto-generates unique IDs to prevent conflicts
 * - Supports different message types (info, success, error) with color coding
 * - Automatically removes notifications after 3 seconds
 * - Gracefully handles removal errors (e.g., if already removed)
 */
(function () {
    'use strict';

    /**
     * Display a toast notification
     * @param {string} message - The message to display
     * @param {string} type - The type of notification ('info', 'success', 'error')
     */
    function toast(message, type = 'info') {
        // Generate unique ID using timestamp to avoid conflicts
        const id = 'toast-' + Date.now();

        // Create toast element
        const el = document.createElement('div');
        el.id = id;
        el.className = 'fixed right-4 top-4 z-60 px-4 py-2 rounded shadow text-white';

        // Set background color based on type
        el.style.background = (type === 'error') ? '#ef4444' : // Red for errors
            (type === 'success' ? '#10b981' : // Green for success
                '#0ea5e9'); // Blue for info (default)

        // Set message text
        el.textContent = message;

        // Add to DOM
        document.body.appendChild(el);

        // Auto-remove after 3 seconds with error handling
        setTimeout(() => {
            try {
                document.body.removeChild(el);
            } catch (e) {
                // Element might have been removed already, ignore error
            }
        }, 3000);
    }

    // Make toast function available globally for use in other scripts
    window.toast = toast;
})();