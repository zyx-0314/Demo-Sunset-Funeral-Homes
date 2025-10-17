<?php
// Component: components/headers/page_header.php
// Purpose: Displays a page header with title and optional description
// Data Contract:
// - $title: string - The main heading text
// - $description: string | null - Optional subtitle or description text
?>
<header class="mb-8">
    <h1 class="font-bold text-2xl"><?= esc($title) ?></h1>
    <?php if (!empty($description)): ?>
        <p class="text-gray-600"><?= esc($description) ?></p>
    <?php endif; ?>
</header>