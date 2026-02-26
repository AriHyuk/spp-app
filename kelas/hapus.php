<?php
include '../config/koneksi.php';

if (!isset($_GET['id_kelas'])) {
    header("Location: index.php");
    exit();
}

$id_kelas = trim($_GET['id_kelas']);

// Verifikasi data ada
$stmt_get = $conn->prepare("SELECT id_kelas FROM tb_kelas WHERE id_kelas=?");
$stmt_get->bind_param("s", $id_kelas);
$stmt_get->execute();
$result = $stmt_get->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit();
}

// Check apakah ada siswa yang menggunakan kelas ini (Relasi sebenarnya pada id_kelas)
$stmt_check = $conn->prepare("SELECT COUNT(*) as total FROM tb_siswa WHERE id_kelas=? LIMIT 1");
$stmt_check->bind_param("s", $id_kelas);
$stmt_check->execute();
$check_siswa = $stmt_check->get_result();
$data_siswa = $check_siswa->fetch_assoc();

if ($data_siswa['total'] > 0) {
    $error_msg = "Tidak bisa menghapus! Ada " . $data_siswa['total'] . " siswa di kelas ini";
    header("Location: index.php?error=" . urlencode($error_msg));
    exit();
}

// Hapus kelas
$stmt_delete = $conn->prepare("DELETE FROM tb_kelas WHERE id_kelas=?");
$stmt_delete->bind_param("s", $id_kelas);

if ($stmt_delete->execute()) {
    header("Location: index.php?success=Data kelas berhasil dihapus");
    exit();
} else {
    $error = "Error: Terjadi kesalahan saat menghapus data.";
    header("Location: index.php?error=" . urlencode($error));
    exit();
}
?>
