<?php
require_once '../includes/auth.php';
requireAdmin();
require_once '../includes/db.php';

$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);

// Suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {
    $del_id = intval($_POST['id']);
    $stmt = $db->prepare("DELETE FROM activities WHERE id = :id AND pole = 'sport'");
    $stmt->execute(['id' => $del_id]);
    header('Location: sports.php?success=deleted');
    exit;
}

// Ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $name = trim($_POST['name']);
    $activity_date = $_POST['activity_date'];
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);
    $image_path = uploadImage($_FILES['image'] ?? [], 'sport');

    $stmt = $db->prepare("INSERT INTO activities (name, activity_date, price, description, image, pole, admin_id)
                           VALUES (:name, :date, :price, :desc, :img, 'sport', :admin)");
    $stmt->execute([
        'name' => $name, 'date' => $activity_date, 'price' => $price,
        'desc' => $description, 'img' => $image_path, 'admin' => $_SESSION['admin_id']
    ]);
    header('Location: sports.php?success=added');
    exit;
}

// Modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit') {
    $name = trim($_POST['name']);
    $activity_date = $_POST['activity_date'];
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);
    $image_path = uploadImage($_FILES['image'] ?? [], 'sport');

    if ($image_path) {
        $stmt = $db->prepare("UPDATE activities SET name=:name, activity_date=:date, price=:price, description=:desc, image=:img WHERE id=:id AND pole='sport'");
        $stmt->execute(['name' => $name, 'date' => $activity_date, 'price' => $price, 'desc' => $description, 'img' => $image_path, 'id' => $id]);
    } else {
        $stmt = $db->prepare("UPDATE activities SET name=:name, activity_date=:date, price=:price, description=:desc WHERE id=:id AND pole='sport'");
        $stmt->execute(['name' => $name, 'date' => $activity_date, 'price' => $price, 'desc' => $description, 'id' => $id]);
    }
    header('Location: sports.php?success=updated');
    exit;
}

// Charger le sport pour édition
$sport = null;
if ($action === 'edit' && $id) {
    $stmt = $db->prepare("SELECT * FROM activities WHERE id = :id AND pole = 'sport'");
    $stmt->execute(['id' => $id]);
    $sport = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$sport) { header('Location: sports.php'); exit; }
}

require_once '../includes/admin_header.php';
$success = $_GET['success'] ?? '';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="fas fa-dumbbell"></i> Sports</h2>
    <?php if ($action === 'list'): ?>
        <a href="sports.php?action=add" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter
        </a>
    <?php endif; ?>
</div>

<?php if ($success === 'added'): ?>
    <div class="alert alert-success"><i class="fas fa-check"></i> Sport ajouté avec succès.</div>
<?php elseif ($success === 'updated'): ?>
    <div class="alert alert-success"><i class="fas fa-check"></i> Sport modifié avec succès.</div>
<?php elseif ($success === 'deleted'): ?>
    <div class="alert alert-success"><i class="fas fa-check"></i> Sport supprimé.</div>
<?php endif; ?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-form">
        <h4 class="mb-4"><?= $action === 'add' ? 'Nouveau sport' : 'Modifier le sport' ?></h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" required
                           value="<?= htmlspecialchars($sport['name'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="activity_date" class="form-control" required
                           value="<?= $sport['activity_date'] ?? '' ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Prix (EUR)</label>
                    <input type="number" step="0.01" min="0" name="price" class="form-control"
                           value="<?= $sport['price'] ?? '0' ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($sport['description'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <?php if (!empty($sport['image'])): ?>
                    <div class="mt-2">
                        <img src="<?= htmlspecialchars($sport['image']) ?>" style="height: 80px; border-radius: 8px;">
                        <small class="text-muted ms-2">Image actuelle</small>
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
            <a href="sports.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

<?php else: ?>
    <?php
    $sports = $db->query("SELECT * FROM activities WHERE pole='sport' ORDER BY activity_date DESC")->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <?php if (empty($sports)): ?>
        <div class="text-center py-5 text-muted">
            <i class="fas fa-dumbbell fa-3x mb-3"></i>
            <p>Aucun sport pour le moment.</p>
        </div>
    <?php else: ?>
        <div class="admin-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Date</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sports as $sp): ?>
                    <tr>
                        <td>
                            <?php if ($sp['image']): ?>
                                <img src="<?= htmlspecialchars($sp['image']) ?>">
                            <?php else: ?>
                                <div style="width:50px;height:50px;background:#e9ecef;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($sp['name']) ?></strong></td>
                        <td><?= date('d/m/Y', strtotime($sp['activity_date'])) ?></td>
                        <td><?= number_format($sp['price'], 2) ?> &euro;</td>
                        <td>
                            <a href="sports.php?action=edit&id=<?= $sp['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="sports.php?action=delete" class="d-inline"
                                  onsubmit="return confirm('Supprimer ce sport ?');">
                                <input type="hidden" name="id" value="<?= $sp['id'] ?>">
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
