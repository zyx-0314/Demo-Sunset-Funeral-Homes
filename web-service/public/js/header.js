/**
 * Mobile Menu Handler
 *
 * Manages responsive mobile menu functionality including:
 * - Smooth slide-in/slide-out animations
 * - Focus management and accessibility
 * - Body scroll prevention during modal state
 * - Keyboard navigation support (Escape key)
 * - Touch-friendly backdrop dismissal
 */
(function () {
    'use strict';

    // Get all required DOM elements
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
    const drawerPanel = mobileMenu.querySelector('.fixed.inset-y-0');

    // Exit if any required elements are missing
    if (!mobileMenuButton || !mobileMenu || !mobileMenuClose || !mobileMenuBackdrop || !drawerPanel) {
        return;
    }

    /**
     * Open the mobile menu with smooth animation
     */
    function openMobileMenu() {
        // Make menu visible
        mobileMenu.classList.remove('hidden');
        mobileMenuButton.setAttribute('aria-expanded', 'true');

        // Toggle hamburger/close icon states
        mobileMenuButton.querySelector('.block').classList.add('hidden');
        mobileMenuButton.querySelector('.hidden').classList.remove('hidden');

        // Animate drawer slide-in with slight delay for smooth transition
        setTimeout(() => {
            drawerPanel.classList.remove('translate-x-full');
            drawerPanel.classList.add('translate-x-0');
        }, 10);

        // Move focus to close button for accessibility
        mobileMenuClose.focus();

        // Prevent background scrolling when menu is open
        document.body.style.overflow = 'hidden';
    }

    /**
     * Close the mobile menu with smooth animation
     */
    function closeMobileMenu() {
        // Animate drawer slide-out
        drawerPanel.classList.remove('translate-x-0');
        drawerPanel.classList.add('translate-x-full');

        // Wait for animation to complete before hiding menu
        setTimeout(() => {
            mobileMenu.classList.add('hidden');
            mobileMenuButton.setAttribute('aria-expanded', 'false');

            // Reset hamburger icon state
            mobileMenuButton.querySelector('.hidden').classList.add('hidden');
            mobileMenuButton.querySelector('.block').classList.remove('hidden');

            // Restore body scrolling
            document.body.style.overflow = '';

            // Return focus to menu button
            mobileMenuButton.focus();
        }, 300); // Match CSS transition duration
    }

    // Set up event listeners
    mobileMenuButton.addEventListener('click', openMobileMenu);
    mobileMenuClose.addEventListener('click', closeMobileMenu);
    mobileMenuBackdrop.addEventListener('click', closeMobileMenu);

    // Keyboard accessibility: close menu on Escape key
    mobileMenu.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeMobileMenu();
        }
    });

    // Close menu when clicking on navigation links (for better UX)
    const mobileNavLinks = mobileMenu.querySelectorAll('a[role="menuitem"]');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });
})();