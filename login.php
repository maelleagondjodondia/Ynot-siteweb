<?php
require_once 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['admin_id'])) {
    header('Location: admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare("SELECT * FROM admins WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        $authenticated = false;

        if (password_verify($password, $admin['password'])) {
            $authenticated = true;
        } elseif ($admin['password'] === $password) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $upd = $db->prepare("UPDATE admins SET password = :p WHERE id = :id");
            $upd->execute(['p' => $hashed, 'id' => $admin['id']]);
            $authenticated = true;
        }

        if ($authenticated) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: admin/index.php');
            exit;
        }
    }

    $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
}

require_once 'includes/header.php';
?>

<section class="login-split">
    <!-- Panneau visuel gauche -->
    <div class="login-visual">
        <div class="hero-blobs">
            <span class="blob blob-1"></span>
            <span class="blob blob-2"></span>
            <span class="blob blob-3"></span>
        </div>
        <i class="fas fa-graduation-cap floating-shape s-1"></i>
        <i class="fas fa-calendar-star floating-shape s-2"></i>
        <i class="fas fa-bolt floating-shape s-3"></i>
        <i class="fas fa-star floating-shape s-4"></i>

        <div class="login-visual-inner">
            <a href="index.php" class="login-back">
                <i class="fas fa-arrow-left"></i> Retour au site
            </a>

            <div class="login-quote">
                <span class="login-eyebrow">
                    <i class="fas fa-shield-halved"></i> Espace privé
                </span>
                <h2 class="login-title">
                    Pilote la vie<br>
                    étudiante depuis<br>
                    <span class="word-glow">un seul endroit.</span>
                </h2>
                <p class="login-sub">
                    Gère les événements, les sports, les membres et les partenaires.
                    Tout ce qu'il faut pour faire vibrer le BDE Ynov Toulouse.
                </p>
            </div>

            <div class="login-features">
                <div class="login-feat">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Créer un évent en 30s</span>
                </div>
                <div class="login-feat">
                    <i class="fas fa-users"></i>
                    <span>Gérer l'équipe</span>
                </div>
                <div class="login-feat">
                    <i class="fas fa-image"></i>
                    <span>Upload photos</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Panneau formulaire droite -->
    <div class="login-form-side">
        <div class="login-form-wrap">
            <div class="login-brand">
                <i class="fas fa-graduation-cap"></i> BDE Ynov
            </div>

            <h1 class="login-form-title">Connexion admin</h1>
            <p class="login-form-sub">Entre tes identifiants pour accéder au tableau de bord.</p>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="mb-3">
                    <label class="form-label" for="username">Nom d'utilisateur</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" id="username" name="username" class="form-control" required
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                               autocomplete="username">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" id="password" name="password" class="form-control" required
                               autocomplete="current-password">
                    </div>
                </div>
                <button type="submit" class="btn btn-login w-100">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>
            </form>

            <p class="login-footer-note">
                Pas d'accès ? Contacte un membre du BDE.
            </p>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
