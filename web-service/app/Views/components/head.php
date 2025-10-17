<?php
// Component: components/head.php
// Purpose: HTML head section with meta tags, fonts, and CSS includes
// Data Contract:
// - $title: string|null - Page title (optional)
// - $css: array - Array of CSS file paths to include
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= esc($title ?? null ? $title . ": " : "") ?> Sunset Funeral Homes</title>

    <!-- Default CDN includes -->
    <!-- Google Fonts: Playfair Display + Lato (global) -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Global CSS -->
    <link rel="stylesheet" href="/css/global.css">

    <!-- CSS files -->
    <?php
    $cssFiles = $css ?? [];
    foreach ($cssFiles as $cssFile) {
        echo '<link rel="stylesheet" href="' . esc($cssFile, 'attr') . '">' . PHP_EOL;
    }
    ?>
</head>