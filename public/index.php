<?php
include 'templates/navbar.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
require_once __DIR__ . '/../src/config.php';
require_once __DIR__. '/../src/functions/lagu_functions.php';
$currentUserId = $_SESSION['user_id'];
$daftarGenre = [];
if(function_exists('getGenres')){
  $daftarGenre = getGenres($conn);
}
$daftarlagutampil = getAllLagu($conn);
if($conn){
  mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css"
    />
</head>
<body>
    <main>
      <div class="container">
        <h1>Katalog Lagu</h1>
        <p>Jelajahi semua lagu yang ada di MySongList.</p>
        <div class="song-list">
          <?php if (!empty($daftarlagutampil)): ?>
            <?php foreach ($daftarlagutampil as $lagu): ?>
              <div class="song-card">
                <div class="song-title"><?php echo htmlspecialchars($lagu['judul']); ?></div>
                <div class="song-artist"><?php echo htmlspecialchars($lagu['nama_artist']); ?></div>
                <span class="song-year"><?php echo htmlspecialchars($lagu['tahun']); ?></span>
                <button class="btn-fav" title="Tambah ke Favorit"><i class="ph ph-heart"></i></button>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="no-songs">Belum ada lagu di database.</p>
          <?php endif; ?>
        </div>
      </div>
    </main>
    <button id="openAddModalBtn" class="fab-btn" title="Tambah Lagu Baru">
      <i class="ph ph-plus"></i>
    </button>
    <div id="addSongModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
              <h2>Tambah Lagu Baru</h2>
              <button class="close-modal-btn">&times;</button>
            </div>
            <div class="modal-body">
              <form action="../src/actions/tambah_lagu.php" method="POST">
                <div class="form-group">
                  <label for="judul">Judul Lagu <span class="required">*</span></label>
                  <input type="text" id="judul" name="judul" required placeholder="Contoh: Bohemian Rhapsody">
                </div>
                <div class="form-group">
                  <label for="artist_name">Nama Artis <span class="required">*</span></label>
                  <input type="text" id="artist_name" name="artist_name" required placeholder="Contoh: Queen">
                  <small>Jika artis belum ada, akan dibuat otomatis.</small>
                </div>
                <div class="form-group">
                  <label for="tahun">Tahun Rilis</label>
                  <input type="number" id="tahun" name="tahun" min="1900" max="2025" placeholder="Tahun (1900-2025)">
                </div>
                <div class="form-group">
                  <label>Genre (Pilih Minimal 1) <span class="required">*</span></label>
                  <div class="genre-checkbox-group">
                    <?php if (!empty($daftarGenre)): ?>
                      <?php foreach ($daftarGenre as $genre): ?>
                        <label class="checkbox-label">
                          <input type="checkbox" name="genre_ids[]" value="<?php echo htmlspecialchars($genre['genre_id']); ?>">
                          <?php echo htmlspecialchars($genre['nama_genre']); ?>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <p>Tidak ada genre tersedia.</p>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn-primary block">Simpan Lagu</button>
              </form>
            </div>
        </div>
    </div>
    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>