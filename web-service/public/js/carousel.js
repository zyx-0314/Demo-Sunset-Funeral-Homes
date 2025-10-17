/**
 * Horizontal Sliding Carousel with Proximity Arrows
 *
 * Features:
 * - Smooth sliding animation between item groups
 * - Proximity-based arrow visibility (arrows appear when mouse is near edges)
 * - Responsive design (2 items per view on desktop, 1 on mobile)
 * - Automatic layout recalculation on resize
 * - Touch-friendly navigation controls
 */
(function () {
    // Get DOM elements and exit if any are missing
    const track = document.getElementById('serviceCarousel');
    const viewport = document.getElementById('carouselViewport');
    const prev = document.getElementById('carouselPrev');
    const next = document.getElementById('carouselNext');
    if (!track || !viewport || !prev || !next) return;

    // Convert track children to array and set up state variables
    const items = Array.from(track.children); // .carousel-item elements
    const itemWidth = () => items[0] ? items[0].getBoundingClientRect().width : 0;
    let index = 0; // Current page index (0-based)
    const pageSize = 2; // Number of items visible per page

    /**
     * Clamp index to valid range
     * @param {number} i - Target index
     * @returns {number} Clamped index within valid bounds
     */
    function clampIndex(i) {
        const max = Math.max(0, Math.ceil(items.length / pageSize) - 1);
        return Math.min(max, Math.max(0, i));
    }

    /**
     * Navigate to specific page index
     * @param {number} i - Target page index
     */
    function goTo(i) {
        index = clampIndex(i);
        const width = itemWidth();
        if (width <= 0) return; // Avoid calculating with zero width
        // Calculate offset: current page * items per page * item width
        const offset = index * pageSize * width;
        track.style.transform = `translateX(-${offset}px)`;
        updateArrows();
    }

    /**
     * Update arrow visibility based on current position
     */
    function updateArrows() {
        const maxIndex = Math.max(0, Math.ceil(items.length / pageSize) - 1);
        prev.classList.toggle('visible', index > 0); // Show prev if not at start
        next.classList.toggle('visible', index < maxIndex); // Show next if not at end
    }

    // Set up click event listeners for navigation arrows
    prev.addEventListener('click', () => goTo(index - 1));
    next.addEventListener('click', () => goTo(index + 1));

    // Proximity detection: show arrows when mouse is near viewport edges
    viewport.addEventListener('mousemove', (e) => {
        const rect = viewport.getBoundingClientRect();
        const x = e.clientX - rect.left; // Mouse X relative to viewport
        const edge = 120; // Distance from edge to trigger arrow visibility

        // Show left arrow when cursor is within left edge region
        if (x <= edge) prev.classList.add('visible');
        else prev.classList.remove('visible');

        // Show right arrow when cursor is within right edge region
        if (x >= rect.width - edge) next.classList.add('visible');
        else next.classList.remove('visible');
    });

    // Hide arrows when mouse leaves viewport
    viewport.addEventListener('mouseleave', () => {
        prev.classList.remove('visible');
        next.classList.remove('visible');
    });

    // Recalculate layout on window resize
    window.addEventListener('resize', () => goTo(index));

    // Initial layout setup function
    function layout() {
        const vw = viewport.getBoundingClientRect().width;
        // Calculate item width: full width on mobile, half on desktop (2 per view)
        const single = vw / (window.innerWidth >= 768 ? 2 : 1);
        items.forEach(it => {
            it.style.width = `${single}px`;
            it.style.height = '100%'; // Ensure consistent height
        });
        // Apply smooth transition animation
        track.style.transition = 'transform 420ms cubic-bezier(.2,.9,.2,1)';
    }

    // Initial layout and delayed re-layout for font/image loading
    layout();
    setTimeout(() => {
        layout();
        goTo(0); // Reset to first page after layout
    }, 120);
})();