<?php
// Component: components/sections/services_carousel.php
// Purpose: Marketing section with services carousel displaying available services or a fallback message
// Data Contract:
// - $services: object array | string | null - Services data for carousel; if string, shows a simple card instead
?>
<?php
$items = $services ?? [];
$items = array_values($items);
$carousel = array_slice($items, 0, 4);
?>

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