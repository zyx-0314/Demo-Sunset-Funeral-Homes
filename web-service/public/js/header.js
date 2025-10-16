(function () {
    'use strict';

    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
    const drawerPanel = mobileMenu.querySelector('.fixed.inset-y-0');

    if (!mobileMenuButton || !mobileMenu || !mobileMenuClose || !mobileMenuBackdrop || !drawerPanel) {
        return;
    }

    function openMobileMenu() {
        mobileMenu.classList.remove('hidden');
        mobileMenuButton.setAttribute('aria-expanded', 'true');

        // Show menu icons
        mobileMenuButton.querySelector('.block').classList.add('hidden');
        mobileMenuButton.querySelector('.hidden').classList.remove('hidden');

        // Animate drawer in
        setTimeout(() => {
            drawerPanel.classList.remove('translate-x-full');
            drawerPanel.classList.add('translate-x-0');
        }, 10);

        // Trap focus
        mobileMenuClose.focus();

        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        // Animate drawer out
        drawerPanel.classList.remove('translate-x-0');
        drawerPanel.classList.add('translate-x-full');

        setTimeout(() => {
            mobileMenu.classList.add('hidden');
            mobileMenuButton.setAttribute('aria-expanded', 'false');

            // Show hamburger icon
            mobileMenuButton.querySelector('.hidden').classList.add('hidden');
            mobileMenuButton.querySelector('.block').classList.remove('hidden');

            // Restore body scroll
            document.body.style.overflow = '';

            // Return focus to button
            mobileMenuButton.focus();
        }, 300);
    }

    // Event listeners
    mobileMenuButton.addEventListener('click', openMobileMenu);
    mobileMenuClose.addEventListener('click', closeMobileMenu);
    mobileMenuBackdrop.addEventListener('click', closeMobileMenu);

    // Keyboard support
    mobileMenu.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeMobileMenu();
        }
    });

    // Close menu when clicking on navigation links
    const mobileNavLinks = mobileMenu.querySelectorAll('a[role="menuitem"]');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });
})();