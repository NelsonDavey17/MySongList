<?php
require_once __DIR__ . '/../../src/config.php';
require_once __DIR__. '/../../src/functions/lagu_functions.php';
$daftarGenre = [];
if(function_exists('getGenres')){
  $daftarGenre = getGenres($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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
              <input type="number" id="edit_tahun" name="tahun" min="1901" max="2025">
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
</body>
</html>