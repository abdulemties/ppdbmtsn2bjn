-- Struktur Tabel untuk `tbl_orangtua`
-- Sistem Informasi PPDB Online MTsN 2 Bojonegoro

CREATE TABLE IF NOT EXISTS `tbl_orangtua` (
  `id_orangtua` INT(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` INT(11) NOT NULL UNIQUE COMMENT 'Relasi 1-to-1 ke tbl_siswa',
  
  -- Data Ayah Kandung
  `nama_ayah` VARCHAR(150) NOT NULL,
  `nik_ayah` CHAR(16) DEFAULT NULL,
  `status_ayah` ENUM('Masih Hidup', 'Meninggal Dunia') NOT NULL DEFAULT 'Masih Hidup',
  `pendidikan_ayah` VARCHAR(50) DEFAULT NULL,
  `pekerjaan_ayah` VARCHAR(100) NOT NULL,
  `penghasilan_ayah` VARCHAR(50) NOT NULL COMMENT 'Rentang penghasilan standar EMIS/Kemenag',
  `no_wa_ayah` VARCHAR(20) DEFAULT NULL COMMENT 'Nomor WhatsApp aktif Ayah (format: 08xxx / 628xxx)',
  
  -- Data Ibu Kandung
  `nama_ibu` VARCHAR(150) NOT NULL,
  `nik_ibu` CHAR(16) DEFAULT NULL,
  `status_ibu` ENUM('Masih Hidup', 'Meninggal Dunia') NOT NULL DEFAULT 'Masih Hidup',
  `pendidikan_ibu` VARCHAR(50) DEFAULT NULL,
  `pekerjaan_ibu` VARCHAR(100) NOT NULL,
  `penghasilan_ibu` VARCHAR(50) NOT NULL COMMENT 'Rentang penghasilan standar EMIS/Kemenag',
  `no_wa_ibu` VARCHAR(20) DEFAULT NULL COMMENT 'Nomor WhatsApp aktif Ibu (format: 08xxx / 628xxx)',
  
  -- Data Wali (Opsional jika tinggal bersama wali)
  `nama_wali` VARCHAR(150) DEFAULT NULL,
  `nik_wali` CHAR(16) DEFAULT NULL,
  `hubungan_wali` VARCHAR(50) DEFAULT NULL COMMENT 'Contoh: Kakek, Nenek, Paman, Bibi, Kakak',
  `pekerjaan_wali` VARCHAR(100) DEFAULT NULL,
  `penghasilan_wali` VARCHAR(50) DEFAULT NULL,
  `no_wa_wali` VARCHAR(20) DEFAULT NULL COMMENT 'Nomor WhatsApp aktif Wali',
  
  -- Kontak Utama & Alamat Domisili Keluarga
  `no_wa_utama` VARCHAR(20) NOT NULL COMMENT 'Nomor WhatsApp utama untuk notifikasi otomatis PPDB',
  `alamat_ortu` TEXT NOT NULL,
  `rt_rw` VARCHAR(15) DEFAULT NULL,
  `desa_kelurahan` VARCHAR(100) DEFAULT NULL,
  `kecamatan` VARCHAR(100) DEFAULT NULL,
  `kabupaten_kota` VARCHAR(100) DEFAULT 'Bojonegoro',
  
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_orangtua`),
  CONSTRAINT `fk_orangtua_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `tbl_siswa` (`id_siswa`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
