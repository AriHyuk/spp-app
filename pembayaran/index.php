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
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pembayaran</title>
    <?php include '../includes/styles.php'; ?>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-6">
                <h3 class="fw-bold text-primary"><i class="bi bi-wallet2 me-2"></i> Data Pembayaran</h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Pembayaran</a>
            </div>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i><?= $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3">No</th>
                                <th class="py-3">ID Bayar</th>
                                <th class="py-3">NISN</th>
                                <th class="py-3">Tgl Bayar</th>
                                <th class="py-3">Bulan</th>
                                <th class="py-3 text-end">Nominal</th>
                                <th class="py-3 text-end">Jumlah Bayar</th>
                                <th class="py-3 text-end">Kembali</th>
                                <th class="py-3 text-center">Status</th>
                                <th class="py-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="fw-bold text-primary">#<?= $row['id_pembayaran']; ?></td>
                                <td><?= $row['nisn']; ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tgl_bayar'])); ?></td>
                                <td><span class="badge bg-light text-dark border"><?= $row['jumlah_bulan']; ?> Bln</span></td>
                                <td class="text-end">Rp <?= number_format($row['nominal_bayar'], 0, ',', '.'); ?></td>
                                <td class="text-end fw-semibold text-success">Rp <?= number_format($row['jumlah_bayar'], 0, ',', '.'); ?></td>
                                <td class="text-end text-muted small">Rp <?= number_format($row['kembalian'], 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <?php if($row['status'] == 'Sudah Lunas'): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Sudah Lunas</span>
                                    <?php elseif($row['status'] == 'Belum Lunas'): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Belum Lunas</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= $row['status']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="edit.php?id_pembayaran=<?= $row['id_pembayaran']; ?>" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="hapus.php?id_pembayaran=<?= $row['id_pembayaran']; ?>" onclick="return confirm('Yakin ingin hapus?')" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-between align-items-center">
            <a href="../index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
            <p class="small text-muted mb-0">Total Data: <strong><?= mysqli_num_rows($result); ?></strong></p>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>