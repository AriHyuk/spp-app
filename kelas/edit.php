<?php
session_start();
include '../config/koneksi.php';

$error = '';
$success = '';

if (!isset($_GET['id_kelas'])) {
    header("Location: index.php");
    exit();
}

$id_kelas = trim($_GET['id_kelas']);

$stmt_get = $conn->prepare("SELECT * FROM tb_kelas WHERE id_kelas=? LIMIT 1");
$stmt_get->bind_param("s", $id_kelas);
$stmt_get->execute();
$result = $stmt_get->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kelas = trim($_POST['nama_kelas']);
    $komp_keahlian = trim($_POST['komp_keahlian'] ?? '');

    if (empty($nama_kelas)) {
        $error = 'Nama Kelas harus diisi!';
    } else {
        $stmt_update = $conn->prepare("UPDATE tb_kelas SET nama_kelas=?, komp_keahlian=? WHERE id_kelas=? LIMIT 1");
        $stmt_update->bind_param("sss", $nama_kelas, $komp_keahlian, $id_kelas);
        
        if ($stmt_update->execute()) {
            $success = 'Data kelas berhasil diperbarui!';
            $row['nama_kelas'] = $nama_kelas;
            $row['komp_keahlian'] = $komp_keahlian;
        } else {
            $error = 'Error saat memperbarui data.';
        }
    }
}

$page_title = 'Edit Kelas';
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
                        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i> Edit Data Kelas</h5>
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
                                <label class="form-label fw-bold small text-muted text-uppercase">ID Kelas</label>
                                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($row['id_kelas']); ?>" disabled>
                                <small class="text-secondary">ID Kelas tidak dapat diubah (Read-only)</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Kelas</label>
                                <input type="text" class="form-control" name="nama_kelas" required value="<?= htmlspecialchars($row['nama_kelas'] ?? ''); ?>">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Kompetensi Keahlian</label>
                                <input type="text" class="form-control" name="komp_keahlian" value="<?= htmlspecialchars($row['komp_keahlian'] ?? ''); ?>">
                                <small class="text-secondary">Opsional (Bisa dikosongkan jika tidak ada).</small>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="index.php" class="btn btn-light px-4 border">Batal</a>
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
