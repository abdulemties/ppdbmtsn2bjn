<?php
require_once 'config/koneksi.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = trim($_POST['nisn']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM tbl_siswa WHERE nisn = ? LIMIT 1");
    $stmt->execute([$nisn]);
    $siswa = $stmt->fetch();

    if ($siswa && password_verify($password, $siswa['password_akun'])) {
        $_SESSION['id_siswa'] = $siswa['id_siswa'];
        $_SESSION['no_pendaftaran'] = $siswa['no_pendaftaran'];
        $_SESSION['nama_siswa'] = $siswa['nama_lengkap'];
        header("Location: siswa/dashboard.php");
        exit;
    } else {
        $error = "NISN atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa - PPDB MTsN 2 Bojonegoro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
<div class="container" style="max-width: 420px;">
    <div class="card border-0 shadow">
        <div class="card-body p-4">
            <h4 class="fw-bold text-center text-success mb-1">Login Panel Siswa</h4>
            <p class="text-center text-muted small mb-4">PPDB <?= htmlspecialchars($setting['nama_madrasah']) ?></p>
            <?php if ($error): ?>
                <div class="alert alert-danger py-2 small"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">NISN</label>
                    <input type="text" name="nisn" maxlength="10" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold">Masuk</button>
            </form>
            <div class="text-center mt-3">
                <a href="index.php" class="text-decoration-none small">&larr; Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
