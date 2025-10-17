<?php
// Page: admin/dashboard.php
// Purpose: Admin dashboard displaying system statistics and management overview
// Data Contract:
// - $requestsCount: number | string - Total number of inquiries/requests in the system
// - $servicesCount: number | string - Total number of services available
// - $activeClientsCount: number | string - Count of currently active client accounts
?>

<!doctype html>
<html lang="en">
<?= view('components/head', ['title' => 'Admin Dashboard']) ?>

<body class="bg-gray-50 min-h-screen font-sans text-slate-900">

    <?= view('components/headers/navigation_header') ?>

    <main class="mx-auto px-6 py-10 max-w-6xl">
        <div class="md:flex md:space-x-6">
            <?= view('components/aside/admin_manager', ['active' => 'dashboard']) ?>
            <section class="flex-1">
                <h2 class="mb-6 font-semibold text-2xl">Admin Dashboard</h2>
                <div class="gap-4 grid grid-cols-1 sm:grid-cols-3">
                    <?= view('components/cards/card_stat', ['title' => 'Total Inquiries', 'value' => $requestsCount]) ?>
                    <?= view('components/cards/card_stat', ['title' => 'Total Services', 'value' => $servicesCount]) ?>
                    <?= view('components/cards/card_stat', ['title' => 'Active Clients', 'value' => $activeClientsCount, 'subtitle' => 'Registered and active']) ?>
                </div>
            </section>
        </div>
    </main>

    <?= view('components/footer') ?>
</body>

</html>