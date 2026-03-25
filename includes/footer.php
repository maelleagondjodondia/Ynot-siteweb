<footer>
    <div class="container">
        <p class="mb-0">&copy; <?= date('Y') ?> BDE Ynot — Tous droits réservés</p>
    </div>
</footer>

<!-- Scroll to top -->
<button class="scroll-top" id="scrollTop" aria-label="Retour en haut">
    <i class="fas fa-chevron-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
window.addEventListener('scroll', function() {
    var nav = document.querySelector('.navbar');
    var btn = document.getElementById('scrollTop');
    var y = window.scrollY;
    if (nav) nav.classList.toggle('scrolled', y > 30);
    if (btn) btn.classList.toggle('visible', y > 400);
});
document.getElementById('scrollTop')?.addEventListener('click', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>
</body>
</html>
