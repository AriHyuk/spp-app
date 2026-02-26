<?php

include '../config/koneksi.php';

if (!isset($_GET['id_spp'])) {
    header("Location: index.php");
    exit();
}

$id_spp = trim($_GET['id_spp']);

// Verifikasi data ada
$stmt_get = $conn->prepare("SELECT id_spp FROM tb_spp WHERE id_spp=?");
$stmt_get->bind_param("s", $id_spp);
$stmt_get->execute();
$result = $stmt_get->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit();
}

// Check apakah ada siswa yang menggunakan SPP ini
$stmt_check = $conn->prepare("SELECT COUNT(*) as total FROM tb_siswa WHERE id_spp=? LIMIT 1");
$stmt_check->bind_param("s", $id_spp);
$stmt_check->execute();
$check_siswa = $stmt_check->get_result();
$data_siswa = $check_siswa->fetch_assoc();

if ($data_siswa['total'] > 0) {
    $error_msg = "Tidak bisa menghapus! Ada " . $data_siswa['total'] . " siswa yang masih menggunakan SPP ini";
    header("Location: index.php?error=" . urlencode($error_msg));
    exit();
}

// Hapus SPP
$stmt_delete = $conn->prepare("DELETE FROM tb_spp WHERE id_spp=?");
$stmt_delete->bind_param("s", $id_spp);

if ($stmt_delete->execute()) {
    header("Location: index.php?success=Data SPP berhasil dihapus");
    exit();
} else {
    $error = "Error: Terjadi kesalahan saat menghapus data.";
    header("Location: index.php?error=" . urlencode($error));
    exit();
}
?>