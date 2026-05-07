<footer>
    <div class="container">
        <p class="mb-0">&copy; <?= date('Y') ?> BDE Ynot — Tous droits réservés</p>
    </div>
</footer>

<button class="scroll-top" id="scrollTop" aria-label="Retour en haut">
    <i class="fas fa-chevron-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    'use strict';

    // ── Navbar scrolled + scroll to top ──
    var nav = document.querySelector('.navbar');
    var btn = document.getElementById('scrollTop');
    window.addEventListener('scroll', function () {
        var y = window.scrollY;
        if (nav) nav.classList.toggle('scrolled', y > 30);
        if (btn) btn.classList.toggle('visible', y > 400);
    }, { passive: true });
    btn?.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // ── Dark mode toggle ──
    var toggle = document.getElementById('themeToggle');
    toggle?.addEventListener('click', function () {
        var current = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', current);
        try { localStorage.setItem('theme', current); } catch (e) {}
    });

    // ── Scroll-reveal (IntersectionObserver + fallback robuste) ──
    var revealEls = document.querySelectorAll('.reveal');
    function revealAll() {
        revealEls.forEach(function (el) { el.classList.add('in-view'); });
    }
    if ('IntersectionObserver' in window && revealEls.length) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('in-view');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.05, rootMargin: '0px 0px 0px 0px' });
        revealEls.forEach(function (el) { io.observe(el); });

        // Filet de sécurité : si une card est déjà visible mais l'observer
        // n'a pas encore tiré (layout shift, fonts), on force la révélation
        // de tout ce qui est dans le viewport au load
        function checkInitial() {
            var vh = window.innerHeight;
            revealEls.forEach(function (el) {
                if (el.classList.contains('in-view')) return;
                var r = el.getBoundingClientRect();
                if (r.top < vh && r.bottom > 0) {
                    el.classList.add('in-view');
                    io.unobserve(el);
                }
            });
        }
        window.addEventListener('load', checkInitial);
        setTimeout(checkInitial, 600);

        // Garde-fou ultime : après 2s, tout reste caché → on force
        setTimeout(revealAll, 2500);
    } else {
        revealAll();
    }

    // ── Mouse-follow glow on cards ──
    document.querySelectorAll('.event-card, .sport-card').forEach(function (card) {
        card.addEventListener('mousemove', function (e) {
            var r = card.getBoundingClientRect();
            card.style.setProperty('--x', (e.clientX - r.left) + 'px');
            card.style.setProperty('--y', (e.clientY - r.top) + 'px');
        });
    });

    // ── Tilt 3D léger (sans dépendance) ──
    var tiltEls = document.querySelectorAll('[data-tilt]');
    tiltEls.forEach(function (el) {
        var max = 8;
        el.style.transformStyle = 'preserve-3d';
        el.addEventListener('mousemove', function (e) {
            var r = el.getBoundingClientRect();
            var px = (e.clientX - r.left) / r.width;
            var py = (e.clientY - r.top) / r.height;
            var rx = (py - 0.5) * -max;
            var ry = (px - 0.5) * max;
            el.style.transform = 'perspective(900px) rotateX(' + rx.toFixed(2) + 'deg) rotateY(' + ry.toFixed(2) + 'deg) translateY(-6px)';
        });
        el.addEventListener('mouseleave', function () {
            el.style.transform = '';
        });
    });

    // ── Compteurs animés ──
    var counters = document.querySelectorAll('[data-counter]');
    if (counters.length) {
        var co = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting) return;
                var el = e.target;
                var target = parseInt(el.getAttribute('data-counter'), 10) || 0;
                var suffix = el.getAttribute('data-suffix') || '';
                var duration = 1400;
                var start = performance.now();
                function tick(now) {
                    var p = Math.min((now - start) / duration, 1);
                    var eased = 1 - Math.pow(1 - p, 3);
                    el.textContent = Math.round(target * eased) + suffix;
                    if (p < 1) requestAnimationFrame(tick);
                }
                requestAnimationFrame(tick);
                co.unobserve(el);
            });
        }, { threshold: 0.4 });
        counters.forEach(function (c) { co.observe(c); });
    }

    // ── TikTok video : toggle son ──
    var ttVideo = document.getElementById('ttVideo');
    var ttMute = document.getElementById('ttMute');
    if (ttVideo && ttMute) {
        ttMute.addEventListener('click', function () {
            ttVideo.muted = !ttVideo.muted;
            ttMute.classList.toggle('unmuted', !ttVideo.muted);
            ttMute.querySelector('i').className = ttVideo.muted ? 'fas fa-volume-mute' : 'fas fa-volume-up';
            ttMute.setAttribute('aria-label', ttVideo.muted ? 'Activer le son' : 'Couper le son');
            if (!ttVideo.muted) {
                ttVideo.play().catch(function () {});
            }
        });
        // Pause quand hors viewport (économie batterie/data)
        if ('IntersectionObserver' in window) {
            var vio = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) {
                        ttVideo.play().catch(function () {});
                    } else {
                        ttVideo.pause();
                    }
                });
            }, { threshold: 0.25 });
            vio.observe(ttVideo);
        }
    }

    // ── Compte à rebours ──
    var cd = document.getElementById('countdown');
    if (cd) {
        var target = new Date(cd.getAttribute('data-target')).getTime();
        var d = cd.querySelector('[data-d]');
        var h = cd.querySelector('[data-h]');
        var m = cd.querySelector('[data-m]');
        var s = cd.querySelector('[data-s]');
        function pad(n) { return n < 10 ? '0' + n : '' + n; }
        function update() {
            var diff = target - Date.now();
            if (diff <= 0) {
                if (d) d.textContent = '00';
                if (h) h.textContent = '00';
                if (m) m.textContent = '00';
                if (s) s.textContent = '00';
                return;
            }
            var dd = Math.floor(diff / 86400000);
            var hh = Math.floor((diff % 86400000) / 3600000);
            var mm = Math.floor((diff % 3600000) / 60000);
            var ss = Math.floor((diff % 60000) / 1000);
            if (d) d.textContent = pad(dd);
            if (h) h.textContent = pad(hh);
            if (m) m.textContent = pad(mm);
            if (s) s.textContent = pad(ss);
        }
        update();
        setInterval(update, 1000);
    }
})();
</script>
</body>
</html>
