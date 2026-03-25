<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ../login.php');
        exit;
    }
}

function isAdmin(): bool {
    return isset($_SESSION['admin_id']);
}

function uploadImage(array $file, string $prefix = 'img'): string {
    // Pas de fichier envoyé
    if (empty($file['name']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return '';
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return '';
    }

    // Vérifier l'extension
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg' => 'jpeg', 'jpeg' => 'jpeg', 'png' => 'png', 'gif' => 'gif', 'webp' => 'webp'];
    if (!isset($allowed[$ext])) {
        return '';
    }

    // Vérifier la taille (5 MB max)
    if ($file['size'] > 5 * 1024 * 1024) {
        return '';
    }

    // Lire le fichier et convertir en base64 data URI
    $data = file_get_contents($file['tmp_name']);
    if ($data === false) {
        return '';
    }

    $mime = 'image/' . $allowed[$ext];
    return 'data:' . $mime . ';base64,' . base64_encode($data);
}
