<?php
function getAllLagu(mysqli $conn): array {
    $daftarLagu = [];
    $sql = "SELECT 
              lagu.lagu_id, 
              lagu.judul, 
              lagu.tahun, 
              artist.nama_artist 
            FROM lagu 
            JOIN artist ON lagu.artist_id = artist.artist_id 
            ORDER BY lagu.judul ASC";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $daftarLagu = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    }
    return $daftarLagu;
}
function getGenres(mysqli $conn): array {
    //dipake pas tambah lagu buat nampilin list genre
    $genres = [];
    $sql = "SELECT genre_id, nama_genre FROM genre ORDER BY nama_genre ASC";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $genres = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    }
    return $genres;
}
function getLaguById(mysqli $conn, int $laguId): ?array {
    $sql = "SELECT l.lagu_id, l.judul, l.tahun, l.artist_id, a.nama_artist 
            FROM lagu l 
            JOIN artist a ON l.artist_id = a.artist_id 
            WHERE l.lagu_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $laguId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $lagu = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    if ($lagu) {
        $sql_genres = "SELECT genre_id FROM lagu_genre WHERE lagu_id = ?";
        $stmt_genres = mysqli_prepare($conn, $sql_genres);
        mysqli_stmt_bind_param($stmt_genres, "i", $laguId);
        mysqli_stmt_execute($stmt_genres);
        $result_genres = mysqli_stmt_get_result($stmt_genres);
        $genre_ids = [];
        while ($row = mysqli_fetch_assoc($result_genres)) {
            $genre_ids[] = $row['genre_id'];
        }
        $lagu['genre_ids'] = $genre_ids;
        mysqli_stmt_close($stmt_genres);
    }
    return $lagu;
}
function getLaguAdvanced(mysqli $conn, string $keyword, string $genreId, string $sort): array {
    //ini yang sekarang dipake buat nampilin lagu di halaman utama dengan filter dan sorting
    $daftarLagu = [];
    $sql = "SELECT DISTINCT l.lagu_id, l.judul, l.tahun, a.nama_artist 
            FROM lagu l
            JOIN artist a ON l.artist_id = a.artist_id";
    if (!empty($genreId)) {
        $sql .= " JOIN lagu_genre lg ON l.lagu_id = lg.lagu_id";
    }
    $conditions = [];
    $params = [];
    $types = "";
    if (!empty($keyword)) {
        $conditions[] = "(l.judul LIKE ? OR a.nama_artist LIKE ?)";
        $searchTerm = "%" . $keyword . "%";
        $params[] = $searchTerm;
        $params[] = $searchTerm; 
        $types .= "ss";
    }
    if (!empty($genreId)) {
        $conditions[] = "lg.genre_id = ?";
        $params[] = (int)$genreId;
        $types .= "i";
    }
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    switch ($sort) {
        case 'judul_desc': $orderBy = "l.judul DESC"; break;
        case 'tahun_asc': $orderBy = "l.tahun ASC"; break;
        case 'tahun_desc': $orderBy = "l.tahun DESC"; break;
        default: $orderBy = "l.judul ASC"; break;
    }
    $sql .= " ORDER BY " . $orderBy;
    $stmt = mysqli_prepare($conn, $sql);
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) > 0) {
        $daftarLagu = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    }
    mysqli_stmt_close($stmt);
    return $daftarLagu;
}
?>