<?php
try {
    $env = getenv('APP_ENV') ?: 'sqlite';

    if ($env === 'mysql') {
        // Production MySQL (InfinityFree) — activé uniquement si APP_ENV=mysql
        $db = new PDO(
            'mysql:host=sql100.infinityfree.com;dbname=if0_41302736_bde;charset=utf8mb4',
            'if0_41302736',
            'iTzpDy1izEkg0uY'
        );
    } else {
        // SQLite (local ou VPS Debian)
        $db_path = __DIR__ . '/../database.sqlite';
        $db = new PDO('sqlite:' . $db_path);
    }

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur connexion BDD : " . $e->getMessage());
}
