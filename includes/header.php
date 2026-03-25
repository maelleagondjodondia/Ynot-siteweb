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

// Classe body par page (pour les gradients différents)
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- CSS global -->
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
            </ul>
        </div>
    </div>
</nav>
