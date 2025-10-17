<?php
// Component: components/control_panels/filter_search_sort/accounts.php
// Purpose: Renders search/sort/type controls for admin accounts with server-side filtering.
?>
<form id="accountsFilterForm" method="GET" action="<?= current_url() ?>" class="flex sm:flex-row flex-col sm:items-center gap-3 mb-4">
    <input type="search" name="search" id="accounts_q" placeholder="Search by name or email" value="<?= esc($_GET['search'] ?? '') ?>" class="shadow-sm px-3 py-2 border border-slate-200 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full sm:w-1/3">

    <select name="sort" id="accounts_sort" class="shadow-sm px-3 py-2 border border-slate-200 rounded-md focus:outline-none w-full sm:w-48">
        <option value="">Sort — default</option>
        <option value="name_asc" <?= (($_GET['sort'] ?? '') === 'name_asc') ? 'selected' : '' ?>>Name A → Z</option>
        <option value="name_desc" <?= (($_GET['sort'] ?? '') === 'name_desc') ? 'selected' : '' ?>>Name Z → A</option>
        <option value="email_asc" <?= (($_GET['sort'] ?? '') === 'email_asc') ? 'selected' : '' ?>>Email A → Z</option>
        <option value="email_desc" <?= (($_GET['sort'] ?? '') === 'email_desc') ? 'selected' : '' ?>>Email Z → A</option>
    </select>

    <select name="type" id="accounts_type" class="shadow-sm px-3 py-2 border border-slate-200 rounded-md focus:outline-none w-full sm:w-48">
        <option value="all" <?= (($_GET['type'] ?? 'all') === 'all') ? 'selected' : '' ?>>Type — all</option>
        <option value="client" <?= (($_GET['type'] ?? '') === 'client') ? 'selected' : '' ?>>Client</option>
        <option value="driver" <?= (($_GET['type'] ?? '') === 'driver') ? 'selected' : '' ?>>Driver</option>
        <option value="embalmer" <?= (($_GET['type'] ?? '') === 'embalmer') ? 'selected' : '' ?>>Embalmer</option>
        <option value="staff" <?= (($_GET['type'] ?? '') === 'staff') ? 'selected' : '' ?>>Staff</option>
        <option value="florist" <?= (($_GET['type'] ?? '') === 'florist') ? 'selected' : '' ?>>Florist</option>
        <option value="manager" <?= (($_GET['type'] ?? '') === 'manager') ? 'selected' : '' ?>>Manager</option>
        <option value="employee" <?= (($_GET['type'] ?? '') === 'employee') ? 'selected' : '' ?>>Employee (non-client)</option>
    </select>

    <div class="flex gap-2 ml-auto">
        <input type="hidden" name="page" value="<?= esc($_GET['page'] ?? 1) ?>" />
        <input type="hidden" name="per_page" value="<?= esc($_GET['per_page'] ?? 10) ?>" />
        <button type="submit" class="inline-flex items-center hover:bg-indigo-700 shadow-sm px-4 py-2 border border-transparent rounded-md font-semibold text-white btn-sage-dark">Apply</button>
        <a href="<?= current_url() ?>?page=<?= esc($_GET['page'] ?? 1) ?>&per_page=<?= esc($_GET['per_page'] ?? 10) ?>" class="inline-flex items-center bg-white hover:bg-slate-50 shadow-sm px-4 py-2 border border-slate-300 rounded-md font-semibold text-slate-700">Reset</a>
    </div>
</form>