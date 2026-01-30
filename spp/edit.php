<?php
include '../config/koneksi.php';

$error = '';
$success = '';

if (!isset($_GET['id_spp'])) {
    header("Location: index.php");
    exit();
}

$id_spp = mysqli_real_escape_string($conn, $_GET['id_spp']);
$result = mysqli_query($conn, "SELECT * FROM tb_spp WHERE id_spp='$id_spp'");

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun']);
    $nominal = mysqli_real_escape_string($conn, $_POST['nominal']);

    if (empty($tahun) || empty($nominal)) {
        $error = 'Semua field harus diisi!';
    } else {
        $query = "UPDATE tb_spp SET 
                  tahun='$tahun',
                  nominal='$nominal'
                  WHERE id_spp='$id_spp'";
        
        if (mysqli_query($conn, $query)) {
            $success = 'Data SPP berhasil diperbarui!';
            $result = mysqli_query($conn, "SELECT * FROM tb_spp WHERE id_spp='$id_spp'");
            $row = mysqli_fetch_assoc($result);
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
    <title>Edit SPP</title>
    <?php include '../includes/styles.php'; ?>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);">
                        <h5 class="mb-0 text-white"><i class="bi bi-pencil me-2"></i> Edit SPP</h5>
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
                                <input type="text" class="form-control" value="<?= $row['id_spp']; ?>" disabled>
                                <small class="text-muted">ID tidak dapat diubah</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tahun</label>
                                <input type="number" class="form-control" name="tahun" required value="<?= $row['tahun']; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nominal (Rp)</label>
                                <input type="number" class="form-control" name="nominal" required value="<?= $row['nominal']; ?>">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i> Perbarui
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
