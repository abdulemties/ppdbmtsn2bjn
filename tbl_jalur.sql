-- Struktur Tabel untuk `tbl_jalur`
-- Sistem Informasi PPDB Online MTsN 2 Bojonegoro

CREATE TABLE IF NOT EXISTS `tbl_jalur` (
  `id_jalur` INT(11) NOT NULL AUTO_INCREMENT,
  `kode_jalur` VARCHAR(20) NOT NULL UNIQUE COMMENT 'Kode singkat untuk prefix nomor pendaftaran, misal: PRS, THF, AFR, REG',
  `nama_jalur` VARCHAR(100) NOT NULL,
  `kuota_jalur` INT(11) NOT NULL DEFAULT 0 COMMENT 'Alokasi kuota per jalur (total akumulasi 288 siswa)',
  `persyaratan_khusus` TEXT DEFAULT NULL COMMENT 'Daftar berkas/syarat khusus jalur tersebut',
  `deskripsi` TEXT DEFAULT NULL,
  `status_aktif` ENUM('Aktif', 'Nonaktif') NOT NULL DEFAULT 'Aktif',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jalur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data Awal (Default Seeder) Jalur Pendaftaran Khas Madrasah
-- Total pembagian kuota diset ke 288 sesuai pengaturan awal MTsN 2 Bojonegoro
INSERT INTO `tbl_jalur` (
  `id_jalur`, 
  `kode_jalur`, 
  `nama_jalur`, 
  `kuota_jalur`, 
  `persyaratan_khusus`, 
  `deskripsi`, 
  `status_aktif`
) VALUES
(
  1, 
  'PRS', 
  'Jalur Prestasi Akademik/Non-Akademik', 
  60, 
  'Piagam/Sertifikat kejuaraan minimal tingkat Kabupaten/Kota (OSN, KSM, PORSENI, O2SN, dll.) atau Surat Keterangan Peringkat 1-3 paralel di SD/MI.', 
  'Jalur penerimaan khusus bagi calon peserta didik yang memiliki prestasi unggul di bidang akademik maupun non-akademik (olahraga, seni, keagamaan).', 
  'Aktif'
),
(
  2, 
  'THF', 
  'Jalur Tahfidz Al-Qur''an', 
  36, 
  'Sertifikat/Syahadah hafalan dari lembaga/TPQ/Madrasah (minimal hafal 1-3 Juz) dan mengikuti tes sambung ayat/hafalan.', 
  'Jalur apresiasi bagi calon peserta didik yang memiliki kemampuan menghafal Al-Qur''an.', 
  'Aktif'
),
(
  3, 
  'AFR', 
  'Jalur Afirmasi', 
  48, 
  'Kartu Indonesia Pintar (KIP), Program Keluarga Harapan (PKH), Kartu Keluarga Sejahtera (KKS), atau Surat Keterangan Tidak Mampu (SKTM) resmi.', 
  'Jalur penerimaan yang diperuntukkan bagi calon peserta didik dari keluarga kurang mampu secara ekonomi atau penyandang disabilitas.', 
  'Aktif'
),
(
  4, 
  'REG', 
  'Jalur Reguler', 
  144, 
  'Rapor SD/MI kelas 4, 5, dan 6 (semester 1) serta mengikuti tes seleksi akademik dasar dan Baca Tulis Al-Qur''an (BTQ).', 
  'Jalur penerimaan umum bagi lulusan SD/MI melalui seleksi nilai rapor dan tes potensi akademik serta keagamaan.', 
  'Aktif'
);
