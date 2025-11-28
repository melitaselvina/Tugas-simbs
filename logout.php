<?php
session_start();
// Hancurkan semua variabel sesi
$_SESSION = [];
// Hapus sesi di server
session_unset();
session_destroy();
// Redirect ke halaman login
header("Location: login.php");
exit;
?>