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
    $nisn = mysqli_real_escape_string($conn, trim($_POST['nisn']));
    $nis = mysqli_real_escape_string($conn, trim($_POST['nis']));
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $id_kelas = mysqli_real_escape_string($conn, trim($_POST['id_kelas'])); // Ini ID (Angka 1, 2, dll)
    $alamat = mysqli_real_escape_string($conn, trim($_POST['alamat'] ?? ''));
    $no_telp = mysqli_real_escape_string($conn, trim($_POST['no_telp'] ?? ''));
    $id_spp = mysqli_real_escape_string($conn, trim($_POST['id_spp']));

    if (empty($nisn) || empty($nis) || empty($nama) || empty($id_kelas) || empty($id_spp)) {
        $error = '⚠️ Semua kolom wajib harus diisi!';
    } else {
        // Cek NISN Duplikat
        $cek = mysqli_query($conn, "SELECT nisn FROM tb_siswa WHERE nisn='$nisn'");
        if (mysqli_num_rows($cek) > 0) {
            $error = '❌ NISN sudah terdaftar!';
        } else {
            
            // --- BAGIAN PENTING (DEBUGGING) ---
            // Kita cari nama kelas berdasarkan ID yang dipilih
            $qCariKelas = mysqli_query($conn, "SELECT nama_kelas FROM tb_kelas WHERE id_kelas='$id_kelas'");
            
            // Cek apakah ID Kelas ditemukan di database?
            if (mysqli_num_rows($qCariKelas) > 0) {
                $dataKelas = mysqli_fetch_assoc($qCariKelas);
                $nama_kelas_txt = mysqli_real_escape_string($conn, $dataKelas['nama_kelas']); // Contoh: "XII RPL 1"
                
                // --- INSERT KE DATABASE ---
                // Perhatikan urutan kolom harus sesuai dengan database kamu
                $query = "INSERT INTO tb_siswa (nisn, nis, nama, id_kelas, nama_kelas, alamat, no_telp, id_spp) 
                          VALUES ('$nisn', '$nis', '$nama', '$id_kelas', '$nama_kelas_txt', '$alamat', '$no_telp', '$id_spp')";
                
                if (mysqli_query($conn, $query)) {
                    $success = "✅ Berhasil! Siswa masuk ke kelas: <b>$nama_kelas_txt</b>";
                    // Reset Form
                    $nisn = $nis = $nama = $id_kelas = $alamat = $no_telp = $id_spp = "";
                    $_POST = array();
                } else {
                    $error = '❌ Gagal Simpan DB: ' . mysqli_error($conn);
                }
            } else {
                // Jika ID Kelas tidak ditemukan (Aneh, tapi bisa terjadi)
                $error = "❌ Error: ID Kelas ($id_kelas) tidak ditemukan di database tb_kelas!";
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
    <title>Tambah Siswa</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">APP SPP</a>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-person-plus-fill me-2"></i>Tambah Data
                            Siswa</h5>
                    </div>
                    <div class="card-body p-4">

                        <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $error; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= $success; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">NISN</label>
                                    <input type="number" class="form-control" name="nisn" required
                                        value="<?= isset($_POST['nisn']) ? $_POST['nisn'] : ''; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">NIS</label>
                                    <input type="number" class="form-control" name="nis" required
                                        value="<?= isset($_POST['nis']) ? $_POST['nis'] : ''; ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" required
                                    value="<?= isset($_POST['nama']) ? $_POST['nama'] : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Kelas</label>
                                <select class="form-select" name="id_kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach ($kelasList as $k): ?>
                                    <option value="<?= $k['id_kelas']; ?>"
                                        <?= (isset($_POST['id_kelas']) && $_POST['id_kelas'] == $k['id_kelas']) ? 'selected' : ''; ?>>
                                        <?= $k['nama_kelas']; ?> (<?= $k['komp_keahlian']; ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat</label>
                                <textarea class="form-control" name="alamat"
                                    rows="2"><?= isset($_POST['alamat']) ? $_POST['alamat'] : ''; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">No. Telepon</label>
                                <input type="text" class="form-control" name="no_telp"
                                    value="<?= isset($_POST['no_telp']) ? $_POST['no_telp'] : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tahun SPP / Nominal</label>
                                <select class="form-select" name="id_spp" required>
                                    <option value="">-- Pilih SPP --</option>
                                    <?php foreach ($sppList as $s): ?>
                                    <option value="<?= $s['id_spp']; ?>"
                                        <?= (isset($_POST['id_spp']) && $_POST['id_spp'] == $s['id_spp']) ? 'selected' : ''; ?>>
                                        Tahun <?= $s['tahun']; ?> - Rp <?= number_format($s['nominal'], 0, ',', '.'); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="../index.php" class="btn btn-secondary px-4">Batal</a>
                                <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
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