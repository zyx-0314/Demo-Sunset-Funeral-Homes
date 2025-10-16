(function () {
    var opener = document.getElementById('openForgotModal');
    var tpl = document.getElementById('forgot-template');

    function modalEl() {
        return document.getElementById('forgotModal');
    }

    function openModal() {
        if (!modalEl()) {
            var clone = tpl.content.cloneNode(true);
            document.body.appendChild(clone);

            var m = modalEl();
            m.addEventListener('click', function (e) {
                if (e.target === m) closeModal();
            });

            var cancel = document.getElementById('cancelForgot');
            cancel && cancel.addEventListener('click', closeModal);

            var form = document.getElementById('forgotForm');
            var feedback = document.getElementById('forgotFeedback');
            var submit = document.getElementById('sendResetBtn');

            if (form) {
                form.addEventListener('submit', function (ev) {
                    ev.preventDefault();
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }
                    feedback.textContent = '';
                    submit.disabled = true;
                    var old = submit.textContent;
                    submit.textContent = 'Sending...';

                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        credentials: 'same-origin'
                    })
                        .then(function (res) {
                            submit.disabled = false;
                            submit.textContent = old;
                            var ct = (res.headers.get('content-type') || '');
                            if (ct.indexOf('application/json') !== -1) return res.json().then(function (d) {
                                if (d.error) {
                                    feedback.textContent = d.error.message || JSON.stringify(d.error);
                                    feedback.className = 'mt-3 text-sm text-red-600';
                                } else {
                                    feedback.textContent = d.message || 'If an account exists we sent a reset link.';
                                    feedback.className = 'mt-3 text-sm text-green-600';
                                }
                            });
                            return res.text().then(function () {
                                feedback.textContent = 'If an account exists we sent a reset link.';
                                feedback.className = 'mt-3 text-sm text-green-600';
                            });
                        })
                        .catch(function () {
                            submit.disabled = false;
                            submit.textContent = old;
                            feedback.textContent = 'Network error.';
                            feedback.className = 'mt-3 text-sm text-red-600';
                        });
                });
            }
            setTimeout(function () {
                var e = document.getElementById('forgotEmail');
                e && e.focus();
            }, 10);
        }
    }

    function closeModal() {
        var m = modalEl();
        if (m) m.remove();
    }

    opener && opener.addEventListener('click', openModal);
})();