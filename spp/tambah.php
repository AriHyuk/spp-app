<?php
include '../config/koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_spp = trim($_POST['id_spp']);
    $tahun = trim($_POST['tahun']);
    $nominal = trim($_POST['nominal']);

    if (empty($id_spp) || empty($tahun) || empty($nominal)) {
        $error = 'Semua field harus diisi!';
    } else {
        $stmt_cek = $conn->prepare("SELECT id_spp FROM tb_spp WHERE id_spp=?");
        $stmt_cek->bind_param("s", $id_spp);
        $stmt_cek->execute();
        $cek = $stmt_cek->get_result();
        
        if ($cek->num_rows > 0) {
            $error = 'ID SPP sudah terdaftar!';
        } else {
            $stmt_insert = $conn->prepare("INSERT INTO tb_spp (id_spp, tahun, nominal) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("ssi", $id_spp, $tahun, $nominal);
            
            if ($stmt_insert->execute()) {
                $success = 'Data SPP berhasil ditambahkan!';
                $_POST = array();
            } else {
                $error = 'Error saat menyimpan data.';
            }
        }
    }
}

$page_title = 'Tambah SPP';
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
                        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-plus-lg me-2"></i> Tambah SPP Baru</h5>
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
                                <input type="text" class="form-control" name="id_spp" placeholder="Contoh: SPP-001" required value="<?= isset($_POST['id_spp']) ? htmlspecialchars($_POST['id_spp']) : ''; ?>">
                                <small class="text-secondary">Tidak boleh sama dengan ID lain</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Tahun Ajaran</label>
                                <input type="number" class="form-control" name="tahun" placeholder="Contoh: 2024" required value="<?= isset($_POST['tahun']) ? htmlspecialchars($_POST['tahun']) : ''; ?>">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nominal Bayar (Rp)</label>
                                <input type="number" class="form-control" name="nominal" placeholder="Contoh: 500000" required value="<?= isset($_POST['nominal']) ? htmlspecialchars($_POST['nominal']) : ''; ?>">
                                <small class="text-secondary">Masukkan nilai tanpa titik atau koma (hanya angka)</small>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="index.php" class="btn btn-light border px-4">
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-save me-1"></i> Simpan Data
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
