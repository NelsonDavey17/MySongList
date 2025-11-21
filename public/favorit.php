<?php
include 'templates/navbar.php'; // Navbar
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/functions/user_song_functions.php';
require_once __DIR__ . '/../src/functions/lagu_functions.php'; 
require_once __DIR__ . '/../src/functions/artist_functions.php';

$currentUserId = $_SESSION['user_id'];
$keyword = $_GET['keyword'] ?? '';
if (!empty($keyword)) {
    $daftarFavorit = searchUserFavorites($conn, $currentUserId, trim($keyword));
} else {
    $daftarFavorit = getFavoritedSongs($conn, $currentUserId);
}
$daftarGenre = [];
if(function_exists('getGenres')){
  $daftarGenre = getGenres($conn);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lagu Favorit Saya</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" />
</head>
<body>
    <main>
      <div class="container">
        <div class="page-header">
          <h1>Lagu Favorit Saya</h1>
          <p>Koleksi lagu yang Anda sukai.</p>
        </div>

        <div class="filter-bar-container" style="margin-bottom: 20px;">
          <form action="favorit.php" method="GET" class="filter-form">
            <div class="search-row">
              <div class="search-group">
                <i class="ph ph-magnifying-glass search-icon"></i>
                <input type="text" name="keyword" placeholder="Cari di favorit..." value="<?php echo htmlspecialchars($keyword); ?>">
                <?php if (!empty($keyword)): ?>
                  <a href="favorit.php" class="btn-reset" title="Reset Pencarian"><i class="ph ph-arrow-counter-clockwise"></i></a>
                <?php endif; ?>
                <button type="submit" class="btn-search-submit"><i class="ph ph-arrow-right"></i></button>
              </div>
            </div>  
          </form>
        </div>

        <div class="song-list">
          <?php if (!empty($daftarFavorit)): ?>
            <?php foreach ($daftarFavorit as $lagu): ?>
              <?php $detailLagu = getLaguById($conn, $lagu['lagu_id']); ?>
              <?php $genreIdsJson = htmlspecialchars(json_encode($detailLagu['genre_ids'] ?? [])); ?>

              <div class="song-card">
                <div class="song-info">
                  <div class="song-title"><?php echo htmlspecialchars($lagu['judul']); ?></div>
                  <div class="song-artist"><?php echo htmlspecialchars($lagu['nama_artist']); ?></div>
                  <span class="song-year"><?php echo htmlspecialchars($lagu['tahun']); ?></span>
                </div>
                
                <div class="song-action">
                  <button class="btn-fav active" data-id="<?php echo $lagu['lagu_id']; ?>" title="Hapus dari Favorit">
                    <i class="ph-fill ph-heart"></i>
                  </button>

                  <div class="dropdown">
                    <button class="btn-icon dropdown-toggle"><i class="ph ph-dots-three-outline-vertical"></i></i></button>
                    <div class="dropdown-menu">
                      <a href="#" class="dropdown-item btn-edit" 
                      data-id="<?php echo $lagu['lagu_id']; ?>"
                      data-judul="<?php echo htmlspecialchars($lagu['judul']); ?>"
                      data-artist="<?php echo htmlspecialchars($lagu['nama_artist']); ?>"
                      data-tahun="<?php echo $lagu['tahun']; ?>"
                      data-genres="<?php echo $genreIdsJson; ?>">
                        <i class="fa-solid fa-pen"></i> Edit
                      </a>
                      <a href="#" class="dropdown-item btn-delete"><i class="fa-solid fa-trash"></i> Hapus</a>
                    </div>
                  </div>
                </div>      
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="no-data">
              <p>Anda belum memiliki lagu favorit.</p>
              <div class="btn-no-fav">
                <a href="index.php" class="btn-primary">Jelajahi Lagu</a>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </main>

    <div id="editSongModal" class="modal-overlay">
      <div class="modal-container">
        <div class="modal-header">
          <h2>Edit Lagu</h2>
          <button class="close-modal-btn">&times;</button>
        </div>
        <div class="modal-body">
          <form action="../src/actions/edit_lagu.php" method="POST" id="editSongForm">
            <input type="hidden" id="edit_lagu_id" name="lagu_id">
            <div class="form-group">
              <label for="edit_judul">Judul Lagu <span class="required">*</span></label>
              <input type="text" id="edit_judul" name="judul" required>
            </div>
            <div class="form-group">
              <label for="edit_artist_name">Nama Artis <span class="required">*</span></label>
              <input type="text" id="edit_artist_name" name="artist_name" required>
            </div>
            <div class="form-group">
              <label for="edit_tahun">Tahun Rilis</label>
              <input type="number" id="edit_tahun" name="tahun" min="1900" max="2025">
            </div>
            <div class="form-group">
              <label>Genre (Pilih Minimal 1) <span class="required">*</span></label>
              <div class="genre-checkbox-group">
                <?php if (!empty($daftarGenre)): ?>
                  <?php foreach ($daftarGenre as $genre): ?>
                    <label class="checkbox-label">
                      <input type="checkbox" name="genre_ids[]" value="<?php echo htmlspecialchars($genre['genre_id']); ?>" class="edit-genre-checkbox">
                      <?php echo htmlspecialchars($genre['nama_genre']); ?>
                    </label>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
            <button type="submit" class="btn-primary block">Update Lagu</button>
          </form>
        </div>
      </div>
    </div>

    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/main.js"></script>
    <?php if($conn){ mysqli_close($conn); } ?>
</body>
</html>