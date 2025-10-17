<?php
// Page: user/landing.php
// Purpose: Landing page for the funeral home website showcasing services and company information
// Data Contract:
// - $services: object array | string | null - Services data for carousel display

// Variable declarations
$process = ["You Arrange", "We Collect", "We Register", "We Return"];
$features = [
    [
        'title' => 'Simple process',
        'excerpt' => 'We guide you step-by-step so arrangements are clear and manageable.',
        'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=200&q=80'
    ],
    [
        'title' => 'Transparent pricing',
        'excerpt' => 'Upfront options and pricing to remove uncertainty for families.',
        'image' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=200&q=80'
    ],
    [
        'title' => 'Compassionate care',
        'excerpt' => 'Our team supports families with empathy and professionalism.',
        'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=200&q=80'
    ]
];
?>
<!doctype html>
<html lang="en">

<?= view('components/head', ['css' => ['/css/landing.css']]) ?>

<body class="bg-gray-50 font-sans text-slate-900">
    <?= view('components/headers/navigation_header', ['active' => 'Home']) ?>

    <main class="mx-auto px-6 py-12 max-w-5xl">
        <!-- Hero -->
        <?= view('components/sections/hero') ?>

        <!-- Features (fragmented into reusable cards) -->
        <section class="mt-12">
            <div class="gap-6 grid grid-cols-1 md:grid-cols-3">
                <?php foreach ($features as $feature): ?>
                    <?= view('components/cards/general_card', $feature) ?>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Marketing + Services carousel -->
        <?= view('components/sections/services_carousel', ['services' => $services ?? []]) ?>

        <!-- Steps -->
        <?= view('components/sections/steps', ['steps' => $process]) ?>

        <!-- CTA (component) -->
        <?= view('components/sections/cta', [
            'heading' => 'Can we help?',
            'sub' => 'Our Care Team is available 24 hours a day by phone and live-chat.',
            'primary' => ['label' => 'Call (555) 123-4567', 'href' => 'tel:5551234567'],
            'secondary' => ['label' => 'Request Assistance', 'href' => '/services']
        ]) ?>

    </main>

    <script src="/js/carousel.js"></script>

    <?= view('components/footer') ?>
</body>

</html>