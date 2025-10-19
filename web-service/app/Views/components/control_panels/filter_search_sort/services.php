<?php
// Component: components/control_panels/filter_search_sort/services.php
// Purpose: Renders search/sort/availability controls for services list with server-side filtering.
// Data Contract:
// - $_GET['search']: string|null - Current search query for title
// - $_GET['sort']: string|null - Current sort parameter (cost_desc, cost_asc, name_asc, name_desc)
// - $_GET['available']: string|null - Current availability filter (all, yes, no)
// - $_GET['page']: int - Current page number
// - $_GET['per_page']: int - Items per page
?>
<form id="servicesFilterForm" method="GET" action="<?= current_url() ?>" class="flex sm:flex-row flex-col sm:items-center gap-3 mb-4">
    <input type="search" name="search" id="services_q" placeholder="Search by title" value="<?= esc($_GET['search'] ?? '') ?>" class="shadow-sm px-3 py-2 border border-slate-200 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full sm:w-1/3">

    <select name="sort" id="services_sort" class="shadow-sm px-3 py-2 border border-slate-200 rounded-md focus:outline-none w-full sm:w-48">
        <option value="">Sort — default</option>
        <option value="cost_desc" <?= (($_GET['sort'] ?? '') === 'cost_desc') ? 'selected' : '' ?>>Cost — High → Low</option>
        <option value="cost_asc" <?= (($_GET['sort'] ?? '') === 'cost_asc') ? 'selected' : '' ?>>Cost — Low → High</option>
        <option value="name_asc" <?= (($_GET['sort'] ?? '') === 'name_asc') ? 'selected' : '' ?>>Name A → Z</option>
        <option value="name_desc" <?= (($_GET['sort'] ?? '') === 'name_desc') ? 'selected' : '' ?>>Name Z → A</option>
    </select>

    <select name="available" id="services_available" class="shadow-sm px-3 py-2 border border-slate-200 rounded-md focus:outline-none w-full sm:w-48">
        <option value="all" <?= (($_GET['available'] ?? 'all') === 'all') ? 'selected' : '' ?>>Available — all</option>
        <option value="yes" <?= (($_GET['available'] ?? '') === 'yes') ? 'selected' : '' ?>>Yes</option>
        <option value="no" <?= (($_GET['available'] ?? '') === 'no') ? 'selected' : '' ?>>No</option>
    </select>

    <div class="flex gap-2 ml-auto">
        <input type="hidden" name="page" value="<?= esc($_GET['page'] ?? 1) ?>" />
        <input type="hidden" name="per_page" value="<?= esc($_GET['per_page'] ?? 10) ?>" />
        <button type="submit" class="inline-flex items-center hover:bg-indigo-700 shadow-sm px-4 py-2 border border-transparent rounded-md font-semibold text-white btn-sage-dark">Apply</button>
        <a href="<?= current_url() ?>?page=<?= esc($_GET['page'] ?? 1) ?>&per_page=<?= esc($_GET['per_page'] ?? 10) ?>" class="inline-flex items-center bg-white hover:bg-slate-50 shadow-sm px-4 py-2 border border-slate-300 rounded-md font-semibold text-slate-700">Reset</a>
    </div>
</form>