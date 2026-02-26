<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['id_petugas'])) {
    header("Location: ../login.php");
    exit();
}

$tgl_awal = $_GET['tgl_awal'] ?? '';
$tgl_akhir = $_GET['tgl_akhir'] ?? '';

$query = "SELECT 
            tb_pembayaran.id_pembayaran,
            tb_pembayaran.tgl_bayar,
            tb_pembayaran.nisn,
            tb_pembayaran.jumlah_bayar, 
            tb_siswa.nama, 
            tb_siswa.nis, 
            tb_kelas.nama_kelas 
          FROM tb_pembayaran 
          LEFT JOIN tb_siswa ON tb_pembayaran.nisn = tb_siswa.nisn 
          LEFT JOIN tb_kelas ON tb_siswa.id_kelas = tb_kelas.id_kelas
          WHERE tb_pembayaran.tgl_bayar BETWEEN ? AND ?
          ORDER BY tb_pembayaran.tgl_bayar ASC";

$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("ss", $tgl_awal, $tgl_akhir);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran SPP</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <style>
    @media print {
        .no-print {
            display: none;
        }

        body {
            -webkit-print-color-adjust: exact;
        }
    }

    body {
        font-family: 'Times New Roman', sans-serif;
        font-size: 11pt;
    }

    .header-laporan {
        text-align: center;
        border-bottom: 2px solid black;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .table th {
        background-color: #f8f9fa !important;
        vertical-align: middle;
        text-align: center;
    }

    .table td {
        vertical-align: middle;
        padding: 5px;
    }
    </style>
</head>

<body class="p-4">

    <div class="header-laporan">
        <h3 class="fw-bold">SMK UNGGULAN PAMULANG</h3>
        <p>Jl. Pendidikan No. 123, Kota Coding | Telp: (021) 555-999</p>
        <h5 class="fw-bold text-decoration-underline mt-3">LAPORAN PEMBAYARAN SPP</h5>
    </div>

    <div class="row mb-3">
        <div class="col-6">
            Periode: <?= date('d/m/Y', strtotime($tgl_awal)); ?> s.d. <?= date('d/m/Y', strtotime($tgl_akhir)); ?>
        </div>
        <div class="col-6 text-end">
            Petugas: <?= $_SESSION['nama_petugas']; ?>
        </div>
    </div>

    <table class="table table-bordered border-dark w-100">
        <thead class="table-light border-dark">
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th>NISN / NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th width="20%">Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $grand_total = 0;

            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) { 
                    $grand_total += $row['jumlah_bayar'];
            ?>
            <tr>
                <td class="text-center"><?= $no++; ?></td>
                <td class="text-center"><?= date('d/m/Y', strtotime($row['tgl_bayar'])); ?></td>
                <td>
                    <?= $row['nisn']; ?> <br>
                    <small class="text-muted">NIS: <?= $row['nis']; ?></small>
                </td>
                <td><?= strtoupper($row['nama']); ?></td>

                <td class="text-center">
                    <?php 
                        // Cek jika kelas kosong
                        if(!empty($row['nama_kelas'])) {
                            echo $row['nama_kelas']; 
                        } else {
                            echo "<span class='text-danger fst-italic'>Tanpa Kelas</span>";
                        }
                        ?>
                </td>

                <td class="text-end fw-bold">
                    Rp <?= number_format($row['jumlah_bayar'], 0, ',', '.'); ?>
                </td>
            </tr>
            <?php 
                }
            } else { 
            ?>
            <tr>
                <td colspan="6" class="text-center py-4 fst-italic text-danger">Tidak ada data transaksi.</td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot class="bg-light fw-bold border-dark">
            <tr>
                <td colspan="5" class="text-end pe-3">TOTAL PEMASUKAN</td>
                <td class="text-end">Rp <?= number_format($grand_total, 0, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="row mt-5">
        <div class="col-4 offset-8 text-center">
            <p>Jakarta, <?= date('d F Y'); ?></p>
            <br><br><br>
            <p class="fw-bold text-decoration-underline"><?= $_SESSION['nama_petugas']; ?></p>
        </div>
    </div>

    <script>
    window.onload = function() {
        window.print();
    }
    </script>
</body>

</html>