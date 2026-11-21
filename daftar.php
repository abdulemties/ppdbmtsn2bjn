<?php
require_once 'config/koneksi.php';

if ($setting['status_pendaftaran'] !== 'Buka') {
    die("<script>alert('Maaf, pendaftaran saat ini sedang ditutup.'); window.location='index.php';</script>");
}

$jalurList = $pdo->query("SELECT * FROM tbl_jalur WHERE status_aktif = 'Aktif'")->fetchAll();
$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn         = trim($_POST['nisn']);
    $nik          = trim($_POST['nik']);
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $asal_sekolah = trim($_POST['asal_sekolah']);
    $nilai_rapor  = (float) $_POST['nilai_rapor'];
    $id_jalur     = (int) $_POST['id_jalur'];
    $no_wa_utama  = trim($_POST['no_wa_utama']);
    $password_raw = $_POST['password'];

    // Validasi sederhana
    if (strlen($nisn) !== 10 || strlen($nik) !== 16) {
        $pesan = '<div class="alert alert-danger">NISN harus 10 digit dan NIK harus 16 digit!</div>';
    } else {
        try {
            $pdo->beginTransaction();

            $password_hash = password_hash($password_raw, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO tbl_siswa (nisn, nik, nama_lengkap, asal_sekolah, nilai_rapor, id_jalur, password_akun) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nisn, $nik, $nama_lengkap, $asal_sekolah, $nilai_rapor, $id_jalur, $password_hash]);
            $id_siswa_baru = $pdo->lastInsertId();

            // Buat entri awal di tbl_orangtua dan tbl_berkas agar relasi siap dilengkapi
            $stmtOrtu = $pdo->prepare("INSERT INTO tbl_orangtua (id_siswa, nama_ayah, pekerjaan_ayah, penghasilan_ayah, nama_ibu, pekerjaan_ibu, penghasilan_ibu, no_wa_utama, alamat_ortu) 
                                       VALUES (?, '-', '-', '-', '-', '-', '-', ?, '-')");
            $stmtOrtu->execute([$id_siswa_baru, $no_wa_utama]);

            $stmtBerkas = $pdo->prepare("INSERT INTO tbl_berkas (id_siswa) VALUES (?)");
            $stmtBerkas->execute([$id_siswa_baru]);

            // Ambil nomor pendaftaran yang baru dibuat oleh trigger
            $stmtNo = $pdo->prepare("SELECT no_pendaftaran FROM tbl_siswa WHERE id_siswa = ?");
            $stmtNo->execute([$id_siswa_baru]);
            $no_daftar = $stmtNo->fetchColumn();

            $pdo->commit();
            $pesan = "<div class='alert alert-success'>
                        <strong>Pendaftaran Berhasil!</strong><br>
                        Nomor Pendaftaran Anda: <span class='badge bg-dark fs-6'>$no_daftar</span><br>
                        Silakan simpan nomor ini dan <a href='login.php' class='alert-link'>Login ke Panel Siswa</a> untuk melengkapi biodata orang tua & mengunggah berkas.
                      </div>";
        } catch (PDOException $e) {
            $pdo->rollBack();
            if ($e->getCode() == 23000) {
                $pesan = '<div class="alert alert-danger">NISN atau NIK sudah terdaftar sebelumnya!</div>';
            } else {
                $pesan = '<div class="alert alert-danger">Terjadi kesalahan: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran - PPDB MTsN 2 Bojonegoro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5" style="max-width: 650px;">
    <div class="card border-0 shadow">
        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0 fw-bold">Formulir Registrasi Akun PPDB</h5>
            <small><?= htmlspecialchars($setting['nama_madrasah']) ?> - TA <?= htmlspecialchars($setting['tahun_ajaran']) ?></small>
        </div>
        <div class="card-body p-4">
            <?= $pesan ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jalur Pendaftaran</label>
                    <select name="id_jalur" class="form-select" required>
                        <option value="">-- Pilih Jalur Pendaftaran --</option>
                        <?php foreach ($jalurList as $j): ?>
                            <option value="<?= $j['id_jalur'] ?>"><?= htmlspecialchars($j['nama_jalur']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">NISN (10 Digit)</label>
                        <input type="text" name="nisn" maxlength="10" pattern="\d{10}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">NIK Siswa (16 Digit)</label>
                        <input type="text" name="nik" maxlength="16" pattern="\d{16}" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap (Sesuai Ijazah/Akta)</label>
                    <input type="text" name="nama_lengkap" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-semibold">Asal Sekolah (SD/MI)</label>
                        <input type="text" name="asal_sekolah" placeholder="Contoh: MIN 1 Bojonegoro" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Rata-rata Rapor</label>
                        <input type="number" step="0.01" min="0" max="100" name="nilai_rapor" placeholder="85.50" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor WhatsApp Aktif (Orang Tua/Siswa)</label>
                    <input type="text" name="no_wa_utama" placeholder="081234567890" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Buat Password Akun</label>
                    <input type="password" name="password" minlength="6" class="form-control" required>
                    <div class="form-text">Minimal 6 karakter, digunakan untuk login ke Panel Siswa.</div>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-outline-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success fw-bold px-4">Daftar & Buat Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
