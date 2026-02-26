<?php
include '../config/koneksi.php';

$error = '';
$success = '';

if (!isset($_GET['id_spp'])) {
    header("Location: index.php");
    exit();
}

$id_spp = trim($_GET['id_spp']);

$stmt_get = $conn->prepare("SELECT * FROM tb_spp WHERE id_spp=?");
$stmt_get->bind_param("s", $id_spp);
$stmt_get->execute();
$result = $stmt_get->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tahun = trim($_POST['tahun']);
    $nominal = trim($_POST['nominal']);

    if (empty($tahun) || empty($nominal)) {
        $error = 'Semua field harus diisi!';
    } else {
        $stmt_update = $conn->prepare("UPDATE tb_spp SET tahun=?, nominal=? WHERE id_spp=?");
        $stmt_update->bind_param("sis", $tahun, $nominal, $id_spp);
        
        if ($stmt_update->execute()) {
            $success = 'Data SPP berhasil diperbarui!';
            $row['tahun'] = $tahun;
            $row['nominal'] = $nominal;
        } else {
            $error = 'Error saat memperbarui data.';
        }
    }
}

$page_title = 'Edit SPP';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<main class="main-content">
    <div class="d-md-none mb-4">
        <button class="btn btn-primary" id="sidebarToggle">
            <i class="bi bi-list"></i> Menu
        </button>
    </div>

    <div class="container-fluid mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i> Edit Data SPP</h5>
                    </div>
                    <div class="card-body p-4">
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

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">ID SPP</label>
                                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($row['id_spp']); ?>" disabled>
                                <small class="text-secondary">ID SPP tidak dapat diubah (Read-only)</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Tahun Ajaran</label>
                                <input type="number" class="form-control" name="tahun" required value="<?= htmlspecialchars($row['tahun']); ?>">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nominal Bayar (Rp)</label>
                                <input type="number" class="form-control" name="nominal" required value="<?= htmlspecialchars($row['nominal']); ?>">
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="index.php" class="btn btn-light px-4 border">
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
