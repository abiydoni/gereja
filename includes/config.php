<?php
// Konfigurasi Database Gereja
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'gereja_db');

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
