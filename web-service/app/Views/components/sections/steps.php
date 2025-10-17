<?php
// Component: components/sections/steps.php
// Purpose: Steps section component for displaying process steps in a grid
// Data Contract:
// - $steps: array - Array of step strings to display
?>
<section class="mt-12">
    <h3 class="font-semibold text-lg">We guide you through the process</h3>
    <div class="gap-6 grid grid-cols-1 md:grid-cols-4 mt-6">
        <?php
        foreach ($steps as $value) : ?>
            <div class="bg-white p-4 rounded-lg text-center">
                <div class="font-medium text-sm"><?php echo $value ?></div>
            </div>
        <?php
        endforeach;
        ?>
    </div>
</section>