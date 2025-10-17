/**
 * Roadmap Status Filter Handler
 *
 * Manages filtering of roadmap items based on status selection:
 * - Normalizes string values for case-insensitive comparison
 * - Shows/hides roadmap articles based on selected status
 * - Supports "all" option to display all items
 */
(function () {
    'use strict';

    // Get the status filter dropdown element
    const select = document.getElementById('statusFilter');

    /**
     * Normalize string for consistent comparison
     * Converts to lowercase and trims whitespace
     * @param {string} str - String to normalize
     * @returns {string} Normalized string
     */
    function normalize(str) {
        return String(str || '').trim().toLowerCase();
    }

    // Listen for changes on the status filter dropdown
    select.addEventListener('change', function (e) {
        // Get and normalize the selected value
        const selectedValue = normalize(e.target.value);

        // Iterate through all roadmap articles
        document.querySelectorAll('#roadmapList article').forEach(function (article) {
            // Get and normalize the article's status from data attribute
            const articleStatus = normalize(article.dataset.status);

            // Show article if it matches selected status or if "all" is selected
            if (selectedValue === 'all' || articleStatus === selectedValue) {
                article.style.display = ''; // Show (default display)
            } else {
                article.style.display = 'none'; // Hide
            }
        });
    });
})();