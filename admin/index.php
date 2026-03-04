<?php require_once '../includes/admin_header.php'; ?>

<?php
$nb_events = $db->query("SELECT COUNT(*) FROM activities WHERE pole='event'")->fetchColumn();
$nb_sports = $db->query("SELECT COUNT(*) FROM activities WHERE pole='sport'")->fetchColumn();
$nb_members = $db->query("SELECT COUNT(*) FROM members")->fetchColumn();
?>

<h2 class="fw-bold mb-4"><i class="fas fa-tachometer-alt"></i> Tableau de bord</h2>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <a href="evenements.php" class="stat-card fade-in" style="animation-delay: 0.1s">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: linear-gradient(135deg, #7c3aed, #a78bfa);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <div class="stat-number"><?= $nb_events ?></div>
                    <div class="stat-label">Événements</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="sports.php" class="stat-card fade-in" style="animation-delay: 0.2s">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: linear-gradient(135deg, #2563eb, #60a5fa);">
                    <i class="fas fa-dumbbell"></i>
                </div>
                <div>
                    <div class="stat-number"><?= $nb_sports ?></div>
                    <div class="stat-label">Sports</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="membres.php" class="stat-card fade-in" style="animation-delay: 0.3s">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: linear-gradient(135deg, #059669, #34d399);">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="stat-number"><?= $nb_members ?></div>
                    <div class="stat-label">Membres</div>
                </div>
            </div>
        </a>
    </div>
</div>

<?php require_once '../includes/admin_footer.php'; ?>
