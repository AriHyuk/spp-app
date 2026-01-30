<?php
session_start();
include '../config/koneksi.php';

$error = '';
$success = '';

if (!isset($_GET['id_kelas'])) {
    header("Location: index.php");
    exit();
}

$id_kelas = mysqli_real_escape_string($conn, $_GET['id_kelas']);
$result = mysqli_query($conn, "SELECT * FROM tb_kelas WHERE id_kelas='$id_kelas'");

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kelas = mysqli_real_escape_string($conn, trim($_POST['nama_kelas']));
    $komp_keahlian = mysqli_real_escape_string($conn, trim($_POST['komp_keahlian'] ?? ''));

    if (empty($nama_kelas)) {
        $error = 'Nama Kelas harus diisi!';
    } else {
        $query = "UPDATE tb_kelas SET nama_kelas='$nama_kelas', komp_keahlian='$komp_keahlian' WHERE id_kelas='$id_kelas' LIMIT 1";
        
        if (mysqli_query($conn, $query)) {
            header("Location: index.php?success=Data kelas berhasil diperbarui!");
            exit();
        } else {
            $error = 'Error: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">SPP Dashboard</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Data Kelas</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">ID Kelas</label>
                                <input type="text" class="form-control" value="<?= $row['id_kelas']; ?>" disabled>
                                <small class="text-muted">ID Kelas tidak dapat diubah</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Kelas</label>
                                <input type="text" class="form-control" name="nama_kelas" required value="<?= $row['nama_kelas'] ?? ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Kompetensi Keahlian</label>
                                <input type="text" class="form-control" name="komp_keahlian" value="<?= $row['komp_keahlian'] ?? ''; ?>">
                                <small class="text-muted">Opsional</small>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-warning">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
