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
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);
    $level = mysqli_real_escape_string($conn, trim($_POST['level']));

    if (empty($username) || empty($password) || empty($level)) {
        $error = '⚠️ Semua field harus diisi!';
    } else if (strlen($password) < 5) {
        $error = '⚠️ Password minimal 5 karakter!';
    } else {
        // Cek username sudah ada
        $cek = mysqli_query($conn, "SELECT id_petugas FROM tb_petugas WHERE username='$username' LIMIT 1");
        if (mysqli_num_rows($cek) > 0) {
            $error = '❌ Username sudah terdaftar!';
        } else {
            // Generate ID otomatis
            $query_id = "SELECT MAX(CAST(id_petugas AS UNSIGNED)) as max_id FROM tb_petugas";
            $result_id = mysqli_query($conn, $query_id);
            $row_id = mysqli_fetch_assoc($result_id);
            $id_petugas = ($row_id['max_id'] ?? 0) + 1;
            
            // Hash password dengan password_hash
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert tanpa hash password (plaintext)
            $insert_query = "INSERT INTO tb_petugas (id_petugas, username, password, level) 
                           VALUES ('$id_petugas', '$username', '$hashed_password', '$level')";
            
            if (mysqli_query($conn, $insert_query)) {
                $success = '✅ Petugas berhasil ditambahkan!';
                $_POST = array();
                // Redirect setelah 2 detik
                header("Refresh: 2; url=index.php");
            } else {
                $error = '❌ Error: ' . mysqli_error($conn);
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
    <title>Tambah Petugas</title>
    <?php include '../includes/styles.php'; ?>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header" style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);">
                        <h5 class="mb-0 text-white"><i class="bi bi-person-plus me-2"></i> Tambah Petugas</h5>
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
                                <label class="form-label fw-bold">Username</label>
                                <input type="text" class="form-control" name="username" placeholder="Contoh: admin2"
                                    required value="<?= isset($_POST['username']) ? $_POST['username'] : ''; ?>">
                                <small class="text-muted">Harus unik</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Password</label>
                                <input type="password" class="form-control" name="password"
                                    placeholder="Minimal 5 karakter" required>
                                <small class="text-muted">Minimal 5 karakter</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Level</label>
                                <select class="form-select" name="level" required>
                                    <option value="">-- Pilih Level --</option>
                                    <option value="admin"
                                        <?= (isset($_POST['level']) && $_POST['level'] == 'admin') ? 'selected' : ''; ?>>
                                        Admin</option>
                                    <option value="petugas"
                                        <?= (isset($_POST['level']) && $_POST['level'] == 'petugas') ? 'selected' : ''; ?>>
                                        Petugas</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="index.php" class="btn btn-light px-4">Batal</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save me-1"></i> Simpan
                                </button>
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