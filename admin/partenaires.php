<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);

// Suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {
    $del_id = intval($_POST['id']);
    $stmt = $db->prepare("DELETE FROM partners WHERE id = :id");
    $stmt->execute(['id' => $del_id]);
    header('Location: partenaires.php?success=deleted');
    exit;
}

// Ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $telephone = trim($_POST['telephone']);
    $logo_path = uploadImage($_FILES['logo'] ?? [], 'partner');

    $stmt = $db->prepare("INSERT INTO partners (name, address, telephone, logo) VALUES (:name, :address, :telephone, :logo)");
    $stmt->execute(['name' => $name, 'address' => $address, 'telephone' => $telephone, 'logo' => $logo_path]);
    header('Location: partenaires.php?success=added');
    exit;
}

// Modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit') {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $telephone = trim($_POST['telephone']);
    $logo_path = uploadImage($_FILES['logo'] ?? [], 'partner');

    if ($logo_path) {
        $stmt = $db->prepare("UPDATE partners SET name=:name, address=:address, telephone=:telephone, logo=:logo WHERE id=:id");
        $stmt->execute(['name' => $name, 'address' => $address, 'telephone' => $telephone, 'logo' => $logo_path, 'id' => $id]);
    } else {
        $stmt = $db->prepare("UPDATE partners SET name=:name, address=:address, telephone=:telephone WHERE id=:id");
        $stmt->execute(['name' => $name, 'address' => $address, 'telephone' => $telephone, 'id' => $id]);
    }
    header('Location: partenaires.php?success=updated');
    exit;
}

// Charger le partenaire pour édition
$partner = null;
if ($action === 'edit' && $id) {
    $stmt = $db->prepare("SELECT * FROM partners WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $partner = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$partner) { header('Location: partenaires.php'); exit; }
}

require_once '../includes/admin_header.php';
$success = $_GET['success'] ?? '';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="fas fa-handshake"></i> Partenaires</h2>
    <?php if ($action === 'list'): ?>
        <a href="partenaires.php?action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter
        </a>
    <?php endif; ?>
</div>

<?php if ($success === 'added'): ?>
    <div class="alert alert-success"><i class="fas fa-check"></i> Partenaire ajouté avec succès.</div>
<?php elseif ($success === 'updated'): ?>
    <div class="alert alert-success"><i class="fas fa-check"></i> Partenaire modifié avec succès.</div>
<?php elseif ($success === 'deleted'): ?>
    <div class="alert alert-success"><i class="fas fa-check"></i> Partenaire supprimé.</div>
<?php endif; ?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-form">
        <h4 class="mb-4"><?= $action === 'add' ? 'Nouveau partenaire' : 'Modifier le partenaire' ?></h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" required
                           value="<?= htmlspecialchars($partner['name'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="address" class="form-control"
                           value="<?= htmlspecialchars($partner['address'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control"
                           value="<?= htmlspecialchars($partner['telephone'] ?? '') ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Logo</label>
                <input type="file" name="logo" class="form-control" accept="image/*">
                <?php if (!empty($partner['logo'])): ?>
                    <div class="mt-2">
                        <img src="<?= htmlspecialchars($partner['logo']) ?>" style="height: 80px; border-radius: 8px;">
                        <small class="text-muted ms-2">Logo actuel</small>
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
            <a href="partenaires.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

<?php else: ?>
    <?php
    $partners = $db->query("SELECT * FROM partners ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <?php if (empty($partners)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-handshake fa-3x mb-3"></i>
            <p>Aucun partenaire pour le moment.</p>
        </div>
    <?php else: ?>
        <div class="admin-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Nom</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($partners as $p): ?>
                    <tr>
                        <td>
                            <?php if ($p['logo']): ?>
                                <img src="<?= htmlspecialchars($p['logo']) ?>">
                            <?php else: ?>
                                <div style="width:50px;height:50px;background:#e9ecef;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-handshake text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
                        <td><?= htmlspecialchars($p['address'] ?: '—') ?></td>
                        <td><?= htmlspecialchars($p['telephone'] ?: '—') ?></td>
                        <td>
                            <a href="partenaires.php?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="partenaires.php?action=delete" class="d-inline"
                                  onsubmit="return confirm('Supprimer ce partenaire ?');">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
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
