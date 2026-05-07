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

// Évènements à venir
$queryUpcoming = "SELECT * FROM activities
                  WHERE pole = 'event'
                  AND activity_date >= :today
                  AND (name LIKE :search OR description LIKE :search)"
                  . $orderUpcoming;

$stmt = $db->prepare($queryUpcoming);
$stmt->execute([
    'today'  => $today,
    'search' => "%$search%"
]);
$evenementsAVenir = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Évènements passés
$queryPast = "SELECT * FROM activities
              WHERE pole = 'event'
              AND activity_date < :today
              AND (name LIKE :search OR description LIKE :search)"
              . $orderPast;

$stmt = $db->prepare($queryPast);
$stmt->execute([
    'today'  => $today,
    'search' => "%$search%"
]);
$evenementsPasses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prochain événement (pour compte à rebours)
$nextStmt = $db->prepare("SELECT name, activity_date FROM activities
                          WHERE pole = 'event' AND activity_date >= :today
                          ORDER BY activity_date ASC LIMIT 1");
$nextStmt->execute(['today' => $today]);
$nextEvent = $nextStmt->fetch(PDO::FETCH_ASSOC);

function afficherSectionEvenements(array $events, string $titre, string $description, string $emptyTitle, string $emptyText): void
{
    ?>
    <div class="mb-5">
        <h2 class="fw-bold mb-2 reveal"><?= htmlspecialchars($titre) ?></h2>
        <p class="text-muted mb-4 reveal delay-1"><?= htmlspecialchars($description) ?></p>

        <?php if (!empty($events)): ?>
            <div class="row">
                <?php foreach ($events as $i => $event): ?>
                    <div class="col-md-4 mb-4 reveal delay-<?= ($i % 4) + 1 ?>">
                        <div class="event-card" data-tilt>
                            <div class="card-media">
                                <span class="card-tag tag-event">
                                    <i class="fas fa-calendar-star"></i> Évènement
                                </span>
                                <?php if (!empty($event['image'])): ?>
                                    <img src="<?= htmlspecialchars($event['image']) ?>"
                                         alt="<?= htmlspecialchars($event['name']) ?>"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="card-placeholder"><i class="fas fa-calendar-alt"></i></div>
                                <?php endif; ?>
                            </div>

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
                                            data-bs-target="#eventModal<?= (int)$event['id'] ?>">
                                        Voir plus <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="eventModal<?= (int)$event['id'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">
                                        <?= htmlspecialchars($event['name']) ?>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>

                                <div class="modal-body">
                                    <?php if (!empty($event['image'])): ?>
                                        <img src="<?= htmlspecialchars($event['image']) ?>"
                                             class="img-fluid rounded mb-3"
                                             alt="<?= htmlspecialchars($event['name']) ?>"
                                             loading="lazy">
                                    <?php endif; ?>

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
            <div class="no-events text-center reveal">
                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                <h3><?= htmlspecialchars($emptyTitle) ?></h3>
                <p class="text-muted"><?= htmlspecialchars($emptyText) ?></p>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

// Pour conserver la recherche dans les liens de pills
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
    <i class="fas fa-calendar floating-shape s-1"></i>
    <i class="fas fa-music floating-shape s-3"></i>
    <div class="container">
        <h1 class="display-4 fw-bold">
            <i class="fas fa-calendar-alt"></i> Nos Événements
        </h1>
        <p class="lead">Découvrez tous les événements organisés par le BDE</p>
    </div>
</section>

<!-- Compte à rebours prochain événement -->
<?php if ($nextEvent): ?>
<div class="container mt-4">
    <div id="countdown" class="countdown-banner reveal" data-target="<?= htmlspecialchars($nextEvent['activity_date']) ?>T18:00:00">
        <span class="label">Prochain événement</span>
        <h3><?= htmlspecialchars($nextEvent['name']) ?></h3>
        <small class="d-block opacity-75">
            <i class="fas fa-calendar"></i>
            <?= date('d F Y', strtotime($nextEvent['activity_date'])) ?>
        </small>
        <div class="countdown-timer">
            <div class="countdown-unit"><span class="num" data-d>--</span><span class="lbl">jours</span></div>
            <div class="countdown-unit"><span class="num" data-h>--</span><span class="lbl">heures</span></div>
            <div class="countdown-unit"><span class="num" data-m>--</span><span class="lbl">min</span></div>
            <div class="countdown-unit"><span class="num" data-s>--</span><span class="lbl">sec</span></div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Recherche & Tri (pills) -->
<div class="container mb-5">
    <form method="GET" action="evenements.php" class="search-section reveal" id="filterForm">
        <div class="row g-3 align-items-center">
            <div class="col-md-7">
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

<!-- Liste des événements -->
<div class="container pb-5">
    <?php
    afficherSectionEvenements(
        $evenementsAVenir,
        'Les évènements à venir',
        'Retrouvez ici tous les évènements qui auront lieu prochainement.',
        'Aucun évènement à venir',
        'Revenez plus tard.'
    );

    afficherSectionEvenements(
        $evenementsPasses,
        'Les évènements passés',
        'Retrouvez ici les évènements qui ont déjà eu lieu.',
        'Aucun évènement passé',
        'Aucun ancien évènement à afficher.'
    );
    ?>
</div>

<?php require_once 'includes/footer.php'; ?>
