<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/youtube_config.php';

// Ambil video YouTube dengan pagination, pencarian, dan channel filter
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$channel_filter = isset($_GET['channel_filter']) ? trim($_GET['channel_filter']) : '';

// Jika ada pencarian, gunakan fungsi search
if (!empty($search_query) && $youtube_config['search_enabled']) {
            $youtube_data = searchYouTubeVideos($search_query, $youtube_config, $current_page);
} else {
    $youtube_data = getYouTubeVideosWithPagination($youtube_config, $current_page);
}

// Filter video berdasarkan channel jika ada filter
if (!empty($channel_filter) && $youtube_config['multi_channel_enabled']) {
    $filtered_videos = array_filter($youtube_data['videos'], function($video) use ($channel_filter) {
        return isset($video['channel_id']) && $video['channel_id'] === $channel_filter;
    });
    
    // Update pagination untuk video yang sudah difilter
    $total_filtered = count($filtered_videos);
    $videos_per_page = $youtube_config['max_results'];
    $total_pages = ceil($total_filtered / $videos_per_page);
    $start_index = ($current_page - 1) * $videos_per_page;
    $page_videos = array_slice($filtered_videos, $start_index, $videos_per_page);
    
    $youtube_data['videos'] = $page_videos;
    $youtube_data['pagination'] = [
        'total_videos' => $total_filtered,
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'videos_per_page' => $videos_per_page,
        'has_previous' => $current_page > 1,
        'has_next' => $current_page < $total_pages,
        'previous_page' => $current_page > 1 ? $current_page - 1 : null,
        'next_page' => $current_page < $total_pages ? $current_page + 1 : null
    ];
}

$youtube_videos = $youtube_data['videos'];
$pagination = $youtube_data['pagination'];

// Ambil informasi channel YouTube
$youtube_channel_info = getYouTubeChannelInfo($youtube_config);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - <?php echo getNamaGereja(); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

