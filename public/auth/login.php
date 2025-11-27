<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
$success_msg = htmlspecialchars(urldecode($_GET['success'] ?? ''));
$error_msg = htmlspecialchars(urldecode($_GET['error'] ?? ''));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MySongList</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
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
    
    <div class="auth-container">
        <h2>Login MySongList</h2>
        <p>Silakan Login untuk melanjutkan</p>

        <form id="loginForm" action="../../src/auth/proses_login.php" method="POST">
            
            <?php if (!empty($error_msg)): ?>
                <div class="error-message"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <?php if (!empty($success_msg)): ?>
                <div class="success-message"><?php echo $success_msg; ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="flexbox">
                    <input type="password" id="password" name="password" required placeholder="Masukkan Password...">
                    <i class="ph ph-eye-slash" id="eyeicon"></i>
                </div>                
            </div>

            <div class="form-group">
                <button type="submit" class="auth-button">Login</button>
            </div>
        </form>

        <p class="auth-switch">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
    <script src="../assets/js/register.js"></script>
</body>
</html>