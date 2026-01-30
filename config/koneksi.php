<?php
class Database {
    // Properti (Variabel dalam class)
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db   = "pembayaran";
    public $koneksi;

    // Constructor: Dijalankan otomatis saat class dipanggil
    public function __construct() {
        // Menggunakan gaya OOP: new mysqli()
        $this->koneksi = new mysqli($this->host, $this->user, $this->pass, $this->db);

        // Cek koneksi (menggunakan properti connect_error bawaan object mysqli)
        if ($this->koneksi->connect_error) {
            die("Koneksi gagal: " . $this->koneksi->connect_error);
        }
    }
}

// 1. Membuat Object baru dari class Database
$database = new Database();

// 2. Mengambil koneksi agar bisa dipakai di file lain
// Kita simpan ke variabel $conn supaya file login/index kamu TIDAK PERLU diubah
$conn = $database->koneksi; 
?>