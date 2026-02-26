<?php
// Load environment variables / config
require_once __DIR__ . '/env.php';

class Database {
    // Properti (Variabel dalam class) menggunakan konstanta dari env.php
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $db   = DB_NAME;
    public $koneksi;

    // Constructor: Dijalankan otomatis saat class dipanggil
    public function __construct() {
        // Menggunakan gaya OOP: new mysqli()
        $this->koneksi = new mysqli($this->host, $this->user, $this->pass, $this->db);

        // Cek koneksi
        if ($this->koneksi->connect_error) {
            // Sebaiknya jangan echo error asli di production, tapi untuk sekarang kita simpan log
            error_log("Connection failed: " . $this->koneksi->connect_error);
            die("Platform kami sedang mengalami gangguan koneksi. Silahkan coba lagi nanti.");
        }
        
        // Atur charset agar mendukung karakter khusus
        $this->koneksi->set_charset("utf8mb4");
    }
}

// 1. Membuat Object baru dari class Database
$database = new Database();

// 2. Mengambil koneksi agar bisa dipakai di file lain
$conn = $database->koneksi; 
?>