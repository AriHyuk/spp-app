## ADR-001: Refactoring ke Clean, Modular, & Secure Architecture

**Status:** Accepted

**Konteks:**
Aplikasi SPP pada awalnya memiliki kerentanan keamanan signifikan (rawan terhadap SQL Injection), logika bisnis yang tercampur dengan presentasi UI (spaghetti code), dan file konfigurasi hardcode seperti password database langsung terpapar pada skrip utama. Kondisi ini membuat sistem sulit di-maintenance dan tidak siap untuk standardisasi level _production_.

**Stack & Infra saat ini:**

- Stack: PHP Native (Procedural), MySQLi, HTML/CSS Bootstrap 5
- Infra: Local Server (XAMPP/Apache)

**Keputusan:**
Kami mengimplementasikan tiga pendekatan arsitektural utama:

1. **Lapisan Keamanan (Database & Auth):** Migrasi seluruh operasi CRUD dari String Interpolation (`$query = "SELECT * FROM ... $id"`) menjadi **Prepared Statements** (`$stmt->bind_param(...)`). Seluruh password kini wajib di-hash menggunakan algoritma modern (`Bcrypt` via `password_hash`).
2. **Modular Layouts & DRY (Don't Repeat Yourself):** Seluruh elemen yang berulang seperti _Navigation Bar_, _Sidebar_, dan _Footer HTML_ telah diekstrak ke dalam folder independen `includes/`. File terpisah dipanggil di setiap rute menggunakan `include`.
3. **Pemisahan Konfigurasi Lingkungan (Environment-based Config):** Credentials database tidak lagi berada dalam `koneksi.php` secara hardcode, melainkan dimuat dari parser Environment Variable sederhana (terinspirasi dari library bawaan DotEnv).

**Alternatif yang dipertimbangkan:**

- _Opsi A: Menggunakan ORM Pihak Ketiga (seperti Eloquent) atau Framework (seperti Laravel)._
  - Alasan Penolakan: Mengubah base teknologi dari prosedural murni menjadi Framework modern merupakan perubahan terlalu invasif (rewrite total). Objektif utama kami adalah meningkatkan standar kode secara gradual dengan _cost of refactor_ yang minim pada stack yang _existing_.
- _Opsi B: Hanya menggunakan `mysqli_real_escape_string`._
  - Alasan Penolakan: Kurang _future-proof_ karena human-error masih sering terjadi saat menyusun query kompleks. Prepared statement jauh lebih absolut menolak tipe payload SQL injeksi di level driver.

**Konsekuensi:**

- ✅ Keamanan meningkat drastis: Terhindar dari SQL Injection. Auth lebih robust karena penggunaan _Bcrypt_.
- ✅ Kerapian & konsistensi presentasi: Karena modular, modifikasi layout di 1 file akan tercermin pada halaman Manajemen Siswa, Kelas, Petugas, dan Pembayaran sekaligus.
- ⚠️ Kurva kompleksitas bertambah sedikit: Penggunaan Prepared Statement mengubah bentuk cara kita mengeksekusi parameter query di MySQLi (harus bind-param dulu sebelum memproses results). Hal ini memaksa _developer_ selanjutnya yang masuk proyek ini harus familiar dengan konsep _Binding Variables_.

**Review date:** Segera setelah fitur _Advanced Role-Based Access Control_ (Siswa/Admin/Petugas login logic) diperdalam.
