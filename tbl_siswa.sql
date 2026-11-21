-- Struktur Tabel untuk `tbl_siswa`
-- Sistem Informasi PPDB Online MTsN 2 Bojonegoro

CREATE TABLE IF NOT EXISTS `tbl_siswa` (
  `id_siswa` INT(11) NOT NULL AUTO_INCREMENT,
  `no_pendaftaran` VARCHAR(25) NOT NULL UNIQUE COMMENT 'Format otomatis via trigger: PPDB27-0001',
  `nisn` CHAR(10) NOT NULL UNIQUE COMMENT '10 digit Nomor Induk Siswa Nasional',
  `nik` CHAR(16) NOT NULL UNIQUE COMMENT '16 digit Nomor Induk Kependudukan',
  `nama_lengkap` VARCHAR(150) NOT NULL,
  `tempat_lahir` VARCHAR(100) DEFAULT NULL,
  `tanggal_lahir` DATE DEFAULT NULL,
  `jenis_kelamin` ENUM('L', 'P') DEFAULT NULL,
  `asal_sekolah` VARCHAR(150) NOT NULL COMMENT 'Nama SD/MI asal',
  `npsn_asal` VARCHAR(15) DEFAULT NULL,
  `nilai_rapor` DECIMAL(5,2) NOT NULL DEFAULT 0.00 COMMENT 'Rata-rata nilai rapor SD/MI (skala 0.00 - 100.00)',
  `nilai_tes` DECIMAL(5,2) NOT NULL DEFAULT 0.00 COMMENT 'Nilai tes BTQ / Wawancara / Akademik',
  `id_jalur` INT(11) NOT NULL COMMENT 'Relasi ke tbl_jalur',
  `password_akun` VARCHAR(255) NOT NULL COMMENT 'Hash password (bcrypt/argon2)',
  `status_verifikasi` ENUM('Menunggu Verifikasi', 'Berkas Lengkap', 'Perlu Perbaikan') NOT NULL DEFAULT 'Menunggu Verifikasi',
  `catatan_verifikasi` TEXT DEFAULT NULL COMMENT 'Catatan revisi dari panitia jika status Perlu Perbaikan',
  `status_kelulusan` ENUM('Belum Diseleksi', 'Diterima', 'Cadangan', 'Tidak Diterima') NOT NULL DEFAULT 'Belum Diseleksi',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_siswa`),
  KEY `idx_id_jalur` (`id_jalur`),
  CONSTRAINT `fk_siswa_jalur` FOREIGN KEY (`id_jalur`) REFERENCES `tbl_jalur` (`id_jalur`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Trigger Otomatis untuk Membuat `no_pendaftaran` (Format: PPDB27-0001)
DELIMITER $$

DROP TRIGGER IF EXISTS `trg_generate_no_pendaftaran`$$
CREATE TRIGGER `trg_generate_no_pendaftaran`
BEFORE INSERT ON `tbl_siswa`
FOR EACH ROW
BEGIN
  DECLARE next_id INT;
  DECLARE tahun_prefix VARCHAR(4);

  -- Ambil 2 digit awal tahun ajaran dari tbl_pengaturan (misal '2027/2028' -> '27'), default '27'
  SELECT IFNULL(SUBSTRING(tahun_ajaran, 3, 2), '27') INTO tahun_prefix
  FROM `tbl_pengaturan`
  WHERE `id_pengaturan` = 1
  LIMIT 1;

  IF tahun_prefix IS NULL OR tahun_prefix = '' THEN
    SET tahun_prefix = '27';
  END IF;

  -- Ambil nilai AUTO_INCREMENT berikutnya dari tabel tbl_siswa
  SELECT AUTO_INCREMENT INTO next_id
  FROM information_schema.TABLES
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'tbl_siswa';

  IF next_id IS NULL THEN
    SET next_id = 1;
  END IF;

  -- Set nomor pendaftaran otomatis jika belum diisi manual
  IF NEW.no_pendaftaran IS NULL OR NEW.no_pendaftaran = '' THEN
    SET NEW.no_pendaftaran = CONCAT('PPDB', tahun_prefix, '-', LPAD(next_id, 4, '0'));
  END IF;
END$$

DELIMITER ;
