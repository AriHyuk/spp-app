<?php
include '../config/koneksi.php';

$error = '';
$success = '';

// Cek parameter URL untuk pesan
if (isset($_GET['success'])) {
    $success = $_GET['success'];
}
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}

$result = mysqli_query($conn, "SELECT * FROM tb_pembayaran");
$page_title = 'Data Pembayaran';
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
            <h3 class="fw-bold text-dark">Data Pembayaran</h3>
            <a href="tambah.php" class="btn btn-primary shadow-sm"><i class="bi bi-plus-lg me-1"></i> Entri Pembayaran Baru</a>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i><?= $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="py-3">ID Transaksi</th>
                                <th class="py-3">NISN</th>
                                <th class="py-3">Tgl Bayar</th>
                                <th class="py-3">Periode</th>
                                <th class="py-3 text-end">Tagihan</th>
                                <th class="py-3 text-end">Dibayar</th>
                                <th class="py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="px-4"><?= $no++; ?></td>
                                <td><span class="badge bg-secondary fw-bold">#<?= $row['id_pembayaran']; ?></span></td>
                                <td><span class="fw-semibold text-dark"><?= $row['nisn']; ?></span></td>
                                <td><?= date('d M Y', strtotime($row['tgl_bayar'])); ?></td>
                                <td><span class="badge bg-light text-dark border"><?= $row['jumlah_bulan']; ?> Bulan</span></td>
                                <td class="text-end">Rp <?= number_format($row['nominal_bayar'], 0, ',', '.'); ?></td>
                                <td class="text-end fw-bold text-success">Rp <?= number_format($row['jumlah_bayar'], 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <?php if($row['status'] == 'Sudah Lunas'): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Lunas</span>
                                    <?php elseif($row['status'] == 'Belum Lunas'): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Pending</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= $row['status']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 text-end">
                                    <a href="edit.php?id_pembayaran=<?= $row['id_pembayaran']; ?>" class="btn btn-warning btn-sm shadow-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <a href="hapus.php?id_pembayaran=<?= $row['id_pembayaran']; ?>" onclick="return confirm('Yakin ingin hapus transaksi ini?')" class="btn btn-danger btn-sm shadow-sm" title="Hapus"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if(mysqli_num_rows($result) == 0): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Belum ada transaksi pembayaran.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3 border-0">
                <p class="small text-muted mb-0 text-end">Total Transaksi: <strong><?= mysqli_num_rows($result); ?></strong></p>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>