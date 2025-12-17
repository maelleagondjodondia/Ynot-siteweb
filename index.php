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
                <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600&h=400&fit=crop" 
                     alt="Étudiants BDE" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Boutons de redirection -->
<section class="text-center py-5 bg-light">
    <div class="container">
        <h3 class="mb-4">Découvrez nos activités</h3>
        <a href="sports.php" class="btn-custom">
            <i class="fas fa-futbol"></i> Activités Sportives
        </a>
        <a href="evenements.php" class="btn-custom">
            <i class="fas fa-star"></i> Nos Événements
        </a>
    </div>
</section>

<!-- Partenaires -->
<section class="partners-section">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Nos Partenaires</h2>
        <div class="row justify-content-center">
            <div class="col-md-2 col-6 text-center animate__animated animate__fadeIn">
                <div class="partner-logo">
                    <i class="fas fa-building fa-3x text-primary"></i>
                    <p class="mt-2 mb-0 fw-bold">Partenaire 1</p>
                </div>
            </div>
            <div class="col-md-2 col-6 text-center animate__animated animate__fadeIn">
                <div class="partner-logo">
                    <i class="fas fa-store fa-3x text-success"></i>
                    <p class="mt-2 mb-0 fw-bold">Partenaire 2</p>
                </div>
            </div>
            <div class="col-md-2 col-6 text-center animate__animated animate__fadeIn">
                <div class="partner-logo">
                    <i class="fas fa-utensils fa-3x text-danger"></i>
                    <p class="mt-2 mb-0 fw-bold">Partenaire 3</p>
                </div>
            </div>
            <div class="col-md-2 col-6 text-center animate__animated animate__fadeIn">
                <div class="partner-logo">
                    <i class="fas fa-coffee fa-3x text-warning"></i>
                    <p class="mt-2 mb-0 fw-bold">Partenaire 4</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact -->
<section id="contact" class="py-5">
    <div class="container">
        <div class="col-md-8 mx-auto text-center">
            <h2 class="mb-4">Contactez-nous</h2>
            <p class="lead mb-4">Une question ? Une suggestion ? N'hésitez pas à nous contacter !</p>
            <p>
                <i class="fas fa-envelope me-2"></i> bde@ecole.fr<br>
                <i class="fas fa-phone me-2"></i> 01 23 45 67 89
            </p>
            <div class="mt-4">
                <a href="#" class="btn btn-outline-primary btn-lg me-2">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="#" class="btn btn-outline-info btn-lg me-2">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="btn btn-outline-dark btn-lg">
                    <i class="fab fa-twitter"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
