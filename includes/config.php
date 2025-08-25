<?php
// Konfigurasi Database Gereja
define('DB_HOST', 'localhost');
define('DB_USER', 'appsbeem_admin');
define('DB_PASS', 'A7by777__');
define('DB_NAME', 'appsbeem_gereja');

// Konfigurasi Aplikasi
define('APP_NAME', 'Sistem Gereja');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/gereja');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Error Reporting (set false untuk production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
