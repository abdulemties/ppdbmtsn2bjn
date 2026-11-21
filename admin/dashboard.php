<?php
require_once '../config/koneksi.php';

if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role_admin'];

// Aksi cepat verifikasi atau kelulusan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi_cepat'])) {
    $id_siswa = (int) $_POST['id_siswa'];
    if ($role === 'Super Admin' || $role === 'Verifikator Berkas') {
        $status_verif = $_POST['status_verifikasi'];
        $catatan = trim($_POST['catatan_verifikasi']);
        $pdo->prepare("UPDATE tbl_siswa SET status_verifikasi = ?, catatan_verifikasi = ? WHERE id_siswa = ?")
            ->execute([$status_verif, $catatan, $id_siswa]);
    }
    if ($role === 'Super Admin' || $role === 'Penguji BTQ/Wawancara') {
        $nilai_tes = (float) $_POST['nilai_tes'];
        $pdo->prepare("UPDATE tbl_siswa SET nilai_tes = ? WHERE id_siswa = ?")
            ->execute([$nilai_tes, $id_siswa]);
    }
    if ($role === 'Super Admin' && isset($_POST['status_kelulusan'])) {
        $status_lulus = $_POST['status_kelulusan'];
        $pdo->prepare("UPDATE tbl_siswa SET status_kelulusan = ? WHERE id_siswa = ?")
            ->execute([$status_lulus, $id_siswa]);
    }
    header("Location: dashboard.php?updated=1");
    exit;
}

$pendaftar = $pdo->query("SELECT s.*, j.nama_jalur, o.no_wa_utama 
                          FROM tbl_siswa s 
                          JOIN tbl_jalur j ON s.id_jalur = j.id_jalur 
                          LEFT JOIN tbl_orangtua o ON s.id_siswa = o.id_siswa 
                          ORDER BY s.id_siswa DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Panitia - PPDB MTsN 2 Bojonegoro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid px-4">
        <span class="navbar-brand fw-bold">Admin PPDB | <span class="badge bg-success"><?= htmlspecialchars($role) ?></span></span>
        <div class="d-flex align-items-center gap-3 text-white">
            <small>Halo, <?= htmlspecialchars($_SESSION['nama_admin']) ?></small>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container-fluid px-4 my-4">
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success py-2">Data pendaftar berhasil diperbarui!</div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
            <span>Data Calon Peserta Didik Masuk</span>
            <span class="badge bg-primary">Total: <?= count($pendaftar) ?> Pendaftar</span>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle small">
                <thead class="table-light">
                    <tr>
                        <th>No. Daftar</th>
                        <th>Nama / NISN</th>
                        <th>Asal Sekolah</th>
                        <th>Jalur</th>
                        <th>Rapor</th>
                        <th>Nilai Tes</th>
                        <th>Status Verifikasi</th>
                        <th>Kelulusan</th>
                        <th>Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendaftar as $p): ?>
                    <tr>
                        <form method="POST">
                            <input type="hidden" name="id_siswa" value="<?= $p['id_siswa'] ?>">
                            <td class="fw-bold"><?= htmlspecialchars($p['no_pendaftaran']) ?></td>
                            <td>
                                <strong><?= htmlspecialchars($p['nama_lengkap']) ?></strong><br>
                                <span class="text-muted">NISN: <?= htmlspecialchars($p['nisn']) ?></span>
                            </td>
                            <td><?= htmlspecialchars($p['asal_sekolah']) ?></td>
                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($p['nama_jalur']) ?></span></td>
                            <td><?= $p['nilai_rapor'] ?></td>
                            <td style="width: 100px;">
                                <input type="number" step="0.01" name="nilai_tes" value="<?= $p['nilai_tes'] ?>" class="form-control form-control-sm" <?= ($role === 'Verifikator Berkas') ? 'readonly' : '' ?>>
                            </td>
                            <td>
                                <select name="status_verifikasi" class="form-select form-select-sm mb-1" <?= ($role === 'Penguji BTQ/Wawancara') ? 'disabled' : '' ?>>
                                    <?php foreach (['Menunggu Verifikasi', 'Berkas Lengkap', 'Perlu Perbaikan'] as $st): ?>
                                        <option value="<?= $st ?>" <?= $p['status_verifikasi'] === $st ? 'selected' : '' ?>><?= $st ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" name="catatan_verifikasi" value="<?= htmlspecialchars($p['catatan_verifikasi'] ?? '') ?>" placeholder="Catatan revisi..." class="form-control form-control-sm">
                            </td>
                            <td>
                                <?php if ($role === 'Super Admin'): ?>
                                    <select name="status_kelulusan" class="form-select form-select-sm">
                                        <?php foreach (['Belum Diseleksi', 'Diterima', 'Cadangan', 'Tidak Diterima'] as $kl): ?>
                                            <option value="<?= $kl ?>" <?= $p['status_kelulusan'] === $kl ? 'selected' : '' ?>><?= $kl ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($p['status_kelulusan']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="submit" name="aksi_cepat" class="btn btn-success btn-sm">Simpan</button>
                            </td>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
