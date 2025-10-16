// Horizontal sliding carousel with smooth animation and proximity arrows
(function () {
    const track = document.getElementById('serviceCarousel');
    const viewport = document.getElementById('carouselViewport');
    const prev = document.getElementById('carouselPrev');
    const next = document.getElementById('carouselNext');
    if (!track || !viewport || !prev || !next) return;

    const items = Array.from(track.children); // .carousel-item
    const itemWidth = () => items[0] ? items[0].getBoundingClientRect().width : 0;
    let index = 0; // page index (0..)
    const pageSize = 2; // two items per view

    function clampIndex(i) {
        const max = Math.max(0, Math.ceil(items.length / pageSize) - 1);
        return Math.min(max, Math.max(0, i));
    }

    function goTo(i) {
        index = clampIndex(i);
        const width = itemWidth();
        if (width <= 0) return; // Avoid calculating with zero width
        const offset = index * pageSize * width;
        track.style.transform = `translateX(-${offset}px)`;
        updateArrows();
    }

    function updateArrows() {
        const maxIndex = Math.max(0, Math.ceil(items.length / pageSize) - 1);
        prev.classList.toggle('visible', index > 0);
        next.classList.toggle('visible', index < maxIndex);
    }

    // Arrow clicks
    prev.addEventListener('click', () => goTo(index - 1));
    next.addEventListener('click', () => goTo(index + 1));

    // Show arrows when mouse is near edges of viewport (proximity)
    viewport.addEventListener('mousemove', (e) => {
        const rect = viewport.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const edge = 120; // px from edge to show arrow
        // show left arrow when cursor is within left edge region
        if (x <= edge) prev.classList.add('visible');
        else prev.classList.remove('visible');
        // show right arrow when cursor is within right edge region
        if (x >= rect.width - edge) next.classList.add('visible');
        else next.classList.remove('visible');
    });
    viewport.addEventListener('mouseleave', () => {
        prev.classList.remove('visible');
        next.classList.remove('visible');
    });

    // Resize handling: recalc transform
    window.addEventListener('resize', () => goTo(index));

    // initialize
    // apply initial widths to items for consistent sizing
    function layout() {
        const vw = viewport.getBoundingClientRect().width;
        const single = vw / (window.innerWidth >= 768 ? 2 : 1); // two per view on md+
        items.forEach(it => {
            it.style.width = `${single}px`;
            it.style.height = '100%';
        });
        track.style.transition = 'transform 420ms cubic-bezier(.2,.9,.2,1)';
    }

    layout();
    // re-layout after a short delay to allow fonts/images to load
    setTimeout(() => {
        layout();
        goTo(0);
    }, 120);
})();