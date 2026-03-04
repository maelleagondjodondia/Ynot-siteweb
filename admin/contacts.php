<?php require_once '../includes/admin_header.php'; ?>

<?php
$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);
$success = $_GET['success'] ?? '';

// Suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {
    $del_id = intval($_POST['id']);
    $stmt = $db->prepare("DELETE FROM contacts WHERE id = :id");
    $stmt->execute(['id' => $del_id]);
    header('Location: contacts.php?success=deleted');
    exit;
}

// Voir un message (marquer comme lu)
$contact = null;
if ($action === 'view' && $id) {
    $stmt = $db->prepare("UPDATE contacts SET is_read = 1 WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $stmt = $db->prepare("SELECT * FROM contacts WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) { header('Location: contacts.php'); exit; }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="fas fa-envelope"></i> Messages</h2>
</div>

<?php if ($success === 'deleted'): ?>
    <div class="alert alert-success"><i class="fas fa-check"></i> Message supprimé.</div>
<?php endif; ?>

<?php if ($action === 'view' && $contact): ?>
    <!-- Vue détaillée -->
    <div class="admin-form">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h4 class="mb-1"><?= htmlspecialchars($contact['subject']) ?></h4>
                <p class="text-muted mb-0">
                    De <strong><?= htmlspecialchars($contact['name']) ?></strong>
                    &lt;<?= htmlspecialchars($contact['email']) ?>&gt;
                    &mdash; <?= date('d/m/Y à H:i', strtotime($contact['created_at'])) ?>
                </p>
            </div>
            <a href="contacts.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
        <hr>
        <div class="py-3">
            <?= nl2br(htmlspecialchars($contact['message'])) ?>
        </div>
        <hr>
        <a href="mailto:<?= htmlspecialchars($contact['email']) ?>?subject=Re: <?= htmlspecialchars($contact['subject']) ?>"
           class="btn btn-primary">
            <i class="fas fa-reply"></i> Répondre par email
        </a>
        <form method="POST" action="contacts.php?action=delete" class="d-inline"
              onsubmit="return confirm('Supprimer ce message ?');">
            <input type="hidden" name="id" value="<?= $contact['id'] ?>">
            <button class="btn btn-danger"><i class="fas fa-trash"></i> Supprimer</button>
        </form>
    </div>

<?php else: ?>
    <!-- Liste -->
    <?php
    $contacts = $db->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <?php if (empty($contacts)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-envelope fa-3x mb-3"></i>
            <p>Aucun message pour le moment.</p>
        </div>
    <?php else: ?>
        <div class="admin-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Statut</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Sujet</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $c): ?>
                    <tr class="<?= !$c['is_read'] ? 'fw-bold' : '' ?>">
                        <td>
                            <?php if (!$c['is_read']): ?>
                                <span class="badge bg-warning">Nouveau</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Lu</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($c['name']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['subject']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></td>
                        <td>
                            <a href="contacts.php?action=view&id=<?= $c['id'] ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form method="POST" action="contacts.php?action=delete" class="d-inline"
                                  onsubmit="return confirm('Supprimer ce message ?');">
                                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php require_once '../includes/admin_footer.php'; ?>
