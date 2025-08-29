<?php
// Header partial untuk halaman Admin
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!function_exists('isAdminLoggedIn') || !isAdminLoggedIn()) {
    // Cari path login relatif dari lokasi pemanggil (dashboard atau subfolder)
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
    // Default: dari file di admin/*/ pakai ../login.php, dari admin/ langsung login.php
    $loginPath = 'login.php';
    if (isset($backtrace[0]['file'])) {
        $caller = $backtrace[0]['file'];
        if (strpos($caller, DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR) !== false &&
            strpos($caller, DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR) === strrpos($caller, DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR)) {
            // di admin/ langsung
            $loginPath = 'login.php';
        }
        if (strpos($caller, DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR) !== false &&
            preg_match('#' . preg_quote(DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR, '#') . '[^' . preg_quote(DIRECTORY_SEPARATOR, '#') . ']+' . preg_quote(DIRECTORY_SEPARATOR, '#') . '#', $caller)) {
            // di admin/subfolder/
            $loginPath = '../login.php';
        }
    }
    redirect($loginPath);
}

// Ambil nama admin untuk navbar
$adminName = isset($_SESSION['admin_nama']) ? $_SESSION['admin_nama'] : 'Admin';
$baseAdminUrl = rtrim(APP_URL, '/') . '/admin/';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Sistem Gereja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $baseAdminUrl; ?>admin-style.css">
</head>
<body class="bg-gray-50" style="padding-top: 64px;">
    <div class="min-h-screen flex flex-col">
    <nav class="fixed top-0 left-0 right-0 bg-white shadow z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-church text-2xl text-amber-600"></i>
                    <span class="font-bold text-gray-800">Panel Admin</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-700 hidden sm:inline"><i class="fas fa-user mr-2"></i><?php echo htmlspecialchars($adminName); ?></span>
                    <a href="<?php echo $baseAdminUrl; ?>logout.php" class="px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 text-sm">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <div class="flex flex-1">
        <?php require_once __DIR__ . '/sidebar.php'; ?>
        <main class="flex-1 px-4 py-6">

