# Kerangka Sistem PPDB Online MTsN 2 Bojonegoro

## Struktur Folder
- `config/koneksi.php` : Koneksi database MySQL menggunakan PDO (aman dari SQL Injection) & pemuat pengaturan madrasah.
- `index.php` : Halaman depan portal informasi PPDB, statistik sisa kuota (288 siswa), dan daftar jalur masuk.
- `daftar.php` : Formulir pendaftaran akun calon siswa baru (otomatis menghasilkan nomor `PPDB27-0001`).
- `login.php` : Halaman login siswa menggunakan NISN dan password.
- `siswa/dashboard.php` : Panel siswa untuk memantau status verifikasi, hasil kelulusan, dan unggah berkas persyaratan.
- `admin/login.php` : Halaman login panitia PPDB.
- `admin/dashboard.php` : Panel manajemen panitia dengan pembagian hak akses (`Super Admin`, `Verifikator Berkas`, `Penguji BTQ/Wawancara`).
- `uploads/` : Direktori penyimpanan file unggahan siswa (KK, Akta, Rapor, Pas Foto, Sertifikat Prestasi/Tahfidz).

## Langkah Instalasi Lokal (Laragon / XAMPP)
1. Buat database baru di phpMyAdmin dengan nama **`db_ppdb_mtsn2bjn`**.
2. Impor 6 file SQL yang sudah dibuat secara berurutan:
   - `tbl_pengaturan.sql`
   - `tbl_jalur.sql`
   - `tbl_siswa.sql`
   - `tbl_orangtua.sql`
   - `tbl_berkas.sql`
   - `tbl_admin.sql`
3. Ekstrak folder `ppdb_mtsn2_bojonegoro` ini ke dalam direktori `htdocs` (XAMPP) atau `www` (Laragon).
4. Akses melalui browser di `http://localhost/ppdb_mtsn2_bojonegoro/`.
5. Login Admin bawaan: username `superadmin`, password `admin123`.
