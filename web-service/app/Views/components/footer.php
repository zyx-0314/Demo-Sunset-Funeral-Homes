<?php
// Component: components/footer.php
// Purpose: Site footer with logo, navigation links, and contact information
// Data Contract:
// - None (hardcoded content)
$copyright = 'Sunset Funeral Homes — CI4 Sample Project 1';

$links = [
  ['label' => 'Mood board', 'href' => '/mood-board'],
  ['label' => 'Road map', 'href' => '/road-map']
]
?>
<footer class="bg-white mt-12 border-t" role="contentinfo">
  <div class="mx-auto px-6 py-8 max-w-6xl text-gray-600 text-sm">
    <div class="flex md:flex-row flex-col md:justify-between md:items-start gap-6">
      <div>
        <img src="/logo/main.svg" alt="Sunset Funeral Homes" class="mb-2 h-11">
        <p>Sunset Funeral Homes — Compassionate care, every step of the way</p>
      </div>
      <div class="gap-6 grid grid-cols-1 sm:grid-cols-3">
        <div>
          <h4 class="mb-2 font-semibold">Services</h4>
        </div>
        <div>
          <h4 class="mb-2 font-semibold">Company</h4>
          <ul>
            <?php
            foreach ($links as $link) :
            ?>
              <li><a href="<?= esc($link['href']) ?>" class="hover:underline"><?= esc($link['label']) ?></a></li>
            <?php
            endforeach;
            ?>
          </ul>
        </div>
        <div>
          <h4 class="mb-2 font-semibold">Contact</h4>
          <p>Phone: (555) 123-4567</p>
          <p>Email: <a href="mailto:info@sunsetfunerals.example" class="hover:underline">info@sunsetfunerals.example</a></p>
        </div>
      </div>
    </div>
    <div class="mt-6 text-gray-500">© <?= esc($copyright) ?></div>
  </div>
</footer>