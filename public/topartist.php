<?php
include 'templates/session_start.php';

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/functions/lagu_functions.php';
require_once __DIR__ . '/../src/functions/user_song_functions.php';
require_once __DIR__ . '/../src/functions/artist_functions.php';
require_once __DIR__ . '/../src/functions/top_functions.php';

$daftarTopArtist = getTopArtists($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopArtist - MySongList</title>
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
    <?php 
    include 'templates/navbar.php';
	  include 'templates/footer.php';
    ?>
    <main>
      <div class="container">
        <div class="page-header">
            <h1><i class="ph-fill ph-microphone-stage"></i> Top Artis Terpopuler</h1>
            <p>Artis dengan total lagu paling banyak difavoritkan di komunitas.</p>
        </div>

        <div class="song-list"> <?php if (!empty($daftarTopArtist)): ?>
            <?php $rank = 1; ?>
            <?php foreach ($daftarTopArtist as $artis): ?>
            <?php $rankClass = ($rank <= 3) ? "rank-$rank" : ""; ?>
              <div class="song-card">
                <div class="rank-badge <?php echo $rankClass; ?>"><?php echo $rank++; ?></div>
                <div class="artist-icon-wrapper">
                    <i class="ph-fill ph-microphone-stage"></i>
                </div>

                <div class="song-info">
                    <div class="song-title">
                        <?php echo htmlspecialchars($artis['nama_artist']); ?>
                    </div>
                    
                    <div class="artist-stats">
                        <div class="stat-item" title="Jumlah Lagu di Database">
                            <i class="ph-fill ph-music-notes-simple"></i> 
                            <span><?php echo $artis['jumlah_lagu']; ?> Lagu</span>
                        </div>
                        <div class="stat-item" title="Total Difavoritkan User">
                            <i class="ph-fill ph-heart"></i> 
                            <span><?php echo $artis['total_favorit']; ?> Favorit</span>
                        </div>
                    </div>
                </div>
              </div>

            <?php endforeach; ?>
          <?php else: ?>
            <div class="no-data">
                <p>Belum ada data artis populer saat ini.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </main>

    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/main.js"></script>
    <?php if($conn){ mysqli_close($conn); } ?>
</body>
</html>