<?php
include '../config/koneksi.php';

$error = '';
$success = '';

// --- 1. AMBIL DATA UNTUK DROPDOWN ---
$kelasResult = mysqli_query($conn, "SELECT * FROM tb_kelas ORDER BY nama_kelas ASC");
$sppResult = mysqli_query($conn, "SELECT * FROM tb_spp ORDER BY tahun DESC");

$kelasList = [];
$sppList = [];

while ($row = mysqli_fetch_assoc($kelasResult)) {
    $kelasList[] = $row;
}
while ($row = mysqli_fetch_assoc($sppResult)) {
    $sppList[] = $row;
}

// --- 2. PROSES SIMPAN ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nisn = trim($_POST['nisn']);
    $nis = trim($_POST['nis']);
    $nama = trim($_POST['nama']);
    $id_kelas = trim($_POST['id_kelas']); // Ini ID (Angka 1, 2, dll)
    $alamat = trim($_POST['alamat'] ?? '');
    $no_telp = trim($_POST['no_telp'] ?? '');
    $id_spp = trim($_POST['id_spp']);

    if (empty($nisn) || empty($nis) || empty($nama) || empty($id_kelas) || empty($id_spp)) {
        $error = '⚠️ Semua kolom wajib harus diisi!';
    } else {
        // Cek NISN Duplikat menggunakan Prepared Statement
        $stmt_cek = $conn->prepare("SELECT nisn FROM tb_siswa WHERE nisn=?");
        $stmt_cek->bind_param("s", $nisn);
        $stmt_cek->execute();
        $cek = $stmt_cek->get_result();
        
        if ($cek->num_rows > 0) {
            $error = '❌ NISN sudah terdaftar!';
        } else {
            // Kita cari nama kelas berdasarkan ID yang dipilih
            $stmt_kelas = $conn->prepare("SELECT nama_kelas FROM tb_kelas WHERE id_kelas=?");
            $stmt_kelas->bind_param("i", $id_kelas);
            $stmt_kelas->execute();
            $qCariKelas = $stmt_kelas->get_result();
            
            if ($qCariKelas->num_rows > 0) {
                $dataKelas = $qCariKelas->fetch_assoc();
                $nama_kelas_txt = $dataKelas['nama_kelas'];
                
                // --- INSERT KE DATABASE DENGAN PREPARED STATEMENT ---
                $stmt_insert = $conn->prepare("INSERT INTO tb_siswa (nisn, nis, nama, id_kelas, nama_kelas, alamat, no_telp, id_spp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt_insert->bind_param("sssissii", $nisn, $nis, $nama, $id_kelas, $nama_kelas_txt, $alamat, $no_telp, $id_spp);
                
                if ($stmt_insert->execute()) {
                    $success = "✅ Berhasil! Siswa masuk ke kelas: <b>$nama_kelas_txt</b>";
                    // Reset Form
                    $nisn = $nis = $nama = $id_kelas = $alamat = $no_telp = $id_spp = "";
                    $_POST = array();
                } else {
                    $error = '❌ Gagal Simpan DB: Terjadi kesalahan saat menyimpan data.';
                }
            } else {
                $error = "❌ Error: ID Kelas tidak valid!";
            }
        }
    }
}

$page_title = 'Tambah Siswa';
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
                        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-person-plus-fill me-2"></i>Tambah Data Siswa</h5>
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
                                    <input type="number" class="form-control bg-light" name="nisn" required
                                        value="<?= isset($_POST['nisn']) ? htmlspecialchars($_POST['nisn']) : ''; ?>" placeholder="Masukkan 10 digit NISN">
                                    <small class="text-secondary">NISN akan digunakan sebagai ID tagihan.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">NIS</label>
                                    <input type="number" class="form-control" name="nis" required
                                        value="<?= isset($_POST['nis']) ? htmlspecialchars($_POST['nis']) : ''; ?>" placeholder="Nomor Induk Siswa">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" required
                                    value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>" placeholder="Nama lengkap siswa">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Kelas</label>
                                <select class="form-select" name="id_kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach ($kelasList as $k): ?>
                                    <option value="<?= $k['id_kelas']; ?>"
                                        <?= (isset($_POST['id_kelas']) && $_POST['id_kelas'] == $k['id_kelas']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($k['nama_kelas']); ?> (<?= htmlspecialchars($k['komp_keahlian']); ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Alamat</label>
                                <textarea class="form-control" name="alamat" rows="2" placeholder="Alamat tempat tinggal"><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">No. Telepon</label>
                                <input type="text" class="form-control" name="no_telp"
                                    value="<?= isset($_POST['no_telp']) ? htmlspecialchars($_POST['no_telp']) : ''; ?>" placeholder="Contoh: 08123456789">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted text-uppercase">Tahun SPP / Tagihan Bulanan</label>
                                <select class="form-select bg-light border-primary" name="id_spp" required>
                                    <option value="">-- Pilih SPP --</option>
                                    <?php foreach ($sppList as $s): ?>
                                    <option value="<?= $s['id_spp']; ?>"
                                        <?= (isset($_POST['id_spp']) && $_POST['id_spp'] == $s['id_spp']) ? 'selected' : ''; ?>>
                                        Tahun <?= $s['tahun']; ?> - Rp <?= number_format($s['nominal'], 0, ',', '.'); ?>/Bulan
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-secondary">Pilih tarif SPP yang akan dikenakan ke siswa ini.</small>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="index.php" class="btn btn-light px-4 border">Batal</a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="bi bi-save me-1"></i> Simpan Siswa Baru</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>