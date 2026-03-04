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

<section style="min-height: calc(100vh - 76px); display: flex; align-items: center; padding: 120px 0 60px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-card card">
                    <div class="card-header text-center text-white">
                        <h3 class="mb-0"><i class="fas fa-user-shield"></i> Espace Admin</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nom d'utilisateur</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" name="username" class="form-control" required
                                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-sign-in-alt"></i> Se connecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
