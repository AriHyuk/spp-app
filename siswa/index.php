<?php
include '../config/koneksi.php';

$error = '';
$success = '';

if (isset($_GET['success'])) {
    $success = $_GET['success'];
}
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}

$result = mysqli_query($conn, "SELECT * FROM tb_siswa");
$page_title = 'Data Siswa';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<main class="main-content">
    <div class="d-md-none mb-4">
        <button class="btn btn-primary" id="sidebarToggle">
            <i class="bi bi-list"></i> Menu
        </button>
    </div>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark">Data Siswa</h3>
            <a href="tambah.php" class="btn btn-success shadow-sm"><i class="bi bi-plus-lg me-1"></i> Tambah Siswa</a>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i> <?= $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i> <?= $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="py-3">NISN</th>
                                <th class="py-3">NIS</th>
                                <th class="py-3">Nama Lengkap</th>
                                <th class="py-3">Kelas</th>
                                <th class="py-3">No. Telp</th>
                                <th class="px-4 py-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="px-4"><?= $no++; ?></td>
                                <td><span class="badge bg-secondary"><?= $row['nisn']; ?></span></td>
                                <td><?= $row['nis']; ?></td>
                                <td><span class="fw-semibold text-dark"><?= $row['nama']; ?></span></td>
                                <td><?= $row['nama_kelas']; ?></td>
                                <td><?= $row['no_telp']; ?></td>
                                <td class="px-4 text-end">
                                    <a href="edit.php?nisn=<?= $row['nisn']; ?>" class="btn btn-warning btn-sm shadow-sm" title="Edit Data"><i class="bi bi-pencil"></i></a>
                                    <a href="hapus.php?nisn=<?= $row['nisn']; ?>" onclick="return confirm('Yakin ingin hapus data siswa ini?')" class="btn btn-danger btn-sm shadow-sm" title="Hapus Data"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if(mysqli_num_rows($result) == 0): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data siswa.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>