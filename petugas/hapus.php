<?php
session_start();
include '../config/koneksi.php';

// Check login
if (!isset($_SESSION['id_petugas'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id_petugas'])) {
    header("Location: index.php");
    exit();
}

$id_petugas = mysqli_real_escape_string($conn, $_GET['id_petugas']);

// Verifikasi data ada
$result = mysqli_query($conn, "SELECT * FROM tb_petugas WHERE id_petugas='$id_petugas' LIMIT 1");
if (mysqli_num_rows($result) == 0) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit();
}

// Hapus petugas
$query = "DELETE FROM tb_petugas WHERE id_petugas='$id_petugas' LIMIT 1";

if (mysqli_query($conn, $query)) {
    header("Location: index.php?success=Petugas berhasil dihapus!");
    exit();
} else {
    $error = "Error: " . mysqli_error($conn);
    header("Location: index.php?error=" . urlencode($error));
    exit();
}
?>
