<?php
include '../config/koneksi.php';

$error = '';
$success = '';

// Ambil semua siswa untuk dropdown (optimize: 1 query saja)
$siswaResult = mysqli_query($conn, "SELECT nisn, nama FROM tb_siswa ORDER BY nama");
$siswaList = [];
while ($row = mysqli_fetch_assoc($siswaResult)) {
    $siswaList[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pembayaran = trim($_POST['id_pembayaran']);
    $nisn = trim($_POST['nisn']);
    $tgl_bayar = trim($_POST['tgl_bayar']);
    $tgl_terakhir_bayar = trim($_POST['tgl_terakhir_bayar'] ?? null);
    $batas_pembayaran = trim($_POST['batas_pembayaran'] ?? null);
    $jumlah_bulan = trim($_POST['jumlah_bulan']);
    $id_spp = trim($_POST['id_spp'] ?? '');
    $nominal_bayar = (int)$_POST['nominal_bayar'];
    $jumlah_bayar = (int)$_POST['jumlah_bayar'];
    $kembalian = (int)($_POST['kembalian'] ?? 0);
    $status = trim($_POST['status']);

    // Validasi
    if (empty($id_pembayaran) || empty($nisn) || empty($tgl_bayar) || empty($jumlah_bulan) || empty($nominal_bayar) || empty($jumlah_bayar) || empty($status)) {
        $error = 'Semua field harus diisi!';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_bayar)) {
        $error = 'Format tanggal tidak valid (YYYY-MM-DD)!';
    } elseif ($nominal_bayar <= 0 || $jumlah_bayar <= 0) {
        $error = 'Nominal harus lebih dari 0!';
    } elseif ($status != 'Belum Lunas' && $status != 'Sudah Lunas') {
        $error = 'Status tidak valid!';
    } else {
        // Cek apakah NISN ada di database dan ambil id_spp-nya
        $stmt_siswa = $conn->prepare("SELECT nisn, id_spp FROM tb_siswa WHERE nisn=? LIMIT 1");
        $stmt_siswa->bind_param("s", $nisn);
        $stmt_siswa->execute();
        $cekSiswa = $stmt_siswa->get_result();

        if ($cekSiswa->num_rows == 0) {
            $error = 'NISN tidak terdaftar!';
        } else {
            $siswa = $cekSiswa->fetch_assoc();
            $id_spp_siswa = $siswa['id_spp'];
            
            // Cek apakah ID pembayaran sudah ada
            $stmt_cek = $conn->prepare("SELECT id_pembayaran FROM tb_pembayaran WHERE id_pembayaran=? LIMIT 1");
            $stmt_cek->bind_param("s", $id_pembayaran);
            $stmt_cek->execute();
            $cek = $stmt_cek->get_result();
            
            if ($cek->num_rows > 0) {
                $error = 'ID Pembayaran sudah terdaftar!';
            } else {
                // Konversi tanggal kosong jadi NULL
                $tgl_terakhir_bayar_bind = !empty($tgl_terakhir_bayar) ? $tgl_terakhir_bayar : null;
                $batas_pembayaran_bind = !empty($batas_pembayaran) ? $batas_pembayaran : null;

                $stmt_insert = $conn->prepare("INSERT INTO tb_pembayaran (id_pembayaran, status, nisn, tgl_bayar, tgl_terakhir_bayar, batas_pembayaran, jumlah_bulan, id_spp, nominal_bayar, jumlah_bayar, kembalian) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                $stmt_insert->bind_param("ssssssisiii", $id_pembayaran, $status, $nisn, $tgl_bayar, $tgl_terakhir_bayar_bind, $batas_pembayaran_bind, $jumlah_bulan, $id_spp_siswa, $nominal_bayar, $jumlah_bayar, $kembalian);
                
                if ($stmt_insert->execute()) {
                    header("Location: index.php?success=Data pembayaran berhasil ditambahkan!");
                    exit();
                } else {
                    $error = 'Error menyimpan data.';
                }
            }
        }
    }
}

$page_title = 'Tambah Pembayaran';
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
                    <div class="bg-primary text-white p-3 rounded-3 me-3 shadow-sm">
                        <i class="bi bi-cash-stack fs-3"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0 text-dark">Tambah Pembayaran</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item small"><a href="index.php" class="text-decoration-none">Pembayaran</a></li>
                                <li class="breadcrumb-item small active" aria-current="page">Tambah Data Baru</li>
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
                                    <input type="text" class="form-control" id="id_pembayaran" name="id_pembayaran" required
                                        value="<?= isset($_POST['id_pembayaran']) ? htmlspecialchars($_POST['id_pembayaran']) : ''; ?>" placeholder="Contoh: BYR001">
                                    <small class="text-secondary">Pasti unik</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="nisn" class="form-label fw-bold small text-muted text-uppercase">NISN Siswa</label>
                                    <select class="form-select" id="nisn" name="nisn" required>
                                        <option value="">-- Pilih NISN --</option>
                                        <?php foreach ($siswaList as $siswa): ?>
                                        <option value="<?= htmlspecialchars($siswa['nisn']); ?>"
                                            <?= (isset($_POST['nisn']) && $_POST['nisn'] == $siswa['nisn']) ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($siswa['nisn']); ?> - <?= htmlspecialchars($siswa['nama']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tgl_bayar" class="form-label fw-bold small text-muted text-uppercase">Tanggal Pembayaran</label>
                                    <input type="date" class="form-control" id="tgl_bayar" name="tgl_bayar" required
                                        value="<?= isset($_POST['tgl_bayar']) ? htmlspecialchars($_POST['tgl_bayar']) : date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="tgl_terakhir_bayar" class="form-label fw-bold small text-muted text-uppercase">Tgl Terakhir Bayar</label>
                                    <input type="date" class="form-control" id="tgl_terakhir_bayar" name="tgl_terakhir_bayar"
                                        value="<?= isset($_POST['tgl_terakhir_bayar']) ? htmlspecialchars($_POST['tgl_terakhir_bayar']) : ''; ?>">
                                    <small class="text-secondary">Opsional</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="batas_pembayaran" class="form-label fw-bold small text-muted text-uppercase">Batas Pembayaran</label>
                                    <input type="date" class="form-control" id="batas_pembayaran" name="batas_pembayaran"
                                        value="<?= isset($_POST['batas_pembayaran']) ? htmlspecialchars($_POST['batas_pembayaran']) : ''; ?>">
                                    <small class="text-secondary">Opsional</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="jumlah_bulan" class="form-label fw-bold small text-muted text-uppercase">Jumlah Bulan Dibayar</label>
                                    <input type="number" class="form-control" id="jumlah_bulan" name="jumlah_bulan" required
                                        value="<?= isset($_POST['jumlah_bulan']) ? htmlspecialchars($_POST['jumlah_bulan']) : ''; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nominal_bayar" class="form-label fw-bold small text-muted text-uppercase">Tagihan SPP (Rp)</label>
                                    <input type="number" class="form-control" id="nominal_bayar" name="nominal_bayar" required
                                        value="<?= isset($_POST['nominal_bayar']) ? htmlspecialchars($_POST['nominal_bayar']) : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="jumlah_bayar" class="form-label fw-bold small text-muted text-uppercase">Uang Dibayarkan (Rp)</label>
                                    <input type="number" class="form-control" id="jumlah_bayar" name="jumlah_bayar" required
                                        value="<?= isset($_POST['jumlah_bayar']) ? htmlspecialchars($_POST['jumlah_bayar']) : ''; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="kembalian" class="form-label fw-bold small text-muted text-uppercase">Kembalian (Rp)</label>
                                    <input type="number" class="form-control bg-light" id="kembalian" name="kembalian"
                                        value="<?= isset($_POST['kembalian']) ? htmlspecialchars($_POST['kembalian']) : '0'; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-bold small text-muted text-uppercase">Status Pembayaran</label>
                                    <select class="form-select border-primary bg-light" id="status" name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Belum Lunas" <?= (isset($_POST['status']) && $_POST['status'] == 'Belum Lunas') ? 'selected' : ''; ?>>
                                            Belum Lunas</option>
                                        <option value="Sudah Lunas" <?= (isset($_POST['status']) && $_POST['status'] == 'Sudah Lunas') ? 'selected' : ''; ?>>
                                            Sudah Lunas</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-light border px-4">Reset</button>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-save me-1"></i> Simpan Transaksi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Empty col-lg-4 space can be used for mini invoice/receipt preview in future -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 border-top border-primary border-4 rounded-3 mt-4 mt-lg-0">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Form</h6>
                        <ul class="text-secondary small mb-0 ps-3">
                            <li class="mb-2">Pastikan NISN siswa dipilih dengan benar.</li>
                            <li class="mb-2">Nominal tagihan SPP disesuaikan dengan angkatan siswa.</li>
                            <li>Tekan tombol "Simpan Transaksi" jika seluruh data sudah dikonfirmasi, karena pembayaran yang salah harus dihapus oleh Administrator.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>