<?php
session_start();

// Hapus semua session
session_destroy();

// Hapus cookies jika ada
if (isset($_COOKIE['remember_username'])) {
    setcookie('remember_username', '', time() - 3600, "/");
}
if (isset($_COOKIE['admin_password'])) {
    setcookie('admin_password', '', time() - 3600, "/");
}

// Redirect ke halaman login
header("Location: login.php");
exit();
?>