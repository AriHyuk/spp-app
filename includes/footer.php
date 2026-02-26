    <?php $base_url = 'http://localhost/spp-app'; ?>
    <script src="<?= $base_url ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
    // Script sederhana untuk toggle sidebar di mobile
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    }
    </script>
</body>
</html>
