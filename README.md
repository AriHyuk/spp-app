# Sistem Informasi Pembayaran SPP (Sekolah)

![SPP Online Banner](https://via.placeholder.com/1200x400?text=SPP+Online+Application)

Aplikasi **Sistem Informasi Pembayaran SPP (Sumbangan Pembinaan Pendidikan)** berbasis web yang dirancang untuk memudahkan pihak sekolah dalam mengelola data siswa, tagihan, dan rekaman historis transaksi pembayaran SPP. Aplikasi ini dibangun dengan fokus pada **Clean Code, Modularity, dan Security (SQL Injection Prevention)**, menjadikannya standar yang kuat untuk sistem pencatatan level sekolah.

## 🚀 Fitur Utama

- **Autentikasi Aman:** Sistem otentikasi menggunakan algoritma hashing `Bcrypt` untuk keamanan password level industri (Petugas & Admin).
- **CRUD Modular & Tersentralisasi:** Manajemen data Siswa, Petugas, Kelas, dan SPP menggunakan antarmuka terstruktur dengan UI Bootstrap 5 yang seragam dan rapi (Reusable Layouts).
- **Transaksi Pembayaran Intuitif:** Proses validasi relasional (Siswa -> SPP -> Pembayaran) untuk mencegah redudansi data.
- **Keamanan Lapis Database (Prepared Statements):** Semua interaksi database dibangun menggunakan eksekusi _Prepared Statements_ (`mysqli_stmt`) yang sepenuhnya menghilangkan celah _SQL Injection_.
- **Sistem Role-based (Soon):** Skema data sudah mendukung Multi-tier (Admin, Petugas, dan Siswa) untuk kontrol akses masa depan.

## 🛠️ Tech Stack & Architecture

- **Backend:** PHP 8.x (Native / Procedural)
- **Database:** MySQL / MariaDB (via `mysqli` extension)
- **Frontend / UI:** HTML5, CSS3, Bootstrap 5.x, Bootstrap Icons
- **Security:** Bcrypt Hashing, MySQLi Prepared Statements, Cross-Site Scripting (XSS) Prevention via `htmlspecialchars`

## 📐 Prinsip Arsitektur (Software Engineering)

Aplikasi ini telah direfaktor untuk memenuhi standar pengembangan perangkat lunak modern:

1. **Separation of Concerns (SoC):** Pemisahan file konfigurasi (`config/`), layout UI (`includes/`), dan logika modul (`siswa/`, `petugas/`, dll).
2. **Kredensial Environment (dotenv pattern):** Pengamanan koneksi database melalui variabel Environment terpisah (`.env` dan `koneksi.php`).
3. **DRY (Don't Repeat Yourself):** Penggunaan komponen template UI interaktif berulang seperti `header`, `sidebar`, dan `footer`.

> Seluruh keputusan restrukturisasi tercatat transparan pada [Architecture Decision Records (ADR)](docs/).

## ⚙️ Panduan Instalasi (Setup Lengkap)

1. **Clone repositori ini:**

   ```bash
   git clone https://github.com/yourusername/spp-app.git
   cd spp-app
   ```

2. **Setup Database:**
   - Buka phpMyAdmin (atau klien SQL lainnya).
   - Buat database baru dengan nama `db_spp`
   - Import file `db_spp.sql` (jika tersedia di root project) ke dalam database tersebut.

3. **Konfigurasi Environment:**
   - Salin file `.env.example` menjadi `.env`.
   - Sesuaikan konfigurasi database Anda di dalam file `.env`:
     ```env
     DB_HOST=localhost
     DB_USER=root
     DB_PASS=
     DB_NAME=db_spp
     ```

4. **Jalankan Aplikasi:**
   - Pindahkan folder `spp-app` ke kerangka server lokal Anda (seperti `htdocs` untuk XAMPP).
   - Buka browser dan akses: `http://localhost/spp-app`
5. **Login Default Admin:**
   - **Username:** `admin`
   - **Password:** `admin123` (Atau sesuai input seed data pada instalasi awal)

## 📁 Struktur Folder Project

```
spp-app/
├── assets/          # File statis CSS, JS, Gambar (Bootstrap)
├── config/          # Pengaturan database & Environment Loader
├── docs/            # Dokumen ADR (Architecture Decision Records)
├── includes/        # Template reusable (Header, Sidebar, Footer)
├── kelas/           # Modul Manajemen Kelas
├── login/           # Modul Autentikasi
├── pembayaran/      # Modul Inti Transaksi SPP
├── petugas/         # Modul Manajemen Staff / Admin
├── siswa/           # Modul Manajemen Siswa
├── spp/             # Modul Data Tarif SPP
├── index.php        # Dashboard Utama Aplikasi
└── .env.example     # Template Environment Database
```

## 📸 Screenshots (Mockup)

|         Halaman Dashboard         |         Halaman Transaksi         |
| :-------------------------------: | :-------------------------------: |
| _(Silahkan Tambahkan Screenshot)_ | _(Silahkan Tambahkan Screenshot)_ |

---

