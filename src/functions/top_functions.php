<?php
function getTopSongs(mysqli $conn): array {
    $topSongs = [];
    $sql = "SELECT l.lagu_id, l.judul, l.tahun, a.nama_artist, 
            COUNT(lu.user_id) as total_favorit
            FROM lagu l
            JOIN artist a ON l.artist_id = a.artist_id
            LEFT JOIN lagu_user lu ON l.lagu_id = lu.lagu_id
            GROUP BY l.lagu_id
            HAVING total_favorit > 0
            ORDER BY total_favorit DESC, l.judul ASC
            LIMIT 15";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $topSongs = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    }
    
    return $topSongs;
}
?>