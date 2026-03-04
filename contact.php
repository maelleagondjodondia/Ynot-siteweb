<?php
require_once 'includes/db.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse email invalide.';
    } else {
        $stmt = $db->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (:name, :email, :subject, :message)");
        $stmt->execute([
            'name'    => $name,
            'email'   => $email,
            'subject' => $subject,
            'message' => $message
        ]);
        $success = true;
    }
}

require_once 'includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1 class="display-5 fw-bold"><i class="fas fa-envelope"></i> Contactez-nous</h1>
        <p class="lead">Une question ? Une suggestion ? Écrivez-nous !</p>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if ($success): ?>
                    <div class="alert alert-success text-center">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <h5>Message envoyé avec succès !</h5>
                        <p class="mb-0">Nous vous répondrons dans les plus brefs délais.</p>
                    </div>
                <?php else: ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow">
                        <div class="card-body p-4">
                            <form method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nom</label>
                                        <input type="text" name="name" class="form-control" required
                                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" required
                                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Sujet</label>
                                    <input type="text" name="subject" class="form-control" required
                                           value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Message</label>
                                    <textarea name="message" class="form-control" rows="5" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-paper-plane"></i> Envoyer
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <p class="text-muted">
                        <i class="fas fa-envelope me-2"></i> bde@ecole.fr |
                        <i class="fas fa-phone ms-2 me-2"></i> 01 23 45 67 89
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
