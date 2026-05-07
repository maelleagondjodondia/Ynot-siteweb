<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Recherche & tri
$search = $_GET['search'] ?? '';
$sort   = $_GET['sort'] ?? 'activity_date';
$today  = date('Y-m-d');

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

function afficherSectionSports(array $sports, string $titre, string $description, string $emptyTitle, string $emptyText): void
{
    ?>
    <div class="mb-5">
        <h2 class="fw-bold mb-2 reveal"><?= htmlspecialchars($titre) ?></h2>
        <p class="text-muted mb-4 reveal delay-1"><?= htmlspecialchars($description) ?></p>

        <?php if (!empty($sports)): ?>
            <div class="row">
                <?php foreach ($sports as $i => $sport): ?>
                    <div class="col-md-4 mb-4 reveal delay-<?= ($i % 4) + 1 ?>">
                        <div class="sport-card" data-tilt>
                            <div class="card-media">
                                <span class="card-tag tag-sport">
                                    <i class="fas fa-medal"></i> Sport
                                </span>
                                <?php if (!empty($sport['image'])): ?>
                                    <img src="<?= htmlspecialchars($sport['image']) ?>"
                                         alt="<?= htmlspecialchars($sport['name']) ?>"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="card-placeholder"><i class="fas fa-dumbbell"></i></div>
                                <?php endif; ?>
                            </div>

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
                                        <img src="<?= htmlspecialchars($sport['image']) ?>"
                                             class="img-fluid rounded mb-3"
                                             alt="<?= htmlspecialchars($sport['name']) ?>"
                                             loading="lazy">
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
            <div class="text-center reveal">
                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                <h3><?= htmlspecialchars($emptyTitle) ?></h3>
                <p class="text-muted"><?= htmlspecialchars($emptyText) ?></p>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

function pillUrl(string $sortValue, string $search): string
{
    $params = ['sort' => $sortValue];
    if ($search !== '') $params['search'] = $search;
    return '?' . http_build_query($params);
}
?>

<!-- Page Header -->
<section class="page-header">
    <div class="hero-blobs">
        <span class="blob blob-1"></span>
        <span class="blob blob-2"></span>
    </div>
    <i class="fas fa-futbol floating-shape s-1"></i>
    <i class="fas fa-running floating-shape s-3"></i>
    <div class="container">
        <h1 class="display-4 fw-bold">
            <i class="fas fa-dumbbell"></i> Nos Sports
        </h1>
        <p class="lead">Découvrez tous les événements sportifs organisés par le BDE</p>
    </div>
</section>

<!-- Recherche & Tri (pills) -->
<div class="container mb-5">
    <form method="GET" action="sports.php" class="search-section reveal">
        <div class="row g-3 align-items-center">
            <div class="col-md-7">
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
            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Rechercher
                </button>
            </div>
            <div class="col-12">
                <div class="filter-pills">
                    <span class="me-2 align-self-center text-muted small fw-bold">Trier :</span>
                    <a class="filter-pill <?= $sort === 'activity_date' ? 'active' : '' ?>"
                       href="<?= pillUrl('activity_date', $search) ?>">
                        <i class="fas fa-calendar"></i> Date
                    </a>
                    <a class="filter-pill <?= $sort === 'name' ? 'active' : '' ?>"
                       href="<?= pillUrl('name', $search) ?>">
                        <i class="fas fa-font"></i> Nom
                    </a>
                    <a class="filter-pill <?= $sort === 'price' ? 'active' : '' ?>"
                       href="<?= pillUrl('price', $search) ?>">
                        <i class="fas fa-euro-sign"></i> Prix
                    </a>
                </div>
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
