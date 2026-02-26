<?php
session_start();
include '../config/koneksi.php';

// Cek apakah ada parameter id_pembayaran
if (!isset($_GET['id_pembayaran'])) {
    header("Location: index.php");
    exit();
}

$id_pembayaran = trim($_GET['id_pembayaran']);

// Ambil data pembayaran untuk verifikasi
$stmt_get = $conn->prepare("SELECT id_pembayaran FROM tb_pembayaran WHERE id_pembayaran=?");
$stmt_get->bind_param("s", $id_pembayaran);
$stmt_get->execute();
$result = $stmt_get->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit();
}

// Hapus data pembayaran
$stmt_delete = $conn->prepare("DELETE FROM tb_pembayaran WHERE id_pembayaran=?");
$stmt_delete->bind_param("s", $id_pembayaran);

if ($stmt_delete->execute()) {
    // Redirect dengan pesan sukses
    header("Location: index.php?success=Data pembayaran berhasil dihapus");
    exit();
} else {
    // Redirect dengan pesan error yang detail
    $error = "Error: Terjadi kesalahan saat menghapus data.";
    header("Location: index.php?error=" . urlencode($error));
    exit();
}
?>