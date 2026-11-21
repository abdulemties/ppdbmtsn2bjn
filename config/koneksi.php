<?php
session_start();

$host = 'localhost';
$db   = 'db_ppdb_mtsn2bjn';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Ambil data pengaturan madrasah
$stmtSetting = $pdo->query("SELECT * FROM tbl_pengaturan WHERE id_pengaturan = 1");
$setting = $stmtSetting->fetch();
?>
