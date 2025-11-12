<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'mysonglist';
//variabel dengan fungsi untuk menghubungkan database
$conn = @mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
if (!mysqli_set_charset($conn, "utf8mb4")) {
    error_log("Error loading character set utf8mb4: " . mysqli_error($conn));
}
?>