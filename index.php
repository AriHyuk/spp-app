<?php
session_start();
include './config/koneksi.php';

// Cek Login
if (!isset($_SESSION['id_petugas'])) {
    header("Location: login.php");
    exit();
}

// Ambil data petugas dari session (Pastikan saat login session ini dibuat)
// Jika tidak ada, set default
$nama_petugas = isset($_SESSION['nama_petugas']) ? $_SESSION['nama_petugas'] : "Petugas";
$level = isset($_SESSION['level']) ? $_SESSION['level'] : "Admin"; // Asumsi ada level admin/petugas

// Hitung statistik (Sama seperti sebelumnya)
$total_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_siswa LIMIT 1"))['total'];
$total_kelas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_kelas LIMIT 1"))['total'];
$total_spp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_spp LIMIT 1"))['total'];
$total_pembayaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_pembayaran LIMIT 1"))['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - SPP</title>
    <link href="./assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    :root {
        --color-teal: #0891b2;
        --color-teal-dark: #0e7490;
        --sidebar-width: 260px;
    }

    body {
        background-color: #f3f4f6;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* Sidebar Styling */
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background: linear-gradient(180deg, #0891b2 0%, #06b6d4 100%);
        color: white;
        z-index: 1000;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }

    .sidebar-brand {
        padding: 1.5rem;
        font-size: 1.5rem;
        font-weight: bold;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .user-panel {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(0, 0, 0, 0.1);
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.85);
        padding: 12px 24px;
        display: flex;
        align-items: center;
        transition: all 0.3s;
        border-left: 4px solid transparent;
    }

    .nav-link:hover,
    .nav-link.active {
        color: white;
        background: rgba(255, 255, 255, 0.1);
        border-left-color: white;
    }

    .nav-link i {
        margin-right: 12px;
        font-size: 1.1rem;
    }

    /* Main Content Styling */
    .main-content {
        margin-left: var(--sidebar-width);
        padding: 2rem;
        width: calc(100% - var(--sidebar-width));
    }

    .stat-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s;
        background: white;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .icon-box {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        .sidebar.active {
            margin-left: 0;
        }

        .main-content {
            margin-left: 0;
            width: 100%;
        }
    }
    </style>
</head>

<body>

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
                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($nama_petugas); ?></h6>
                    <small class="text-white-50"><?= htmlspecialchars($level); ?></small>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-grow-1 py-3 overflow-auto">
            <small class="text-uppercase px-4 mb-2 text-white-50" style="font-size: 0.75rem; letter-spacing: 1px;">Menu
                Utama</small>

            <a href="index.php" class="nav-link active">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <a href="pembayaran/index.php" class="nav-link">
                <i class="bi bi-cash-coin"></i> Entri Transaksi
            </a>

            <a href="petugas/index.php" class="nav-link">
                <i class="bi bi-people"></i> Data Petugas
            </a>


            <small class="text-uppercase px-4 mb-2 mt-4 text-white-50"
                style="font-size: 0.75rem; letter-spacing: 1px;">Master Data</small>

            <a href="siswa/index.php" class="nav-link">
                <i class="bi bi-people"></i> Data Siswa
            </a>
            <a href="kelas/index.php" class="nav-link">
                <i class="bi bi-collection"></i> Data Kelas
            </a>
            <a href="spp/index.php" class="nav-link">
                <i class="bi bi-credit-card"></i> Data SPP
            </a>

            <div class="mt-auto"></div>

            <a href="logout.php" class="nav-link text-danger mt-3" onclick="return confirm('Yakin ingin keluar?');"
                style="background: rgba(0,0,0,0.2);">
                <i class="bi bi-box-arrow-left"></i> Logout
            </a>
        </div>
    </nav>

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
                        <p class="text-muted mb-0">Selamat datang kembali, <strong><?= $nama_petugas; ?></strong>.
                            Berikut adalah ringkasan data sistem pembayaran SPP.</p>
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
                    <a href="pembayaran/index.php"
                        class="btn btn-primary w-100 py-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-plus-circle fs-5"></i> Entri Transaksi Baru
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="laporan/index.php"
                        class="btn btn-outline-secondary w-100 py-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-printer fs-5"></i> Cetak Laporan
                    </a>
                </div>
            </div>

        </div>
    </main>

    <script src="./assets/bootstrap/js/bootstrap.bundle.min.js"></script>
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