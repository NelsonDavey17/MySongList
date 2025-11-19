<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../public/auth/login.php");
    exit;
}
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions/artist_functions.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $laguId = filter_input(INPUT_POST, 'lagu_id', FILTER_VALIDATE_INT);
    $judul = trim($_POST['judul'] ?? '');
    $artistName = trim($_POST['artist_name'] ?? '');
    $tahun = filter_input(INPUT_POST, 'tahun', FILTER_VALIDATE_INT);
    $genre_ids = $_POST['genre_ids'] ?? [];
    if (!$laguId || empty($judul) || empty($artistName) || empty($genre_ids)) {
        header("Location: ../../public/index.php?error=" . urlencode("Data tidak valid/lengkap."));
        exit;
    }
    $sql_get_old_artist = "SELECT artist_id FROM lagu WHERE lagu_id = ?";
    $stmt_old = mysqli_prepare($conn, $sql_get_old_artist);
    mysqli_stmt_bind_param($stmt_old, "i", $laguId);
    mysqli_stmt_execute($stmt_old);
    $result_old = mysqli_stmt_get_result($stmt_old);
    $oldArtistId = mysqli_fetch_assoc($result_old)['artist_id'] ?? null;
    mysqli_stmt_close($stmt_old);
    $newArtistId = findOrCreateArtist($conn, $artistName);
    if ($newArtistId === 0) {
        header("Location: ../../public/index.php?error=" . urlencode("Gagal memproses artis."));
        exit;
    }
    if ($tahun === false || $tahun === null || $tahun < 1900 || $tahun > 2025) { $tahun = null; }
    $sql_update = "UPDATE lagu SET judul = ?, artist_id = ?, tahun = ? WHERE lagu_id = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "siii", $judul, $newArtistId, $tahun, $laguId);
    if (mysqli_stmt_execute($stmt_update)) {
        mysqli_stmt_close($stmt_update);
        $sql_delete_genres = "DELETE FROM lagu_genre WHERE lagu_id = ?";
        $stmt_del_genre = mysqli_prepare($conn, $sql_delete_genres);
        mysqli_stmt_bind_param($stmt_del_genre, "i", $laguId);
        mysqli_stmt_execute($stmt_del_genre);
        mysqli_stmt_close($stmt_del_genre);
        $sql_insert_genre = "INSERT INTO lagu_genre (lagu_id, genre_id) VALUES (?, ?)";
        $stmt_ins_genre = mysqli_prepare($conn, $sql_insert_genre);
        foreach ($genre_ids as $gid) {
            $gid_int = (int)$gid;
            mysqli_stmt_bind_param($stmt_ins_genre, "ii", $laguId, $gid_int);
            mysqli_stmt_execute($stmt_ins_genre);
        }
        mysqli_stmt_close($stmt_ins_genre);
        if ($oldArtistId && $oldArtistId !== $newArtistId) {
            deleteArtistIfUnused($conn, $oldArtistId);
        }
        header("Location: ../../public/index.php?success=" . urlencode("Lagu berhasil diperbarui!"));
        exit;
    } else {
        header("Location: ../../public/index.php?error=" . urlencode("Gagal update lagu."));
        exit;
    }
}
?>