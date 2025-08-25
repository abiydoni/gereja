<?php
require_once '../includes/config.php';

// Hapus semua session
session_destroy();

// Redirect ke halaman login dengan pesan logout
header("Location: login.php?logout=1");
exit();
?>
