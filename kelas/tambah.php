<?php
include '../config/koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kelas = trim($_POST['id_kelas']);
    $nama_kelas = trim($_POST['nama_kelas']);
    $komp_keahlian = trim($_POST['komp_keahlian'] ?? '');

    if (empty($id_kelas) || empty($nama_kelas)) {
        $error = 'ID Kelas dan Nama Kelas harus diisi!';
    } else {
        $stmt_cek = $conn->prepare("SELECT id_kelas FROM tb_kelas WHERE id_kelas=? LIMIT 1");
        $stmt_cek->bind_param("s", $id_kelas);
        $stmt_cek->execute();
        $cek = $stmt_cek->get_result();

        if ($cek->num_rows > 0) {
            $error = 'ID Kelas sudah terdaftar!';
        } else {
            $stmt_insert = $conn->prepare("INSERT INTO tb_kelas (id_kelas, nama_kelas, komp_keahlian) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $id_kelas, $nama_kelas, $komp_keahlian);
            
            if ($stmt_insert->execute()) {
                $success = 'Data kelas berhasil ditambahkan!';
                $_POST = array(); // Kosongkan form setelah sukses
            } else {
                $error = 'Error saat menyimpan data.';
            }
        }
    }
}

$page_title = 'Tambah Kelas';
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
                        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-plus-lg me-2"></i> Tambah Kelas Baru</h5>
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
                                <input type="text" class="form-control" name="id_kelas" placeholder="Contoh: X-1, XI-2" required value="<?= isset($_POST['id_kelas']) ? htmlspecialchars($_POST['id_kelas']) : ''; ?>">
                                <small class="text-secondary">ID Kelas harus unik dan tidak boleh sama dengan yang sudah ada.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Kelas</label>
                                <input type="text" class="form-control" name="nama_kelas" placeholder="Contoh: Kelas X IPA 1" required value="<?= isset($_POST['nama_kelas']) ? htmlspecialchars($_POST['nama_kelas']) : ''; ?>">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Kompetensi Keahlian</label>
                                <input type="text" class="form-control" name="komp_keahlian" placeholder="Contoh: Teknik Komputer Jaringan" value="<?= isset($_POST['komp_keahlian']) ? htmlspecialchars($_POST['komp_keahlian']) : ''; ?>">
                                <small class="text-secondary">Opsional (Bisa dikosongkan jika tidak ada).</small>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="index.php" class="btn btn-light px-4 border">
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