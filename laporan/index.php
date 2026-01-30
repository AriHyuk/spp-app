<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['id_petugas'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Detail</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
    .card-header-teal {
        background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
        color: white;
    }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark mb-4" style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header card-header-teal py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-list-columns-reverse me-2"></i> Laporan Rincian Transaksi
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small">Silahkan pilih periode laporan:</p>

                        <form action="cetak.php" method="GET" target="_blank">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dari Tanggal</label>
                                <input type="date" name="tgl_awal" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Sampai Tanggal</label>
                                <input type="date" name="tgl_akhir" class="form-control" required
                                    value="<?= date('Y-m-d'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary w-100"
                                style="background-color: #0891b2; border: none;">
                                <i class="bi bi-printer-fill me-2"></i> Cetak Laporan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>