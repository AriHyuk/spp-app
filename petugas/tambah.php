<?php
session_start();
include '../config/koneksi.php';

// Check login
if (!isset($_SESSION['id_petugas'])) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_petugas = trim($_POST['nama_petugas']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $level = trim($_POST['level']);

    if (empty($nama_petugas) || empty($username) || empty($password) || empty($level)) {
        $error = '⚠️ Semua kolom harus diisi!';
    } else if (strlen($password) < 5) {
        $error = '⚠️ Password minimal 5 karakter!';
    } else {
        // Cek username sudah ada
        $stmt_cek = $conn->prepare("SELECT id_petugas FROM tb_petugas WHERE username=? LIMIT 1");
        $stmt_cek->bind_param("s", $username);
        $stmt_cek->execute();
        $cek = $stmt_cek->get_result();
        
        if ($cek->num_rows > 0) {
            $error = '❌ Username sudah terdaftar!';
        } else {
            // Generate ID otomatis (bisa null jika auto-increment, tapi kita ikuti flow lama jika ID adalah int)
            $query_id = "SELECT MAX(CAST(id_petugas AS UNSIGNED)) as max_id FROM tb_petugas";
            $result_id = mysqli_query($conn, $query_id);
            $row_id = mysqli_fetch_assoc($result_id);
            $id_petugas = ($row_id['max_id'] ?? 0) + 1;
            
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert menggunakan prepared statement
            $stmt_insert = $conn->prepare("INSERT INTO tb_petugas (id_petugas, username, password, nama_petugas, level) VALUES (?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("issss", $id_petugas, $username, $hashed_password, $nama_petugas, $level);
            
            if ($stmt_insert->execute()) {
                $success = '✅ Petugas berhasil ditambahkan!';
                $_POST = array();
                echo "<meta http-equiv='refresh' content='2;url=index.php'>";
            } else {
                $error = '❌ Error saat menyimpan data.';
            }
        }
    }
}

$page_title = 'Tambah Petugas';
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
                        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-person-plus-fill me-2"></i>Tambah Data Petugas</h5>
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
                                    value="<?= isset($_POST['nama_petugas']) ? htmlspecialchars($_POST['nama_petugas']) : ''; ?>" placeholder="Nama asil petugas">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Username</label>
                                <input type="text" class="form-control" name="username" required
                                    value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" placeholder="Username untuk login">
                                <small class="text-secondary">Harus unik, tidak boleh sama dengan petugas lain.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Password</label>
                                <input type="password" class="form-control" name="password" required
                                    placeholder="Minimal 5 karakter">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted text-uppercase">Hak Akses</label>
                                <select class="form-select border-primary bg-light" name="level" required>
                                    <option value="">-- Pilih Level --</option>
                                    <option value="admin" <?= (isset($_POST['level']) && $_POST['level'] == 'admin') ? 'selected' : ''; ?>>Administrator (Akses Penuh)</option>
                                    <option value="petugas" <?= (isset($_POST['level']) && $_POST['level'] == 'petugas') ? 'selected' : ''; ?>>Petugas (Akses Terbatas)</option>
                                </select>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="index.php" class="btn btn-light px-4 border">Batal</a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="bi bi-save me-1"></i> Simpan Petugas</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>