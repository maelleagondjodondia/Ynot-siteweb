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
    if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return '';
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowed)) {
        return '';
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        return '';
    }
    $filename = $prefix . '_' . uniqid() . '.' . $ext;
    $destination = __DIR__ . '/../uploads/' . $filename;
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return 'uploads/' . $filename;
    }
    return '';
}
