<?php

include '../config/koneksi.php';

if (!isset($_GET['id_spp'])) {
    header("Location: index.php");
    exit();
}

$id_spp = mysqli_real_escape_string($conn, $_GET['id_spp']);

// Verifikasi data ada
$result = mysqli_query($conn, "SELECT * FROM tb_spp WHERE id_spp='$id_spp'");
if (mysqli_num_rows($result) == 0) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit();
}

// Check apakah ada siswa yang menggunakan SPP ini
$check_siswa = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_siswa WHERE id_spp='$id_spp' LIMIT 1");
$data_siswa = mysqli_fetch_assoc($check_siswa);

if ($data_siswa['total'] > 0) {
    $error_msg = "Tidak bisa menghapus! Ada " . $data_siswa['total'] . " siswa yang masih menggunakan SPP ini";
    header("Location: index.php?error=" . urlencode($error_msg));
    exit();
}

// Hapus SPP
$query = "DELETE FROM tb_spp WHERE id_spp='$id_spp'";

if (mysqli_query($conn, $query)) {
    header("Location: index.php?success=Data SPP berhasil dihapus");
    exit();
} else {
    $error = "Error: " . mysqli_error($conn);
    header("Location: index.php?error=" . urlencode($error));
    exit();
}
?>
?>