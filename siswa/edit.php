<?php
include '../config/koneksi.php';

$error = '';
$success = '';

// --- 1. CEK URL & AMBIL DATA SISWA YANG AKAN DIEDIT ---
if (!isset($_GET['nisn'])) {
    header("Location: index.php"); // Redirect jika tidak ada NISN di URL
    exit();
}

$nisn_target = trim($_GET['nisn']);

// Prepared statement untuk query SELECT
$stmt_get = $conn->prepare("SELECT * FROM tb_siswa WHERE nisn=?");
$stmt_get->bind_param("s", $nisn_target);
$stmt_get->execute();
$queryGetSiswa = $stmt_get->get_result();

if ($queryGetSiswa->num_rows == 0) {
    header("Location: index.php"); // Redirect jika siswa tidak ditemukan
    exit();
}

// Data siswa lama disimpan di variabel $dataSiswa
$dataSiswa = $queryGetSiswa->fetch_assoc();


// --- 2. AMBIL DATA UNTUK DROPDOWN (Sama seperti tambah.php) ---
$kelasResult = mysqli_query($conn, "SELECT * FROM tb_kelas ORDER BY nama_kelas ASC");
$sppResult = mysqli_query($conn, "SELECT * FROM tb_spp ORDER BY tahun DESC");

$kelasList = [];
$sppList = [];

while ($row = mysqli_fetch_assoc($kelasResult)) { $kelasList[] = $row; }
while ($row = mysqli_fetch_assoc($sppResult)) { $sppList[] = $row; }


// --- 3. PROSES UPDATE DATA (Saat tombol Simpan ditekan) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // NISN diambil dari input readonly (tidak diubah)
    $nisn = trim($_POST['nisn']);
    $nis = trim($_POST['nis']);
    $nama = trim($_POST['nama']);
    $id_kelas = trim($_POST['id_kelas']); // ID Kelas baru
    $alamat = trim($_POST['alamat'] ?? '');
    $no_telp = trim($_POST['no_telp'] ?? '');
    $id_spp = trim($_POST['id_spp']);

    if (empty($nis) || empty($nama) || empty($id_kelas) || empty($id_spp)) {
        $error = '⚠️ Semua kolom wajib harus diisi!';
    } else {
        // --- PENTING: Cari Nama Kelas baru berdasarkan ID yang dipilih ---
        $stmt_kelas = $conn->prepare("SELECT nama_kelas FROM tb_kelas WHERE id_kelas=?");
        $stmt_kelas->bind_param("i", $id_kelas);
        $stmt_kelas->execute();
        $qCariKelas = $stmt_kelas->get_result();
        
        if ($qCariKelas->num_rows > 0) {
            $dataKelasBaru = $qCariKelas->fetch_assoc();
            $nama_kelas_baru_txt = $dataKelasBaru['nama_kelas'];

            // --- QUERY UPDATE MENGGUNAKAN PREPARED STATEMENT ---
            $stmt_update = $conn->prepare("UPDATE tb_siswa SET nis=?, nama=?, id_kelas=?, nama_kelas=?, alamat=?, no_telp=?, id_spp=? WHERE nisn=?");
            $stmt_update->bind_param("ssisssis", $nis, $nama, $id_kelas, $nama_kelas_baru_txt, $alamat, $no_telp, $id_spp, $nisn);
            
            if ($stmt_update->execute()) {
                $success = "✅ Data siswa berhasil diperbarui!";
                // Refresh data siswa agar form menampilkan data terbaru
                $dataSiswa['nis'] = $nis;
                $dataSiswa['nama'] = $nama;
                $dataSiswa['id_kelas'] = $id_kelas;
                $dataSiswa['alamat'] = $alamat;
                $dataSiswa['no_telp'] = $no_telp;
                $dataSiswa['id_spp'] = $id_spp;
                
                // Opsional: Redirect otomatis setelah 2 detik
                echo "<meta http-equiv='refresh' content='2;url=index.php'>";
            } else {
                $error = '❌ Gagal Update DB: Terjadi kesalahan saat menyimpan data.';
            }
        } else {
             $error = "❌ Error: ID Kelas tidak valid!";
        }
    }
}

$page_title = 'Edit Siswa';
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
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i>Edit Data Siswa</h5>
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
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">NISN</label>
                                    <input type="number" class="form-control bg-light" name="nisn" readonly
                                        value="<?= htmlspecialchars($dataSiswa['nisn']); ?>">
                                    <small class="text-secondary">NISN tidak dapat diubah.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">NIS</label>
                                    <input type="number" class="form-control" name="nis" required
                                        value="<?= htmlspecialchars($dataSiswa['nis']); ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" required
                                    value="<?= htmlspecialchars($dataSiswa['nama']); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Kelas</label>
                                <select class="form-select" name="id_kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach ($kelasList as $k): ?>
                                    <option value="<?= $k['id_kelas']; ?>"
                                        <?= ($dataSiswa['id_kelas'] == $k['id_kelas']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($k['nama_kelas']); ?> (<?= htmlspecialchars($k['komp_keahlian']); ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Alamat</label>
                                <textarea class="form-control" name="alamat" rows="2"><?= htmlspecialchars($dataSiswa['alamat']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">No. Telepon</label>
                                <input type="text" class="form-control" name="no_telp"
                                    value="<?= htmlspecialchars($dataSiswa['no_telp']); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Tahun SPP / Nominal</label>
                                <select class="form-select border-primary bg-light" name="id_spp" required>
                                    <option value="">-- Pilih SPP --</option>
                                    <?php foreach ($sppList as $s): ?>
                                    <option value="<?= $s['id_spp']; ?>"
                                        <?= ($dataSiswa['id_spp'] == $s['id_spp']) ? 'selected' : ''; ?>>
                                        Tahun <?= $s['tahun']; ?> - Rp <?= number_format($s['nominal'], 0, ',', '.'); ?>/Bulan
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="index.php" class="btn btn-light px-4 border">Kembali</a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>