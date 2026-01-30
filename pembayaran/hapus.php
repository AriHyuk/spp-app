<?php
session_start();
include '../config/koneksi.php';

// Cek apakah ada parameter id_pembayaran
if (!isset($_GET['id_pembayaran'])) {
    header("Location: index.php");
    exit();
}

$id_pembayaran = mysqli_real_escape_string($conn, $_GET['id_pembayaran']);

// Ambil data pembayaran untuk verifikasi
$query = "SELECT * FROM tb_pembayaran WHERE id_pembayaran='$id_pembayaran'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit();
}

// Hapus data pembayaran
$delete_query = "DELETE FROM tb_pembayaran WHERE id_pembayaran='$id_pembayaran'";

if (mysqli_query($conn, $delete_query)) {
    // Redirect dengan pesan sukses
    header("Location: index.php?success=Data pembayaran berhasil dihapus");
    exit();
} else {
    // Redirect dengan pesan error yang detail
    $error = "Error: " . mysqli_error($conn);
    header("Location: index.php?error=" . urlencode($error));
    exit();
}
?>