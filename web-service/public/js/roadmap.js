(function () {
    const select = document.getElementById('statusFilter');

    function normalize(s) {
        return String(s || '').trim().toLowerCase();
    }

    select.addEventListener('change', function (e) {
        const v = normalize(e.target.value);
        document.querySelectorAll('#roadmapList article').forEach(function (el) {
            const s = normalize(el.dataset.status);
            if (v === 'all' || s === v) el.style.display = '';
            else el.style.display = 'none';
        });
    });
})();