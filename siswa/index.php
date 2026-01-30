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

$result = mysqli_query($conn, "SELECT * FROM tb_siswa");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold text-primary">Data Siswa</h3>
            <a href="tambah.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Siswa</a>
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

        <table class="table table-striped table-hover shadow-sm rounded">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Alamat</th>
                    <th>No. Telp</th>
                    <th>ID SPP</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
            $no = 1;
            while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nisn']; ?></td>
                    <td><?= $row['nis']; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['nama_kelas']; ?></td>
                    <td><?= $row['alamat']; ?></td>
                    <td><?= $row['no_telp']; ?></td>
                    <td><?= $row['id_spp']; ?></td>
                    <td>
                        <a href="edit.php?nisn=<?= $row['nisn']; ?>" class="btn btn-warning btn-sm"><i
                                class="bi bi-pencil"></i></a>
                        <a href="hapus.php?nisn=<?= $row['nisn']; ?>"
                            onclick="return confirm('Yakin ingin hapus data ini?')" class="btn btn-danger btn-sm"><i
                                class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="../index.php" class="btn btn-secondary mt-3"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>