</head>
<body class="bg-gray-50" style="padding-top: 80px;">
    <nav class="fixed top-0 left-0 right-0 bg-white shadow-lg z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <a href="../" class="flex items-center space-x-2 text-gray-600 hover:text-amber-600 transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                        <span class="text-lg font-semibold">Kembali ke Beranda</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <img src="<?php echo getLogoPath(); ?>" alt="Logo Gereja" class="w-10 h-10 object-contain logo-amber" style="filter: invert(33%) sepia(85%) saturate(900%) hue-rotate(8deg) brightness(92%) contrast(95%) !important;">
                    <span class="text-xl font-bold text-gray-800">Galeri</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <section class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4" data-aos="fade-up">Galeri Video YouTube</h1>
            <p class="text-xl opacity-90" data-aos="fade-up" data-aos-delay="150">Koleksi video rohani dan kegiatan gereja dari channel YouTube</p>
        </div>
    </section>

    <!-- YouTube Videos Content -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <?php if (empty($youtube_videos)): ?>
            <div class="text-center py-12 bg-white rounded-xl shadow" data-aos="fade-up">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fab fa-youtube text-2xl text-red-600"></i>
                </div>
                <p class="text-gray-600">Belum ada video YouTube.</p>
                <p class="text-sm text-gray-500 mt-2">Hubungi admin untuk mengatur integrasi YouTube</p>
            </div>
            <?php else: ?>
            <!-- YouTube Configuration Status -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl shadow-lg p-6 mb-6" data-aos="fade-up">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-blue-800 mb-2">⚙️ Status Konfigurasi YouTube</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-key text-blue-600"></i>
                                <span class="text-gray-700">API Key: 
                                    <span class="font-semibold <?php echo !empty($youtube_config['api_key']) && $youtube_config['api_key'] !== 'YOUR_YOUTUBE_API_KEY_HERE' ? 'text-green-600' : 'text-red-600'; ?>">
                                        <?php echo !empty($youtube_config['api_key']) && $youtube_config['api_key'] !== 'YOUR_YOUTUBE_API_KEY_HERE' ? '✓ Terkonfigurasi' : '✗ Belum Dikonfigurasi'; ?>
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-tv text-blue-600"></i>
                                <span class="text-gray-700">Multi-Channel: 
                                    <span class="font-semibold <?php echo $youtube_config['multi_channel_enabled'] ? 'text-green-600' : 'text-gray-600'; ?>">
                                        <?php echo $youtube_config['multi_channel_enabled'] ? '✓ Aktif' : '✗ Nonaktif'; ?>
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-search text-blue-600"></i>
                                <span class="text-gray-700">Pencarian: 
                                    <span class="font-semibold <?php echo $youtube_config['search_enabled'] ? 'text-green-600' : 'text-gray-600'; ?>">
                                        <?php echo $youtube_config['search_enabled'] ? '✓ Aktif' : '✗ Nonaktif'; ?>
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-database text-blue-600"></i>
                                <span class="text-gray-700">Cache: 
                                    <span class="font-semibold <?php echo $youtube_config['enable_cache'] ? 'text-green-600' : 'text-gray-600'; ?>">
                                        <?php echo $youtube_config['enable_cache'] ? '✓ Aktif (' . gmdate('H:i:s', $youtube_config['cache_duration']) . ')' : '✗ Nonaktif'; ?>
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-tv text-blue-600"></i>
                                <span class="text-gray-700">Channel Aktif: 
                                    <span class="font-semibold text-green-600">
                                        <?php 
                                        $active_channels_count = 0;
                                        if (isset($youtube_config['channels'])) {
                                            foreach ($youtube_config['channels'] as $channel) {
                                                if (isset($channel['active']) && $channel['active']) {
                                                    $active_channels_count++;
                                                }
                                            }
                                        }
                                        echo $active_channels_count . ' Channel';
                                        ?>
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-list text-blue-600"></i>
                                <span class="text-gray-700">Video per Halaman: 
                                    <span class="font-semibold text-green-600">
                                        <?php echo $youtube_config['max_results']; ?> Video
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-download text-blue-600"></i>
                                <span class="text-gray-700">Total Video API: 
                                    <span class="font-semibold text-green-600">
                                        <?php echo number_format($youtube_config['total_videos_to_fetch']); ?> Video
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-infinity text-blue-600"></i>
                                <span class="text-gray-700">Fetch All Videos: 
                                    <span class="font-semibold <?php echo $youtube_config['fetch_all_videos'] ? 'text-green-600' : 'text-gray-600'; ?>">
                                        <?php echo $youtube_config['fetch_all_videos'] ? '✓ Aktif' : '✗ Nonaktif'; ?>
                                    </span>
                                </span>
                            </div>
                            <?php 
                            $inactive_channels = [];
                            if (isset($youtube_config['channels'])) {
                                foreach ($youtube_config['channels'] as $channel) {
                                    if (!isset($channel['active']) || !$channel['active']) {
                                        $inactive_channels[] = $channel;
                                    }
                                }
                            }
                            if (!empty($inactive_channels)): 
                            ?>
                            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center space-x-2 text-yellow-800">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span class="font-medium">Channel Nonaktif:</span>
                                </div>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <?php foreach ($inactive_channels as $channel): ?>
                                    <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">
                                        <?php echo htmlspecialchars($channel['name']); ?> (<?php echo htmlspecialchars($channel['id']); ?>)
                                    </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Cache File Status -->
                        <?php if ($youtube_config['enable_cache']): ?>
                        <div class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2 text-gray-700">
                                    <i class="fas fa-file-alt"></i>
                                    <span class="font-medium">Status Cache File:</span>
                                </div>
                                <div class="text-right">
                                    <?php 
                                    $cache_file = '../cache/youtube_videos.json';
                                    if (file_exists($cache_file)) {
                                        $cache_size = filesize($cache_file);
                                        $cache_time = filemtime($cache_file);
                                        $cache_age = time() - $cache_time;
                                        $cache_status = $cache_age < $youtube_config['cache_duration'] ? 'Valid' : 'Expired';
                                        $status_color = $cache_status === 'Valid' ? 'text-green-600' : 'text-red-600';
                                        echo '<span class="font-semibold ' . $status_color . '">' . $cache_status . '</span>';
                                        echo '<br><span class="text-xs text-gray-500">' . number_format($cache_size) . ' bytes, ' . gmdate('H:i:s', $cache_age) . ' ago</span>';
                                    } else {
                                        echo '<span class="font-semibold text-gray-600">Tidak Ada</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="text-right space-x-2">
                        <a href="../admin/system_config_manager.php" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            <i class="fas fa-cog mr-2"></i>Pengaturan
                        </a>
                        <button onclick="refreshCache()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh Cache
                        </button>
                    </div>
                </div>
            </div>

            <!-- Channel Info -->
            <?php if ($youtube_channel_info): ?>
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8" data-aos="fade-up">
                <?php if ($youtube_channel_info['type'] === 'multi_channel'): ?>
                <!-- Multi-Channel Info -->
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">📺 Multi-Channel YouTube</h2>
                    <p class="text-gray-600">Menampilkan video dari <?php echo $youtube_channel_info['total_channels']; ?> channel aktif</p>
                </div>
                
                <!-- Combined Statistics -->
                <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-center space-x-8 text-center">
                        <div>
                            <div class="text-2xl font-bold text-red-600"><?php echo number_format($youtube_channel_info['combined_statistics']['subscriber_count']); ?></div>
                            <div class="text-sm text-gray-600">Total Subscriber</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-red-600"><?php echo number_format($youtube_channel_info['combined_statistics']['video_count']); ?></div>
                            <div class="text-sm text-gray-600">Total Video</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-red-600"><?php echo number_format($youtube_channel_info['combined_statistics']['view_count']); ?></div>
                            <div class="text-sm text-gray-600">Total View</div>
                        </div>
                    </div>
                </div>
                
                <!-- Individual Channels -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($youtube_channel_info['channels'] as $channel): ?>
                    <?php 
                    // Cari konfigurasi channel yang sesuai
                    $channel_config = null;
                    foreach ($youtube_config['channels'] as $config_channel) {
                        if ($config_channel['id'] === $channel['id']) {
                            $channel_config = $config_channel;
                            break;
                        }
                    }
                    ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center space-x-3">
                            <img src="<?php echo htmlspecialchars($channel['thumbnail']); ?>" alt="<?php echo htmlspecialchars($channel['title']); ?>" class="w-12 h-12 rounded-full">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800"><?php echo htmlspecialchars($channel['title']); ?></h3>
                                <div class="flex items-center space-x-3 text-xs text-gray-500 mt-1">
                                    <span><i class="fas fa-users mr-1"></i><?php echo number_format($channel['statistics']['subscriber_count']); ?></span>
                                    <span><i class="fas fa-video mr-1"></i><?php echo number_format($channel['statistics']['video_count']); ?></span>
                                </div>
                                <?php if ($channel_config && !empty($channel_config['url'])): ?>
                                <div class="mt-2">
                                    <a href="<?php echo htmlspecialchars($channel_config['url']); ?>" target="_blank" class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-800 hover:bg-red-200 transition-colors">
                                        <i class="fab fa-youtube mr-1"></i>Kunjungi Channel
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <?php else: ?>
                <!-- Single Channel Info -->
                <div class="flex items-center space-x-4">
                    <img src="<?php echo htmlspecialchars($youtube_channel_info['thumbnail']); ?>" alt="<?php echo htmlspecialchars($youtube_channel_info['title']); ?>" class="w-16 h-16 rounded-full">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($youtube_channel_info['title']); ?></h2>
                        <p class="text-gray-600 mt-1"><?php echo htmlspecialchars(substr($youtube_channel_info['description'], 0, 200)) . '...'; ?></p>
                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                            <span><i class="fas fa-users mr-1"></i><?php echo number_format($youtube_channel_info['statistics']['subscriber_count']); ?> subscriber</span>
                            <span><i class="fas fa-video mr-1"></i><?php echo number_format($youtube_channel_info['statistics']['video_count']); ?> video</span>
                            <span><i class="fas fa-eye mr-1"></i><?php echo number_format($youtube_channel_info['statistics']['view_count']); ?> view</span>
                        </div>
                    </div>
                    <?php 
                    // Cari URL channel dari konfigurasi
                    $channel_url = '';
                    if (isset($youtube_config['channels'])) {
                        foreach ($youtube_config['channels'] as $config_channel) {
                            if ($config_channel['id'] === $youtube_channel_info['id']) {
                                $channel_url = $config_channel['url'];
                                break;
                            }
                        }
                    }
                    // Fallback ke channel ID utama jika tidak ada URL
                    if (empty($channel_url)) {
                        $channel_url = 'https://www.youtube.com/channel/' . $youtube_channel_info['id'];
                    }
                    ?>
                    <a href="<?php echo htmlspecialchars($channel_url); ?>" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fab fa-youtube mr-2"></i>Kunjungi Channel
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Search and Filter Form -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8" data-aos="fade-up">
                <form method="GET" action="" class="space-y-4">
                    <input type="hidden" name="page" value="1">
                    
                    <!-- Search Row -->
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Cari video berdasarkan judul atau deskripsi..." class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                                <i class="fas fa-search mr-2"></i>Cari
                            </button>
                            <?php if (!empty($search_query)): ?>
                            <a href="?page=1" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors flex items-center">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Channel Filter Row -->
                    <?php if ($youtube_config['multi_channel_enabled'] && isset($youtube_config['channels']) && count($youtube_config['channels']) > 1): ?>
                    <div class="border-t pt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Channel:</label>
                        <div class="flex flex-wrap gap-2">
                            <label class="flex items-center">
                                <input type="radio" name="channel_filter" value="" <?php echo empty($_GET['channel_filter']) ? 'checked' : ''; ?> class="mr-2">
                                <span class="text-sm">Semua Channel</span>
                            </label>
                            <?php foreach ($youtube_config['channels'] as $channel): ?>
                            <?php if (isset($channel['active']) && $channel['active']): ?>
                            <label class="flex items-center">
                                <input type="radio" name="channel_filter" value="<?php echo htmlspecialchars($channel['id']); ?>" <?php echo (isset($_GET['channel_filter']) && $_GET['channel_filter'] === $channel['id']) ? 'checked' : ''; ?> class="mr-2">
                                <span class="text-sm"><?php echo htmlspecialchars($channel['name']); ?></span>
                            </label>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
            
            <!-- Video Stats -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl p-4 mb-8" data-aos="fade-up">
                <div class="text-center">
                    <h3 class="text-lg font-semibold mb-2">
                        <?php if (!empty($search_query)): ?>
                        🔍 Hasil Pencarian Video
                        <?php else: ?>
                        📺 Semua Video dari Channel
                        <?php endif; ?>
                    </h3>
                    <p class="text-sm opacity-90">
                        <?php if (!empty($search_query)): ?>
                        Menampilkan <?php echo number_format($pagination['total_videos']); ?> video untuk pencarian: <strong>"<?php echo htmlspecialchars($search_query); ?>"</strong>
                        <?php else: ?>
                        Menampilkan <?php echo number_format($pagination['total_videos']); ?> video dengan pagination
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <!-- Video Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($youtube_videos as $video): ?>
                <div class="bg-white rounded-xl shadow-lg border border-red-200 overflow-hidden video-card" data-aos="fade-up">
                    <div class="relative group">
                        <img src="<?php echo htmlspecialchars($video['thumbnail']); ?>" alt="<?php echo htmlspecialchars($video['title']); ?>" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                            <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 play-button">
                                <i class="fas fa-play text-white text-xl"></i>
                            </div>
                        </div>
                        <button onclick="playVideo('<?php echo $video['id']; ?>', '<?php echo htmlspecialchars($video['title']); ?>')" class="absolute inset-0 w-full h-full"></button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2"><?php echo htmlspecialchars($video['title']); ?></h3>
                        <p class="text-sm text-gray-600 mb-2 line-clamp-2"><?php echo htmlspecialchars(substr($video['description'], 0, 100)) . '...'; ?></p>
                        
                        <!-- Channel Info -->
                        <?php if (isset($video['channel_name']) && !empty($video['channel_name'])): ?>
                        <div class="mb-2">
                            <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                <i class="fas fa-tv mr-1"></i><?php echo htmlspecialchars($video['channel_name']); ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-800"><?php echo formatTanggalIndonesia(date('Y-m-d', strtotime($video['published_at']))); ?></span>
                            <a href="https://www.youtube.com/watch?v=<?php echo $video['id']; ?>" target="_blank" class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-800 hover:bg-red-100 hover:text-red-800 transition-colors">
                                <i class="fab fa-youtube mr-1"></i>Tonton
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
                         <!-- Pagination -->
             <?php if ($pagination['total_pages'] > 1): ?>
             <div class="mt-8">
                 <?php echo generatePaginationHTML($pagination, 'galeri.php', $search_query, $channel_filter); ?>
             </div>
             <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Video Modal -->
    <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4 video-modal">
        <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-hidden video-modal-content">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 id="videoTitle" class="text-lg font-semibold text-gray-800"></h3>
                <button onclick="closeVideoModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="relative" style="padding-bottom: 56.25%;">
                <iframe id="videoFrame" class="absolute top-0 left-0 w-full h-full" src="" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <footer class="bg-amber-900 text-amber-50 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2025 Gereja Kristen Jawa Randuares. Semua hak dilindungi. | <a href="https://appsbee.my.id" target="_blank" class="text-amber-200 hover:text-amber-100 transition-colors">appsBee</a></p>
        </div>
    </footer>

    <script>
        AOS.init({ duration: 800, once: true });

        // Video modal functionality
        function playVideo(videoId, title) {
            document.getElementById('videoTitle').textContent = title;
            document.getElementById('videoFrame').src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
            document.getElementById('videoModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeVideoModal() {
            document.getElementById('videoModal').classList.add('hidden');
            document.getElementById('videoFrame').src = '';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('videoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeVideoModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeVideoModal();
            }
        });

        // Refresh cache functionality
        function refreshCache() {
            if (confirm('Apakah Anda yakin ingin me-refresh cache YouTube? Ini akan mengambil data terbaru dari API.')) {
                // Redirect ke halaman admin dengan parameter clear cache
                window.location.href = '../admin/system_config_manager.php?action=clear_cache&redirect=galeri';
            }
        }

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.querySelector('form[method="GET"]');
            const searchInput = document.querySelector('input[name="search"]');
            
            // Auto-submit search on Enter key
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchForm.submit();
                    }
                });
            }
            
            // Highlight search terms in video titles and descriptions
            const searchQuery = '<?php echo addslashes($search_query); ?>';
            if (searchQuery) {
                const videoTitles = document.querySelectorAll('.video-card h3');
                const videoDescriptions = document.querySelectorAll('.video-card p');
                
                videoTitles.forEach(function(title) {
                    title.innerHTML = title.innerHTML.replace(
                        new RegExp(searchQuery, 'gi'),
                        '<mark class="bg-yellow-200 px-1 rounded">$&</mark>'
                    );
                });
                
                videoDescriptions.forEach(function(desc) {
                    desc.innerHTML = desc.innerHTML.replace(
                        new RegExp(searchQuery, 'gi'),
                        '<mark class="bg-yellow-200 px-1 rounded">$&</mark>'
                    );
                });
            }
        });
    </script>
</body>
</html>
