<?php
function getUserProfile(mysqli $conn, int $userId): ?array {
    //return nilai array berupa data profil user
    $sql = "SELECT username, email, gender FROM user WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if($stmt){
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $user;
    }
    return null;
}
function countUserFavorites(mysqli $conn, int $userId): int {
    //menghitung total lagu favorit user
    $total = 0;
    $sql =  "SELECT COUNT(*) AS total FROM lagu_user WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if($stmt){
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $total = (int)$row['total'];
        }
        mysqli_stmt_close($stmt);
    }
    return $total;
}
?>