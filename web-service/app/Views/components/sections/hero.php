<?php
// Component: components/sections/hero.php
// Purpose: Hero section displaying the main headline, description, call-to-action button, and contact info with an image
// Data Contract:
// - None (hardcoded content)
?>
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