<?php
// Page: admin/services.php
// Purpose: Admin services management page with service statistics and service listing interface
// Data Contract:
// - $services: string | object array - Service data or error message if loading fails
// - $servicesCount: string | number - Total count of active services
// - $availableServicesCount: string | number - Count of available active services
// - $notAvailableServicesCount: string | number - Count of unavailable active services
// - $currentPage: number - Current page number for pagination
// - $perPage: number - Number of items per page
// - $totalServices: number - Total number of filtered services
// - $sort: string - Current sort parameter
// - $available: string - Current availability filter
// - $searchQuery: string - Current search query
?>
<!doctype html>
<html lang="en">
<?= view('components/head', ['title' => 'Admin — Services']) ?>

<body class="bg-gray-50 min-h-screen font-sans text-slate-900">
    <?= view('components/headers/navigation_header') ?>

    <main class="mx-auto px-6 py-10 max-w-6xl">
        <div class="md:flex md:space-x-6">
            <?= view('components/aside/admin_manager', ['active' => 'services']) ?>

            <section class="flex-1">
                <h2 class="mb-6 font-semibold text-2xl">Services</h2>
                <?php if (is_string($services)) : ?>
                    <?= view('components/cards/card', [
                        'title' => $services,
                        'value' => 0
                    ]); ?>
                <?php else : ?>
                    <div class="gap-4 grid grid-cols-1 md:grid-cols-3 mb-6" id="serviceStats">
                        <?= view('components/cards/card_stat', [
                            'title' => 'Total Active',
                            'value' => $servicesCount
                        ]) ?>
                        <?= view('components/cards/card_stat', [
                            'title' => 'Available & active',
                            'value' => $availableServicesCount
                        ]) ?>
                        <?= view('components/cards/card_stat', [
                            'title' => 'Not available but active',
                            'value' => $notAvailableServicesCount
                        ]) ?>
                    </div>
                    <div class="flex justify-end gap-3 mb-4">
                        <div class="flex justify-end mb-4">
                            <a class="px-3 py-2 btn-border hover:btn-border-dark rounded text-white duration-200 cursor-pointer" href="<?php echo site_url('services/'); ?>">
                                Services List
                            </a>
                        </div>
                        <?= view('components/modal/services/create') ?>
                    </div>
                    <?= view('components/control_panels/filter_search_sort/services') ?>
                    <?= view('components/table/services', [
                        'services' => $services,
                        'currentPage' => $currentPage,
                        'perPage' => $perPage,
                        'totalServices' => $totalServices ?? 0,
                        'sort' => $sort,
                        'available' => $available,
                        'searchQuery' => $searchQuery,
                    ]) ?>
                <?php endif; ?>
            </section>

        </div>
    </main>

    <?= view('components/footer') ?>
</body>

</html>