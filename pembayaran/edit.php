<?php
include '../config/koneksi.php';

$error = '';
$success = '';

if (!isset($_GET['id_pembayaran'])) {
    header("Location: index.php");
    exit();
}

$id_pembayaran = trim($_GET['id_pembayaran']);

// Ambil data pembayaran
$stmt_get = $conn->prepare("SELECT * FROM tb_pembayaran WHERE id_pembayaran=? LIMIT 1");
$stmt_get->bind_param("s", $id_pembayaran);
$stmt_get->execute();
$result = $stmt_get->get_result();

// Ambil semua siswa untuk dropdown
$siswaResult = mysqli_query($conn, "SELECT nisn, nama FROM tb_siswa ORDER BY nama");

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$row = $result->fetch_assoc();

// Convert untuk dropdown
$siswaList = [];
while ($siswa = mysqli_fetch_assoc($siswaResult)) {
    $siswaList[] = $siswa;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nisn = trim($_POST['nisn']);
    $tgl_bayar = trim($_POST['tgl_bayar']);
    $tgl_terakhir_bayar = trim($_POST['tgl_terakhir_bayar'] ?? null);
    $batas_pembayaran = trim($_POST['batas_pembayaran'] ?? null);
    $jumlah_bulan = trim($_POST['jumlah_bulan']);
    $nominal_bayar = (int)$_POST['nominal_bayar'];
    $jumlah_bayar = (int)$_POST['jumlah_bayar'];
    $kembalian = (int)($_POST['kembalian'] ?? 0);
    $status = trim($_POST['status']);

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
        $stmt_cek = $conn->prepare("SELECT nisn FROM tb_siswa WHERE nisn=? LIMIT 1");
        $stmt_cek->bind_param("s", $nisn);
        $stmt_cek->execute();
        $cekSiswa = $stmt_cek->get_result();
        
        if ($cekSiswa->num_rows == 0) {
            $error = 'NISN tidak terdaftar!';
        } else {
            $tgl_terakhir_bayar_bind = !empty($tgl_terakhir_bayar) ? $tgl_terakhir_bayar : null;
            $batas_pembayaran_bind = !empty($batas_pembayaran) ? $batas_pembayaran : null;

            $stmt_update = $conn->prepare("UPDATE tb_pembayaran SET nisn=?, tgl_bayar=?, tgl_terakhir_bayar=?, batas_pembayaran=?, jumlah_bulan=?, nominal_bayar=?, jumlah_bayar=?, kembalian=?, status=? WHERE id_pembayaran=?");
            $stmt_update->bind_param("sssssiiiss", $nisn, $tgl_bayar, $tgl_terakhir_bayar_bind, $batas_pembayaran_bind, $jumlah_bulan, $nominal_bayar, $jumlah_bayar, $kembalian, $status, $id_pembayaran);
            
            if ($stmt_update->execute()) {
                header("Location: index.php?success=Data pembayaran berhasil diperbarui!");
                exit();
            } else {
                $error = 'Error saat memperbarui data.';
            }
        }
    }
}

