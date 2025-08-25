<?php
require_once '../../includes/config.php';
require_once '../../includes/functions.php';

// Cek login admin
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Redirect ke dashboard untuk sementara
redirect('../dashboard.php');
?>
