<?php
include '../config/koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kelas = mysqli_real_escape_string($conn, trim($_POST['id_kelas']));
    $nama_kelas = mysqli_real_escape_string($conn, trim($_POST['nama_kelas']));
    $komp_keahlian = mysqli_real_escape_string($conn, trim($_POST['komp_keahlian'] ?? ''));

    if (empty($id_kelas) || empty($nama_kelas)) {
        $error = 'ID Kelas dan Nama Kelas harus diisi!';
    } else {
        $cek = mysqli_query($conn, "SELECT * FROM tb_kelas WHERE id_kelas='$id_kelas' LIMIT 1");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'ID Kelas sudah terdaftar!';
        } else {
            $query = "INSERT INTO tb_kelas (id_kelas, nama_kelas, komp_keahlian) VALUES ('$id_kelas', '$nama_kelas', '$komp_keahlian')";
            
            if (mysqli_query($conn, $query)) {
                header("Location: index.php?success=Data kelas berhasil ditambahkan!");
                exit();
            } else {
                $error = 'Error: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kelas</title>
    <?php include '../includes/styles.php'; ?>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);">
                        <h5 class="mb-0 text-white"><i class="bi bi-plus-lg me-2"></i> Tambah Kelas Baru</h5>
                    </div>
                    <div class="card-body p-4">
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

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">ID Kelas</label>
                                <input type="text" class="form-control" name="id_kelas" placeholder="Contoh: X-1, XI-2" required value="<?= isset($_POST['id_kelas']) ? $_POST['id_kelas'] : ''; ?>">
                                <small class="text-muted">ID Kelas harus unik</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Kelas</label>
                                <input type="text" class="form-control" name="nama_kelas" placeholder="Contoh: Kelas X IPA 1" required value="<?= isset($_POST['nama_kelas']) ? $_POST['nama_kelas'] : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Kompetensi Keahlian</label>
                                <input type="text" class="form-control" name="komp_keahlian" placeholder="Contoh: Teknik Komputer Jaringan" value="<?= isset($_POST['komp_keahlian']) ? $_POST['komp_keahlian'] : ''; ?>">
                                <small class="text-muted">Opsional</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i> Simpan
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-1"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>