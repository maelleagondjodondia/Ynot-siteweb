</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Sidebar toggle for mobile
const toggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('adminSidebar');
const overlay = document.getElementById('sidebarOverlay');
const closeBtn = document.getElementById('sidebarClose');

function openSidebar() {
    sidebar.classList.add('open');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
}

toggle.addEventListener('click', openSidebar);
overlay.addEventListener('click', closeSidebar);
closeBtn.addEventListener('click', closeSidebar);

// Close sidebar when clicking a nav link on mobile
sidebar.querySelectorAll('.nav-link').forEach(function(link) {
    link.addEventListener('click', function() {
        if (window.innerWidth < 992) closeSidebar();
    });
});
</script>
</body>
</html>
