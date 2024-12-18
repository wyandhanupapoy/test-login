<?php
$host = 'sql311.infinityfree.com'; // Ganti dengan host database Anda
$db = 'if0_37697654_sumber_ganda_mekar'; // Nama database yang Anda buat
$user = 'if0_37697654'; // Ganti dengan username database Anda
$pass = 'papoyhola123 '; // Ganti dengan password database Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>
