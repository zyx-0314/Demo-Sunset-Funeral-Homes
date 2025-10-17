/**
 * Forgot Password Modal Handler
 *
 * Manages the forgot password modal functionality including:
 * - Modal opening/closing with template cloning
 * - Form submission with AJAX validation
 * - Error/success feedback display
 * - Accessibility features and focus management
 */
(function () {
    'use strict';

    // Get references to DOM elements
    const opener = document.getElementById('openForgotModal');
    const tpl = document.getElementById('forgot-template');

    /**
     * Get the modal element (created dynamically)
     * @returns {HTMLElement|null} The modal element or null if not found
     */
    function modalEl() {
        return document.getElementById('forgotModal');
    }

    /**
     * Open the forgot password modal
     * Clones template, sets up event listeners, and manages focus
     */
    function openModal() {
        // Only create modal if it doesn't already exist
        if (!modalEl()) {
            // Clone the template and add to DOM
            const clone = tpl.content.cloneNode(true);
            document.body.appendChild(clone);

            const m = modalEl();

            // Close modal when clicking backdrop
            m.addEventListener('click', function (e) {
                if (e.target === m) closeModal();
            });

            // Set up cancel button
            const cancel = document.getElementById('cancelForgot');
            cancel && cancel.addEventListener('click', closeModal);

            // Set up form submission
            const form = document.getElementById('forgotForm');
            const feedback = document.getElementById('forgotFeedback');
            const submit = document.getElementById('sendResetBtn');

            if (form) {
                form.addEventListener('submit', function (ev) {
                    ev.preventDefault();

                    // Client-side validation
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    // Clear previous feedback and disable submit
                    feedback.textContent = '';
                    submit.disabled = true;
                    const old = submit.textContent;
                    submit.textContent = 'Sending...';

                    // Submit form via AJAX
                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        credentials: 'same-origin'
                    })
                        .then(function (res) {
                            // Reset button state
                            submit.disabled = false;
                            submit.textContent = old;

                            // Check response content type
                            const ct = (res.headers.get('content-type') || '');

                            if (ct.indexOf('application/json') !== -1) {
                                // Handle JSON response
                                return res.json().then(function (d) {
                                    if (d.error) {
                                        feedback.textContent = d.error.message || JSON.stringify(d.error);
                                        feedback.className = 'mt-3 text-sm text-red-600';
                                    } else {
                                        feedback.textContent = d.message || 'If an account exists we sent a reset link.';
                                        feedback.className = 'mt-3 text-sm text-green-600';
                                    }
                                });
                            } else {
                                // Handle non-JSON response
                                return res.text().then(function () {
                                    feedback.textContent = 'If an account exists we sent a reset link.';
                                    feedback.className = 'mt-3 text-sm text-green-600';
                                });
                            }
                        })
                        .catch(function () {
                            // Handle network errors
                            submit.disabled = false;
                            submit.textContent = old;
                            feedback.textContent = 'Network error.';
                            feedback.className = 'mt-3 text-sm text-red-600';
                        });
                });
            }

            // Focus email input after modal opens
            setTimeout(function () {
                const e = document.getElementById('forgotEmail');
                e && e.focus();
            }, 10);
        }
    }

    /**
     * Close and remove the forgot password modal
     */
    function closeModal() {
        const m = modalEl();
        if (m) m.remove();
    }

    // Initialize event listener for modal trigger
    opener && opener.addEventListener('click', openModal);
})();