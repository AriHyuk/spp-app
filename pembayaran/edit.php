<?php
include '../config/koneksi.php';

$error = '';
$success = '';

if (!isset($_GET['id_pembayaran'])) {
    header("Location: index.php");
    exit();
}

$id_pembayaran = mysqli_real_escape_string($conn, $_GET['id_pembayaran']);

// Ambil data pembayaran dan semua siswa dengan 2 query
$result = mysqli_query($conn, "SELECT * FROM tb_pembayaran WHERE id_pembayaran='$id_pembayaran' LIMIT 1");
$siswaResult = mysqli_query($conn, "SELECT nisn, nama FROM tb_siswa ORDER BY nama");

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

// Convert untuk dropdown
$siswaList = [];
while ($siswa = mysqli_fetch_assoc($siswaResult)) {
    $siswaList[] = $siswa;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nisn = mysqli_real_escape_string($conn, trim($_POST['nisn']));
    $tgl_bayar = mysqli_real_escape_string($conn, trim($_POST['tgl_bayar']));
    $tgl_terakhir_bayar = mysqli_real_escape_string($conn, trim($_POST['tgl_terakhir_bayar'] ?? ''));
    $batas_pembayaran = mysqli_real_escape_string($conn, trim($_POST['batas_pembayaran'] ?? ''));
    $jumlah_bulan = mysqli_real_escape_string($conn, trim($_POST['jumlah_bulan']));
    $nominal_bayar = (int)$_POST['nominal_bayar'];
    $jumlah_bayar = (int)$_POST['jumlah_bayar'];
    $kembalian = (int)($_POST['kembalian'] ?? 0);
    $status = mysqli_real_escape_string($conn, trim($_POST['status']));

    // Validasi
    if (empty($nisn) || empty($tgl_bayar) || empty($jumlah_bulan) || empty($nominal_bayar) || empty($jumlah_bayar) || empty($status)) {
        $error = 'Semua field harus diisi!';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_bayar)) {
        $error = 'Format tanggal tidak valid (YYYY-MM-DD)!';
    } elseif ($nominal_bayar <= 0 || $jumlah_bayar <= 0) {
        $error = 'Nominal harus lebih dari 0!';
    } elseif ($status != 'Belum Lunas' && $status != 'Sudah Lunas') {
        $error = 'Status tidak valid!';
    } else {
        // Cek apakah NISN ada di database
        $cekSiswa = mysqli_query($conn, "SELECT nisn FROM tb_siswa WHERE nisn='$nisn' LIMIT 1");
        if (mysqli_num_rows($cekSiswa) == 0) {
            $error = 'NISN tidak terdaftar!';
        } else {
            $update_query = "UPDATE tb_pembayaran SET 
                            nisn='$nisn',
                            tgl_bayar='$tgl_bayar',
                            tgl_terakhir_bayar='$tgl_terakhir_bayar',
                            batas_pembayaran='$batas_pembayaran',
                            jumlah_bulan='$jumlah_bulan',
                            nominal_bayar='$nominal_bayar',
                            jumlah_bayar='$jumlah_bayar',
                            kembalian='$kembalian',
                            status='$status'
                            WHERE id_pembayaran='$id_pembayaran'";
            
            if (mysqli_query($conn, $update_query)) {
                header("Location: index.php?success=Data pembayaran berhasil diperbarui!");
                exit();
            } else {
                $error = 'Error: ' . mysqli_error($conn);
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
    <title>Edit Data Pembayaran | SPP Online</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
    :root {
        --bs-body-bg: #f8f9fa;
    }

    .card {
        border: none;
        border-radius: 0.75rem;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.95rem;
        color: #2c3e50;
    }

    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 1px solid #ddd;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row align-items-center mb-4">
            <div class="col-lg-6 col-md-8">
                <div class="d-flex align-items-center">
                    <div class="bg-warning text-white p-3 rounded-3 me-3 shadow-sm">
                        <i class="bi bi-pencil-square fs-3"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0 text-dark">Edit Pembayaran</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item small"><a href="index.php" class="text-decoration-none">Pembayaran</a></li>
                                <li class="breadcrumb-item small active" aria-current="page">Edit</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-4 text-md-end mt-3 mt-md-0">
                <a href="index.php" class="btn btn-secondary px-4 py-2 shadow-sm rounded-pill">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

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

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form method="POST" action="">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="id_pembayaran" class="form-label">ID Pembayaran</label>
                                    <input type="text" class="form-control" id="id_pembayaran" name="id_pembayaran" disabled value="<?= $row['id_pembayaran']; ?>">
                                    <small class="text-muted">ID tidak dapat diubah</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="nisn" class="form-label">NISN Siswa</label>
                                    <select class="form-select" id="nisn" name="nisn" required>
                                        <option value="">-- Pilih NISN --</option>
                                        <?php foreach ($siswaList as $siswa): ?>
                                            <option value="<?= $siswa['nisn']; ?>" <?= ($row['nisn'] == $siswa['nisn']) ? 'selected' : ''; ?>>
                                                <?= $siswa['nisn']; ?> - <?= $siswa['nama']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tgl_bayar" class="form-label">Tanggal Pembayaran</label>
                                    <input type="date" class="form-control" id="tgl_bayar" name="tgl_bayar" required value="<?= $row['tgl_bayar']; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="tgl_terakhir_bayar" class="form-label">Tgl Terakhir Bayar (Opsional)</label>
                                    <input type="date" class="form-control" id="tgl_terakhir_bayar" name="tgl_terakhir_bayar" value="<?= $row['tgl_terakhir_bayar'] ?? ''; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="batas_pembayaran" class="form-label">Batas Pembayaran (Opsional)</label>
                                    <input type="date" class="form-control" id="batas_pembayaran" name="batas_pembayaran" value="<?= $row['batas_pembayaran'] ?? ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="jumlah_bulan" class="form-label">Jumlah Bulan</label>
                                    <input type="number" class="form-control" id="jumlah_bulan" name="jumlah_bulan" required value="<?= $row['jumlah_bulan']; ?>">
                                </div>
                            </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nominal_bayar" class="form-label">Nominal Bayar (Rp)</label>
                                    <input type="number" class="form-control" id="nominal_bayar" name="nominal_bayar" required value="<?= $row['nominal_bayar']; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="jumlah_bayar" class="form-label">Jumlah Bayar (Rp)</label>
                                    <input type="number" class="form-control" id="jumlah_bayar" name="jumlah_bayar" required value="<?= $row['jumlah_bayar']; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="kembalian" class="form-label">Kembalian (Rp)</label>
                                    <input type="number" class="form-control" id="kembalian" name="kembalian" value="<?= $row['kembalian']; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Belum Lunas" <?= ($row['status'] == 'Belum Lunas') ? 'selected' : ''; ?>>Belum Lunas</option>
                                        <option value="Sudah Lunas" <?= ($row['status'] == 'Sudah Lunas') ? 'selected' : ''; ?>>Sudah Lunas</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="index.php" class="btn btn-light px-4">Batal</a>
                                <button type="submit" class="btn btn-warning px-4">
                                    <i class="bi bi-save me-1"></i> Update Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">
                            <i class="bi bi-info-circle me-2"></i>Data Saat Ini
                        </h5>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><strong>ID Pembayaran:</strong> <span class="text-primary"><?= $row['id_pembayaran']; ?></span></li>
                            <li class="mb-2"><strong>NISN:</strong> <span class="text-primary"><?= $row['nisn']; ?></span></li>
                            <li class="mb-2"><strong>Tanggal Bayar:</strong> <span class="text-primary"><?= date('d M Y', strtotime($row['tgl_bayar'])); ?></span></li>
                            <li class="mb-2"><strong>Status:</strong> 
                                <?php if($row['status'] == 'Lunas'): ?>
                                    <span class="badge bg-success">Lunas</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Proses</span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
