<?php
// Page: user/landing
// $services: object array | string | null

// Variable declarations
$items = $services ?? [];
$items = array_values($items);
$carousel = array_slice($items, 0, 4);
$process = ["You Arrange", "We Collect", "We Register", "We Return"];
?>
<!doctype html>
<html lang="en">

<?= view('components/head', ['css' => ['/css/landing.css']]) ?>

<body class="bg-gray-50 font-sans text-slate-900">
    <?= view('components/header', ['active' => 'Home']) ?>

    <main class="mx-auto px-6 py-12 max-w-6xl">
        <!-- Hero -->
        <section class="items-center gap-8 grid grid-cols-1 md:grid-cols-2">
            <div class="order-2 md:order-1">
                <h1 class="font-serif font-extrabold text-slate-900 text-4xl md:text-5xl leading-tight">A dignified service your family can trust</h1>
                <p class="mt-4 max-w-xl text-gray-700">We provide respectful, professional support for families during difficult moments — clear guidance, compassionate staff, and thoughtful service options.</p>

                <div class="flex flex-wrap items-center gap-3 mt-6">
                    <?= view('components/buttons/button_primary', ['label' => 'Request Assistance', 'href' => '#']) ?>
                </div>

                <div class="mt-6">
                    <div class="inline-flex items-center gap-3 bg-sage-light px-4 py-3 rounded-full text-sage-dark">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8" />
                        </svg>
                        <div>
                            <div class="font-semibold text-sm">Speak to our Care Team</div>
                            <div class="text-sm">Call (555) 123-4567</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="order-1 md:order-2">
                <div class="shadow-lg rounded-2xl overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1400&q=80" alt="Family embracing in a living room" class="w-full h-72 md:h-[420px] object-cover">
                </div>
            </div>
        </section>

        <!-- Features (fragmented into reusable cards) -->
        <section class="mt-12">
            <div class="gap-6 grid grid-cols-1 md:grid-cols-3">
                <?= view('components/cards/general_card', ['title' => 'Simple process', 'excerpt' => 'We guide you step-by-step so arrangements are clear and manageable.', 'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=200&q=80']) ?>
                <?= view('components/cards/general_card', ['title' => 'Transparent pricing', 'excerpt' => 'Upfront options and pricing to remove uncertainty for families.', 'image' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=200&q=80']) ?>
                <?= view('components/cards/general_card', ['title' => 'Compassionate care', 'excerpt' => 'Our team supports families with empathy and professionalism.', 'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=200&q=80']) ?>
            </div>
        </section>

        <!-- Marketing + Services carousel -->
        <?php if (($services ?? false) && is_string($services)) : ?>
            <?= view('components/cards/card', ['title' => $services, 'value' => 0]); ?>
        <?php else : ?>
            <section class="mt-12">
                <div class="rounded-lg overflow-hidden" style="background: linear-gradient(90deg, rgba(16,185,129,0.12) 0%, rgba(255,255,255,0) 60%);">
                    <div class="mx-auto p-8 max-w-6xl">
                        <div class="items-center gap-6 grid grid-cols-1 md:grid-cols-2">
                            <!-- Left: marketing copy -->
                            <div>
                                <h2 class="font-serif font-extrabold text-slate-900 text-3xl md:text-4xl">Thoughtful services, tailored to your needs</h2>
                                <p class="mt-4 max-w-xl text-slate-700">We offer flexible packages to fit different needs and budgets. Choose a service to learn more, or contact our Care Team for a custom arrangement.</p>
                                <ul class="space-y-1 mt-4 text-slate-700 list-disc list-inside">
                                    <li>Personalized support from planning to service</li>
                                    <li>Clear pricing and transparent options</li>
                                    <li>Simple online reservation and assistance</li>
                                </ul>
                                <div class="mt-6">
                                    <?= view('components/buttons/button_primary', ['label' => 'See all services', 'href' => '/services']) ?>
                                </div>
                            </div>

                            <!-- Right: horizontal card carousel (show 2 cards per view, LTR) -->
                            <div class="relative">

                                <div id="carouselViewport" class="rounded-lg carousel-viewport">
                                    <div id="serviceCarousel" class="carousel-track">
                                        <?php
                                        foreach ($carousel as $service) :
                                        ?>
                                            <div class="w-full md:w-1/2 carousel-item" style="height:100%;">
                                                <?= view('components/cards/service_card', ['service' => $service]) ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div id="carouselPrev" class="left carousel-arrow" aria-hidden="true">&lt;</div>
                                <div id="carouselNext" class="right carousel-arrow" aria-hidden="true">&gt;</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Steps -->
        <section class="mt-12">
            <h3 class="font-semibold text-lg">We guide you through the process</h3>
            <div class="gap-6 grid grid-cols-1 md:grid-cols-4 mt-6">
                <?php
                foreach ($process as $value) : ?>
                    <div class="bg-white p-4 rounded-lg text-center">
                        <div class="font-medium text-sm"><?php echo $value ?></div>
                    </div>
                <?php
                endforeach;
                ?>
            </div>
        </section>

        <!-- CTA (component) -->
        <?= view('components/cta', [
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