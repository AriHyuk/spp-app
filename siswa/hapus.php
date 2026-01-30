<?php
session_start();
include '../config/koneksi.php';

if (!isset($_GET['nisn'])) {
    header("Location: index.php");
    exit();
}

$nisn = mysqli_real_escape_string($conn, $_GET['nisn']);

// Verifikasi data ada
$result = mysqli_query($conn, "SELECT * FROM tb_siswa WHERE nisn='$nisn'");
if (mysqli_num_rows($result) == 0) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit();
}

// Check apakah ada pembayaran untuk siswa ini
$check_pembayaran = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_pembayaran WHERE nisn='$nisn' LIMIT 1");
$data_pembayaran = mysqli_fetch_assoc($check_pembayaran);

if ($data_pembayaran['total'] > 0) {
    $error_msg = "Tidak bisa menghapus! Ada " . $data_pembayaran['total'] . " data pembayaran untuk siswa ini";
    header("Location: index.php?error=" . urlencode($error_msg));
    exit();
}

// Hapus siswa
$query = "DELETE FROM tb_siswa WHERE nisn='$nisn'";

if (mysqli_query($conn, $query)) {
    header("Location: index.php?success=Data siswa berhasil dihapus");
    exit();
} else {
    $error = "Error: " . mysqli_error($conn);
    header("Location: index.php?error=" . urlencode($error));
    exit();
}
?>
?>
