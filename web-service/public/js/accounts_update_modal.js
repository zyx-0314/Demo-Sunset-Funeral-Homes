/**
 * Update Account Modal functionality
 * Handles opening, closing, and submitting the account update modal
 */

(function () {
    'use strict';

    if (window.__updateAccountModalInit) return;
    window.__updateAccountModalInit = true;

    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-update-account-id], .js-update-account-trigger');
        if (!trigger) return;
        e.preventDefault();

        const id = trigger.getAttribute('data-update-account-id');
        const type = trigger.getAttribute('data-update-account-type') || '';

        const container = trigger.closest('td') || trigger.closest('tr') || document;
        const modal = container.querySelector('.update-account-modal');
        if (!modal) return;

        const inputId = modal.querySelector('.update-account-id');
        const typeInput = modal.querySelector('.update-account-type-input');
        const backdrop = modal.querySelector('.update-account-backdrop');
        const btnClose = modal.querySelector('.btn-cancel-update-account');
        const form = modal.querySelector('.update-account-form');

        document.body.style.overflow = 'hidden';
        if (inputId) inputId.value = id || '';
        if (typeInput) typeInput.value = type || '';
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if (btnClose) btnClose.focus();

        function closeModal() {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            if (inputId) inputId.value = '';
            if (typeInput) typeInput.value = '';
            if (backdrop) backdrop.removeEventListener('click', onBackdrop);
            if (btnClose) btnClose.removeEventListener('click', onCancel);
            if (form) form.removeEventListener('submit', onSubmit);
        }

        function onBackdrop() {
            closeModal();
        }

        function onCancel() {
            closeModal();
        }

        let _isSubmitting = false;

        function showToast(message, type = 'info', timeout = 3000) {
            const id = 'toast_' + Date.now();
            const el = document.createElement('div');
            el.id = id;
            el.className = 'fixed right-4 top-4 z-50 px-4 py-2 rounded shadow-lg text-white';
            el.style.background = type === 'error' ? '#ef4444' : (type === 'success' ? '#10b981' : '#111827');
            el.textContent = message;
            document.body.appendChild(el);
            setTimeout(() => {
                try {
                    el.remove();
                } catch (e) { }
            }, timeout);
            return id;
        }

        async function onSubmit(ev) {
            ev.preventDefault();
            if (_isSubmitting) return;
            _isSubmitting = true;

            if (backdrop) backdrop.removeEventListener('click', onBackdrop);
            if (btnClose) btnClose.disabled = true;

            const statusToast = showToast('Updating account...', 'info', 60000);
            const fd = new FormData(form);
            if (inputId && inputId.value) fd.set('id', inputId.value);

            try {
                const resp = await fetch(form.action, {
                    method: 'POST',
                    body: fd,
                });
                let data = null;
                try {
                    data = await resp.json();
                } catch (err) {
                    data = null;
                }

                if (resp.ok && data && data.success) {
                    showToast(data.message || 'Updated', 'success', 3000);
                    setTimeout(() => location.reload(), 600);
                } else {
                    const msg = data && data.message ? data.message : 'Update failed';
                    showToast(msg, 'error', 5000);
                }
            } catch (err) {
                showToast('Network or server error', 'error', 5000);
            } finally {
                _isSubmitting = false;
                try {
                    const t = document.getElementById(statusToast);
                    if (t) t.remove();
                } catch (e) { }
                if (backdrop) backdrop.addEventListener('click', onBackdrop);
                if (btnClose) btnClose.disabled = false;
            }
        }

        if (backdrop) backdrop.addEventListener('click', onBackdrop);
        if (btnClose) btnClose.addEventListener('click', onCancel);
        if (form) form.addEventListener('submit', onSubmit);
    });
})();