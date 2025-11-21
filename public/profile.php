<?php
include 'templates/session_start.php';
include 'templates/navbar.php';
include 'templates/editSongModal.php';
require_once __DIR__ . '/../src/config.php';
require_once __DIR__. '/../src/functions/lagu_functions.php';
require_once __DIR__. '/../src/functions/user_song_functions.php';
require_once __DIR__. '/../src/functions/user_functions.php';
require_once __DIR__. '/../src/functions/artist_functions.php';
require_once __DIR__. '/../src/functions/user_functions.php';

$currentUserId = $_SESSION['user_id'];
$userData = getUserProfile($conn, $currentUserId);
$totalFavorit = countUserFavorites($conn, $currentUserId);
$recentFavorites = getRecentFavorites($conn, $currentUserId, 3);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Saya - MySongList</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" />
</head>
<body>
    <main>
        <div class="container">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert success"><?php echo htmlspecialchars(urldecode($_GET['success'])); ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert error"><?php echo htmlspecialchars(urldecode($_GET['error'])); ?></div>
            <?php endif; ?>

            <div class="profile-header">
                <div class="profile-avatar">
                    <?php echo strtoupper(substr($userData['username'], 0, 1)); ?>
                </div>
                <div class="profile-info" style="flex-grow: 1;">
                    <h2><?php echo htmlspecialchars($userData['username']); ?></h2>
                
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?php echo htmlspecialchars($userData['email']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Gender:</span>
                        <span class="info-value"><?php echo !empty($userData['gender']) ? ucfirst($userData['gender']) : '-'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Password:</span>
                        <span class="info-value">********</span>
                        <button id="openChangePassBtn" class="btn-change-pass" style="margin-left: 15px;">Ubah Password</button>
                    </div>

                    <div class="profile-stats">
                        <div class="stat-box">
                            <span class="stat-number"><?php echo $totalFavorit; ?></span>
                            <span class="stat-label">Lagu Favorit</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-header" style="border-bottom: none; margin-bottom: 10px;">
                <h3><i class="ph-fill ph-clock-counter-clockwise"></i> Baru Ditambahkan ke Favorit</h3>
            </div>

            <div class="song-list">
                <?php if (!empty($recentFavorites)): ?>
                    <?php foreach ($recentFavorites as $lagu): ?> 
                    <?php $detailLagu = getLaguById($conn, $lagu['lagu_id']); ?>
                    <?php $genreIdsJson = htmlspecialchars(json_encode($detailLagu['genre_ids'] ?? [])); ?>
                
                    <div class="song-card">
                        <div class="song-info">
                            <div class="song-title"><?php echo htmlspecialchars($lagu['judul']); ?></div>
                            <div class="song-artist"><?php echo htmlspecialchars($lagu['nama_artist']); ?></div>
                            <div class="song-year"><?php echo htmlspecialchars($lagu['tahun']); ?></div>
                        </div>

                        <div class="song-action">
                            <button class="btn-fav active" data-id="<?php echo $lagu['lagu_id']; ?>" title="Hapus dari Favorit">
                                <i class="ph-fill ph-heart"></i>
                            </button>
                    
                            <div class="dropdown">
                                <button class="btn-icon dropdown-toggle"><i class="ph ph-dots-three-outline-vertical"></i></button>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item btn-edit" 
                                    data-id="<?php echo $lagu['lagu_id']; ?>"
                                    data-judul="<?php echo htmlspecialchars($lagu['judul']); ?>"
                                    data-artist="<?php echo htmlspecialchars($lagu['nama_artist']); ?>"
                                    data-tahun="<?php echo $lagu['tahun']; ?>"
                                    data-genres="<?php echo $genreIdsJson; ?>">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-songs">Belum ada lagu favorit.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <div id="changePasswordModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h2>Ubah Password</h2>
                <button class="close-modal-btn">&times;</button>
            </div>
            <div class="modal-body">
                <form action="../src/actions/update_password.php" method="POST">
                    <div class="form-group">
                        <label for="old_password">Password Lama</label>
                        <input type="password" id="old_password" name="old_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Password Baru (Min. 8 Karakter)</label>
                        <input type="password" id="new_password" name="new_password" required minlength="8">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password Baru</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                    </div>
                    <button type="submit" class="btn-primary block">Simpan Password</button>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/navbar.js"></script>
    <script src="assets/js/main.js"></script>
    <?php if($conn){mysqli_close($conn);} ?>
</body>
</html>