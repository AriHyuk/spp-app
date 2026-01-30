<?php
include '../config/koneksi.php';

$error = '';
$success = '';

// --- 1. CEK URL & AMBIL DATA SISWA YANG AKAN DIEDIT ---
if (!isset($_GET['nisn'])) {
    header("Location: index.php"); // Redirect jika tidak ada NISN di URL
    exit();
}

$nisn_target = mysqli_real_escape_string($conn, $_GET['nisn']);
$queryGetSiswa = mysqli_query($conn, "SELECT * FROM tb_siswa WHERE nisn='$nisn_target'");

if (mysqli_num_rows($queryGetSiswa) == 0) {
    header("Location: index.php"); // Redirect jika siswa tidak ditemukan
    exit();
}

// Data siswa lama disimpan di variabel $dataSiswa
$dataSiswa = mysqli_fetch_assoc($queryGetSiswa);


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
    $nisn = mysqli_real_escape_string($conn, trim($_POST['nisn']));
    $nis = mysqli_real_escape_string($conn, trim($_POST['nis']));
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $id_kelas = mysqli_real_escape_string($conn, trim($_POST['id_kelas'])); // ID Kelas baru
    $alamat = mysqli_real_escape_string($conn, trim($_POST['alamat'] ?? ''));
    $no_telp = mysqli_real_escape_string($conn, trim($_POST['no_telp'] ?? ''));
    $id_spp = mysqli_real_escape_string($conn, trim($_POST['id_spp']));

    if (empty($nis) || empty($nama) || empty($id_kelas) || empty($id_spp)) {
        $error = '⚠️ Semua kolom wajib harus diisi!';
    } else {
        // --- PENTING: Cari Nama Kelas baru berdasarkan ID yang dipilih ---
        $qCariKelas = mysqli_query($conn, "SELECT nama_kelas FROM tb_kelas WHERE id_kelas='$id_kelas'");
        
        if (mysqli_num_rows($qCariKelas) > 0) {
            $dataKelasBaru = mysqli_fetch_assoc($qCariKelas);
            $nama_kelas_baru_txt = mysqli_real_escape_string($conn, $dataKelasBaru['nama_kelas']);

            // --- QUERY UPDATE ---
            // Kita update ID_KELAS dan juga NAMA_KELAS-nya
            $query = "UPDATE tb_siswa SET 
                        nis = '$nis',
                        nama = '$nama',
                        id_kelas = '$id_kelas',
                        nama_kelas = '$nama_kelas_baru_txt',
                        alamat = '$alamat',
                        no_telp = '$no_telp',
                        id_spp = '$id_spp'
                      WHERE nisn = '$nisn'";
            
            if (mysqli_query($conn, $query)) {
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
                $error = '❌ Gagal Update DB: ' . mysqli_error($conn);
            }
        } else {
             $error = "❌ Error: ID Kelas ($id_kelas) tidak ditemukan!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
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
                        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i>Edit Data Siswa
                        </h5>
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
                                    <input type="number" class="form-control bg-light" name="nisn" readonly
                                        value="<?= $dataSiswa['nisn']; ?>">
                                    <small class="text-muted">NISN tidak dapat diubah.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">NIS</label>
                                    <input type="number" class="form-control" name="nis" required
                                        value="<?= $dataSiswa['nis']; ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" required
                                    value="<?= $dataSiswa['nama']; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Kelas</label>
                                <select class="form-select" name="id_kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach ($kelasList as $k): ?>
                                    <option value="<?= $k['id_kelas']; ?>"
                                        <?= ($dataSiswa['id_kelas'] == $k['id_kelas']) ? 'selected' : ''; ?>>
                                        <?= $k['nama_kelas']; ?> (<?= $k['komp_keahlian']; ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat</label>
                                <textarea class="form-control" name="alamat"
                                    rows="2"><?= $dataSiswa['alamat']; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">No. Telepon</label>
                                <input type="text" class="form-control" name="no_telp"
                                    value="<?= $dataSiswa['no_telp']; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tahun SPP / Nominal</label>
                                <select class="form-select" name="id_spp" required>
                                    <option value="">-- Pilih SPP --</option>
                                    <?php foreach ($sppList as $s): ?>
                                    <option value="<?= $s['id_spp']; ?>"
                                        <?= ($dataSiswa['id_spp'] == $s['id_spp']) ? 'selected' : ''; ?>>
                                        Tahun <?= $s['tahun']; ?> - Rp <?= number_format($s['nominal'], 0, ',', '.'); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="index.php" class="btn btn-secondary px-4">Batal</a>
                                <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
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