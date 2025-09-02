<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';
require_once '../includes/youtube_config.php';

// Cek apakah user sudah login
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$message = '';
$message_type = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'clear_cache':
                if (clearYouTubeCache()) {
                    $message = 'Cache YouTube berhasil dibersihkan!';
                    $message_type = 'success';
                } else {
                    $message = 'Gagal membersihkan cache YouTube.';
                    $message_type = 'error';
                }
                break;
                
            case 'refresh_videos':
                // Force refresh dengan menghapus cache terlebih dahulu
                clearYouTubeCache();
                $videos = getYouTubeVideosWithCache($youtube_config);
                $message = 'Video YouTube berhasil diperbarui!';
                $message_type = 'success';
                break;
                
            case 'test_api':
                $status = getYouTubeAPIStatus($youtube_config);
                if ($status['status'] === 'active') {
                    $message = 'YouTube API berfungsi dengan baik. Video ditemukan: ' . $status['videos_count'];
                    $message_type = 'success';
                } else {
                    $message = 'YouTube API belum dikonfigurasi atau mengalami masalah.';
                    $message_type = 'error';
                }
                break;
        }
    }
}

// Get current status
$api_status = getYouTubeAPIStatus($youtube_config);
$cache_file = '../cache/youtube_videos.json';
$cache_exists = file_exists($cache_file);
$cache_info = [];

if ($cache_exists) {
    $cache_data = json_decode(file_get_contents($cache_file), true);
    $cache_info = [
        'last_updated' => date('Y-m-d H:i:s', $cache_data['timestamp']),
        'videos_count' => count($cache_data['videos']),
        'file_size' => filesize($cache_file)
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Cache Manager - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-amber-900 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="flex items-center space-x-2 text-white hover:text-amber-200 transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                        <span class="text-lg font-semibold">Kembali ke Dashboard</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <i class="fab fa-youtube text-2xl"></i>
                    <span class="text-xl font-bold">YouTube Cache Manager</span>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Message -->
        <?php if (!empty($message)): ?>
        <div class="mb-6 p-4 rounded-lg <?php echo $message_type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'; ?>">
            <div class="flex items-center">
                <i class="fas <?php echo $message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-2"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- API Status -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 <?php echo $api_status['status'] === 'active' ? 'border-green-500' : 'border-red-500'; ?>">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">YouTube API Status</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            <?php echo $api_status['message']; ?>
                        </p>
                        <?php if ($api_status['status'] === 'active'): ?>
                        <p class="text-sm text-green-600 mt-1">
                            Video: <?php echo $api_status['videos_count']; ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <div class="w-12 h-12 rounded-full <?php echo $api_status['status'] === 'active' ? 'bg-green-100' : 'bg-red-100'; ?> flex items-center justify-center">
                        <i class="fas <?php echo $api_status['status'] === 'active' ? 'fa-check text-green-600' : 'fa-times text-red-600'; ?> text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Cache Status -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 <?php echo $cache_exists ? 'border-blue-500' : 'border-gray-400'; ?>">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Cache Status</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            <?php echo $cache_exists ? 'Cache tersedia' : 'Cache tidak tersedia'; ?>
                        </p>
                        <?php if ($cache_exists): ?>
                        <p class="text-sm text-blue-600 mt-1">
                            Terakhir update: <?php echo $cache_info['last_updated']; ?>
                        </p>
                        <p class="text-sm text-blue-600">
                            Video: <?php echo $cache_info['videos_count']; ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <div class="w-12 h-12 rounded-full <?php echo $cache_exists ? 'bg-blue-100' : 'bg-gray-100'; ?> flex items-center justify-center">
                        <i class="fas <?php echo $cache_exists ? 'fa-database text-blue-600' : 'fa-times text-gray-600'; ?> text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Configuration -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-amber-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Konfigurasi</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Channel ID: <?php echo !empty($youtube_config['channel_id']) && $youtube_config['channel_id'] !== 'UCxxxxxxxxxx' ? 'Set' : 'Belum set'; ?>
                        </p>
                        <p class="text-sm text-gray-600">
                            API Key: <?php echo !empty($youtube_config['api_key']) && $youtube_config['api_key'] !== 'YOUR_YOUTUBE_API_KEY_HERE' ? '✅ Set' : '❌ Belum set'; ?>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                        <i class="fas fa-cog text-amber-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Aksi Cache</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <form method="POST" class="flex">
                    <input type="hidden" name="action" value="test_api">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-vial mr-2"></i>
                        Test API
                    </button>
                </form>
                
                <form method="POST" class="flex">
                    <input type="hidden" name="action" value="refresh_videos">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh Video
                    </button>
                </form>
                
                <form method="POST" class="flex" onsubmit="return confirm('Yakin ingin membersihkan cache?')">
                    <input type="hidden" name="action" value="clear_cache">
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-trash mr-2"></i>
                        Clear Cache
                    </button>
                </form>
            </div>
        </div>

        <!-- Configuration Info -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Informasi Konfigurasi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">File Konfigurasi</h4>
                    <p class="text-sm text-gray-600 mb-1">Path: <code class="bg-gray-100 px-2 py-1 rounded">includes/youtube_config.php</code></p>
                    <p class="text-sm text-gray-600 mb-1">Cache: <code class="bg-gray-100 px-2 py-1 rounded">cache/youtube_videos.json</code></p>
                    <p class="text-sm text-gray-600">Max Results: <span class="font-medium"><?php echo $youtube_config['max_results']; ?></span></p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">Cache Settings</h4>
                    <p class="text-sm text-gray-600 mb-1">Duration: <span class="font-medium"><?php echo $youtube_config['cache_duration']; ?> detik</span></p>
                    <p class="text-sm text-gray-600 mb-1">Enabled: <span class="font-medium"><?php echo $youtube_config['enable_cache'] ? 'Ya' : 'Tidak'; ?></span></p>
                    <?php if ($cache_exists): ?>
                    <p class="text-sm text-gray-600">File Size: <span class="font-medium"><?php echo number_format($cache_info['file_size'] / 1024, 2); ?> KB</span></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Link Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="pages/galeri.php" target="_blank" class="flex items-center p-4 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors">
                    <i class="fas fa-images text-amber-600 text-xl mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-gray-800">Lihat Galeri</h4>
                        <p class="text-sm text-gray-600">Buka halaman galeri di tab baru</p>
                    </div>
                </a>
                <a href="YOUTUBE_INTEGRATION_README.md" target="_blank" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <i class="fas fa-book text-blue-600 text-xl mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-gray-800">Panduan Lengkap</h4>
                        <p class="text-sm text-gray-600">Baca panduan integrasi YouTube</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto refresh status setiap 30 detik
        setInterval(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
