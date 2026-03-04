<?php
require_once __DIR__ . '/auth.php';
requireAdmin();
require_once __DIR__ . '/db.php';

// Déterminer la page active
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - BDE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../admin_style.css">
</head>
<body>

<!-- Sidebar -->
<nav class="admin-sidebar bg-dark text-white">
    <div class="p-3">
        <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> BDE Admin</h5>
    </div>
    <ul class="nav flex-column flex-grow-1">
        <li class="nav-item">
            <a class="nav-link text-white <?= $current_page === 'index.php' ? 'active' : '' ?>" href="index.php">
                <i class="fas fa-tachometer-alt"></i> Tableau de bord
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $current_page === 'evenements.php' ? 'active' : '' ?>" href="evenements.php">
                <i class="fas fa-calendar-alt"></i> Événements
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $current_page === 'sports.php' ? 'active' : '' ?>" href="sports.php">
                <i class="fas fa-dumbbell"></i> Sports
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $current_page === 'membres.php' ? 'active' : '' ?>" href="membres.php">
                <i class="fas fa-users"></i> Membres
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= $current_page === 'partenaires.php' ? 'active' : '' ?>" href="partenaires.php">
                <i class="fas fa-handshake"></i> Partenaires
            </a>
        </li>
    </ul>
    <div class="p-3">
        <a href="../index.php" class="btn btn-outline-light btn-sm w-100 mb-2">
            <i class="fas fa-globe"></i> Voir le site
        </a>
        <a href="../logout.php" class="btn btn-outline-danger btn-sm w-100">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>
    </div>
</nav>

<!-- Main content -->
<main class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div>
        <span class="text-muted">
            <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['admin_username']) ?>
        </span>
    </div>
