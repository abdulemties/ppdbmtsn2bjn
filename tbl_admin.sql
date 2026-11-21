-- Struktur Tabel untuk `tbl_admin`
-- Sistem Informasi PPDB Online MTsN 2 Bojonegoro

CREATE TABLE IF NOT EXISTS `tbl_admin` (
  `id_admin` INT(11) NOT NULL AUTO_INCREMENT,
  `nip_nik` VARCHAR(25) DEFAULT NULL COMMENT 'NIP ASN/PPPK atau NIK Non-ASN panitia (opsional)',
  `nama_lengkap` VARCHAR(150) NOT NULL,
  `jabatan_panitia` VARCHAR(100) DEFAULT NULL COMMENT 'Contoh: Ketua Panitia, Sekretaris, Operator EMIS, Koordinator BTQ',
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_akun` VARCHAR(255) NOT NULL COMMENT 'Hash password (bcrypt/argon2)',
  `role` ENUM('Super Admin', 'Verifikator Berkas', 'Penguji BTQ/Wawancara') NOT NULL DEFAULT 'Verifikator Berkas',
  `no_wa` VARCHAR(20) DEFAULT NULL COMMENT 'Nomor WhatsApp aktif panitia',
  `status_aktif` ENUM('Aktif', 'Nonaktif') NOT NULL DEFAULT 'Aktif',
  `last_login` TIMESTAMP NULL DEFAULT NULL COMMENT 'Waktu terakhir login ke sistem',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data Awal (Default Seeder) Akun Panitia PPDB
-- Catatan: Nilai password di bawah adalah hash bcrypt standar untuk 'admin123' (segera ganti setelah login pertama)
INSERT INTO `tbl_admin` (
  `id_admin`,
  `nip_nik`,
  `nama_lengkap`,
  `jabatan_panitia`,
  `username`,
  `password_akun`,
  `role`,
  `no_wa`,
  `status_aktif`
) VALUES
(
  1,
  NULL,
  'Administrator Utama PPDB',
  'Operator Utama & Koordinator Sistem',
  'superadmin',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'Super Admin',
  '081234567890',
  'Aktif'
),
(
  2,
  NULL,
  'Panitia Verifikator Berkas',
  'Tim Verifikasi Administrasi',
  'verifikator1',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'Verifikator Berkas',
  '081234567891',
  'Aktif'
),
(
  3,
  NULL,
  'Tim Penguji BTQ & Wawancara',
  'Koordinator Tes Keagamaan',
  'penguji1',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'Penguji BTQ/Wawancara',
  '081234567892',
  'Aktif'
);
