-- Struktur Tabel untuk `tbl_pengaturan`
-- Sistem Informasi PPDB Online MTsN 2 Bojonegoro

CREATE TABLE IF NOT EXISTS `tbl_pengaturan` (
  `id_pengaturan` INT(11) NOT NULL AUTO_INCREMENT,
  `tahun_ajaran` VARCHAR(20) NOT NULL COMMENT 'Contoh: 2027/2028',
  `status_pendaftaran` ENUM('Buka', 'Tutup') NOT NULL DEFAULT 'Buka',
  `kuota_total` INT(11) NOT NULL DEFAULT 288,
  `nama_instansi_atas` VARCHAR(150) NOT NULL DEFAULT 'KEMENTERIAN AGAMA REPUBLIK INDONESIA',
  `nama_kantor_kab` VARCHAR(150) NOT NULL DEFAULT 'KANTOR KEMENTERIAN AGAMA KABUPATEN BOJONEGORO',
  `nama_madrasah` VARCHAR(150) NOT NULL DEFAULT 'MADRASAH TSANAWIYAH NEGERI 2 BOJONEGORO',
  `alamat_kop` TEXT NOT NULL,
  `telepon_kop` VARCHAR(50) DEFAULT NULL,
  `email_kop` VARCHAR(100) DEFAULT NULL,
  `website_kop` VARCHAR(100) DEFAULT NULL,
  `logo_kiri` VARCHAR(255) DEFAULT 'logo_kemenag.png' COMMENT 'Path file logo Kemenag di kop surat',
  `logo_kanan` VARCHAR(255) DEFAULT 'logo_mtsn2.png' COMMENT 'Path file logo Madrasah di kop surat',
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pengaturan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data Awal (Default Seeder) untuk `tbl_pengaturan`
INSERT INTO `tbl_pengaturan` (
  `id_pengaturan`, 
  `tahun_ajaran`, 
  `status_pendaftaran`, 
  `kuota_total`, 
  `nama_instansi_atas`, 
  `nama_kantor_kab`, 
  `nama_madrasah`, 
  `alamat_kop`, 
  `telepon_kop`, 
  `email_kop`, 
  `website_kop`, 
  `logo_kiri`, 
  `logo_kanan`
) VALUES (
  1, 
  '2027/2028', 
  'Buka', 
  288, 
  'KEMENTERIAN AGAMA REPUBLIK INDONESIA', 
  'KANTOR KEMENTERIAN AGAMA KABUPATEN BOJONEGORO', 
  'MADRASAH TSANAWIYAH NEGERI 2 BOJONEGORO', 
  'Jl. Raya Sumberrejo No. 123, Kecamatan Sumberrejo, Kabupaten Bojonegoro, Jawa Timur 62191', 
  '(0353) 331xxx', 
  'mtsn2bojonegoro@kemenag.go.id', 
  'https://mtsn2bojonegoro.sch.id', 
  'logo_kemenag.png', 
  'logo_mtsn2.png'
);
