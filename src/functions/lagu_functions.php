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
?>