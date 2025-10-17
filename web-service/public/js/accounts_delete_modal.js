/**
 * Delete Account Modal functionality
 * Handles opening, closing, and submitting the account deletion modal
 */

(function () {
    'use strict';

    if (window.__deleteAccountModalInit) return;
    window.__deleteAccountModalInit = true;

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

    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-delete-account-id], .js-delete-account-trigger');
        if (!trigger) return;
        e.preventDefault();
        const id = trigger.getAttribute('data-delete-account-id');
        const name = trigger.getAttribute('data-delete-account-name') || trigger.textContent || '';
        const email = trigger.getAttribute('data-delete-account-email') || '';

        const container = trigger.closest('td') || trigger.closest('tr') || document;
        const modal = container.querySelector('.delete-account-modal');
        if (!modal) return;

        const inputId = modal.querySelector('.delete-account-id');
        const nameEl = modal.querySelector('.delete-account-name');
        const emailEl = modal.querySelector('.delete-account-email');
        const backdrop = modal.querySelector('.delete-account-backdrop');
        const btnCancel = modal.querySelector('.btn-cancel-delete-account');
        const form = modal.querySelector('.delete-account-form');

        document.body.style.overflow = 'hidden';
        if (inputId) inputId.value = id || '';
        if (nameEl) nameEl.textContent = name.trim() || '\u2014';
        if (emailEl) emailEl.textContent = email || '';
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if (btnCancel) btnCancel.focus();

        function closeModal() {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            if (inputId) inputId.value = '';
            if (nameEl) nameEl.textContent = '\u2014';
            if (emailEl) emailEl.textContent = '';
            if (backdrop) backdrop.removeEventListener('click', onBackdrop);
            if (btnCancel) btnCancel.removeEventListener('click', onCancel);
            if (form) form.removeEventListener('submit', onSubmit);
        }

        function onBackdrop() {
            closeModal();
        }

        function onCancel() {
            closeModal();
        }

        let _isSubmitting = false;
        async function onSubmit(ev) {
            ev.preventDefault();
            if (_isSubmitting) return;
            _isSubmitting = true;
            if (backdrop) backdrop.removeEventListener('click', onBackdrop);
            if (btnCancel) btnCancel.disabled = true;
            const statusToast = showToast('Deleting account...', 'info', 60000);
            const fd = new FormData(form);
            if (inputId && inputId.value) fd.set('id', inputId.value);
            try {
                const resp = await fetch(form.action, {
                    method: 'POST',
                    body: fd
                });
                let data = null;
                try {
                    data = await resp.json();
                } catch (err) {
                    data = null;
                }
                if (resp.ok && data && data.success) {
                    showToast(data.message || 'Deleted', 'success', 3000);
                    setTimeout(() => location.reload(), 600);
                } else {
                    const msg = data && data.message ? data.message : 'Delete failed';
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
                if (btnCancel) btnCancel.disabled = false;
            }
        }

        if (backdrop) backdrop.addEventListener('click', onBackdrop);
        if (btnCancel) btnCancel.addEventListener('click', onCancel);
        if (form) form.addEventListener('submit', onSubmit);
    });
})();