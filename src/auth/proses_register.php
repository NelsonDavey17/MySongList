<?php
session_start();
require_once __DIR__ . '/../config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $queryString = "&username=" . urlencode($username) . "&email=" . urlencode($email);
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        header("Location: ../../public/auth/register.php?error=" . urlencode("Semua field wajib diisi (kecuali gender)."). $queryString);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../../public/auth/register.php?error=" . urlencode("Format email tidak valid."). $queryString);
        exit;
    }
    if (strlen($password) < 8) {
        header("Location: ../../public/auth/register.php?error=" . urlencode("Password harus minimal 8 karakter."). $queryString);
        exit;
    }
    if ($password !== $confirm_password) {
        header("Location: ../../public/auth/register.php?error=" . urlencode("Konfirmasi password tidak cocok."). $queryString);
        exit;
    }
    $gender_to_db = NULL;
    if (!empty($gender)) {
        if ($gender === 'male' || $gender === 'female') {
            $gender_to_db = $gender;
        } else {
            header("Location: ../../public/auth/register.php?error=" . urlencode("Nilai gender tidak valid."). $queryString);
            exit;
        }
    }
    $sql_check_user = "SELECT user_id FROM user WHERE username = ?";
    $stmt_user = mysqli_prepare($conn, $sql_check_user);
    mysqli_stmt_bind_param($stmt_user, "s", $username);
    mysqli_stmt_execute($stmt_user);
    mysqli_stmt_store_result($stmt_user);
    if (mysqli_stmt_num_rows($stmt_user) > 0) {
        mysqli_stmt_close($stmt_user);
        header("Location: ../../public/auth/register.php?error=" . urlencode("Username $username sudah terdaftar."). $queryString);
        exit;
    }
    mysqli_stmt_close($stmt_user);
    $sql_check_email = "SELECT user_id FROM user WHERE email = ?";
    $stmt_email = mysqli_prepare($conn, $sql_check_email);
    mysqli_stmt_bind_param($stmt_email, "s", $email);
    mysqli_stmt_execute($stmt_email);
    mysqli_stmt_store_result($stmt_email);
    if (mysqli_stmt_num_rows($stmt_email) > 0) {
        mysqli_stmt_close($stmt_email);
        header("Location: ../../public/auth/register.php?error=" . urlencode("Email $email sudah terdaftar."). $queryString);
        exit;
    }
    mysqli_stmt_close($stmt_email);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql_insert = "INSERT INTO user (username, email, password, gender) VALUES (?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "ssss", $username, $email, $hashed_password, $gender_to_db);
    if (mysqli_stmt_execute($stmt_insert)) {
        // Registrasi berhasil! Arahkan ke halaman login dengan pesan sukses.
        header("Location: ../../public/auth/login.php?success=" . urlencode("Registrasi berhasil! Silakan login."));
        mysqli_stmt_close($stmt_insert);
        mysqli_close($conn);
        exit;
    } else {
        error_log("Gagal insert user: " . mysqli_error($conn)); // Catat error di log server
        header("Location: ../../public/auth/register.php?error=" . urlencode("Registrasi gagal karena masalah teknis."). $queryString);
        mysqli_stmt_close($stmt_insert);
        mysqli_close($conn);
        exit;
    }    
} else {
    header("Location: ../../public/auth/register.php");
    exit;
}
?>