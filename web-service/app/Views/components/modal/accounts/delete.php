<?php
// Component: components/modal/accounts/delete.php
// Purpose: Modal dialog for deleting user accounts with confirmation and feedback
// Data contract:
// - $account: object - Account data with id, name, and email properties
?>

<div class="flex justify-end mb-4">
    <button type="button" <?= isset($account->id) ? 'data-delete-account-id="' . esc($account->id) . '"' : '' ?> <?= isset($account->name) ? 'data-delete-account-name="' . esc($account->name) . '"' : '' ?> class="bg-red-600/70 hover:bg-red-600/60 px-3 py-2 rounded text-white duration-200 cursor-pointer js-delete-account-trigger">
        <i class="fa-solid fa-trash"></i>
    </button>
</div>

<div class="hidden z-50 fixed inset-0 justify-center items-center m-0 delete-account-modal">
    <div class="absolute inset-0 bg-black opacity-50 delete-account-backdrop"></div>

    <div class="relative bg-white shadow-lg mx-4 my-8 rounded w-full max-w-lg max-h-[90vh] overflow-auto" role="dialog" aria-modal="true" aria-labelledby="deleteAccountTitle">
        <header class="px-6 py-4 border-b">
            <h3 id="deleteAccountTitle" class="font-semibold text-lg">Delete account</h3>
        </header>

        <form class="space-y-4 px-6 py-4 delete-account-form" method="POST" action="/admin/accounts/delete">
            <?= csrf_field() ?>
            <input type="hidden" name="id" class="delete-account-id" value="<?= esc($account->id ?? '') ?>" />

            <p class="text-gray-700 text-sm">You are about to delete the following account. This action cannot be undone.</p>

            <div class="mt-4 px-2">
                <div class="font-medium text-gray-900 text-sm">Account</div>
                <div class="mt-1 text-gray-700 text-base delete-account-name"><?= esc($account->name) ?></div>
                <div class="mt-1 text-gray-700 text-base delete-account-name"><?= esc($account->email) ?></div>
            </div>

            <footer class="flex justify-end space-x-2 pt-4 border-t">
                <button type="button" class="px-4 py-2 border rounded cursor-pointer btn-cancel-delete-account">Cancel</button>
                <button type="submit" class="bg-red-600 px-4 py-2 rounded text-white cursor-pointer">Delete</button>
            </footer>
        </form>
    </div>
</div>

<script src="<?= base_url('js/accounts_delete_modal.js'); ?>"></script>