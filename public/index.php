<?php
include 'templates/session_start.php';
//navigation bar
include 'templates/navbar.php';
//template edit modal
include 'templates/editSongModal.php';

require_once __DIR__ . '/../src/config.php';
require_once __DIR__. '/../src/functions/lagu_functions.php';
require_once __DIR__. '/../src/functions/user_song_functions.php';
//buat nampilin lagu dengan filter dan sorting
$keyword = $_GET['keyword'] ?? '';
$filterGenre = $_GET['genre'] ?? '';
$sortBy = $_GET['sort'] ?? 'judul_asc';

$currentUserId = $_SESSION['user_id'];
$userFavoriteIds = getUserFavoriteIds($conn, $currentUserId);
$daftarGenre = [];
if(function_exists('getGenres')){
  $daftarGenre = getGenres($conn);
}
//song card ditampilkan dengan fungsi getLaguAdvanced
$daftarlagutampil = getLaguAdvanced($conn, $keyword, $filterGenre, $sortBy);//getAllLagu($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - MySongList</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
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
        <h1>List Lagu</h1>
        <p>Jelajahi semua lagu yang ada di MySongList.</p>

        <div class="filter-bar-container">
          <form action="index.php" method="GET" class="main-filter-form">
            <div class="search-row">
              <div class="search-group">
                <i class="ph ph-magnifying-glass search-icon"></i>
                <input type="text" name="keyword" placeholder="Cari Judul atau Artis..." value="<?php echo htmlspecialchars($keyword); ?>">
                <button type="submit" class="btn-search-submit"><i class="ph ph-arrow-right"></i></button>
              </div>
            </div>

            <div class="filter-row">
              <span class="filter-label">Filter & Urutkan:</span>
              <div class="filter-group">
                  <select name="genre" onchange="this.form.submit()">
                    <option value="">Semua Genre</option>
                    <?php foreach ($daftarGenre as $genre): ?>
                      <option value="<?php echo $genre['genre_id']; ?>" <?php echo ($filterGenre == $genre['genre_id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($genre['nama_genre']); ?>
                    </option>
                    <?php endforeach; ?>
                  </select>
              </div>

              <div class="filter-group">
                <select name="sort" onchange="this.form.submit()">
                  <option value="judul_asc" <?php echo ($sortBy == 'judul_asc') ? 'selected' : ''; ?>>Judul (A-Z)</option>
                  <option value="judul_desc" <?php echo ($sortBy == 'judul_desc') ? 'selected' : ''; ?>>Judul (Z-A)</option>
                  <option value="tahun_desc" <?php echo ($sortBy == 'tahun_desc') ? 'selected' : ''; ?>>Tahun (Terbaru)</option>
                  <option value="tahun_asc" <?php echo ($sortBy == 'tahun_asc') ? 'selected' : ''; ?>>Tahun (Terlama)</option>
                </select>
              </div>

              <?php if (!empty($keyword) || !empty($filterGenre) || $sortBy != 'judul_asc'): ?>
                <a href="index.php" class="btn-reset" title="Reset Semua Filter"><i class="ph ph-arrow-counter-clockwise"></i></a>
              <?php endif; ?>
            </div>
          </form>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert success"><?php echo htmlspecialchars(urldecode($_GET['success'])); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert error"><?php echo htmlspecialchars(urldecode($_GET['error'])); ?></div>
        <?php endif; ?>

        <div class="song-list">
          <?php if (!empty($daftarlagutampil)): ?>
            <?php foreach ($daftarlagutampil as $lagu): ?>
              <?php $isFavorite = in_array($lagu['lagu_id'], $userFavoriteIds); ?>
              <?php $activeClass = $isFavorite ? 'active' : ''; ?>
              <?php $iconType = $isFavorite ? 'ph-fill' : 'ph';?>
              <?php $iconName = $isFavorite ? 'ph-heart' : 'ph-heart';?>
              <?php $detailLagu = getLaguById($conn, $lagu['lagu_id']); ?>
              <?php $genreIdsJson = htmlspecialchars(json_encode($detailLagu['genre_ids'])); ?>

              <div class="song-card">
                <div class="song-info">
                  <div class="song-title"><?php echo htmlspecialchars($lagu['judul']); ?></div>
                  <div class="song-artist"><?php echo htmlspecialchars($lagu['nama_artist']); ?></div>
                  <span class="song-year"><?php echo htmlspecialchars($lagu['tahun']); ?></span>
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
                </div>
                <button type="submit" class="btn-primary block">Simpan Lagu</button>
              </form>
            </div>
        </div>
    </div>

    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/main.js"></script>
    <?php if($conn){ mysqli_close($conn); } ?>
</body>
</html>