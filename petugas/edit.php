<?php
session_start();
include '../config/koneksi.php';

// Cek Login
if (!isset($_SESSION['id_petugas'])) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';

// Cek ID di URL
if (!isset($_GET['id_petugas'])) {
    header("Location: index.php");
    exit();
}

$id_petugas = mysqli_real_escape_string($conn, $_GET['id_petugas']);
$result = mysqli_query($conn, "SELECT * FROM tb_petugas WHERE id_petugas='$id_petugas' LIMIT 1");

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

// Proses Update Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_petugas = mysqli_real_escape_string($conn, trim($_POST['nama_petugas']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']); // Password mentah
    $level = mysqli_real_escape_string($conn, trim($_POST['level']));

    if (empty($nama_petugas) || empty($username) || empty($level)) {
        $error = '⚠️ Nama, Username, dan Level harus diisi!';
    } else {
        // Cek username tidak duplikat (kecuali punya sendiri)
        $cek = mysqli_query($conn, "SELECT id_petugas FROM tb_petugas WHERE username='$username' AND id_petugas != '$id_petugas'");
        
        if (mysqli_num_rows($cek) > 0) {
            $error = '❌ Username sudah digunakan petugas lain!';
        } else {
            // Logika Update Password (TANPA HASH)
            if (!empty($password)) {
                // Jika password diisi, update semuanya termasuk password
                $query = "UPDATE tb_petugas SET 
                          nama_petugas='$nama_petugas',
                          username='$username', 
                          password='$password', 
                          level='$level' 
                          WHERE id_petugas='$id_petugas'";
            } else {
                // Jika password kosong, jangan ubah password lama
                $query = "UPDATE tb_petugas SET 
                          nama_petugas='$nama_petugas',
                          username='$username', 
                          level='$level' 
                          WHERE id_petugas='$id_petugas'";
            }

            if (mysqli_query($conn, $query)) {
                $success = '✅ Data petugas berhasil diperbarui!';
                // Refresh data di form
                $row['nama_petugas'] = $nama_petugas;
                $row['username'] = $username;
                $row['level'] = $level;
                
                // Redirect otomatis
                echo "<meta http-equiv='refresh' content='1;url=index.php'>";
            } else {
                $error = '❌ Gagal update: ' . mysqli_error($conn);
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
    <title>Edit Petugas</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">SPP Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-pencil-square me-2"></i>Edit Data Petugas
                        </h5>
                    </div>
                    <div class="card-body p-4">

                        <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Petugas</label>
                                <input type="text" class="form-control" name="nama_petugas" required
                                    value="<?= $row['nama_petugas']; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Username</label>
                                <input type="text" class="form-control" name="username" required
                                    value="<?= $row['username']; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Password Baru</label>
                                <input type="text" class="form-control" name="password"
                                    placeholder="Kosongkan jika tidak ingin ganti password">
                                <div class="form-text text-muted">Password akan disimpan tanpa enkripsi.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Level Akses</label>
                                <select class="form-select" name="level" required>
                                    <option value="admin" <?= ($row['level'] == 'admin') ? 'selected' : ''; ?>>Admin
                                    </option>
                                    <option value="petugas" <?= ($row['level'] == 'petugas') ? 'selected' : ''; ?>>
                                        Petugas</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary px-4">Batal</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save me-2"></i>Simpan Perubahan
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