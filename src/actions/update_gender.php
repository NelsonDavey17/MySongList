<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../public/auth/login.php");
    exit;
}
require_once __DIR__ . '/../config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'];
    if (isset($_POST['gender'])) {
        $genderInput = $_POST['gender'];
        $genderToDb = null;
        if ($genderInput === 'male' || $genderInput === 'female') {
            $genderToDb = $genderInput;
        } 
        $sql = "UPDATE user SET gender = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $genderToDb, $userId);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: ../../public/profile.php?success=" . urlencode("Profil berhasil diperbarui!"));
            exit;
        } else {
            error_log("Gagal update profil: " . mysqli_error($conn));
            header("Location: ../../public/profile.php?error=" . urlencode("Gagal memperbarui profil."));
            exit;
        }
    }
}
if ($conn) mysqli_close($conn);
header("Location: ../../public/profile.php");
exit;
?>