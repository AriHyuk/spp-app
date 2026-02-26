<?php
// Tentukan base url agar link konsisten meski di panggil dari subdirectory
$base_url = 'http://localhost/spp-app';

// Detect active page based on URI
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<nav class="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-wallet2 me-2"></i> App SPP
    </div>

    <div class="user-panel">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <i class="bi bi-person-circle fs-2"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($_SESSION['nama_petugas'] ?? 'Petugas'); ?></h6>
                <small class="text-white-50"><?= htmlspecialchars($_SESSION['level'] ?? 'Admin'); ?></small>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-grow-1 py-3 overflow-auto">
        <small class="text-uppercase px-4 mb-2 text-white-50" style="font-size: 0.75rem; letter-spacing: 1px;">Menu Utama</small>
        
        <a href="<?= $base_url ?>/index.php" class="nav-link sidebar-link <?= ($current_dir == 'spp-app' && $current_page == 'index.php') ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="<?= $base_url ?>/pembayaran/index.php" class="nav-link sidebar-link <?= ($current_dir == 'pembayaran') ? 'active' : '' ?>">
            <i class="bi bi-cash-coin"></i> Entri Transaksi
        </a>

        <a href="<?= $base_url ?>/petugas/index.php" class="nav-link sidebar-link <?= ($current_dir == 'petugas') ? 'active' : '' ?>">
            <i class="bi bi-people"></i> Data Petugas
        </a>

        <small class="text-uppercase px-4 mb-2 mt-4 text-white-50" style="font-size: 0.75rem; letter-spacing: 1px;">Master Data</small>

        <a href="<?= $base_url ?>/siswa/index.php" class="nav-link sidebar-link <?= ($current_dir == 'siswa') ? 'active' : '' ?>">
            <i class="bi bi-people"></i> Data Siswa
        </a>
        <a href="<?= $base_url ?>/kelas/index.php" class="nav-link sidebar-link <?= ($current_dir == 'kelas') ? 'active' : '' ?>">
            <i class="bi bi-collection"></i> Data Kelas
        </a>
        <a href="<?= $base_url ?>/spp/index.php" class="nav-link sidebar-link <?= ($current_dir == 'spp') ? 'active' : '' ?>">
            <i class="bi bi-credit-card"></i> Data SPP
        </a>
        <a href="<?= $base_url ?>/laporan/index.php" class="nav-link sidebar-link <?= ($current_dir == 'laporan') ? 'active' : '' ?>">
            <i class="bi bi-printer"></i> Cetak Laporan
        </a>

        <div class="mt-auto"></div>

        <a href="<?= $base_url ?>/logout.php" class="nav-link sidebar-link text-danger mt-3" onclick="return confirm('Yakin ingin keluar?');" style="background: rgba(0,0,0,0.2);">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>
</nav>
