<?php
// Component: components/modal/accounts/update.php
// Purpose: Modal dialog for updating user account types with form submission and feedback
// Data contract:
// - $account: object - Account data with id and type properties
?>

<div class="flex justify-end mb-4">
    <button type="button" <?= isset($account->id) ? 'data-update-account-id="' . esc($account->id) . '"' : '' ?> <?= isset($account->type) ? 'data-update-account-type="' . esc($account->type) . '"' : '' ?> class="bg-amber-600/70 hover:bg-amber-600/60 px-3 py-2 rounded text-white duration-200 cursor-pointer js-update-account-trigger">
        <i class="fa-pen-to-square fa-solid"></i>
    </button>
</div>

<div class="hidden z-50 fixed inset-0 justify-center items-center m-0 update-account-modal">
    <div class="absolute inset-0 bg-black opacity-50 update-account-backdrop"></div>

    <div class="relative bg-white shadow-lg mx-4 my-8 rounded w-full max-w-lg max-h-[90vh] overflow-auto" role="dialog" aria-modal="true" aria-labelledby="updateAccountTitle">
        <header class="px-6 py-4 border-b">
            <h3 id="updateAccountTitle" class="font-semibold text-lg">Update account</h3>
        </header>

        <div class="space-y-4 px-6 py-4 update-account-content">
            <form class="update-account-form" method="POST" action="/admin/accounts/update">
                <?= csrf_field() ?>
                <input type="hidden" name="id" class="update-account-id" value="<?= esc($account->id ?? '') ?>" />

                <p class="text-gray-700 text-sm">Choose the account type and click Update to save.</p>

                <div class="mt-4 px-2">
                    <label class="block font-medium text-gray-900 text-sm">Type</label>
                    <select name="type" class="block mt-1 px-3 py-2 border rounded w-full update-account-type-input">
                        <option value="client" <?= isset($account->type) && $account->type === 'client' ? 'selected' : '' ?>>Client</option>
                        <option value="embalmer" <?= isset($account->type) && $account->type === 'embalmer' ? 'selected' : '' ?>>Embalmer</option>
                        <option value="driver" <?= isset($account->type) && $account->type === 'driver' ? 'selected' : '' ?>>Driver</option>
                        <option value="florist" <?= isset($account->type) && $account->type === 'florist' ? 'selected' : '' ?>>Florist</option>
                        <option value="manager" <?= isset($account->type) && $account->type === 'manager' ? 'selected' : '' ?>>Manager</option>
                        <option value="staff" <?= isset($account->type) && $account->type === 'staff' ? 'selected' : '' ?>>Staff</option>
                    </select>
                </div>

                <br>

                <footer class="flex justify-end space-x-2 pt-4 border-t">
                    <button type="button" class="px-4 py-2 border rounded cursor-pointer btn-cancel-update-account">Cancel</button>
                    <button type="submit" class="bg-amber-600 px-4 py-2 rounded text-white cursor-pointer">Update</button>
                </footer>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('js/accounts_update_modal.js'); ?>"></script>