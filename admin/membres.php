<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);

$poles = ['Sport', 'Evenementiel', 'Communication', 'Partenaria'];

// Suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {
    $del_id = intval($_POST['id']);
    $stmt = $db->prepare("DELETE FROM members WHERE id = :id");
    $stmt->execute(['id' => $del_id]);
    header('Location: membres.php?success=deleted');
    exit;
}

// Ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);
    $pole = $_POST['pole'];
    $image_path = uploadImage($_FILES['image'] ?? [], 'member');

    $stmt = $db->prepare("INSERT INTO members (name, role, pole, image) VALUES (:name, :role, :pole, :image)");
    $stmt->execute(['name' => $name, 'role' => $role, 'pole' => $pole, 'image' => $image_path]);
    header('Location: membres.php?success=added');
    exit;
}

// Modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit') {
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);
    $pole = $_POST['pole'];
    $image_path = uploadImage($_FILES['image'] ?? [], 'member');

    if ($image_path) {
        $stmt = $db->prepare("UPDATE members SET name=:name, role=:role, pole=:pole, image=:image WHERE id=:id");
        $stmt->execute(['name' => $name, 'role' => $role, 'pole' => $pole, 'image' => $image_path, 'id' => $id]);
    } else {
        $stmt = $db->prepare("UPDATE members SET name=:name, role=:role, pole=:pole WHERE id=:id");
        $stmt->execute(['name' => $name, 'role' => $role, 'pole' => $pole, 'id' => $id]);
    }
    header('Location: membres.php?success=updated');
    exit;
}

// Charger le membre pour édition
$member = null;
if ($action === 'edit' && $id) {
    $stmt = $db->prepare("SELECT * FROM members WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$member) { header('Location: membres.php'); exit; }
}

require_once '../includes/admin_header.php';
$success = $_GET['success'] ?? '';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="fas fa-users"></i> Membres</h2>
    <?php if ($action === 'list'): ?>
        <a href="membres.php?action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter
        </a>
    <?php endif; ?>
</div>

<?php if ($success === 'added'): ?>
    <div class="alert alert-success"><i class="fas fa-check"></i> Membre ajouté avec succès.</div>
<?php elseif ($success === 'updated'): ?>
    <div class="alert alert-success"><i class="fas fa-check"></i> Membre modifié avec succès.</div>
<?php elseif ($success === 'deleted'): ?>
    <div class="alert alert-success"><i class="fas fa-check"></i> Membre supprimé.</div>
<?php endif; ?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-form">
        <h4 class="mb-4"><?= $action === 'add' ? 'Nouveau membre' : 'Modifier le membre' ?></h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" required
                           value="<?= htmlspecialchars($member['name'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Rôle</label>
                    <input type="text" name="role" class="form-control" required
                           value="<?= htmlspecialchars($member['role'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Pôle</label>
                    <select name="pole" class="form-select" required>
                        <?php foreach ($poles as $p): ?>
                            <option value="<?= $p ?>" <?= ($member['pole'] ?? '') === $p ? 'selected' : '' ?>>
                                <?= $p ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Photo</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <?php if (!empty($member['image'])): ?>
                    <div class="mt-2">
                        <img src="<?= htmlspecialchars($member['image']) ?>" style="height: 80px; border-radius: 8px;">
                        <small class="text-muted ms-2">Photo actuelle</small>
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
            <a href="membres.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

<?php else: ?>
    <?php
    $members = $db->query("SELECT * FROM members ORDER BY pole ASC, name ASC")->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <?php if (empty($members)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-users fa-3x mb-3"></i>
            <p>Aucun membre pour le moment.</p>
        </div>
    <?php else: ?>
        <div class="admin-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Rôle</th>
                        <th>Pôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $m): ?>
                    <tr>
                        <td>
                            <?php if ($m['image']): ?>
                                <img src="<?= htmlspecialchars($m['image']) ?>">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($m['name']) ?>&background=2563eb&color=fff&size=50">
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($m['name']) ?></strong></td>
                        <td><?= htmlspecialchars($m['role']) ?></td>
                        <td><span class="badge bg-primary"><?= htmlspecialchars($m['pole']) ?></span></td>
                        <td>
                            <a href="membres.php?action=edit&id=<?= $m['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="membres.php?action=delete" class="d-inline"
                                  onsubmit="return confirm('Supprimer ce membre ?');">
                                <input type="hidden" name="id" value="<?= $m['id'] ?>">
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
