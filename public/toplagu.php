<?php
include 'templates/navbar.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/functions/lagu_functions.php';
require_once __DIR__ . '/../src/functions/user_song_functions.php';
require_once __DIR__ . '/../src/functions/artist_functions.php';
require_once __DIR__ . '/../src/functions/top_functions.php';

$currentUserId = $_SESSION['user_id'];
$daftarTopLagu = getTopSongs($conn);
$userFavIds = getUserFavoriteIds($conn, $currentUserId);
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
    <title>Top 15 Lagu - MySongList</title>
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
            <h1><i class="ph-fill ph-trophy" style="color: #f1c40f;"></i> Top 15 Lagu Terpopuler</h1>
            <p>Lagu-lagu yang paling banyak difavoritkan oleh komunitas.</p>
        </div>

        <div class="song-list">
          <?php if (!empty($daftarTopLagu)): ?>
            <?php $rank = 1; ?>
            <?php foreach ($daftarTopLagu as $lagu): ?>
                <?php 
                $detailLagu = getLaguById($conn, $lagu['lagu_id']); 
                $genreIdsJson = htmlspecialchars(json_encode($detailLagu['genre_ids'] ?? []));
                $isFav = in_array($lagu['lagu_id'], $userFavIds);
                $activeClass = $isFav ? 'active' : '';
                $iconType = $isFav ? 'ph-fill' : 'ph';
                $iconName = 'ph-heart';
                $rankClass = ($rank <= 3) ? "rank-$rank" : "";
                ?>

                <div class="song-card">
                <div class="rank-badge <?php echo $rankClass; ?>"><?php echo $rank++; ?>
                </div>

                <div class="song-info">
                    <div class="song-title"><?php echo htmlspecialchars($lagu['judul']); ?></div>
                    <div class="song-artist"><?php echo htmlspecialchars($lagu['nama_artist']); ?></div>
                    <div class="song-year"><?php echo htmlspecialchars($lagu['tahun']); ?></div>
                    <div class="fav-count">
                        <i class="ph-fill ph-heart"></i> <?php echo $lagu['total_favorit']; ?> Favorit
                    </div>
                </div>

                <div class="song-action">
                  <button class="btn-fav <?php echo $activeClass; ?>" data-id="<?php echo $lagu['lagu_id']; ?>" title="Favorit">
                    <i class="ph <?php echo $iconType; ?> <?php echo $iconName; ?>"></i>
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
                <i class="ph ph-chart-bar" style="font-size: 4rem; margin-bottom: 15px; color:#ccc;"></i>
                <p>Belum ada data lagu populer saat ini.</p>
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
            <input type="hidden" name="redirect_to" value="toplagu.php">

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