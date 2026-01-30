<?php
include '../config/koneksi.php';

$error = '';
$success = '';

if (isset($_GET['success'])) {
    $success = $_GET['success'];
}
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}

$result = mysqli_query($conn, "SELECT * FROM tb_kelas");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kelas</title>
    <?php include '../includes/styles.php'; ?>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-6">
                <h3 class="fw-bold text-primary"><i class="bi bi-collection me-2"></i>Data Kelas</h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Tambah Kelas</a>
            </div>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> <?= $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?= $success; ?>
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
                                <th class="py-3">ID Kelas</th>
                                <th class="py-3">Nama Kelas</th>
                                <th class="py-3">Kompetensi Keahlian</th>
                                <th class="py-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                        $no = 1;
                        while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><strong><?= $row['id_kelas']; ?></strong></td>
                                <td><?= $row['nama_kelas'] ?? '-'; ?></td>
                                <td><?= $row['komp_keahlian'] ?? '-'; ?></td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="edit.php?id_kelas=<?= $row['id_kelas']; ?>" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="hapus.php?id_kelas=<?= $row['id_kelas']; ?>"
                                            onclick="return confirm('Yakin ingin hapus?')"
                                            class="btn btn-outline-danger"><i class="bi bi-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <a href="../index.php" class="btn btn-outline-secondary mt-4"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>