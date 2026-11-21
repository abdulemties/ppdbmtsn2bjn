-- Struktur Tabel untuk `tbl_berkas`
-- Sistem Informasi PPDB Online MTsN 2 Bojonegoro

CREATE TABLE IF NOT EXISTS `tbl_berkas` (
  `id_berkas` INT(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` INT(11) NOT NULL UNIQUE COMMENT 'Relasi 1-to-1 ke tbl_siswa',
  
  -- Dokumen Wajib Umum
  `file_kk` VARCHAR(255) DEFAULT NULL COMMENT 'Path file scan Kartu Keluarga (.pdf/.jpg/.png)',
  `status_kk` ENUM('Belum Unggah', 'Menunggu Verifikasi', 'Valid', 'Ditolak') NOT NULL DEFAULT 'Belum Unggah',
  
  `file_akta` VARCHAR(255) DEFAULT NULL COMMENT 'Path file scan Akta Kelahiran (.pdf/.jpg/.png)',
  `status_akta` ENUM('Belum Unggah', 'Menunggu Verifikasi', 'Valid', 'Ditolak') NOT NULL DEFAULT 'Belum Unggah',
  
  `file_rapor_skl` VARCHAR(255) DEFAULT NULL COMMENT 'Path file scan Surat Keterangan Aktif / Rapor SD/MI',
  `status_rapor_skl` ENUM('Belum Unggah', 'Menunggu Verifikasi', 'Valid', 'Ditolak') NOT NULL DEFAULT 'Belum Unggah',
  
  `file_pas_foto` VARCHAR(255) DEFAULT NULL COMMENT 'Path file pas foto resmi calon peserta didik',
  `status_pas_foto` ENUM('Belum Unggah', 'Menunggu Verifikasi', 'Valid', 'Ditolak') NOT NULL DEFAULT 'Belum Unggah',
  
  -- Dokumen Khusus Sesuai Jalur Pendaftaran (Opsional/Kondisional)
  `file_prestasi_tahfidz` VARCHAR(255) DEFAULT NULL COMMENT 'Path file Piagam Prestasi atau Sertifikat/Syahadah Tahfidz',
  `status_prestasi_tahfidz` ENUM('Tidak Ada', 'Menunggu Verifikasi', 'Valid', 'Ditolak') NOT NULL DEFAULT 'Tidak Ada',
  
  `file_afirmasi` VARCHAR(255) DEFAULT NULL COMMENT 'Path file KIP / PKH / KKS / SKTM (khusus Jalur Afirmasi)',
  `status_afirmasi` ENUM('Tidak Ada', 'Menunggu Verifikasi', 'Valid', 'Ditolak') NOT NULL DEFAULT 'Tidak Ada',
  
  `catatan_berkas` TEXT DEFAULT NULL COMMENT 'Catatan khusus panitia perihal dokumen yang ditolak/kurang jelas',
  `uploaded_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Waktu terakhir siswa mengunggah/memperbarui berkas',
  `verified_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Waktu terakhir panitia memverifikasi berkas',
  
  PRIMARY KEY (`id_berkas`),
  CONSTRAINT `fk_berkas_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `tbl_siswa` (`id_siswa`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
