<?php
// Component: components/sections/dev_login_buttons.php
// Purpose: Development mode quick login buttons for seeded users
// Data Contract:
// - None (conditionally renders based on ENVIRONMENT constant)
?>
<?php if (ENVIRONMENT === 'development'): ?>
    <div class="mt-8">
        <h3 class="font-medium text-gray-700 text-sm">Dev Mode: Quick Login with Seeded Users</h3>
        <div class="space-y-2 mt-2">
            <button type="button" onclick="fillLogin('alice@example.test', 'Password123!')" class="bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded w-full text-white text-xs">Active Client (Alice)</button>
            <button type="button" onclick="fillLogin('bob@example.test', 'Password123!')" class="bg-red-600 hover:bg-red-700 px-3 py-2 rounded w-full text-white text-xs">Inactive Client (Bob)</button>
            <button type="button" onclick="fillLogin('tina.staff@example.test', 'Password123!')" class="bg-green-600 hover:bg-green-700 px-3 py-2 rounded w-full text-white text-xs">Staff (Tina)</button>
            <button type="button" onclick="fillLogin('flora.florist@example.test', 'Password123!')" class="bg-purple-600 hover:bg-purple-700 px-3 py-2 rounded w-full text-white text-xs">Florist (Flora)</button>
            <button type="button" onclick="fillLogin('martin.manager@example.test', 'Password123!')" class="bg-orange-600 hover:bg-orange-700 px-3 py-2 rounded w-full text-white text-xs">Manager (Martin)</button>
        </div>
    </div>

    <script src="<?= base_url('js/dev_login_buttons.js') ?>"></script>
<?php endif; ?>