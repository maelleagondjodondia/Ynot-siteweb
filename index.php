<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Stats dynamiques
$stat_members  = (int) $db->query("SELECT COUNT(*) FROM members")->fetchColumn();
$stat_events   = (int) $db->query("SELECT COUNT(*) FROM activities WHERE pole = 'event'")->fetchColumn();
$stat_sports   = (int) $db->query("SELECT COUNT(*) FROM activities WHERE pole = 'sport'")->fetchColumn();
$stat_partners = (int) $db->query("SELECT COUNT(*) FROM partners")->fetchColumn();

// Galerie (8 dernières activités avec image)
$galleryStmt = $db->prepare("SELECT id, name, image, activity_date, pole
                             FROM activities
                             WHERE image IS NOT NULL AND image != ''
                             ORDER BY activity_date DESC
                             LIMIT 8");
$galleryStmt->execute();
$gallery = $galleryStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-blobs">
        <span class="blob blob-1"></span>
        <span class="blob blob-2"></span>
        <span class="blob blob-3"></span>
    </div>
    <i class="fas fa-graduation-cap floating-shape s-1"></i>
    <i class="fas fa-music floating-shape s-2"></i>
    <i class="fas fa-futbol floating-shape s-3"></i>
    <i class="fas fa-star floating-shape s-4"></i>

    <div class="container">
        <h1 class="display-3 fw-bold mb-4">Bienvenue au <span class="word-glow">BDE</span></h1>
        <p class="lead mb-4">Ensemble, créons les meilleurs moments de votre vie étudiante !</p>
        <a href="evenements.php" class="btn btn-light btn-lg me-2">
            <i class="fas fa-calendar"></i> Nos Événements
        </a>
        <a href="sports.php" class="btn btn-outline-light btn-lg">
            <i class="fas fa-running"></i> Nos Sports
        </a>
    </div>
</section>

<!-- Stats animées -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6 reveal">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-number" data-counter="<?= $stat_members ?>"><?= $stat_members ?></div>
                    <div class="stat-label">Membres</div>
                </div>
            </div>
            <div class="col-md-3 col-6 reveal delay-1">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="stat-number" data-counter="<?= $stat_events ?>"><?= $stat_events ?></div>
                    <div class="stat-label">Événements</div>
                </div>
            </div>
            <div class="col-md-3 col-6 reveal delay-2">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-running"></i></div>
                    <div class="stat-number" data-counter="<?= $stat_sports ?>"><?= $stat_sports ?></div>
                    <div class="stat-label">Sports</div>
                </div>
            </div>
            <div class="col-md-3 col-6 reveal delay-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-handshake"></i></div>
                    <div class="stat-number" data-counter="<?= $stat_partners ?>"><?= $stat_partners ?></div>
                    <div class="stat-label">Partenaires</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Showcase TikTok / Phone mockup -->
<section class="tiktok-showcase">
    <div class="tt-glow tt-glow-pink"></div>
    <div class="tt-glow tt-glow-cyan"></div>
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 reveal">
                <span class="tt-eyebrow">
                    <i class="fab fa-tiktok"></i> Sur TikTok
                </span>
                <h2 class="tt-title">L'ambiance BDE,<br><span class="tt-gradient-text">en mode IRL.</span></h2>
                <p class="tt-desc">
                    Soirées, tournois, voyages, afterworks… On capture les meilleurs moments
                    et on les balance sur TikTok. Suis-nous pour rien rater de la vie étudiante Ynov Toulouse.
                </p>
                <div class="tt-cta">
                    <a href="https://www.tiktok.com/@bde.ynov.toulouse" target="_blank" rel="noopener" class="btn-tiktok">
                        <i class="fab fa-tiktok"></i> Suivre sur TikTok
                    </a>
                    <a href="https://www.instagram.com/bde.ynovtoulouse?igsh=c28zbTRuYTZlbDVt" target="_blank" rel="noopener" class="btn-insta">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                </div>
                <div class="tt-handle">
                    <i class="fas fa-at"></i> bde.ynov.toulouse
                </div>
            </div>
            <div class="col-lg-6 reveal delay-2">
                <div class="phone-wrapper">
                    <div class="phone-mockup">
                        <span class="phone-notch"></span>
                        <span class="phone-side phone-side-volume"></span>
                        <span class="phone-side phone-side-power"></span>
                        <div class="phone-screen">
                            <video class="phone-video"
                                   id="ttVideo"
                                   autoplay
                                   muted
                                   loop
                                   playsinline
                                   preload="metadata"
                                   poster="videos/bde-tiktok-poster.jpg">
                                <source src="videos/bde-tiktok.webm" type="video/webm">
                                <source src="videos/bde-tiktok.mp4" type="video/mp4">
                            </video>
                            <div class="phone-overlay">
                                <div class="phone-overlay-top">
                                    <span class="dot"></span> EN DIRECT
                                </div>
                                <div class="phone-overlay-bottom">
                                    <strong>@bde.ynov.toulouse</strong>
                                    <small>L'ambiance Ynov Toulouse 🎓</small>
                                </div>
                                <button type="button" class="phone-mute" id="ttMute" aria-label="Activer le son">
                                    <i class="fas fa-volume-mute"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Qui sommes-nous -->
<section class="who-we-are">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 reveal">
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
            <div class="col-md-6 reveal delay-2">
                <img src="bde.png" alt="Étudiants BDE" class="img-fluid" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- Galerie photos -->
<?php if (!empty($gallery)): ?>
<section class="gallery-section">
    <div class="container">
        <h2 class="reveal">Nos meilleurs souvenirs</h2>
        <p class="subtitle reveal delay-1">Un aperçu de l'ambiance BDE — soirées, tournois, voyages.</p>
        <div class="gallery-grid">
            <?php foreach ($gallery as $i => $g): ?>
                <a href="<?= $g['pole'] === 'sport' ? 'sports.php' : 'evenements.php' ?>"
                   class="gallery-item reveal delay-<?= ($i % 4) + 1 ?>">
                    <img src="<?= htmlspecialchars($g['image']) ?>"
                         alt="<?= htmlspecialchars($g['name']) ?>"
                         loading="lazy">
                    <div class="overlay">
                        <strong><?= htmlspecialchars($g['name']) ?></strong>
                        <small><?= date('d/m/Y', strtotime($g['activity_date'])) ?></small>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Partenaires -->
<?php $partners = $db->query("SELECT * FROM partners ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC); ?>
<?php if (!empty($partners)): ?>
<section class="partners-section">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold reveal">Nos Partenaires</h2>
        <div class="row justify-content-center">
            <?php foreach ($partners as $i => $p): ?>
            <div class="col-md-2 col-6 text-center reveal delay-<?= ($i % 4) + 1 ?>">
                <div class="partner-logo">
                    <?php if ($p['logo']): ?>
                        <img src="<?= htmlspecialchars($p['logo']) ?>"
                             alt="<?= htmlspecialchars($p['name']) ?>"
                             loading="lazy"
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
                <h2 class="text-center fw-bold mb-2 reveal">Contactez-nous</h2>
                <p class="text-center text-muted mb-5 reveal delay-1">Une question ? Une suggestion ? On est là pour vous !</p>

                <div class="row g-4">
                    <div class="col-md-4 reveal">
                        <div class="contact-card text-center">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h6 class="fw-bold">Email</h6>
                            <a href="mailto:bde@ecole.fr" class="text-muted">bde@ecole.fr</a>
                        </div>
                    </div>
                    <div class="col-md-4 reveal delay-2">
                        <div class="contact-card text-center">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h6 class="fw-bold">Téléphone</h6>
                            <a href="tel:0123456789" class="text-muted">01 23 45 67 89</a>
                        </div>
                    </div>
                    <div class="col-md-4 reveal delay-3">
                        <div class="contact-card text-center">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h6 class="fw-bold">Adresse</h6>
                            <span class="text-muted">Campus universitaire</span>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5 reveal">
                    <p class="fw-bold mb-3">Suivez-nous sur les réseaux</p>
                    <div class="social-links">
                        <a href="https://www.instagram.com/bde.ynovtoulouse?igsh=c28zbTRuYTZlbDVt" target="_blank" rel="noopener" class="social-btn social-instagram" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.tiktok.com/@bde.ynov.toulouse" target="_blank" rel="noopener" class="social-btn social-tiktok" aria-label="TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
