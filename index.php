<?php
require_once 'config/koneksi.php';

// Ambil daftar jalur aktif beserta jumlah pendaftar per jalur
$sqlJalur = "SELECT j.*, 
             (SELECT COUNT(*) FROM tbl_siswa s WHERE s.id_jalur = j.id_jalur) AS total_pendaftar
             FROM tbl_jalur j WHERE j.status_aktif = 'Aktif'";
$jalurList = $pdo->query($sqlJalur)->fetchAll();

// Hitung total pendaftar keseluruhan
$totalPendaftar = $pdo->query("SELECT COUNT(*) FROM tbl_siswa")->fetchColumn();
$kuotaTotal = $setting['kuota_total'] ?? 288;
$sisaKuota = max(0, $kuotaTotal - $totalPendaftar);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Online - <?= htmlspecialchars($setting['nama_madrasah']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section { background: linear-gradient(135deg, #0f5132 0%, #198754 100%); color: white; padding: 60px 0; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">PPDB MTsN 2 BOJONEGORO</a>
        <div class="d-flex gap-2">
            <a href="login.php" class="btn btn-outline-light btn-sm">Login Siswa</a>
            <a href="admin/login.php" class="btn btn-warning btn-sm fw-semibold">Login Panitia</a>
        </div>
    </div>
</nav>

<section class="hero-section text-center">
    <div class="container">
        <span class="badge bg-warning text-dark mb-2">Tahun Ajaran <?= htmlspecialchars($setting['tahun_ajaran']) ?></span>
        <h1 class="fw-bold"><?= htmlspecialchars($setting['nama_madrasah']) ?></h1>
        <p class="lead mb-4">Portal Penerimaan Peserta Didik Baru (PPDB) Terpadu</p>
        
        <?php if ($setting['status_pendaftaran'] === 'Buka'): ?>
            <a href="daftar.php" class="btn btn-light btn-lg text-success fw-bold px-4 shadow">Daftar Sekarang</a>
        <?php else: ?>
            <button class="btn btn-secondary btn-lg px-4" disabled>Pendaftaran Ditutup</button>
        <?php endif; ?>
    </div>
</section>

<div class="container my-5">
    <!-- Statistik Kuota -->
    <div class="row text-center mb-5 g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <h6 class="text-muted">Total Kuota Disediakan</h6>
                <h2 class="fw-bold text-success"><?= $kuotaTotal ?> Siswa</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <h6 class="text-muted">Total Pendaftar Masuk</h6>
                <h2 class="fw-bold text-primary"><?= $totalPendaftar ?> Siswa</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <h6 class="text-muted">Sisa Kuota Tersedia</h6>
                <h2 class="fw-bold text-warning"><?= $sisaKuota ?> Siswa</h2>
            </div>
        </div>
    </div>

    <!-- Daftar Jalur Pendaftaran -->
    <h4 class="fw-bold mb-3 text-success">Jalur Pendaftaran & Alokasi Kuota</h4>
    <div class="row g-4">
        <?php foreach ($jalurList as $j): ?>
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title fw-bold mb-0"><?= htmlspecialchars($j['nama_jalur']) ?></h5>
                        <span class="badge bg-success">Kuota: <?= $j['kuota_jalur'] ?></span>
                    </div>
                    <p class="card-text text-muted small"><?= htmlspecialchars($j['deskripsi']) ?></p>
                    <hr>
                    <p class="small mb-1 fw-semibold">Persyaratan Khusus:</p>
                    <p class="small text-secondary mb-0"><?= htmlspecialchars($j['persyaratan_khusus']) ?></p>
                </div>
                <div class="card-footer bg-white border-0 text-end">
                    <small class="text-muted">Pendaftar saat ini: <strong><?= $j['total_pendaftar'] ?></strong></small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <small>&copy; <?= date('Y') ?> Panitia PPDB <?= htmlspecialchars($setting['nama_madrasah']) ?></small>
</footer>
</body>
</html>
