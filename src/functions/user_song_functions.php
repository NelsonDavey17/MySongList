<?php
function getFavoritedSongs(mysqli $conn, int $userId): array {
    $favorites = [];
    // Join tabel lagu, artist, dan lagu_user
    $sql = "SELECT l.lagu_id, l.judul, l.tahun, a.nama_artist 
            FROM lagu l
            JOIN artist a ON l.artist_id = a.artist_id
            JOIN lagu_user lu ON l.lagu_id = lu.lagu_id
            WHERE lu.user_id = ?
            ORDER BY lu.tanggal_ditambahkan DESC";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $favorites = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        mysqli_stmt_close($stmt);
    }
    return $favorites;
}
function getUserFavoriteIds(mysqli $conn, int $userId): array {
    //fucntion untuk mengecek status favorit di tabel lagu_user
    $ids = [];
    $sql = "SELECT lagu_id FROM lagu_user WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $ids[] = $row['lagu_id'];
        }
        mysqli_stmt_close($stmt);
    }
    return $ids;
}
function toggleFavorite(mysqli $conn, int $userId, int $laguId): string {
    //fucntion untuk menambah/menghapus favorit di tabel lagu_user
    $checkSql = "SELECT 1 FROM lagu_user WHERE user_id = ? AND lagu_id = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "ii", $userId, $laguId);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);
    $exists = mysqli_stmt_num_rows($checkStmt) > 0;
    mysqli_stmt_close($checkStmt);

    if ($exists) {
        //jika ada, HAPUS (Unfavorite)
        $delSql = "DELETE FROM lagu_user WHERE user_id = ? AND lagu_id = ?";
        $delStmt = mysqli_prepare($conn, $delSql);
        mysqli_stmt_bind_param($delStmt, "ii", $userId, $laguId);
        mysqli_stmt_execute($delStmt);
        mysqli_stmt_close($delStmt);
        return 'removed';
    } else {
        //jika tidak ada, TAMBAH (Favorite)
        $insSql = "INSERT INTO lagu_user (user_id, lagu_id) VALUES (?, ?)";
        $insStmt = mysqli_prepare($conn, $insSql);
        mysqli_stmt_bind_param($insStmt, "ii", $userId, $laguId);
        mysqli_stmt_execute($insStmt);
        mysqli_stmt_close($insStmt);
        return 'added';
    }
}
function searchUserFavorites(mysqli $conn, int $userId, string $keyword): array {
    //function untuk mencari lagu favorit user berdasarkan keyword
    //digunakan di tab favorit.php
    $favorites = [];
    $keywordAman = "%" . $keyword . "%";
    $sql = "SELECT l.lagu_id, l.judul, l.tahun, a.nama_artist 
            FROM lagu l
            JOIN artist a ON l.artist_id = a.artist_id
            JOIN lagu_user lu ON l.lagu_id = lu.lagu_id
            WHERE lu.user_id = ? 
            AND (l.judul LIKE ? OR a.nama_artist LIKE ?)
            ORDER BY lu.tanggal_ditambahkan DESC";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iss", $userId, $keywordAman, $keywordAman);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $favorites = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        mysqli_stmt_close($stmt);
    }
    return $favorites;
}
function getRecentFavorites(mysqli $conn, int $userId, int $limit = 3): array {
    //cari lagu favorit terbaru user, default limit 3(array)
    $recent = [];
    $sql = "SELECT l.lagu_id, l.judul, l.tahun, a.nama_artist 
            FROM lagu l
            JOIN artist a ON l.artist_id = a.artist_id
            JOIN lagu_user lu ON l.lagu_id = lu.lagu_id
            WHERE lu.user_id = ?
            ORDER BY lu.tanggal_ditambahkan DESC
            LIMIT ?"; // Limit dinamis

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $userId, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            $recent = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        mysqli_stmt_close($stmt);
    }
    return $recent;
}
?>