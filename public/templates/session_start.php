<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
//biar ndak repot bentrok session_start di file lain
?>