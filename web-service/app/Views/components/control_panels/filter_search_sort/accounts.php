<?php
// Component: components/control_panels/filter_search_sort/accounts.php
// Purpose: Renders search/sort/type controls for admin accounts and initializes client-side filtering.
// Dependencies: Requires accounts_filter.js to be loaded for functionality
?>
<form id="accountsFilterForm" onsubmit="return false;" class="flex sm:flex-row flex-col sm:items-center gap-3 mb-4">
    <input type="search" id="accounts_q" placeholder="Search by name or email" class="shadow-sm px-3 py-2 border border-slate-200 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 w-full sm:w-1/3">

    <select id="accounts_sort" class="shadow-sm px-3 py-2 border border-slate-200 rounded-md focus:outline-none w-full sm:w-48">
        <option value="">Sort — default</option>
        <option value="name_asc">Name A → Z</option>
        <option value="name_desc">Name Z → A</option>
        <option value="email_asc">Email A → Z</option>
        <option value="email_desc">Email Z → A</option>
    </select>

    <select id="accounts_type" class="shadow-sm px-3 py-2 border border-slate-200 rounded-md focus:outline-none w-full sm:w-48">
        <option value="all">Type — all</option>
        <option value="client">Client</option>
        <option value="driver">Driver</option>
        <option value="embalmer">Embalmer</option>
        <option value="staff">Staff</option>
        <option value="florist">Florist</option>
        <option value="manager">Manager</option>
        <option value="employee">Employee (non-client)</option>
    </select>

    <div class="flex gap-2 ml-auto">
        <button type="button" id="accountsResetBtn" class="inline-flex items-center bg-white hover:bg-slate-50 shadow-sm px-3 py-2 border border-slate-200 rounded-md">Reset</button>
    </div>
</form>

<script src="<?= base_url('js/accounts_filter.js'); ?>"></script>