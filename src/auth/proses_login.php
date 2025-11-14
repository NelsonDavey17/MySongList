<?php
session_start();
require_once __DIR__ . '/../config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($username) || empty($password)) {
        header("Location: ../../public/auth/login.php?error=" . urlencode("Username dan Password wajib diisi."));
        exit;
    }
    $sql = "SELECT user_id, username, password FROM user WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                session_regenerate_id(true);
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                header("Location: ../../public/index.php");
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                exit;
            } else {
                header("Location: ../../public/auth/login.php?error=" . urlencode("Username atau Password salah."));
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                exit;
            }
        } else {
            header("Location: ../../public/auth/login.php?error=" . urlencode("Username atau Password salah."));
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            exit;
        }
    } else {
        error_log("Gagal prepare statement login: " . mysqli_error($conn));
        header("Location: ../../public/auth/login.php?error=" . urlencode("Terjadi kesalahan sistem."));
        mysqli_close($conn);
        exit;
    }
} else {
    header("Location: ../../public/auth/login.php");
    mysqli_close($conn);
    exit;
}
?>