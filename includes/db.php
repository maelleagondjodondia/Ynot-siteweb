<?php
try {
    // Détecte si on est en local ou sur InfinityFree
    if (in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1'])
        || php_sapi_name() === 'cli') {
        // ── Local : SQLite ──
        $db_path = __DIR__ . '/../database.sqlite';
        $db = new PDO('sqlite:' . $db_path);
    } else {
        // ── Production : MySQL (InfinityFree) ──
        $db = new PDO(
            'mysql:host=sql100.infinityfree.com;dbname=if0_41302736_bde;charset=utf8mb4',
            'if0_41302736',
            'iTzpDy1izEkg0uY'
        );
    }
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur connexion BDD : " . $e->getMessage());
}
