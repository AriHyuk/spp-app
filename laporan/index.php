<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['id_petugas'])) {
    header("Location: ../login.php");
    exit();
}

$page_title = 'Cetak Laporan Detail';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<main class="main-content">
    <div class="d-md-none mb-4">
        <button class="btn btn-primary" id="sidebarToggle">
            <i class="bi bi-list"></i> Menu
        </button>
    </div>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark"><i class="bi bi-printer me-2"></i> Cetak Laporan Transaksi</h3>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="bi bi-calendar-range me-2"></i> Filter Periode Laporan
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">Silahkan pilih rentang tanggal transaksi untuk mencetak laporan rekapitulasi pembayaran SPP.</p>

                        <form action="cetak.php" method="GET" target="_blank">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Dari Tanggal</label>
                                <input type="date" name="tgl_awal" class="form-control form-control-lg bg-light" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Sampai Tanggal</label>
                                <input type="date" name="tgl_akhir" class="form-control form-control-lg bg-light" required
                                    value="<?= date('Y-m-d'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">
                                <i class="bi bi-printer-fill me-2"></i> Cetak Laporan Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-7 d-none d-md-block">
                <div class="card border-0 shadow-sm rounded-3 h-100 bg-light d-flex align-items-center justify-content-center text-center p-4">
                    <div>
                        <i class="bi bi-file-earmark-pdf text-black-50" style="font-size: 5rem;"></i>
                        <h4 class="mt-3 text-dark fw-bold">Modul Laporan</h4>
                        <p class="text-muted mb-0">Fitur ini akan menghasilkan file laporan cetak (kertas) berdasarkan transaksi yang berhasil dibayarkan dalam rentang waktu yang dipilih.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>