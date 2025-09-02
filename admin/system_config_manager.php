<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/youtube_config.php';

// Get current configuration from database
try {
    require_once '../includes/youtube_config_manager.php';
    $configManager = new YouTubeConfigManager();
    $current_youtube_config = $configManager->getConfig();
} catch (Exception $e) {
    $current_youtube_config = [
        'api_key' => '',
        'channels' => [],
        'channel_id' => '',
        'max_results' => 12,
        'total_videos_to_fetch' => 500,
        'fetch_all_videos' => true,
        'cache_duration' => 3600,
        'enable_cache' => true,
        'search_enabled' => true,
        'multi_channel_enabled' => true
    ];
}

$pageTitle = 'Pengaturan Sistem';
require_once 'partials/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = '';
    $message_type = '';
    
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_youtube_config':
                $message = updateYouTubeConfig($_POST);
                $message_type = 'success';
                break;
                
            case 'update_general_config':
                $message = updateGeneralConfig($_POST);
                $message_type = 'success';
                break;
                
            case 'clear_cache':
                $message = clearAllCache();
                $message_type = 'success';
                break;
                
            case 'test_youtube_api':
                $message = testYouTubeAPI();
                $message_type = 'info';
                break;
        }
    }
}

// Function to update YouTube configuration - 100% DINAMIS dari Database!
function updateYouTubeConfig($data) {
    try {
        require_once '../includes/youtube_config_manager.php';
        $configManager = new YouTubeConfigManager();
        
        if ($configManager->updateConfig($data)) {
            // Clear cache YouTube agar perubahan langsung terlihat
            clearYouTubeCache();
            return 'Konfigurasi YouTube berhasil diperbarui ke database! Cache juga sudah dibersihkan.';
        } else {
            return 'Gagal memperbarui konfigurasi YouTube ke database.';
        }
    } catch (Exception $e) {
        error_log("Error updating YouTube config: " . $e->getMessage());
        return 'Error: ' . $e->getMessage();
    }
}

// Function to update general configuration
function updateGeneralConfig($data) {
    $config_file = '../includes/config.php';
    $config_content = file_get_contents($config_file);
    
    // Update Site Name
    $new_site_name = $data['site_name'] ?? '';
    $config_content = preg_replace(
        "/define\('SITE_NAME', '[^']*'\)/",
        "define('SITE_NAME', '$new_site_name')",
        $config_content
    );
    
    // Update Site Description
    $new_site_desc = $data['site_description'] ?? '';
    $config_content = preg_replace(
        "/define\('SITE_DESCRIPTION', '[^']*'\)/",
        "define('SITE_DESCRIPTION', '$new_site_desc')",
        $config_content
    );
    
    if (file_put_contents($config_file, $config_content)) {
        return 'Konfigurasi umum berhasil diperbarui!';
    } else {
        return 'Gagal memperbarui konfigurasi umum.';
    }
}

// Function to clear all cache
function clearAllCache() {
    $cache_dir = '../cache/';
    $cleared_files = 0;
    
    if (is_dir($cache_dir)) {
        $files = glob($cache_dir . '*.json');
        foreach ($files as $file) {
            if (unlink($file)) {
                $cleared_files++;
            }
        }
    }
    
    return "Cache berhasil dibersihkan! $cleared_files file dihapus.";
}

// Function to test YouTube API
function testYouTubeAPI() {
    $config = getYouTubeConfig();
    
    if (empty($config['api_key'])) {
        return 'YouTube API Key belum dikonfigurasi!';
    }
    
    $test_url = "https://www.googleapis.com/youtube/v3/channels?" . http_build_query([
        'key' => $config['api_key'],
        'id' => $config['channel_id'],
        'part' => 'snippet'
    ]);
    
    $response = @file_get_contents($test_url);
    if ($response === false) {
        return 'Gagal menghubungi YouTube API. Cek koneksi internet.';
    }
    
    $data = json_decode($response, true);
    if (isset($data['error'])) {
        return 'YouTube API Error: ' . $data['error']['message'];
    }
    
    if (isset($data['items'][0])) {
        $channel_name = $data['items'][0]['snippet']['title'];
        return "YouTube API berfungsi dengan baik! Channel: $channel_name";
    }
    
    return 'YouTube API berfungsi tapi channel tidak ditemukan.';
}

