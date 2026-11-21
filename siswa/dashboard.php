<?php
require_once '../config/koneksi.php';

if (!isset($_SESSION['id_siswa'])) {
    header("Location: ../login.php");
    exit;
}

$id_siswa = $_SESSION['id_siswa'];

// Proses Upload Berkas
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_berkas'])) {
    $allowedExt = ['pdf', 'jpg', 'jpeg', 'png'];
    $maxSize = 2 * 1024 * 1024; // 2 MB

    $berkasMap = [
        'file_kk'               => ['folder' => 'kk',     'status_col' => 'status_kk'],
        'file_akta'             => ['folder' => 'akta',   'status_col' => 'status_akta'],
        'file_rapor_skl'        => ['folder' => 'rapor',  'status_col' => 'status_rapor_skl'],
        'file_pas_foto'         => ['folder' => 'foto',   'status_col' => 'status_pas_foto'],
        'file_prestasi_tahfidz' => ['folder' => 'khusus', 'status_col' => 'status_prestasi_tahfidz']
    ];

    foreach ($berkasMap as $inputName => $meta) {
        if (!empty($_FILES[$inputName]['name'])) {
            $fileName = $_FILES[$inputName]['name'];
            $fileSize = $_FILES[$inputName]['size'];
            $tmpName  = $_FILES[$inputName]['tmp_name'];
            $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($ext, $allowedExt) && $fileSize <= $maxSize) {
                $newFileName = $_SESSION['no_pendaftaran'] . '_' . $inputName . '_' . time() . '.' . $ext;
                $destPath = "../uploads/{$meta['folder']}/" . $newFileName;
                $dbPath   = "uploads/{$meta['folder']}/" . $newFileName;

                if (move_uploaded_file($tmpName, $destPath)) {
                    $sqlUpd = "UPDATE tbl_berkas SET {$inputName} = ?, {$meta['status_col']} = 'Menunggu Verifikasi', uploaded_at = NOW() WHERE id_siswa = ?";
                    $pdo->prepare($sqlUpd)->execute([$dbPath, $id_siswa]);
                    $msg = '<div class="alert alert-success">Berkas berhasil diunggah dan menunggu verifikasi panitia.</div>';
                }
            } else {
                $msg = '<div class="alert alert-danger">Gagal unggah! Pastikan format PDF/JPG/PNG dan ukuran maksimal 2 MB.</div>';
            }
        }
    }
}

// Ambil data lengkap siswa + jalur + berkas
$stmt = $pdo->prepare("SELECT s.*, j.nama_jalur, b.* 
                       FROM tbl_siswa s 
                       JOIN tbl_jalur j ON s.id_jalur = j.id_jalur 
                       LEFT JOIN tbl_berkas b ON s.id_siswa = b.id_siswa 
                       WHERE s.id_siswa = ?");
$stmt->execute([$id_siswa]);
$data = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - PPDB MTsN 2 Bojonegoro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-success">
    <div class="container">
        <span class="navbar-brand fw-bold">Panel Siswa PPDB</span>
        <a href="../logout.php" class="btn btn-outline-light btn-sm">Keluar</a>
    </div>
</nav>

<div class="container my-4">
    <?= $msg ?>
    <div class="row g-4">
        <!-- Ringkasan Status -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Status Pendaftaran Anda</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr><td class="text-muted">No. Pendaftaran</td><td class="fw-bold"><?= htmlspecialchars($data['no_pendaftaran']) ?></td></tr>
                        <tr><td class="text-muted">Nama Lengkap</td><td><?= htmlspecialchars($data['nama_lengkap']) ?></td></tr>
                        <tr><td class="text-muted">NISN</td><td><?= htmlspecialchars($data['nisn']) ?></td></tr>
                        <tr><td class="text-muted">Jalur Pilihan</td><td><span class="badge bg-info text-dark"><?= htmlspecialchars($data['nama_jalur']) ?></span></td></tr>
                        <tr><td class="text-muted">Verifikasi Berkas</td><td><span class="badge bg-warning text-dark"><?= htmlspecialchars($data['status_verifikasi']) ?></span></td></tr>
                        <tr><td class="text-muted">Hasil Seleksi</td><td><span class="badge bg-primary"><?= htmlspecialchars($data['status_kelulusan']) ?></span></td></tr>
                    </table>
                    <?php if (!empty($data['catatan_verifikasi'])): ?>
                        <div class="alert alert-warning small mb-0">
                            <strong>Catatan Panitia:</strong> <?= htmlspecialchars($data['catatan_verifikasi']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Form Unggah Berkas -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Unggah Berkas Persyaratan (PDF/JPG/PNG, Maks. 2MB)</div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Scan Kartu Keluarga (KK) - <span class="badge bg-secondary"><?= $data['status_kk'] ?></span></label>
                            <input type="file" name="file_kk" class="form-control form-control-sm">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Scan Akta Kelahiran - <span class="badge bg-secondary"><?= $data['status_akta'] ?></span></label>
                            <input type="file" name="file_akta" class="form-control form-control-sm">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Scan Rapor / Surat Keterangan Aktif SD/MI - <span class="badge bg-secondary"><?= $data['status_rapor_skl'] ?></span></label>
                            <input type="file" name="file_rapor_skl" class="form-control form-control-sm">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pas Foto Resmi (3x4) - <span class="badge bg-secondary"><?= $data['status_pas_foto'] ?></span></label>
                            <input type="file" name="file_pas_foto" class="form-control form-control-sm">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Piagam Prestasi / Sertifikat Tahfidz (Opsional/Sesuai Jalur) - <span class="badge bg-secondary"><?= $data['status_prestasi_tahfidz'] ?></span></label>
                            <input type="file" name="file_prestasi_tahfidz" class="form-control form-control-sm">
                        </div>
                        <button type="submit" name="upload_berkas" class="btn btn-success fw-bold">Simpan & Unggah Berkas</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
