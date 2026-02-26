<?php
session_start();
include '../config/koneksi.php';

if (!isset($_GET['nisn'])) {
    header("Location: index.php");
    exit();
}

$nisn = $_GET['nisn'];

// Verifikasi data ada
$stmt = $conn->prepare("SELECT nisn FROM tb_siswa WHERE nisn=?");
$stmt->bind_param("s", $nisn);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit();
}

// Check apakah ada pembayaran untuk siswa ini
$stmt_check = $conn->prepare("SELECT COUNT(*) as total FROM tb_pembayaran WHERE nisn=? LIMIT 1");
$stmt_check->bind_param("s", $nisn);
$stmt_check->execute();
$check_pembayaran = $stmt_check->get_result();
$data_pembayaran = $check_pembayaran->fetch_assoc();

if ($data_pembayaran['total'] > 0) {
    $error_msg = "Tidak bisa menghapus! Ada " . $data_pembayaran['total'] . " data pembayaran untuk siswa ini";
    header("Location: index.php?error=" . urlencode($error_msg));
    exit();
}

// Hapus siswa
$stmt_delete = $conn->prepare("DELETE FROM tb_siswa WHERE nisn=?");
$stmt_delete->bind_param("s", $nisn);

if ($stmt_delete->execute()) {
    header("Location: index.php?success=Data siswa berhasil dihapus");
    exit();
} else {
    $error = "Error: Terjadi kesalahan saat menghapus data.";
    header("Location: index.php?error=" . urlencode($error));
    exit();
}
?>
?>
