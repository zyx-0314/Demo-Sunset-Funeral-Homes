<?php
// Component: components/buttons/button_primary.php
// Purpose: Primary button component with optional disabled and dark variants
// Data Contract:
// - $label: string|null - Button text
// - $href: string|null - Button link URL
// - $disable: bool|null - Whether button is disabled
// - $dark: bool|null - Whether to use dark variant
?>
<?php
if ($disable ?? false) :
?>
  <a href="<?= esc($href ?? '#') ?>" class="inline-block opacity-50 shadow px-4 py-2 rounded text-white duration-200 btn-disabled">
    <?= esc($label ?? 'Action') ?>
  </a>
<?php
elseif ($dark ?? false) :
?>
  <a href="<?= esc($href ?? '#') ?>" class="inline-block shadow px-4 py-2 rounded text-white duration-200 btn-sage-dark">
    <?= esc($label ?? 'Action') ?>
  </a>
<?php
else:
?>
  <a href="<?= esc($href ?? '#') ?>" class="inline-block shadow px-4 py-2 rounded text-white duration-200 btn-sage dark:btn-sage-dark">
    <?= esc($label ?? 'Action') ?>
  </a>
<?php
endif;
?>