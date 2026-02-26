<?php
session_start();
include './config/koneksi.php';

// Cek Login
if (!isset($_SESSION['id_petugas'])) {
    header("Location: login.php");
    exit();
}

$nama_petugas = isset($_SESSION['nama_petugas']) ? $_SESSION['nama_petugas'] : "Petugas";

// Hitung statistik
$total_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_siswa LIMIT 1"))['total'];
$total_kelas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_kelas LIMIT 1"))['total'];
$total_spp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_spp LIMIT 1"))['total'];
$total_pembayaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_pembayaran LIMIT 1"))['total'];

// Set page title for header
$page_title = 'Dashboard';
include './includes/header.php';
?>

<?php include './includes/sidebar.php'; ?>

<main class="main-content">
    <div class="d-md-none mb-4">
        <button class="btn btn-primary" id="sidebarToggle">
            <i class="bi bi-list"></i> Menu
        </button>
    </div>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="p-4 bg-white rounded-3 shadow-sm border-start border-4 border-info">
                    <h2 class="fw-bold text-dark">Dashboard</h2>
                    <p class="text-muted mb-0">Selamat datang kembali, <strong><?= htmlspecialchars($nama_petugas); ?></strong>. Berikut adalah ringkasan data sistem pembayaran SPP.</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="stat-card p-3 h-100 position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Total Siswa</p>
                            <h3 class="fw-bold mb-0 text-dark"><?= $total_siswa; ?></h3>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <a href="siswa/index.php" class="stretched-link"></a>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="stat-card p-3 h-100 position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Total Kelas</p>
                            <h3 class="fw-bold mb-0 text-info"><?= $total_kelas; ?></h3>
                        </div>
                        <div class="icon-box bg-info bg-opacity-10 text-info">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                    <a href="kelas/index.php" class="stretched-link"></a>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="stat-card p-3 h-100 position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Data SPP</p>
                            <h3 class="fw-bold mb-0 text-success"><?= $total_spp; ?></h3>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success">
                            <i class="bi bi-tags-fill"></i>
                        </div>
                    </div>
                    <a href="spp/index.php" class="stretched-link"></a>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="stat-card p-3 h-100 position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase fw-bold">Transaksi</p>
                            <h3 class="fw-bold mb-0 text-warning"><?= $total_pembayaran; ?></h3>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-receipt-cutoff"></i>
                        </div>
                    </div>
                    <a href="pembayaran/index.php" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h5 class="fw-bold mb-3">Aksi Cepat</h5>
            </div>
            <div class="col-md-4">
                <a href="pembayaran/index.php" class="btn btn-primary w-100 py-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-plus-circle fs-5"></i> Entri Transaksi Baru
                </a>
            </div>
            <div class="col-md-4">
                <a href="laporan/index.php" class="btn btn-outline-secondary w-100 py-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-printer fs-5"></i> Cetak Laporan
                </a>
            </div>
        </div>

    </div>
</main>

<?php include './includes/footer.php'; ?>