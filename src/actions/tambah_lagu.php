<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../public/auth/login.php?error=" . urlencode("Anda harus login untuk menambah lagu."));
    exit;
}
$currentUserId = $_SESSION['user_id'];
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions/artist_functions.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = trim($_POST['judul'] ?? '');
    $artistName = trim($_POST['artist_name'] ?? '');
    $tahun = filter_input(INPUT_POST, 'tahun', FILTER_VALIDATE_INT);
    $genre_ids = $_POST['genre_ids'] ?? [];
    //validasi
    if (empty($judul) || empty($artistName) || empty($genre_ids)) {
        header("Location: ../../public/index.php?error=" . urlencode("Judul, Artis, dan minimal 1 Genre wajib diisi."));
        exit;
    }
    //panggil fungsi findOrCreateArtist di file artist_functions.php untuk cari apakah artist eksis atau tidak di tabel
    $artist_id = findOrCreateArtist($conn, $artistName);
    if ($artist_id === 0) {
        header("Location: ../../public/index.php?error=" . urlencode("Gagal memproses nama artis."));
        exit;
    }
    if ($tahun === false || $tahun === null || $tahun < 1900 || $tahun > 2025) {
        $tahun = null;
    }
    $sql_lagu = "INSERT INTO lagu (judul, artist_id, tahun) VALUES (?, ?, ?)";
    $stmt_lagu = mysqli_prepare($conn, $sql_lagu);
    mysqli_stmt_bind_param($stmt_lagu, "sii", $judul, $artist_id, $tahun);
    if (mysqli_stmt_execute($stmt_lagu)) {
        $new_lagu_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt_lagu);
        $sql_genre = "INSERT INTO lagu_genre (lagu_id, genre_id) VALUES (?, ?)";
        $stmt_genre = mysqli_prepare($conn, $sql_genre);
        foreach ($genre_ids as $genre_id) {
            $genre_id_int = (int)$genre_id;
            mysqli_stmt_bind_param($stmt_genre, "ii", $new_lagu_id, $genre_id_int);
            mysqli_stmt_execute($stmt_genre);
        }
        mysqli_stmt_close($stmt_genre);
        //auto masuk list favorit user
        $sql_favorit = "INSERT INTO lagu_user (user_id, lagu_id) VALUES (?, ?)";
        $stmt_favorit = mysqli_prepare($conn, $sql_favorit);
        mysqli_stmt_bind_param($stmt_favorit, "ii", $currentUserId, $new_lagu_id);
        mysqli_stmt_execute($stmt_favorit);
        mysqli_stmt_close($stmt_favorit);
        mysqli_close($conn);
        header("Location: ../../public/index.php?success=" . urlencode("Lagu berhasil ditambahkan!"));
        exit;
    } else {
        mysqli_close($conn);
        error_log("Gagal insert lagu: " . mysqli_error($conn));
        header("Location: ../../public/index.php?error=" . urlencode("Gagal menyimpan lagu."));
        exit;
    }
} else {
    header("Location: ../../public/index.php");
    exit;
}
?>