<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section animate__animated animate__fadeIn">
    <div class="container">
        <h1 class="display-3 fw-bold mb-4">Bienvenue au BDE</h1>
        <p class="lead mb-4">Ensemble, créons les meilleurs moments de votre vie étudiante !</p>
        <a href="evenements.php" class="btn btn-light btn-lg me-2">
            <i class="fas fa-calendar"></i> Nos Événements
        </a>
        <a href="sports.php" class="btn btn-outline-light btn-lg">
            <i class="fas fa-running"></i> Nos Sports
        </a>
    </div>
</section>

<!-- Qui sommes-nous -->
<section class="who-we-are">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 animate__animated animate__fadeInLeft">
                <h2 class="display-5 fw-bold mb-4">Qui sommes-nous ?</h2>
                <p class="lead">
                    Le Bureau Des Étudiants (BDE) est une association dynamique qui s'engage à enrichir 
                    votre expérience universitaire à travers une multitude d'activités, d'événements et 
                    de projets passionnants.
                </p>
                <p>
                    Notre mission est de créer une communauté étudiante soudée, de promouvoir la vie 
                    associative et de vous offrir des moments inoubliables tout au long de l'année. 
                    Que ce soit à travers nos soirées, nos tournois sportifs ou nos projets solidaires, 
                    le BDE est là pour vous !
                </p>
                <a href="membres.php" class="btn-custom mt-3">
                    <i class="fas fa-users"></i> Découvrir l'équipe
                </a>
            </div>
            <div class="col-md-6 animate__animated animate__fadeInRight">
                <img src="bde.png" 
                     alt="Étudiants BDE" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Partenaires (dynamique depuis la BDD) -->
<?php $partners = $db->query("SELECT * FROM partners ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC); ?>
<?php if (!empty($partners)): ?>
<section class="partners-section">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Nos Partenaires</h2>
        <div class="row justify-content-center">
            <?php foreach ($partners as $p): ?>
            <div class="col-md-2 col-6 text-center animate__animated animate__fadeIn">
                <div class="partner-logo">
                    <?php if ($p['logo']): ?>
                        <img src="<?= htmlspecialchars($p['logo']) ?>" alt="<?= htmlspecialchars($p['name']) ?>"
                             style="width: 60px; height: 60px; object-fit: contain;">
                    <?php else: ?>
                        <i class="fas fa-handshake fa-3x text-primary"></i>
                    <?php endif; ?>
                    <p class="mt-2 mb-0 fw-bold"><?= htmlspecialchars($p['name']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Contact -->
<section id="contact" class="contact-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2 class="text-center fw-bold mb-2">Contactez-nous</h2>
                <p class="text-center text-muted mb-5">Une question ? Une suggestion ? On est là pour vous !</p>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="contact-card text-center">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h6 class="fw-bold">Email</h6>
                            <a href="mailto:bde@ecole.fr" class="text-muted">bde@ecole.fr</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="contact-card text-center">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h6 class="fw-bold">Téléphone</h6>
                            <a href="tel:0123456789" class="text-muted">01 23 45 67 89</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="contact-card text-center">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h6 class="fw-bold">Adresse</h6>
                            <span class="text-muted">Campus universitaire</span>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <p class="fw-bold mb-3">Suivez-nous sur les réseaux</p>
                    <div class="social-links">
                        <a href="https://facebook.com" target="_blank" class="social-btn social-facebook" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://instagram.com" target="_blank" class="social-btn social-instagram" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://twitter.com" target="_blank" class="social-btn social-twitter" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
