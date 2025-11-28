<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NavBar</title>
    <link rel="stylesheet" href="../assets/css/navbar.css">
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
    <nav class="navbar">
        <div class="logoweb">
            <a href="index.php">
                <img src="assets/image/spotipulogo.png" alt="loogweb">
            </a>
        </div>
        <div id="menu-icon" class="menu-icon">
            <i class="ph ph-list"></i>
        </div>
        <ul id="menu-list" class="hidden">
            <li><a href="index.php">Home</a></li>
            <li><a href="favorit.php">Favorit</a></li>
            <li><a href="toplagu.php">Top Song</a></li>
            <li><a href="topartist.php">Top Artist</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li>
                <a href="../src/auth/logout.php" class="nav-logout" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
                <i class="ph ph-sign-out"></i> Logout
            </a>
            </li>
        </ul>
    </nav>
    <main>
        <!-- isi dari dashboard -->
        <!-- template navbar untuk file2 dashboard -->
    </main>
    <script src="../assets/js/navbar.js"></script>
</body>
</html>