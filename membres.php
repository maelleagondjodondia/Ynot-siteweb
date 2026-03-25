<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Récupération de tous les membres groupés par pôle
try {
    $stmt = $db->query("SELECT * FROM members ORDER BY name ASC");
    $tous_membres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Grouper par pôle
    $membres_par_pole = [];
    foreach ($tous_membres as $membre) {
        $pole = $membre['pole'];
        if (!isset($membres_par_pole[$pole])) {
            $membres_par_pole[$pole] = [];
        }
        $membres_par_pole[$pole][] = $membre;
    }
    
    // Ordre des pôles
    $ordre_poles = ['Sport', 'Evenementiel', 'Communication', 'Partenaria'];
    
} catch(PDOException $e) {
    $membres_par_pole = [];
    $tous_membres = [];
}
?>

<!-- Page Header -->
<section class="page-header animate__animated animate__fadeIn">
    <div class="container">
        <h1 class="display-4 fw-bold">
            Bureau Des Étudiants
        </h1>
        <p class="lead">Découvrez l'équipe qui fait vivre le BDE</p>
    </div>
</section>

<!-- Carousel de tous les membres -->
<?php if (count($tous_membres) > 0): ?>
<section class="carousel-section">
    <div class="container">
        <h3 class="text-center mb-4 fw-bold">Notre Équipe Complète</h3>
        <div id="membersCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php 
                $chunks = array_chunk($tous_membres, 5); // 5 membres par slide
                foreach ($chunks as $index => $chunk): 
                ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="row justify-content-center">
                            <?php foreach ($chunk as $membre): ?>
                                <div class="col-md-2 col-6 text-center mb-3">
                                    <img src="<?php echo $membre['image'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($membre['name']) . '&size=150&background=2563eb&color=fff'; ?>" 
                                         class="img-fluid" 
                                         alt="<?php echo htmlspecialchars($membre['name']); ?>">
                                    <p class="mt-2 mb-0 fw-bold"><?php echo htmlspecialchars($membre['name']); ?></p>
                                    <small class="text-muted"><?php echo htmlspecialchars($membre['role']); ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($chunks) > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#membersCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#membersCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Sections par pôle -->
<div class="container pb-5">
    <?php if (count($membres_par_pole) > 0): ?>
        <?php 
        // Trier les pôles selon l'ordre défini
        uksort($membres_par_pole, function($a, $b) use ($ordre_poles) {
            $pos_a = array_search($a, $ordre_poles);
            $pos_b = array_search($b, $ordre_poles);
            if ($pos_a === false) $pos_a = 999;
            if ($pos_b === false) $pos_b = 999;
            return $pos_a - $pos_b;
        });
        
        foreach ($membres_par_pole as $pole => $membres): 
        ?>
            <div class="pole-section animate__animated animate__fadeIn">
                <h2 class="pole-title fw-bold">
                    <?php 
                    $icon = 'fa-users';
                    if ($pole === 'Sport') $icon = 'fa-running';
                    if ($pole === 'Evenementiel') $icon = 'fa-calendar-alt';
                    if ($pole === 'Communication') $icon = 'fa-bullhorn';
                    if ($pole === 'Partenaria') $icon = 'fa-handshake';
                    ?>
                    <i class="fas <?php echo $icon; ?>"></i> Pôle <?php echo htmlspecialchars($pole); ?>
                </h2>
                
                <div class="row">
                    <?php foreach ($membres as $membre): ?>
                        <div class="col-md-3 col-sm-6">
                            <div class="member-card">
                                <img src="<?php echo $membre['image'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($membre['name']) . '&size=120&background=2563eb&color=fff'; ?>" 
                                     class="member-photo" 
                                     alt="<?php echo htmlspecialchars($membre['name']); ?>">
                                <div class="member-name"><?php echo htmlspecialchars($membre['name']); ?></div>
                                <div class="member-role"><?php echo htmlspecialchars($membre['role']); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-members animate__animated animate__fadeIn">
            <i class="fas fa-users-slash"></i>
            <h3>Aucun membre enregistré</h3>
            <p class="text-muted">Les membres du BDE seront bientôt présentés ici.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>