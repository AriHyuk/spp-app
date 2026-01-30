<?php
include '../config/koneksi.php';

if (!isset($_GET['id_kelas'])) {
    header("Location: index.php");
    exit();
}

$id_kelas = mysqli_real_escape_string($conn, $_GET['id_kelas']);

// Verifikasi data ada
$result = mysqli_query($conn, "SELECT * FROM tb_kelas WHERE id_kelas='$id_kelas'");
if (mysqli_num_rows($result) == 0) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit();
}

// Check apakah ada siswa yang menggunakan kelas ini
$check_siswa = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_siswa WHERE nama_kelas='$id_kelas' LIMIT 1");
$data_siswa = mysqli_fetch_assoc($check_siswa);

if ($data_siswa['total'] > 0) {
    $error_msg = "Tidak bisa menghapus! Ada " . $data_siswa['total'] . " siswa di kelas ini";
    header("Location: index.php?error=" . urlencode($error_msg));
    exit();
}

// Hapus kelas
$query = "DELETE FROM tb_kelas WHERE id_kelas='$id_kelas'";

if (mysqli_query($conn, $query)) {
    header("Location: index.php?success=Data kelas berhasil dihapus");
    exit();
} else {
    $error = "Error: " . mysqli_error($conn);
    header("Location: index.php?error=" . urlencode($error));
    exit();
}
?>
?>