$page_title = 'Edit Pembayaran';
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
                                <li class="breadcrumb-item small active" aria-current="page">Edit Transaksi</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-4 text-md-end mt-3 mt-md-0">
                <a href="index.php" class="btn btn-light px-4 py-2 shadow-sm border rounded-pill">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3">
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
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="id_pembayaran" class="form-label fw-bold small text-muted text-uppercase">ID Pembayaran</label>
                                    <input type="text" class="form-control bg-light" id="id_pembayaran" name="id_pembayaran" disabled value="<?= htmlspecialchars($row['id_pembayaran']); ?>">
                                    <small class="text-secondary">ID tidak dapat diubah</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="nisn" class="form-label fw-bold small text-muted text-uppercase">NISN Siswa</label>
                                    <select class="form-select" id="nisn" name="nisn" required>
                                        <option value="">-- Pilih NISN --</option>
                                        <?php foreach ($siswaList as $siswa): ?>
                                            <option value="<?= htmlspecialchars($siswa['nisn']); ?>" <?= ($row['nisn'] == $siswa['nisn']) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($siswa['nisn']); ?> - <?= htmlspecialchars($siswa['nama']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tgl_bayar" class="form-label fw-bold small text-muted text-uppercase">Tanggal Pembayaran</label>
                                    <input type="date" class="form-control" id="tgl_bayar" name="tgl_bayar" required value="<?= htmlspecialchars($row['tgl_bayar']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="tgl_terakhir_bayar" class="form-label fw-bold small text-muted text-uppercase">Tgl Terakhir Bayar</label>
                                    <input type="date" class="form-control" id="tgl_terakhir_bayar" name="tgl_terakhir_bayar" value="<?= htmlspecialchars($row['tgl_terakhir_bayar'] ?? ''); ?>">
                                    <small class="text-secondary">Opsional</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="batas_pembayaran" class="form-label fw-bold small text-muted text-uppercase">Batas Pembayaran</label>
                                    <input type="date" class="form-control" id="batas_pembayaran" name="batas_pembayaran" value="<?= htmlspecialchars($row['batas_pembayaran'] ?? ''); ?>">
                                    <small class="text-secondary">Opsional</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="jumlah_bulan" class="form-label fw-bold small text-muted text-uppercase">Jumlah Bulan Dibayar</label>
                                    <input type="number" class="form-control" id="jumlah_bulan" name="jumlah_bulan" required value="<?= htmlspecialchars($row['jumlah_bulan']); ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nominal_bayar" class="form-label fw-bold small text-muted text-uppercase">Tagihan SPP (Rp)</label>
                                    <input type="number" class="form-control" id="nominal_bayar" name="nominal_bayar" required value="<?= htmlspecialchars($row['nominal_bayar']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="jumlah_bayar" class="form-label fw-bold small text-muted text-uppercase">Uang Dibayarkan (Rp)</label>
                                    <input type="number" class="form-control" id="jumlah_bayar" name="jumlah_bayar" required value="<?= htmlspecialchars($row['jumlah_bayar']); ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="kembalian" class="form-label fw-bold small text-muted text-uppercase">Kembalian (Rp)</label>
                                    <input type="number" class="form-control bg-light" id="kembalian" name="kembalian" value="<?= htmlspecialchars($row['kembalian']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-bold small text-muted text-uppercase">Status Pembayaran</label>
                                    <select class="form-select border-warning" id="status" name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Belum Lunas" <?= ($row['status'] == 'Belum Lunas') ? 'selected' : ''; ?>>Belum Lunas</option>
                                        <option value="Sudah Lunas" <?= ($row['status'] == 'Sudah Lunas') ? 'selected' : ''; ?>>Sudah Lunas</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="index.php" class="btn btn-light border px-4">Batal</a>
                                <button type="submit" class="btn btn-warning px-4 shadow-sm">
                                    <i class="bi bi-save me-1"></i> Update Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 border-top border-warning border-4 rounded-3 mt-4 mt-lg-0">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2 text-warning"></i>Data Saat Ini</h6>
                        <ul class="text-secondary small list-unstyled mb-0">
                            <li class="mb-2 d-flex justify-content-between">
                                <span>ID Pembayaran:</span>
                                <span class="fw-bold text-dark"><?= htmlspecialchars($row['id_pembayaran']); ?></span>
                            </li>
                            <li class="mb-2 d-flex justify-content-between">
                                <span>NISN Siswa:</span>
                                <span class="fw-bold text-dark"><?= htmlspecialchars($row['nisn']); ?></span>
                            </li>
                            <li class="mb-2 d-flex justify-content-between">
                                <span>Tanggal Bayar:</span>
                                <span class="fw-bold text-dark"><?= date('d M Y', strtotime($row['tgl_bayar'])); ?></span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <span>Status Terakhir:</span>
                                <?php if($row['status'] == 'Sudah Lunas' || $row['status'] == 'Lunas'): ?>
                                    <span class="badge bg-success shadow-sm">Lunas</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark shadow-sm">Belum Lunas</span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
