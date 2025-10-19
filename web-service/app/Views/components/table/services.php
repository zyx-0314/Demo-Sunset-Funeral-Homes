<?php
// Component: components/table/services.php
// Purpose: Displays a paginated table of services with filtering, sorting, and action modals for admin management
// Data Contract:
// - $services: object array | string | null - Array of service objects or error message string for display in the services table
// - $currentPage: int - Current page number
// - $perPage: int - Number of items per page
// - $totalServices: int - Total number of filtered services
// - $sort: string - Current sort parameter
// - $available: string - Current availability filter
// - $searchQuery: string - Current search query

// Use passed data or fallback to GET params
$currentPage = $currentPage ?? (isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1);
$perPage = $perPage ?? (isset($_GET['per_page']) ? max(1, (int) $_GET['per_page']) : 10);
$totalServices = $totalServices ?? 0;
$sort = $sort ?? ($_GET['sort'] ?? '');
$available = $available ?? ($_GET['available'] ?? 'all');
$searchQuery = $searchQuery ?? ($_GET['search'] ?? '');

// Calculate pagination info
$totalPages = (int) max(1, ceil($totalServices / $perPage));
if ($currentPage > $totalPages) $currentPage = $totalPages;

// Build query string for pagination links (preserve filters)
$filterParameters = [];
if ($searchQuery) $filterParameters['search'] = $searchQuery;
if ($sort) $filterParameters['sort'] = $sort;
if ($available && $available !== 'all') $filterParameters['available'] = $available;
$filterQueryString = http_build_query($filterParameters);

$servicesData = $services ?? [];
// Since pagination is now server-side, we can use the services directly
$currentPageServices = is_array($servicesData) ? $servicesData : [];
?>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="p-4 overflow-x-auto">
        <table class="w-full min-w-[640px] text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3">Title</th>
                    <th class="p-3">Cost</th>
                    <th class="p-3">Available</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($currentPageServices)) : ?>
                    <tr>
                        <td class="p-3" colspan="4">No services found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($currentPageServices as $service): ?>
                        <tr class="border-t">
                            <td class="p-3"><?php echo esc($service->title); ?></td>
                            <td class="p-3">₱<?php echo number_format((float) $service->cost, 2); ?></td>
                            <td class="p-3"><?php echo ((int) $service->is_available === 1) ? 'Yes' : 'No'; ?></td>
                            <td class="flex gap-2 p-3">
                                <div class="flex justify-end mb-4">
                                    <a class="bg-gray-600/70 hover:bg-gray-600/60 px-3 py-2 rounded text-white duration-200 cursor-pointer" href="<?php echo site_url('services/' . urlencode($service->id)); ?>">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                                <?= view('components/modal/services/delete', ['service' => $service]) ?>
                                <?= view('components/modal/services/update', ['service' => $service]) ?>
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
                        <option value="5" <?php echo $perPage == 5 ? 'selected' : ''; ?>>5</option>
                        <option value="10" <?php echo $perPage == 10 ? 'selected' : ''; ?>>10</option>
                        <option value="20" <?php echo $perPage == 20 ? 'selected' : ''; ?>>20</option>
                    </select>
                    <?php if ($searchQuery): ?><input type="hidden" name="search" value="<?php echo esc($searchQuery); ?>" /><?php endif; ?>
                    <?php if ($sort): ?><input type="hidden" name="sort" value="<?php echo esc($sort); ?>" /><?php endif; ?>
                    <?php if ($available && $available !== 'all'): ?><input type="hidden" name="available" value="<?php echo esc($available); ?>" /><?php endif; ?>
                    <input type="hidden" name="page" value="1" />
                    <span class="text-gray-700 text-sm">per page</span>
                </form>
            </div>
            <div class="flex justify-end items-center space-x-2">
                <?php if ($totalPages > 1): ?>
                    <?php $startP = max(1, $currentPage - 3);
                    $endP = min($totalPages, $currentPage + 3); ?>
                    <a class="px-3 py-1 border rounded <?php echo ($currentPage <= 1) ? 'opacity-50 pointer-events-none' : ''; ?>" href="?<?php echo $filterQueryString ? $filterQueryString . '&' : ''; ?>page=<?php echo $currentPage - 1 < 1 ? 1 : $currentPage - 1; ?>&per_page=<?php echo $perPage; ?>">Prev</a>
                    <?php for ($p = $startP; $p <= $endP; $p++): ?>
                        <a class="px-3 py-1 border rounded <?php echo ($p == $currentPage) ? 'btn-sage text-white' : ''; ?>" href="?<?php echo $filterQueryString ? $filterQueryString . '&' : ''; ?>page=<?php echo $p; ?>&per_page=<?php echo $perPage; ?>"><?php echo $p; ?></a>
                    <?php endfor; ?>
                    <a class="px-3 py-1 border rounded <?php echo ($currentPage >= $totalPages) ? 'opacity-50 pointer-events-none' : ''; ?>" href="?<?php echo $filterQueryString ? $filterQueryString . '&' : ''; ?>page=<?php echo $currentPage + 1 > $totalPages ? $totalPages : $currentPage + 1; ?>&per_page=<?php echo $perPage; ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>