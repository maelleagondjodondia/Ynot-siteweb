<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/auth.php';

$current = basename($_SERVER['PHP_SELF']);
$page_titles = [
    'index.php' => 'Accueil',
    'evenements.php' => 'Événements',
    'sports.php' => 'Sports',
    'membres.php' => 'Membres',
    'login.php' => 'Connexion Admin',
];
$page_title = ($page_titles[$current] ?? 'Page') . ' — BDE Ynot';

$body_classes = [
    'index.php' => 'index',
    'sports.php' => 'sports',
    'evenements.php' => 'evenements',
    'membres.php' => 'membres',
];
$body_class = $body_classes[$current] ?? '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bureau Des Étudiants Ynot — Événements, sports, vie étudiante et bien plus. Rejoignez la communauté !">

    <title><?= $page_title ?></title>

    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">

    <!-- Init thème AVANT le rendu pour éviter le flash -->
    <script>
    (function () {
        try {
            var saved = localStorage.getItem('theme');
            var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            var theme = saved || (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        } catch (e) {}
    })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body, html { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        h1, h2, h3, h4, h5, h6, .display-1, .display-2, .display-3, .display-4, .display-5, .display-6, .navbar-brand {
            font-family: 'Space Grotesk', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-style: normal;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <link rel="stylesheet" href="style.css">
</head>
<body class="<?= $body_class ?>">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-graduation-cap"></i> BDE Ynot
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link <?= $current === 'index.php' ? 'active' : '' ?>" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link <?= $current === 'evenements.php' ? 'active' : '' ?>" href="evenements.php">Événements</a></li>
                <li class="nav-item"><a class="nav-link <?= $current === 'sports.php' ? 'active' : '' ?>" href="sports.php">Sports</a></li>
                <li class="nav-item"><a class="nav-link <?= $current === 'membres.php' ? 'active' : '' ?>" href="membres.php">Membres</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#contact">Contact</a></li>

                <li class="nav-item ms-lg-2">
                    <?php if (isAdmin()): ?>
                        <a class="btn btn-admin-nav" href="admin/index.php">
                            <i class="fas fa-tachometer-alt"></i> Tableau de bord
                        </a>
                    <?php else: ?>
                        <a class="btn btn-admin-nav" href="login.php">
                            <i class="fas fa-user-shield"></i> Espace Admin
                        </a>
                    <?php endif; ?>
                </li>

                <li class="nav-item">
                    <button id="themeToggle" class="theme-toggle" type="button" aria-label="Basculer le thème">
                        <i class="icon-moon fas fa-moon"></i>
                        <i class="icon-sun fas fa-sun"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>
