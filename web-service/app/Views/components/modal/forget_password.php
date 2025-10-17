<?php
// Component: components/modal/forget_password.php
// Purpose: Modal component for password reset functionality
// Data Contract:
// - None
?>

<button type="button" id="openForgotModal" class="font-medium text-emerald-600 hover:underline">Forgot your password?</button>

<!-- Template (inert inside the page) to avoid being inside the login form DOM tree -->
<template id="forgot-template">
    <div id="forgotModal" class="z-50 fixed inset-0 flex justify-center items-center bg-black/40">
        <div role="dialog" aria-modal="true" aria-labelledby="forgotTitle" class="bg-white mx-4 p-6 rounded-lg w-full max-w-md">
            <h3 id="forgotTitle" class="font-semibold text-lg">Reset your password</h3>
            <p class="mt-2 text-gray-600 text-sm">Enter the email address for your account and we'll send a reset link.</p>

            <form id="forgotForm" class="mt-4" action="/forgot" method="post">
                <?= csrf_field() ?>
                <label for="forgotEmail" class="sr-only">Email address</label>
                <input id="forgotEmail" name="email" type="email" required placeholder="you@example.com" class="px-3 py-2 border border-gray-300 rounded-md w-full">

                <div id="forgotFeedback" class="mt-3 text-gray-700 text-sm" aria-live="polite"></div>

                <div class="flex justify-end items-center gap-3 mt-4">
                    <button type="button" id="cancelForgot" class="px-4 py-2 border rounded-md">Cancel</button>
                    <button type="submit" id="sendResetBtn" class="bg-emerald-600 px-4 py-2 rounded-md text-white">Send reset</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script src="/js/forgot_password.js"></script>