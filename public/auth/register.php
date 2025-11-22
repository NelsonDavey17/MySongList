<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
$old_username = htmlspecialchars($_GET['username'] ?? '');
$old_email = htmlspecialchars($_GET['email'] ?? '');
$popup_status = $_GET['status'] ?? '';
$popup_msg = urldecode($_GET['msg'] ?? '');
$php_error_msg = htmlspecialchars(urldecode($_GET['error'] ?? ''));
$php_success_msg = htmlspecialchars(urldecode($_GET['success'] ?? ''));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun - MySongList</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <h2>Buat Akun Baru</h2>
        <p>Bergabunglah dengan komunitas MySongList!</p>
        <form id="registerForm" action="../../src/auth/proses_register.php" method="POST">
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message"><?php echo htmlspecialchars(urldecode($_GET['error'])); ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['success'])): ?>
                <div class="success-message"><?php echo htmlspecialchars(urldecode($_GET['success'])); ?></div>
            <?php endif; ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo $old_username; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $old_email; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required minlength="8" placeholder="Minimal 8 karakter">
            </div>
            <div class="form-group">
                <label for="confirm_password">Konfirmasi</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8" placeholder="Minimal 8 karakter">
            </div>
            <div class="form-group">
                <label for="gender">Gender (Opsional)</label>
                <select id="gender" name="gender">
                    <option value="">-Pilih Gender-</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="auth-button">Daftar</button>
            </div>
        </form>
        <p class="auth-switch">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
    <div class="popup" id="popup">
        <div class="popup-content">
            <h3 id="popup-title"></h3>
            <p id="popup-message"></p>
            <button onclick="closePopup()">Tutup</button>
        </div>
    </div>
    <script src="../assets/js/register.js"></script>
    <?php
    if (!empty($php_error_msg)) {
        echo "<script>showPopup('error', '$php_error_msg');</script>";
    } elseif (!empty($php_success_msg)) {
        echo "<script>showPopup('success', '$php_success_msg');</script>";
    }
    ?>
</body>
</html>