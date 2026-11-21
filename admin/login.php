<?php
require_once '../config/koneksi.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM tbl_admin WHERE username = ? AND status_aktif = 'Aktif' LIMIT 1");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_akun'])) {
        $_SESSION['id_admin']   = $admin['id_admin'];
        $_SESSION['nama_admin'] = $admin['nama_lengkap'];
        $_SESSION['role_admin'] = $admin['role'];

        $pdo->prepare("UPDATE tbl_admin SET last_login = NOW() WHERE id_admin = ?")->execute([$admin['id_admin']]);
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau Password tidak valid!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Panitia - PPDB MTsN 2 Bojonegoro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center" style="min-height: 100vh;">
<div class="container" style="max-width: 400px;">
    <div class="card border-0 shadow">
        <div class="card-body p-4">
            <h4 class="fw-bold text-center mb-1">Panel Panitia PPDB</h4>
            <p class="text-center text-muted small mb-4">MTsN 2 Bojonegoro</p>
            <?php if ($error): ?>
                <div class="alert alert-danger py-2 small"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold">Login Panitia</button>
            </form>
            <div class="text-center mt-3">
                <a href="../index.php" class="text-decoration-none small">&larr; Kembali ke Portal Utama</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
