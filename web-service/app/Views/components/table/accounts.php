<?php
// Component: components/table/accounts.php
// Purpose: Displays a paginated table of user accounts with filtering, sorting, and action modals for admin management
// Data Contract:
// - $accounts: object array | string | null - Array of user account objects or error message string for display in the accounts table
// - $currentPage: int - Current page number
// - $perPage: int - Number of items per page
// - $totalAccounts: int - Total number of filtered accounts
// - $sort: string - Current sort parameter
// - $type: string - Current type filter
// - $searchQuery: string - Current search query

// Use passed data or fallback to GET params
$currentPage = $currentPage ?? (isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1);
$perPage = $perPage ?? (isset($_GET['per_page']) ? max(1, (int) $_GET['per_page']) : 10);
$totalAccounts = $totalAccounts ?? 0;
$sort = $sort ?? ($_GET['sort'] ?? '');
$type = $type ?? ($_GET['type'] ?? 'all');
$searchQuery = $searchQuery ?? ($_GET['search'] ?? '');

// Calculate pagination info
$totalPages = (int) max(1, ceil($totalAccounts / $perPage));
if ($currentPage > $totalPages) $currentPage = $totalPages;

// Build query string for pagination links (preserve filters)
$filterParameters = [];
if ($searchQuery) $filterParameters['search'] = $searchQuery;
if ($sort) $filterParameters['sort'] = $sort;
if ($type && $type !== 'all') $filterParameters['type'] = $type;
$filterQueryString = http_build_query($filterParameters);

$accountsData = $accounts ?? [];
// Since pagination is now server-side, we can use the accounts directly
$currentPageAccounts = is_array($accountsData) ? $accountsData : [];
?>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="p-4 overflow-x-auto">
        <table class="w-full min-w-[800px] text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3">Name</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Type</th>
                    <th class="p-3">Email Activated</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($currentPageAccounts)) : ?>
                    <tr>
                        <td class="p-3" colspan="5">No accounts found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($currentPageAccounts as $account): ?>
                        <tr class="border-t">
                            <?php
                            // Build full name with middle initial if present
                            $middleInitial = !empty($account->middle_name) ? $account->middle_name[0] . '. ' : '';
                            $account->name = $account->first_name . ' ' . $middleInitial . $account->last_name;
                            ?>
                            <td class="p-3"><?= esc($account->name); ?></td>
                            <td class="p-3"><?= esc($account->email); ?></td>
                            <td class="p-3"><?= esc($account->type); ?></td>
                            <td class="p-3"><?= esc($account->email_activated === "1" ? 'Yes' : 'No'); ?></td>
                            <td class="flex gap-2 p-3">
                                <?= view('components/modal/accounts/delete', ['account' => $account]) ?>
                                <?= view('components/modal/accounts/update', ['account' => $account]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="bg-gray-50 p-4 border-t">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <form method="get" style="display:flex;align-items:center;gap:.5rem;">
                    <label for="per_page" class="text-gray-700 text-sm">Show</label>
                    <select id="per_page" name="per_page" class="p-1 border rounded text-sm" onchange="this.form.submit()">
                        <option value="5" <?= esc($perPage == 5 ? 'selected' : ''); ?>>5</option>
                        <option value="10" <?= esc($perPage == 10 ? 'selected' : ''); ?>>10</option>
                        <option value="20" <?= esc($perPage == 20 ? 'selected' : ''); ?>>20</option>
                    </select>
                    <?php if ($searchQuery): ?><input type="hidden" name="search" value="<?= esc($searchQuery); ?>" /><?php endif; ?>
                    <?php if ($sort): ?><input type="hidden" name="sort" value="<?= esc($sort); ?>" /><?php endif; ?>
                    <?php if ($type && $type !== 'all'): ?><input type="hidden" name="type" value="<?= esc($type); ?>" /><?php endif; ?>
                    <input type="hidden" name="page" value="1" />
                    <span class="text-gray-700 text-sm">per page</span>
                </form>
            </div>
            <div class="flex justify-end items-center space-x-2">
                <?php if ($totalPages > 1): ?>
                    <?php $paginationStart = max(1, $currentPage - 3);
                    $paginationEnd = min($totalPages, $currentPage + 3); ?>
                    <a class="px-3 py-1 border rounded <?= esc(($currentPage <= 1) ? 'opacity-50 pointer-events-none' : ''); ?>" href="?<?= esc($filterQueryString . ($filterQueryString ? '&' : '') . 'page=' . ($currentPage - 1 < 1 ? 1 : $currentPage - 1) . '&per_page=' . $perPage); ?>">Prev</a>
                    <?php for ($pageNumber = $paginationStart; $pageNumber <= $paginationEnd; $pageNumber++): ?>
                        <a class="px-3 py-1 border rounded <?= esc(($pageNumber == $currentPage) ? 'btn-sage text-white' : ''); ?>" href="?<?= esc($filterQueryString . ($filterQueryString ? '&' : '') . 'page=' . $pageNumber . '&per_page=' . $perPage); ?>"><?= esc($pageNumber); ?></a>
                    <?php endfor; ?>
                    <a class="px-3 py-1 border rounded <?= esc(($currentPage >= $totalPages) ? 'opacity-50 pointer-events-none' : ''); ?>" href="?<?= esc($filterQueryString . ($filterQueryString ? '&' : '') . 'page=' . ($currentPage + 1 > $totalPages ? $totalPages : $currentPage + 1) . '&per_page=' . $perPage); ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>