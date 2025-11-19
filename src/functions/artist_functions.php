<?php
function findOrCreateArtist(mysqli $conn, string $artistName): int {
    $artistName = trim($artistName);
    if (empty($artistName)) {
        return 0;
    }
    $sql_check = "SELECT artist_id FROM artist WHERE nama_artist = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $artistName);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    if ($row = mysqli_fetch_assoc($result_check)) {
        mysqli_stmt_close($stmt_check);
        return (int)$row['artist_id'];
    } else {
        mysqli_stmt_close($stmt_check);
        $sql_insert = "INSERT INTO artist (nama_artist) VALUES (?)";
        $stmt_insert = mysqli_prepare($conn, $sql_insert);
        mysqli_stmt_bind_param($stmt_insert, "s", $artistName);
        if (mysqli_stmt_execute($stmt_insert)) {
            $new_id = (int)mysqli_insert_id($conn);
            mysqli_stmt_close($stmt_insert);
            return $new_id;
        } else {
            error_log("Gagal insert artist baru: " . mysqli_error($conn));
            return 0;
        }
    }
}
function deleteArtistIfUnused(mysqli $conn, int $artistId): bool {
    $sql_check = "SELECT COUNT(*) as total FROM lagu WHERE artist_id = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "i", $artistId);
    mysqli_stmt_execute($stmt_check);
    $result = mysqli_stmt_get_result($stmt_check);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt_check);
    if ($row['total'] == 0) {
        $sql_delete = "DELETE FROM artist WHERE artist_id = ?";
        $stmt_delete = mysqli_prepare($conn, $sql_delete);
        mysqli_stmt_bind_param($stmt_delete, "i", $artistId);
        $success = mysqli_stmt_execute($stmt_delete);
        mysqli_stmt_close($stmt_delete);
        return $success;
    }
    return true;
}
?>