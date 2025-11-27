<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../public/auth/login.php");
    exit;
}

require_once __DIR__ . '/../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'];
    $oldPass = $_POST['old_password'] ?? '';
    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    if (empty($oldPass) || empty($newPass) || empty($confirmPass)) {
        header("Location: ../../public/profile.php?error=" . urlencode("Semua kolom password wajib diisi."));
        exit;
    }
    if ($newPass !== $confirmPass) {
        header("Location: ../../public/profile.php?error=" . urlencode("Password baru dan konfirmasi tidak cocok."));
        exit;
    }
    if (strlen($newPass) < 8) {
        header("Location: ../../public/profile.php?error=" . urlencode("Password baru minimal 8 karakter."));
        exit;
    }

    $sql = "SELECT password FROM user WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($user && password_verify($oldPass, $user['password'])) {
        $newHashed = password_hash($newPass, PASSWORD_DEFAULT);
        $updateSql = "UPDATE user SET password = ? WHERE user_id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        
        mysqli_stmt_bind_param($updateStmt, "si", $newHashed, $userId);
        
        if (mysqli_stmt_execute($updateStmt)) {
            mysqli_stmt_close($updateStmt);
            header("Location: ../../public/profile.php?success=" . urlencode("Password berhasil diubah!"));
            exit;
        } else {
            header("Location: ../../public/profile.php?error=" . urlencode("Gagal mengupdate password."));
            exit;
        }
    } else {
        header("Location: ../../public/profile.php?error=" . urlencode("Password lama salah."));
        exit;
    }
}
if ($conn) mysqli_close($conn);
?>