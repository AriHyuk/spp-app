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

$id_petugas = $_GET['id_petugas'];

// Verifikasi data ada
$stmt_cek = $conn->prepare("SELECT id_petugas FROM tb_petugas WHERE id_petugas=? LIMIT 1");
$stmt_cek->bind_param("i", $id_petugas);
$stmt_cek->execute();
$result = $stmt_cek->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit();
}

// Hapus petugas
$stmt_del = $conn->prepare("DELETE FROM tb_petugas WHERE id_petugas=? LIMIT 1");
$stmt_del->bind_param("i", $id_petugas);

if ($stmt_del->execute()) {
    header("Location: index.php?success=Petugas berhasil dihapus!");
    exit();
} else {
    $error = "Error: Terjadi kesalahan saat menghapus data.";
    header("Location: index.php?error=" . urlencode($error));
    exit();
}
?>
