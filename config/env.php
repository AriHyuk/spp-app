<?php
// config/env.php

// Mencegah akses langsung ke file ini dari browser
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

// Konfigurasi Database Utama
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pembayaran');

// Konfigurasi Aplikasi Tambahan (jika diperlukan)
define('APP_NAME', 'SPP Online Universitas Pamulang');
define('APP_URL', 'http://localhost/spp-app'); // Sesuaikan dengan URL project

// Set error reporting untuk environment (Dev: E_ALL, Prod: 0)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
