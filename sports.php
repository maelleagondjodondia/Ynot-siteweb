<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Recherche & tri
$search = $_GET['search'] ?? '';
$sort   = $_GET['sort'] ?? 'activity_date';
$today  = date('Y-m-d');

// Tri
switch ($sort) {
    case 'name':
        $orderUpcoming = " ORDER BY name ASC";
        $orderPast     = " ORDER BY name ASC";
        break;

    case 'price':
        $orderUpcoming = " ORDER BY price ASC";
        $orderPast     = " ORDER BY price ASC";
        break;

    default:
        $orderUpcoming = " ORDER BY activity_date ASC";
        $orderPast     = " ORDER BY activity_date DESC";
        break;
}

// Sports à venir
$queryUpcoming = "SELECT * FROM activities
                  WHERE pole = 'sport'
                  AND activity_date >= :today
                  AND (name LIKE :search OR description LIKE :search)"
                  . $orderUpcoming;

$stmt = $db->prepare($queryUpcoming);
$stmt->execute([
    'today'  => $today,
    'search' => "%$search%"
]);
$sportsAVenir = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sports passés
$queryPast = "SELECT * FROM activities
              WHERE pole = 'sport'
              AND activity_date < :today
              AND (name LIKE :search OR description LIKE :search)"
              . $orderPast;

$stmt = $db->prepare($queryPast);
$stmt->execute([
    'today'  => $today,
    'search' => "%$search%"
]);
$sportsPasses = $stmt->fetchAll(PDO::FETCH_ASSOC);

/**
 * Affiche une section de sports
 */
function afficherSectionSports(array $sports, string $titre, string $description, string $emptyTitle, string $emptyText): void
{
    ?>
    <div class="mb-5">
        <h2 class="fw-bold mb-2"><?= htmlspecialchars($titre) ?></h2>
        <p class="text-muted mb-4"><?= htmlspecialchars($description) ?></p>

        <?php if (!empty($sports)): ?>
            <div class="row">
                <?php foreach ($sports as $sport): ?>
                    <div class="col-md-4 mb-4 animate__animated animate__fadeIn">
                        <div class="sport-card">
                            <?php if (!empty($sport['image'])): ?>
                                <img src="<?= htmlspecialchars($sport['image']) ?>" alt="<?= htmlspecialchars($sport['name']) ?>">
                            <?php else: ?>
                                <div class="card-placeholder"><i class="fas fa-dumbbell"></i></div>
                            <?php endif; ?>

                            <div class="sport-card-body">
                                <p class="sport-date mb-2">
                                    <i class="fas fa-calendar"></i>
                                    <?= date('d/m/Y', strtotime($sport['activity_date'])) ?>
                                </p>

                                <h5 class="fw-bold mb-3">
                                    <?= htmlspecialchars($sport['name']) ?>
                                </h5>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="sport-price">
                                        <?= $sport['price'] > 0 ? number_format($sport['price'], 2) . ' €' : 'Gratuit' ?>
                                    </span>

                                    <button class="btn btn-see-more"
                                            data-bs-toggle="modal"
                                            data-bs-target="#sportModal<?= (int)$sport['id'] ?>">
                                        Voir plus <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="sportModal<?= (int)$sport['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">
                                        <?= htmlspecialchars($sport['name']) ?>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>

                                <div class="modal-body">
                                    <?php if (!empty($sport['image'])): ?>
                                        <img src="<?= htmlspecialchars($sport['image']) ?>" class="img-fluid rounded mb-3" alt="<?= htmlspecialchars($sport['name']) ?>">
                                    <?php endif; ?>

                                    <p><strong>Date :</strong>
                                        <?= date('d/m/Y', strtotime($sport['activity_date'])) ?>
                                    </p>

                                    <p><strong>Prix :</strong>
                                        <?= $sport['price'] > 0 ? number_format($sport['price'], 2) . ' €' : 'Gratuit' ?>
                                    </p>

                                    <hr>

                                    <h6 class="fw-bold">Description</h6>
                                    <p><?= nl2br(htmlspecialchars($sport['description'])) ?></p>
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
            <div class="text-center animate__animated animate__fadeIn">
                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                <h3><?= htmlspecialchars($emptyTitle) ?></h3>
                <p class="text-muted"><?= htmlspecialchars($emptyText) ?></p>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
?>

<!-- Page Header -->
<section class="page-header animate__animated animate__fadeIn">
    <div class="container">
        <h1 class="display-4 fw-bold">
            <i class="fas fa-dumbbell"></i> Nos Sports
        </h1>
        <p class="lead">Découvrez tous les événements sportifs organisés par le BDE</p>
    </div>
</section>

<!-- Recherche & Tri -->
<div class="container mb-5">
    <form method="GET" action="sports.php" class="search-section animate__animated animate__fadeInUp">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text"
                           class="form-control"
                           name="search"
                           placeholder="Rechercher un sport..."
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

<!-- Liste des sports -->
<div class="container pb-5">
    <?php
    afficherSectionSports(
        $sportsAVenir,
        'Les sports à venir',
        'Retrouvez ici tous les événements sportifs qui auront lieu prochainement.',
        'Aucun sport à venir',
        'Revenez plus tard.'
    );

    afficherSectionSports(
        $sportsPasses,
        'Les sports passés',
        'Retrouvez ici les événements sportifs qui ont déjà eu lieu.',
        'Aucun sport passé',
        'Aucun ancien événement sportif à afficher.'
    );
    ?>
</div>

<?php require_once 'includes/footer.php'; ?>