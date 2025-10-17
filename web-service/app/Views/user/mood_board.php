<?php
// Page: user/mood_board.php
// Purpose: Mood board page displaying design system elements including colors, typography, buttons, and card samples
// Data Contract:
// - None (uses hardcoded dummy data for demonstration)

// Dummy data for service cards
$dummyService1 = (object) [
    'id' => 1,
    'title' => 'Traditional Filipino Funeral Service',
    'description' => 'Complete traditional funeral service including casket, viewing, ceremony, and burial arrangements with cultural sensitivity and respect.',
    'cost' => 3500.00,
    'image' => null,
    'banner_image' => null,
    'is_available' => true,
    'category' => 'traditional',
    'created_at' => '2024-01-15'
];
$dummyService2 = (object) [
    'id' => 2,
    'title' => 'Cremation Service Package',
    'description' => 'Dignified cremation service with memorial gathering, urn selection, and scattering options. Includes all necessary arrangements.',
    'cost' => 2200.00,
    'image' => null,
    'banner_image' => null,
    'is_available' => true,
    'category' => 'cremation',
    'created_at' => '2024-02-20'
];
?>
<!doctype html>
<html lang="en">

<?= view('components/head', ['title' => "Mood Board"]) ?>

<body class="bg-gray-50 text-gray-900">
    <?= view('components/headers/navigation_header') ?>

    <main class="mx-auto px-6 py-12 max-w-5xl">
        <?= view('components/headers/page_header', [
            'title' => 'Mood board',
            'description' => 'Visual identity samples for Sunset Funeral Homes (funeral services)'
        ]) ?>

        <!-- Color Palette -->
        <section class="mb-8">
            <h2 class="mb-4 font-semibold text-lg">Color system</h2>
            <p class="mb-4 text-gray-600 text-sm">Three main colors with three vibrance levels (dark → light). Preview and hex codes shown below.</p>
            <div class="gap-4 grid grid-cols-1 sm:grid-cols-3">
                <!-- Sage Green (Accent) -->
                <div>
                    <div class="gap-2 grid grid-cols-1">
                        <div class="swatch" style="background:var(--sage-dark)"></div>
                        <div class="swatch" style="background:var(--sage)"></div>
                        <div class="swatch" style="background:var(--sage-light)"></div>
                    </div>
                    <p class="mt-3 font-medium">Sage Green (Main accent)</p>
                    <div class="mt-1 text-gray-500 text-sm">#6F8E78 — #8DAA91 — #CFE6D7</div>
                </div>

                <!-- Muted Rose -->
                <div>
                    <div class="gap-2 grid grid-cols-1">
                        <div class="swatch" style="background:var(--rose-dark)"></div>
                        <div class="swatch" style="background:var(--rose)"></div>
                        <div class="swatch" style="background:var(--rose-light)"></div>
                    </div>
                    <p class="mt-3 font-medium">Muted Rose (Subtle warmth)</p>
                    <div class="mt-1 text-gray-500 text-sm">#A87D79 — #C7A6A0 — #EDD9D6</div>
                </div>

                <!-- Cool Stone Gray -->
                <div>
                    <div class="gap-2 grid grid-cols-1">
                        <div class="swatch" style="background:var(--stone-dark)"></div>
                        <div class="swatch" style="background:var(--stone)"></div>
                        <div class="swatch" style="background:var(--stone-light)"></div>
                    </div>
                    <p class="mt-3 font-medium">Cool Stone Gray (Secondary background)</p>
                    <div class="mt-1 text-gray-500 text-sm">#B1B1B1 — #E2E2E2 — #F7F7F7</div>
                </div>
            </div>
        </section>

        <!-- Typography -->
        <section class="mb-8">
            <h2 class="mb-4 font-semibold text-lg">Typography</h2>
            <div class="gap-6 grid grid-cols-2">
                <div>
                    <p class="text-gray-500 text-sm">Heading font</p>
                    <p class="font-sample font-semibold">Playfair Display — Heading example</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Body font</p>
                    <p class="font-sample">Lato — Body text example that demonstrates readable copy for longer paragraphs.</p>
                </div>
            </div>
        </section>

        <!-- Button Group -->
        <section class="mb-8">
            <h2 class="mb-4 font-semibold text-lg">Buttons</h2>
            <div class="space-y-4">
                <h3>Light Mode</h3>
                <div class="flex items-center space-x-4">
                    <?= view('components/buttons/button_primary', ['label' => 'Primary', 'href' => '#']) ?>
                    <?= view('components/buttons/button_secondary', ['label' => 'Secondary', 'href' => '#']) ?>
                    <?= view('components/buttons/button_border', ['label' => 'Border', 'href' => '#']) ?>
                    <?= view('components/buttons/button_primary', ['label' => 'Disabled', 'href' => '#', 'disable' => true]) ?>
                </div>
                <h3>Dark Mode</h3>
                <div class="flex items-center space-x-4 bg-stone-700 p-4 w-fit">
                    <?= view('components/buttons/button_primary', ['label' => 'Primary', 'href' => '#', 'dark' => true, 'disable' => false]) ?>
                    <?= view('components/buttons/button_secondary', ['label' => 'Secondary', 'href' => '#']) ?>
                    <?= view('components/buttons/button_border', ['label' => 'Border', 'href' => '#']) ?>
                    <?= view('components/buttons/button_primary', ['label' => 'Disabled', 'href' => '#', 'disable' => true]) ?>
                </div>
                <p class="text-gray-500 text-sm">Primary for main CTAs, secondary for supportive actions, border for subtle actions, disabled for unavailable states.</p>
            </div>
        </section>

        <!-- Card Group -->
        <section class="mb-8">
            <h2 class="mb-4 font-semibold text-lg">Card samples</h2>
            <div class="mb-6">
                <h3 class="mb-2 font-medium text-base">Stats Cards</h3>
                <div class="gap-4 grid grid-cols-1 md:grid-cols-3">
                    <?= view('components/cards/card_stat', [
                        'title' => 'Memorials Completed',
                        'value' => '1,254',
                        'subtitle' => 'Since 2015'
                    ]) ?>
                    <?= view('components/cards/card_stat', [
                        'title' => 'Families Served',
                        'value' => '892',
                        'subtitle' => 'This year'
                    ]) ?>
                    <?= view('components/cards/card_stat', [
                        'title' => 'Average Rating',
                        'value' => '4.9/5',
                        'subtitle' => 'From 450+ reviews'
                    ]) ?>
                </div>
            </div>
            <div class="mb-6">
                <h3 class="mb-2 font-medium text-base">Service Cards</h3>
                <div class="gap-4 grid grid-cols-1 md:grid-cols-2">
                    <?= view('components/cards/service_card', ['service' => $dummyService1]) ?>
                    <?= view('components/cards/service_card', ['service' => $dummyService2]) ?>
                </div>
            </div>
            <div>
                <h3 class="mb-2 font-medium text-base">General Cards</h3>
                <div class="gap-4 grid grid-cols-1 md:grid-cols-2">
                    <?= view('components/cards/general_card', [
                        'title' => 'Premium Casket Selection',
                        'excerpt' => 'Handcrafted oak casket with satin interior and personalized engraving options available in multiple finishes.',
                        'image' => null,
                        'href' => '#'
                    ]) ?>
                    <?= view('components/cards/general_card', [
                        'title' => 'Client Testimonial',
                        'excerpt' => '"Compassionate and attentive service during a difficult time — we felt supported every step of the way." — Jane D., Family',
                        'image' => null
                    ]) ?>
                </div>
            </div>
        </section>

        <!-- Logo Group -->
        <section class="mb-8">
            <h2 class="mb-4 font-semibold text-lg">Logos</h2>
            <div class="gap-4 grid grid-cols-2">
                <div class="bg-white shadow p-6 rounded text-center">
                    <div class="flex justify-center items-center bg-white mx-auto mb-3 rounded-full w-24 h-24 overflow-hidden">
                        <img src="logo/main.svg" alt="Sunset Funeral Homes" style="width:120px; height:120px; object-fit:cover; transform:translateX(0);">
                    </div>
                    <p class="font-medium">Main — Circle</p>
                </div>
                <div class="bg-white shadow p-6 rounded text-center">
                    <div class="flex justify-center items-center bg-white mx-auto mb-3 rounded-md w-24 h-24 overflow-hidden">
                        <img src="logo/main.svg" alt="Sunset Funeral Homes" style="width:120px; height:120px; object-fit:cover; transform:translateX(0);">
                    </div>
                    <p class="font-medium">Main — Square</p>
                </div>
            </div>
        </section>

    </main>

    <?= view('components/footer') ?>
</body>

</html>