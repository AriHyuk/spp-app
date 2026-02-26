<?php
session_start();
include '../config/koneksi.php';

// Cek Login
if (!isset($_SESSION['id_petugas'])) {
    header("Location: ../login.php");
    exit();
}

// Cek ID di URL
if (!isset($_GET['id_petugas'])) {
    header("Location: index.php");
    exit();
}

$id_petugas = trim($_GET['id_petugas']);

// Prepare statement untuk mendapatkan data petugas
$stmt_get = $conn->prepare("SELECT * FROM tb_petugas WHERE id_petugas=? LIMIT 1");
$stmt_get->bind_param("i", $id_petugas);
$stmt_get->execute();
$result = $stmt_get->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$row = $result->fetch_assoc();

// Proses Update Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_petugas = trim($_POST['nama_petugas']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $level = trim($_POST['level']);

    if (empty($nama_petugas) || empty($username) || empty($level)) {
        $error = '⚠️ Nama, Username, dan Hak Akses harus diisi!';
    } else {
        // Cek username tidak duplikat (kecuali punya sendiri)
        $stmt_cek = $conn->prepare("SELECT id_petugas FROM tb_petugas WHERE username=? AND id_petugas != ?");
        $stmt_cek->bind_param("si", $username, $id_petugas);
        $stmt_cek->execute();
        $cek = $stmt_cek->get_result();
        
        if ($cek->num_rows > 0) {
            $error = '❌ Username sudah digunakan petugas lain!';
        } else {
            // Update Data Menggunakan Prepared Statement
            if (!empty($password)) {
                // Hash password terlebih dahulu
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt_update = $conn->prepare("UPDATE tb_petugas SET nama_petugas=?, username=?, password=?, level=? WHERE id_petugas=?");
                $stmt_update->bind_param("ssssi", $nama_petugas, $username, $hashed_password, $level, $id_petugas);
            } else {
                // Jika password kosong, update field lainnya saja
                $stmt_update = $conn->prepare("UPDATE tb_petugas SET nama_petugas=?, username=?, level=? WHERE id_petugas=?");
                $stmt_update->bind_param("sssi", $nama_petugas, $username, $level, $id_petugas);
            }

            if ($stmt_update->execute()) {
                $success = '✅ Data petugas berhasil diperbarui!';
                // Refresh data di form
                $row['nama_petugas'] = $nama_petugas;
                $row['username'] = $username;
                $row['level'] = $level;
                
                // Redirect otomatis
                echo "<meta http-equiv='refresh' content='1;url=index.php'>";
            } else {
                $error = '❌ Gagal update data petugas.';
            }
        }
    }
}

$page_title = 'Edit Petugas';
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
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-pencil-square me-2"></i>Edit Data Petugas
                        </h5>
                    </div>
                    <div class="card-body p-4">

                        <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                            <i class="bi bi-exclamation-circle me-2"></i><?= $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show shadow-sm">
                            <i class="bi bi-check-circle me-2"></i><?= $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama_petugas" required
                                    value="<?= htmlspecialchars($row['nama_petugas']); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Username</label>
                                <input type="text" class="form-control" name="username" required
                                    value="<?= htmlspecialchars($row['username']); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Password Baru</label>
                                <input type="password" class="form-control bg-light" name="password"
                                    placeholder="Isi jika ingin mengganti password">
                                <div class="form-text text-secondary">Kosongkan jika tidak ingin mengubah password yang lama. Password akan dienkripsi secara aman.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Hak Akses</label>
                                <select class="form-select border-primary bg-light" name="level" required>
                                    <option value="admin" <?= ($row['level'] == 'admin') ? 'selected' : ''; ?>>Administrator (Akses Penuh)</option>
                                    <option value="petugas" <?= ($row['level'] == 'petugas') ? 'selected' : ''; ?>>Petugas (Akses Terbatas)</option>
                                </select>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="index.php" class="btn btn-light px-4 border">Batal</a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-save me-2"></i>Simpan Perubahan
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