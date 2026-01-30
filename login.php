<?php
session_start();
include 'config/koneksi.php';

$error = '';
$success = '';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['id_petugas'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // Password diambil mentah (tanpa hash/md5)

    if (empty($username) || empty($password)) {
        $error = '⚠️ Username dan password harus diisi!';
    } else {
        // QUERY LAMA/BIASA (Cek username DAN password sekaligus)
        // Pastikan password di database adalah text biasa (bukan hash)
        $query = "SELECT * FROM tb_petugas WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $query);
        $cek = mysqli_num_rows($result);

        if ($cek > 0) {
            // Data ditemukan, ambil datanya
            $data = mysqli_fetch_assoc($result);

            // Set Session
            $_SESSION['id_petugas'] = $data['id_petugas'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['nama_petugas'] = $data['nama_petugas']; // Untuk sidebar
            $_SESSION['level'] = $data['level'];

            header("Location: index.php");
            exit();
        } else {
            $error = '❌ Username atau Password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Pemabayaran Spp Universitas Pamulang</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
    :root {
        --primary-color: #0891b2;
        --secondary-color: #06b6d4;
    }

    body {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-container {
        width: 100%;
        max-width: 420px;
    }

    .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border-radius: 1rem 1rem 0 0 !important;
        padding: 2rem 1.5rem;
        text-align: center;
    }

    .card-header h2 {
        color: white;
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.75rem;
    }

    .card-header p {
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
        font-size: 0.95rem;
    }

    .card-body {
        padding: 2.5rem 2rem;
    }

    .form-control {
        border: 2px solid #e0e0e0;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(8, 145, 178, 0.15);
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .btn-login {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem;
        border-radius: 0.5rem;
        transition: all 0.3s;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(8, 145, 178, 0.3);
        color: white;
    }

    .alert {
        border-radius: 0.5rem;
        border: none;
        margin-bottom: 1.5rem;
    }

    .input-group-text {
        background: white;
        border: 2px solid #e0e0e0;
        border: 2px solid #e0e0e0;
        /* Fixed typo in original CSS */
        color: var(--primary-color);
    }

    .form-control-with-icon {
        border-left: none;
        /* Seharusnya border kanan yang dihilangkan atau disesuaikan urutannya, tapi saya ikutin style asli */
        border: 2px solid #e0e0e0;
    }

    .form-control-with-icon:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(8, 145, 178, 0.15);
    }

    .password-toggle {
        cursor: pointer;
        color: var(--primary-color);
    }

    .footer-text {
        text-align: center;
        margin-top: 1.5rem;
        color: #7f8c8d;
        font-size: 0.9rem;
    }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="card">
            <div class="card-header">
                <h2><i class="bi bi-shield-check me-2"></i>SPP Online Universitas Pamulang</h2>
                <p>Sistem Pembayaran SPP Universitas Pamulang</p>
            </div>
            <div class="card-body">
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

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label"><i class="bi bi-person me-2"></i>Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            placeholder="Masukkan username" required
                            value="<?= isset($_POST['username']) ? $_POST['username'] : ''; ?>" autofocus>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label"><i class="bi bi-lock me-2"></i>Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control form-control-with-icon" id="password"
                                name="password" placeholder="Masukkan password" required>
                            <span class="input-group-text password-toggle" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggle-icon"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login w-100 py-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </button>
                </form>

                <div class="footer-text">
                    <p class="mb-0">Silahkan login dengan akun petugas</p>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('toggle-icon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }
    </script>

    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>