// Get current configuration values
$current_youtube_config = getYouTubeConfig();
$current_site_name = defined('SITE_NAME') ? SITE_NAME : 'Gereja Kristen Jawa Randuares';
$current_site_description = defined('SITE_DESCRIPTION') ? SITE_DESCRIPTION : 'Website resmi GKJ Randuares';
?>

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Pengaturan Sistem - 100% DINAMIS!</h1>
    <p class="text-gray-600">Kelola konfigurasi YouTube API, pengaturan website, dan manajemen cache</p>
</div>

<?php if (isset($message)): ?>
    <div class="mb-6 p-4 rounded-lg <?php echo $message_type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-blue-100 text-blue-800 border border-blue-200'; ?>">
        <i class="fas fa-info-circle mr-2"></i><?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<!-- Configuration Tabs -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8 px-6">
            <button onclick="showTab('youtube')" class="tab-btn active py-4 px-1 border-b-2 border-amber-500 text-amber-600 font-medium">
                <i class="fab fa-youtube mr-2"></i>YouTube
            </button>
            <button onclick="showTab('general')" class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium">
                <i class="fas fa-cog mr-2"></i>Umum
            </button>
            <button onclick="showTab('cache')" class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium">
                <i class="fas fa-database mr-2"></i>Cache
            </button>
        </nav>
    </div>

    <!-- YouTube Configuration Tab -->
    <div id="youtube-tab" class="tab-content p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Konfigurasi YouTube API - 100% DINAMIS!</h2>
        
        <!-- Status Konfigurasi Saat Ini -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <h3 class="font-medium text-gray-800 mb-3">Status Konfigurasi Saat Ini</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">API Key:</span>
                    <span class="ml-2 font-medium <?php echo !empty($current_youtube_config['api_key']) ? 'text-green-600' : 'text-red-600'; ?>">
                        <?php echo !empty($current_youtube_config['api_key']) ? '✓ Terkonfigurasi' : '✗ Belum Dikonfigurasi'; ?>
                    </span>
                </div>
                <div>
                    <span class="text-gray-600">Channel ID Utama:</span>
                    <span class="ml-2 font-medium <?php echo !empty($current_youtube_config['channel_id']) ? 'text-green-600' : 'text-red-600'; ?>">
                        <?php echo !empty($current_youtube_config['channel_id']) ? '✓ Terkonfigurasi' : '✗ Belum Dikonfigurasi'; ?>
                    </span>
                </div>
                <div>
                    <span class="text-gray-600">Total Channel:</span>
                    <span class="ml-2 font-medium text-blue-600">
                        <?php echo isset($current_youtube_config['channels']) ? count($current_youtube_config['channels']) : 0; ?> channel
                    </span>
                </div>
                <div>
                    <span class="text-gray-600">Multi-Channel:</span>
                    <span class="ml-2 font-medium <?php echo $current_youtube_config['multi_channel_enabled'] ? 'text-green-600' : 'text-gray-600'; ?>">
                        <?php echo $current_youtube_config['multi_channel_enabled'] ? '✓ Aktif' : '✗ Nonaktif'; ?>
                    </span>
                </div>
            </div>
        </div>
        
        <form method="POST" class="space-y-6">
            <input type="hidden" name="action" value="update_youtube_config">
            
            <!-- API Key -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    YouTube API Key <span class="text-red-500">*</span>
                </label>
                <input type="text" name="youtube_api_key" value="<?php echo htmlspecialchars($current_youtube_config['api_key']); ?>" 
                       class="form-input" 
                       placeholder="Masukkan YouTube API Key">
                <p class="text-sm text-gray-500 mt-1">Dapatkan API Key dari <a href="https://console.developers.google.com/" target="_blank" class="text-amber-600 hover:underline">Google Cloud Console</a></p>
            </div>

            <!-- Channel ID Utama -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Channel ID Utama <span class="text-red-500">*</span>
                </label>
                <input type="text" name="youtube_channel_id" value="<?php echo htmlspecialchars($current_youtube_config['channel_id']); ?>" 
                       class="form-input" 
                       placeholder="UCxxxxxxxxxx">
                <p class="text-sm text-gray-500 mt-1">Channel ID YouTube utama gereja (format: UCxxxxxxxxxx)</p>
            </div>

            <!-- Max Results -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Video per Halaman
                </label>
                <input type="number" name="youtube_max_results" value="<?php echo $current_youtube_config['max_results']; ?>" 
                       min="6" max="50" 
                       class="form-input">
            </div>

            <!-- Cache Duration -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Durasi Cache (detik)
                </label>
                <input type="number" name="youtube_cache_duration" value="<?php echo $current_youtube_config['cache_duration']; ?>" 
                       min="300" max="86400" 
                       class="form-input">
                <p class="text-sm text-gray-500 mt-1">300 = 5 menit, 3600 = 1 jam, 86400 = 1 hari</p>
            </div>

            <!-- Total Videos to Fetch -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Total Video yang Diambil
                </label>
                <input type="number" name="youtube_total_videos" value="<?php echo isset($current_youtube_config['total_videos_to_fetch']) ? (int)$current_youtube_config['total_videos_to_fetch'] : ((int)($current_youtube_config['total_videos'] ?? 500)); ?>" 
                       min="50" max="1000" 
                       class="form-input">
                <p class="text-sm text-gray-500 mt-1">Jumlah total video yang akan diambil dari YouTube (50-1000)</p>
            </div>

            <!-- Fetch All Videos -->
            <div class="flex items-center">
                <input type="checkbox" name="youtube_fetch_all_videos" id="fetch_all_videos" 
                       <?php echo $current_youtube_config['fetch_all_videos'] ? 'checked' : ''; ?> 
                       class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                <label for="fetch_all_videos" class="ml-2 block text-sm text-gray-900">
                    Ambil Semua Video (Gunakan pagination YouTube)
                </label>
            </div>

            <!-- Multi-Channel Configuration -->
            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                <h3 class="font-medium text-gray-800 mb-4">Konfigurasi Multi-Channel</h3>
                
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="youtube_multi_channel_enabled" id="multi_channel" 
                           <?php echo $current_youtube_config['multi_channel_enabled'] ? 'checked' : ''; ?> 
                           class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                    <label for="multi_channel" class="ml-2 block text-sm text-gray-900">
                        Aktifkan Multi-Channel
                    </label>
                </div>

                <div id="channels-container" class="space-y-3">
                    <?php if (!empty($current_youtube_config['channels'])): ?>
                        <?php foreach ($current_youtube_config['channels'] as $index => $channel): ?>
                        <div class="channel-item flex items-center space-x-3 p-3 bg-white rounded border">
                            <div class="flex-1">
                                <input type="text" name="channels[<?php echo $index; ?>][id]" 
                                       value="<?php echo htmlspecialchars($channel['id']); ?>" 
                                       placeholder="Channel ID (UCxxxxxxxxxx)" 
                                       class="form-input text-sm">
                            </div>
                            <div class="flex-1">
                                <input type="text" name="channels[<?php echo $index; ?>][name]" 
                                       value="<?php echo htmlspecialchars($channel['name']); ?>" 
                                       placeholder="Nama Channel" 
                                       class="form-input text-sm">
                            </div>
                            <div class="flex-1">
                                <input type="text" name="channels[<?php echo $index; ?>][url]" 
                                       value="<?php echo htmlspecialchars($channel['url']); ?>" 
                                       placeholder="URL Channel" 
                                       class="form-input text-sm">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="channels[<?php echo $index; ?>][active]" 
                                       <?php echo isset($channel['active']) && $channel['active'] ? 'checked' : ''; ?> 
                                       class="h-4 w-4 text-amber-600">
                                <label class="ml-1 text-sm text-gray-700">Aktif</label>
                            </div>
                            <button type="button" onclick="removeChannel(this)" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <button type="button" onclick="addChannel()" class="mt-3 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                    <i class="fas fa-plus mr-2"></i>Tambah Channel
                </button>
            </div>

            <!-- Other Checkboxes -->
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" name="youtube_enable_cache" id="enable_cache" 
                           <?php echo $current_youtube_config['enable_cache'] ? 'checked' : ''; ?> 
                           class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                    <label for="enable_cache" class="ml-2 block text-sm text-gray-900">
                        Aktifkan Cache
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="youtube_search_enabled" id="search_enabled" 
                           <?php echo $current_youtube_config['search_enabled'] ? 'checked' : ''; ?> 
                           class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                    <label for="search_enabled" class="ml-2 block text-sm text-gray-900">
                        Aktifkan Fitur Pencarian
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-4">
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Konfigurasi
                </button>
                
                <button type="button" onclick="testYouTubeAPI()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-play mr-2"></i>Test API
                </button>
            </div>
        </form>
    </div>

    <!-- General Configuration Tab -->
    <div id="general-tab" class="tab-content hidden p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Konfigurasi Umum Website</h2>
        
        <form method="POST" class="space-y-6">
            <input type="hidden" name="action" value="update_general_config">
            
            <!-- Site Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Website
                </label>
                <input type="text" name="site_name" value="<?php echo htmlspecialchars($current_site_name); ?>" 
                       class="form-input" 
                       placeholder="Nama website gereja">
            </div>

            <!-- Site Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Website
                </label>
                <textarea name="site_description" rows="3" 
                          class="form-input" 
                          placeholder="Deskripsi singkat website gereja"><?php echo htmlspecialchars($current_site_description); ?></textarea>
            </div>

            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i>Simpan Konfigurasi
            </button>
        </form>
    </div>

    <!-- Cache Management Tab -->
    <div id="cache-tab" class="tab-content hidden p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Manajemen Cache</h2>
        
        <div class="space-y-6">
            <!-- Cache Info -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-medium text-gray-800 mb-2">Informasi Cache</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Status Cache:</span>
                        <span class="ml-2 font-medium text-green-600">Aktif</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Durasi Cache:</span>
                        <span class="ml-2 font-medium"><?php echo $current_youtube_config['cache_duration']; ?> detik</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Lokasi Cache:</span>
                        <span class="ml-2 font-medium">/cache/</span>
                    </div>
                    <div>
                        <span class="text-gray-600">File Cache:</span>
                        <span class="ml-2 font-medium">youtube_videos.json</span>
                    </div>
                </div>
            </div>

            <!-- Cache Actions -->
            <div class="space-y-4">
                <form method="POST" class="inline">
                    <input type="hidden" name="action" value="clear_cache">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition-colors" 
                            onclick="return confirm('Yakin ingin menghapus semua cache?')">
                        <i class="fas fa-trash mr-2"></i>Hapus Semua Cache
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showTab(tabName) {
        // Hide all tab contents
        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => content.classList.add('hidden'));
        
        // Remove active class from all tab buttons
        const tabBtns = document.querySelectorAll('.tab-btn');
        tabBtns.forEach(btn => {
            btn.classList.remove('active', 'border-amber-500', 'text-amber-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show selected tab content
        document.getElementById(tabName + '-tab').classList.remove('hidden');
        
        // Add active class to selected tab button
        event.target.classList.add('active', 'border-amber-500', 'text-amber-600');
        event.target.classList.remove('border-transparent', 'text-gray-500');
    }

    function testYouTubeAPI() {
        // This will be handled by the form submission
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = '<input type="hidden" name="action" value="test_youtube_api">';
        document.body.appendChild(form);
        form.submit();
    }

    // Fungsi untuk menambah channel baru
    function addChannel() {
        const container = document.getElementById('channels-container');
        const channelCount = container.children.length;
        
        const channelHtml = `
            <div class="channel-item flex items-center space-x-3 p-3 bg-white rounded border">
                <div class="flex-1">
                    <input type="text" name="channels[${channelCount}][id]" 
                           placeholder="Channel ID (UCxxxxxxxxxx)" 
                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                </div>
                <div class="flex-1">
                    <input type="text" name="channels[${channelCount}][name]" 
                           placeholder="Nama Channel" 
                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                </div>
                <div class="flex-1">
                    <input type="text" name="channels[${channelCount}][url]" 
                           placeholder="URL Channel" 
                           class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="channels[${channelCount}][active]" 
                           checked class="h-4 w-4 text-amber-600">
                    <label class="ml-1 text-sm text-gray-700">Aktif</label>
                </div>
                <button type="button" onclick="removeChannel(this)" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', channelHtml);
    }

    // Fungsi untuk menghapus channel
    function removeChannel(button) {
        button.closest('.channel-item').remove();
    }
</script>

<?php require_once 'partials/footer.php'; ?>
