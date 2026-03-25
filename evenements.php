<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Recherche & tri
$search = $_GET['search'] ?? '';
$sort   = $_GET['sort'] ?? 'activity_date';

$query = "SELECT * FROM activities 
          WHERE pole = 'event'
          AND (name LIKE :search OR description LIKE :search)";

switch ($sort) {
    case 'name':
        $query .= " ORDER BY name ASC";
        break;
    case 'price':
        $query .= " ORDER BY price ASC";
        break;
    default:
        $query .= " ORDER BY activity_date ASC";
}

$stmt = $db->prepare($query);
$stmt->execute(['search' => "%$search%"]);
$evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Page Header -->
<section class="page-header animate__animated animate__fadeIn">
    <div class="container">
        <h1 class="display-4 fw-bold">
            <i class="fas fa-calendar-alt"></i> Nos Événements
        </h1>
        <p class="lead">Découvrez tous les événements organisés par le BDE</p>
    </div>
</section>

<!-- Recherche & Tri -->
<div class="container mb-5">
    <form method="GET" action="evenements.php" class="search-section animate__animated animate__fadeInUp">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text"
                           class="form-control"
                           name="search"
                           placeholder="Rechercher un événement..."
                           value="<?= htmlspecialchars($search) ?>">
                </div>
            </div>

            <div class="col-md-4">
                <select class="form-select" name="sort" onchange="this.form.submit()">
                    <option value="activity_date" <?= $sort === 'activity_date' ? 'selected' : '' ?>>
                        Trier par date
                    </option>
                    <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>
                        Trier par nom
                    </option>
                    <option value="price" <?= $sort === 'price' ? 'selected' : '' ?>>
                        Trier par prix
                    </option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Liste des événements -->
<div class="container pb-5">
<?php if ($evenements): ?>
    <div class="row">
        <?php foreach ($evenements as $event): ?>
            <div class="col-md-4 mb-4 animate__animated animate__fadeIn">
                <div class="event-card">
                    <img src="<?= $event['image'] ?: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30' ?>"
                         alt="<?= htmlspecialchars($event['name']) ?>">

                    <div class="event-card-body">
                        <p class="event-date mb-2">
                            <i class="fas fa-calendar"></i>
                            <?= date('d/m/Y', strtotime($event['activity_date'])) ?>
                        </p>

                        <h5 class="fw-bold mb-3">
                            <?= htmlspecialchars($event['name']) ?>
                        </h5>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="event-price">
                                <?= $event['price'] > 0 ? number_format($event['price'], 2) . ' €' : 'Gratuit' ?>
                            </span>

                            <button class="btn btn-see-more"
                                    data-bs-toggle="modal"
                                    data-bs-target="#eventModal<?= $event['id'] ?>">
                                Voir plus <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="eventModal<?= $event['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">
                                <?= htmlspecialchars($event['name']) ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <img src="<?= $event['image'] ?: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30' ?>"
                                 class="img-fluid rounded mb-3"
                                 alt="<?= htmlspecialchars($event['name']) ?>">

                            <p><strong>Date :</strong>
                                <?= date('d/m/Y', strtotime($event['activity_date'])) ?>
                            </p>

                            <p><strong>Prix :</strong>
                                <?= $event['price'] > 0 ? number_format($event['price'], 2) . ' €' : 'Gratuit' ?>
                            </p>

                            <hr>

                            <h6 class="fw-bold">Description</h6>
                            <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="no-events text-center animate__animated animate__fadeIn">
        <i class="fas fa-calendar-times fa-3x mb-3"></i>
        <h3>Aucun événement trouvé</h3>
        <p class="text-muted">Revenez plus tard.</p>
    </div>
<?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
