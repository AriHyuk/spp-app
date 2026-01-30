<?php
include '../config/koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_spp = mysqli_real_escape_string($conn, $_POST['id_spp']);
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun']);
    $nominal = mysqli_real_escape_string($conn, $_POST['nominal']);

    if (empty($id_spp) || empty($tahun) || empty($nominal)) {
        $error = 'Semua field harus diisi!';
    } else {
        $cek = mysqli_query($conn, "SELECT * FROM tb_spp WHERE id_spp='$id_spp'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'ID SPP sudah terdaftar!';
        } else {
            $query = "INSERT INTO tb_spp (id_spp, tahun, nominal) 
                      VALUES ('$id_spp', '$tahun', '$nominal')";
            
            if (mysqli_query($conn, $query)) {
                $success = 'Data SPP berhasil ditambahkan!';
                $_POST = array();
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
    <title>Tambah SPP</title>
    <?php include '../includes/styles.php'; ?>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);">
                        <h5 class="mb-0 text-white"><i class="bi bi-plus-lg me-2"></i> Tambah SPP Baru</h5>
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
                                <label class="form-label fw-bold">ID SPP</label>
                                <input type="text" class="form-control" name="id_spp" placeholder="Contoh: SPP-001" required value="<?= isset($_POST['id_spp']) ? $_POST['id_spp'] : ''; ?>">
                                <small class="text-muted">ID SPP harus unik</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tahun</label>
                                <input type="number" class="form-control" name="tahun" placeholder="Contoh: 2024" required value="<?= isset($_POST['tahun']) ? $_POST['tahun'] : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nominal (Rp)</label>
                                <input type="number" class="form-control" name="nominal" placeholder="Contoh: 500000" required value="<?= isset($_POST['nominal']) ? $_POST['nominal'] : ''; ?>">
                                <small class="text-muted">Masukkan nilai tanpa titik atau koma</small>
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

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
