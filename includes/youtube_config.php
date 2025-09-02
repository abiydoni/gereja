<?php
/**
 * Konfigurasi YouTube API untuk Galeri
 * 
 * File ini berisi fungsi-fungsi YouTube API
 * Konfigurasi 100% dinamis dari halaman admin "Pengaturan Sistem"
 */

// Fungsi untuk mendapatkan konfigurasi YouTube dari database - 100% DINAMIS!
function getYouTubeConfig() {
    try {
        require_once __DIR__ . '/youtube_config_manager.php';
        $configManager = new YouTubeConfigManager();
        return $configManager->getConfig();
    } catch (Exception $e) {
        error_log("Error loading YouTube config from database: " . $e->getMessage());
        // Fallback ke konfigurasi default jika database error
        return [
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
}

// Dapatkan konfigurasi saat ini
$youtube_config = getYouTubeConfig();

// Fungsi sederhana untuk mengambil video dari channel tertentu (untuk index.php)
function getYouTubeVideos($channel_id, $limit = 4) {
    try {
        $config = getYouTubeConfig();
        
        if (empty($config['api_key'])) {
            error_log('YouTube API key tidak tersedia');
            return [];
        }
        
        // Gunakan channel ID yang diberikan
        $params = [
            'key' => $config['api_key'],
            'channelId' => $channel_id,
            'part' => 'snippet,id',
            'order' => 'date',
            'maxResults' => $limit,
            'type' => 'video'
        ];
        
        $url = "https://www.googleapis.com/youtube/v3/search?" . http_build_query($params);
        
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'user_agent' => 'Gereja-Website/1.0'
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response === false) {
            error_log('YouTube API request failed for channel: ' . $channel_id);
            return [];
        }
        
        $data = json_decode($response, true);
        
        if (isset($data['error'])) {
            error_log('YouTube API Error for channel ' . $channel_id . ': ' . json_encode($data['error']));
            return [];
        }
        
        $videos = [];
        
        if (isset($data['items']) && !empty($data['items'])) {
            foreach ($data['items'] as $item) {
                if ($item['id']['kind'] === 'youtube#video') {
                    // Gunakan data dari search API saja, tanpa memanggil statistics API
                    $videos[] = [
                        'video_id' => $item['id']['videoId'],
                        'title' => $item['snippet']['title'],
                        'thumbnail' => $item['snippet']['thumbnails']['high']['url'],
                        'published_at' => $item['snippet']['publishedAt'],
                        'description' => $item['snippet']['description'],
                        'channel_title' => $item['snippet']['channelTitle'],
                        'view_count' => 0, // Set default, bisa diupdate nanti jika diperlukan
                        'like_count' => 0,
                        'comment_count' => 0
                    ];
                }
            }
            
            error_log('Berhasil ambil ' . count($videos) . ' video dari channel: ' . $channel_id);
        } else {
            error_log('Tidak ada video ditemukan dari channel: ' . $channel_id);
        }
        
        return $videos;
        
    } catch (Exception $e) {
        error_log('Error in getYouTubeVideos: ' . $e->getMessage());
        return [];
    }
}

// Fungsi helper untuk mendapatkan statistik video
function getVideoStatistics($api_key, $video_id) {
    try {
        $params = [
            'key' => $api_key,
            'id' => $video_id,
            'part' => 'statistics'
        ];
        
        $url = "https://www.googleapis.com/youtube/v3/videos?" . http_build_query($params);
        
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'user_agent' => 'Gereja-Website/1.0'
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response === false) {
            return ['view_count' => 0, 'like_count' => 0, 'comment_count' => 0];
        }
        
        $data = json_decode($response, true);
        
        if (isset($data['items']) && !empty($data['items'])) {
            $stats = $data['items'][0]['statistics'];
            return [
                'view_count' => intval($stats['viewCount'] ?? 0),
                'like_count' => intval($stats['likeCount'] ?? 0),
                'comment_count' => intval($stats['commentCount'] ?? 0)
            ];
        }
        
        return ['view_count' => 0, 'like_count' => 0, 'comment_count' => 0];
        
    } catch (Exception $e) {
        error_log('Error getting video statistics: ' . $e->getMessage());
        return ['view_count' => 0, 'like_count' => 0, 'comment_count' => 0];
    }
}

// Fungsi untuk mendapatkan video dari YouTube dengan cache
function getYouTubeVideosWithCache($config = null) {
    if ($config === null) {
        $config = getYouTubeConfig();
    }
    $cache_file = '../cache/youtube_videos.json';
    $cache_dir = dirname($cache_file);
    
    // Buat direktori cache jika belum ada
    if (!is_dir($cache_dir)) {
        mkdir($cache_dir, 0755, true);
    }
    
    // Cek apakah cache masih valid
    if ($config['enable_cache'] && file_exists($cache_file)) {
        $cache_data = json_decode(file_get_contents($cache_file), true);
        if ($cache_data && (time() - $cache_data['timestamp']) < $config['cache_duration']) {
            return $cache_data['videos'];
        }
    }
    
    // Ambil data dari YouTube API
    $videos = getYouTubeVideosFromAPI($config);
    
    // Simpan ke cache
    if ($config['enable_cache'] && !empty($videos)) {
        $cache_data = [
            'timestamp' => time(),
            'videos' => $videos
        ];
        file_put_contents($cache_file, json_encode($cache_data));
    }
    
    return $videos;
}

// Fungsi untuk mengambil video dari YouTube API (Multi-Channel Support)
function getYouTubeVideosFromAPI($config) {
    if (empty($config['api_key'])) {
        // Return data dummy jika API key belum diset
        return getDummyYouTubeVideos();
    }
    
    $all_videos = [];
    $max_requests_per_channel = 25; // Maksimal request per channel
    $total_request_count = 0;
    $max_total_requests = 100; // Maksimal total request untuk semua channel
    
    // Jika multi-channel enabled dan ada channels yang aktif
    if ($config['multi_channel_enabled'] && isset($config['channels']) && !empty($config['channels'])) {
        $active_channels = array_filter($config['channels'], function($channel) {
            return isset($channel['active']) && $channel['active'];
        });
        
        if (!empty($active_channels)) {
            // Ambil video dari setiap channel aktif
            foreach ($active_channels as $channel) {
                if ($total_request_count >= $max_total_requests) {
                    break;
                }
                
                $channel_videos = getVideosFromChannel($config, $channel, $max_requests_per_channel, $total_request_count);
                $all_videos = array_merge($all_videos, $channel_videos);
                
                // Jeda kecil antara channel untuk menghindari rate limit
                usleep(200000); // 0.2 detik
            }
        }
    } else {
        // Fallback ke single channel (backward compatibility)
        $channel = [
            'id' => $config['channel_id'],
            'name' => 'Default Channel',
            'url' => ''
        ];
        $all_videos = getVideosFromChannel($config, $channel, $max_requests_per_channel, $total_request_count);
    }
    
    // Jika tidak ada video yang ditemukan, kembalikan dummy data
    if (empty($all_videos)) {
        return getDummyYouTubeVideos();
    }
    
    // Urutkan video berdasarkan tanggal publikasi (terbaru dulu)
    usort($all_videos, function($a, $b) {
        return strtotime($b['published_at']) - strtotime($a['published_at']);
    });
    
    return $all_videos;
}

// Fungsi helper untuk mengambil video dari channel tertentu
function getVideosFromChannel($config, $channel, $max_requests_per_channel, &$total_request_count) {
    $channel_videos = [];
    $next_page_token = null;
    $request_count = 0;
    
    do {
        $request_count++;
        $total_request_count++;
        
        // Jika Channel ID masih placeholder, gunakan search umum untuk gereja/ibadah
        if ($channel['id'] === 'UCxxxxxxxxxx') {
            $params = [
                'key' => $config['api_key'],
                'q' => 'ibadah gereja kristen indonesia',
                'part' => 'snippet,id',
                'order' => 'date',
                'maxResults' => 50, // Maksimal per request
                'type' => 'video',
                'relevanceLanguage' => 'id'
            ];
        } else {
            // Gunakan Channel ID yang spesifik
            $params = [
                'key' => $config['api_key'],
                'channelId' => $channel['id'],
                'part' => 'snippet,id',
                'order' => 'date',
                'maxResults' => 50, // Maksimal per request
                'type' => 'video'
            ];
        }
        
        // Tambahkan page token jika ada
        if ($next_page_token) {
            $params['pageToken'] = $next_page_token;
        }
        
        $url = "https://www.googleapis.com/youtube/v3/search?" . http_build_query($params);
        
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'user_agent' => 'Gereja-Website/1.0'
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response === false) {
            error_log('YouTube API request failed for channel: ' . $channel['id']);
            break;
        }
        
        $data = json_decode($response, true);
        
        if (isset($data['error'])) {
            error_log('YouTube API Error for channel ' . $channel['id'] . ': ' . json_encode($data['error']));
            break;
        }
        
        if (isset($data['items']) && !empty($data['items'])) {
            foreach ($data['items'] as $item) {
                if ($item['id']['kind'] === 'youtube#video') {
                    $channel_videos[] = [
                        'id' => $item['id']['videoId'],
                        'title' => $item['snippet']['title'],
                        'thumbnail' => $item['snippet']['thumbnails']['high']['url'],
                        'published_at' => $item['snippet']['publishedAt'],
                        'description' => $item['snippet']['description'],
                        'channel_title' => $item['snippet']['channelTitle'],
                        'channel_id' => $channel['id'],
                        'channel_name' => $channel['name']
                    ];
                }
            }
        }
        
        // Cek apakah ada halaman berikutnya
        $next_page_token = isset($data['nextPageToken']) ? $data['nextPageToken'] : null;
        
        // Hentikan jika sudah mencapai batas video atau request
        $channel_count = count($config['channels']);
        $total_videos = isset($config['total_videos_to_fetch']) ? $config['total_videos_to_fetch'] : (isset($config['total_videos']) ? $config['total_videos'] : 500);
        $max_videos_per_channel = $channel_count > 0 ? ($total_videos / $channel_count) : $total_videos;
        
        if (count($channel_videos) >= $max_videos_per_channel || 
            $request_count >= $max_requests_per_channel) {
            break;
        }
        
        // Jeda kecil untuk menghindari rate limit
        if ($next_page_token) {
            usleep(100000); // 0.1 detik
        }
        
    } while ($next_page_token && $config['fetch_all_videos']);
    
    return $channel_videos;
}

// Fungsi untuk data dummy YouTube
function getDummyYouTubeVideos() {
    return [
        [
            'id' => 'dQw4w9WgXcQ',
            'title' => 'Ibadah Minggu - Puji Tuhan',
            'thumbnail' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
            'published_at' => '2024-01-15T10:00:00Z',
            'description' => 'Rekaman ibadah minggu yang penuh berkat dan pujian kepada Tuhan',
            'channel_title' => 'Gereja Kristen Jawa Randuares'
        ],
        [
            'id' => '9bZkp7q19f0',
            'title' => 'Khotbah - Kasih Tuhan yang Tak Terbatas',
            'thumbnail' => 'https://img.youtube.com/vi/9bZkp7q19f0/maxresdefault.jpg',
            'published_at' => '2024-01-08T10:00:00Z',
            'description' => 'Khotbah tentang kasih Tuhan yang tak terbatas dan selalu ada untuk kita',
            'channel_title' => 'Gereja Kristen Jawa Randuares'
        ],
        [
            'id' => 'kJQP7kiw5Fk',
            'title' => 'Pujian Rohani - Amazing Grace',
            'thumbnail' => 'https://img.youtube.com/vi/kJQP7kiw5Fk/maxresdefault.jpg',
            'published_at' => '2024-01-01T10:00:00Z',
            'description' => 'Pujian rohani yang menggerakkan hati dan membawa damai sejahtera',
            'channel_title' => 'Gereja Kristen Jawa Randuares'
        ],
        [
            'id' => 'y6120QOlsfU',
            'title' => 'Doa Bersama - Persekutuan Doa',
            'thumbnail' => 'https://img.youtube.com/vi/y6120QOlsfU/maxresdefault.jpg',
            'published_at' => '2023-12-25T10:00:00Z',
            'description' => 'Persekutuan doa bersama jemaat dalam suasana Natal yang penuh sukacita',
            'channel_title' => 'Gereja Kristen Jawa Randuares'
        ],
        [
            'id' => 'hT_nvWreIhg',
            'title' => 'Sekolah Minggu - Cerita Alkitab',
            'thumbnail' => 'https://img.youtube.com/vi/hT_nvWreIhg/maxresdefault.jpg',
            'published_at' => '2023-12-18T10:00:00Z',
            'description' => 'Kegiatan sekolah minggu dengan cerita Alkitab yang menarik untuk anak-anak',
            'channel_title' => 'Gereja Kristen Jawa Randuares'
        ],
        [
            'id' => 'ZZ5LpwO-An4',
            'title' => 'Pemuda Gereja - Retreat Rohani',
            'thumbnail' => 'https://img.youtube.com/vi/ZZ5LpwO-An4/maxresdefault.jpg',
            'published_at' => '2023-12-11T10:00:00Z',
            'description' => 'Retreat rohani pemuda gereja yang penuh dengan persekutuan dan pertumbuhan iman',
            'channel_title' => 'Gereja Kristen Jawa Randuares'
        ]
    ];
}

// Fungsi untuk membersihkan cache YouTube
function clearYouTubeCache() {
    $cache_file = '../cache/youtube_videos.json';
    if (file_exists($cache_file)) {
        unlink($cache_file);
        return true;
    }
    return false;
}

// Fungsi untuk mendapatkan status YouTube API
function getYouTubeAPIStatus($config = null) {
    if ($config === null) {
        $config = getYouTubeConfig();
    }
    if (empty($config['api_key'])) {
        return [
            'status' => 'not_configured',
            'message' => 'YouTube API belum dikonfigurasi',
            'videos_count' => 0
        ];
    }
    
    $videos = getYouTubeVideosFromAPI($config);
    
    return [
        'status' => 'active',
        'message' => 'YouTube API berfungsi dengan baik',
        'videos_count' => count($videos)
    ];
}

/**
 * Fungsi untuk mendapatkan video dengan pagination
 */
function getYouTubeVideosWithPagination($config = null, $page = 1) {
    if ($config === null) {
        $config = getYouTubeConfig();
    }
    $all_videos = getYouTubeVideosWithCache($config);
    $videos_per_page = $config['max_results'];
    $total_videos = count($all_videos);
    $total_pages = ceil($total_videos / $videos_per_page);
    
    // Validasi halaman
    $page = max(1, min($page, $total_pages));
    
    // Hitung offset
    $offset = ($page - 1) * $videos_per_page;
    
    // Ambil video untuk halaman tertentu
    $videos = array_slice($all_videos, $offset, $videos_per_page);
    
    return [
        'videos' => $videos,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_videos' => $total_videos,
            'videos_per_page' => $videos_per_page,
            'has_previous' => $page > 1,
            'has_next' => $page < $total_pages,
            'previous_page' => $page > 1 ? $page - 1 : null,
            'next_page' => $page < $total_pages ? $page + 1 : null
        ]
    ];
}

/**
 * Fungsi untuk mendapatkan informasi channel (Multi-Channel Support)
 */
function getYouTubeChannelInfo($config = null) {
    if ($config === null) {
        $config = getYouTubeConfig();
    }
    if (empty($config['api_key'])) {
        return null;
    }
    
    // Jika multi-channel enabled dan ada channels yang aktif
    if ($config['multi_channel_enabled'] && isset($config['channels']) && !empty($config['channels'])) {
        $active_channels = array_filter($config['channels'], function($channel) {
            return isset($channel['active']) && $channel['active'];
        });
        
        if (!empty($active_channels)) {
            $channels_info = [];
            $total_subscribers = 0;
            $total_videos = 0;
            $total_views = 0;
            
            foreach ($active_channels as $channel) {
                $channel_info = getSingleChannelInfo($config, $channel['id']);
                if ($channel_info) {
                    $channels_info[] = $channel_info;
                    $total_subscribers += $channel_info['statistics']['subscriber_count'];
                    $total_videos += $channel_info['statistics']['video_count'];
                    $total_views += $channel_info['statistics']['view_count'];
                }
                
                // Jeda kecil antara request
                usleep(100000); // 0.1 detik
            }
            
            // Return combined info untuk multi-channel
            if (!empty($channels_info)) {
                return [
                    'type' => 'multi_channel',
                    'channels' => $channels_info,
                    'total_channels' => count($channels_info),
                    'combined_statistics' => [
                        'subscriber_count' => $total_subscribers,
                        'video_count' => $total_videos,
                        'view_count' => $total_views
                    ]
                ];
            }
        }
    }
    
    // Fallback ke single channel (backward compatibility)
    return getSingleChannelInfo($config, $config['channel_id']);
}

// Fungsi helper untuk mendapatkan informasi channel tunggal
function getSingleChannelInfo($config, $channel_id) {
    $url = "https://www.googleapis.com/youtube/v3/channels?" . http_build_query([
        'key' => $config['api_key'],
        'id' => $channel_id,
        'part' => 'snippet,statistics'
    ]);
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'user_agent' => 'Gereja-Website/1.0'
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        return null;
    }
    
    $data = json_decode($response, true);
    
    if (isset($data['error']) || !isset($data['items'][0])) {
        return null;
    }
    
    $channel = $data['items'][0];
    
    return [
        'type' => 'single_channel',
        'id' => $channel_id,
        'title' => $channel['snippet']['title'],
        'description' => $channel['snippet']['description'],
        'published_at' => $channel['snippet']['publishedAt'],
        'thumbnail' => $channel['snippet']['thumbnails']['default']['url'],
        'statistics' => [
            'subscriber_count' => $channel['statistics']['subscriberCount'] ?? 0,
            'video_count' => $channel['statistics']['videoCount'] ?? 0,
            'view_count' => $channel['statistics']['viewCount'] ?? 0
        ]
    ];
}

/**
 * Fungsi untuk mencari video berdasarkan query (Multi-Channel Support)
 */
function searchYouTubeVideos($search_query, $config = null, $page = 1) {
    if ($config === null) {
        $config = getYouTubeConfig();
    }
    if (empty($config['api_key'])) {
        return [
            'videos' => [],
            'pagination' => [
                'current_page' => 1,
                'total_pages' => 0,
                'total_videos' => 0,
                'videos_per_page' => $config['max_results'],
                'has_previous' => false,
                'has_next' => false,
                'previous_page' => null,
                'next_page' => null
            ]
        ];
    }
    
    $all_videos = [];
    $max_requests_per_channel = 5; // Batasi request per channel untuk pencarian
    $total_request_count = 0;
    $max_total_requests = 20; // Maksimal total request untuk semua channel
    
    // Jika multi-channel enabled dan ada channels yang aktif
    if ($config['multi_channel_enabled'] && isset($config['channels']) && !empty($config['channels'])) {
        $active_channels = array_filter($config['channels'], function($channel) {
            return isset($channel['active']) && $channel['active'];
        });
        
        if (!empty($active_channels)) {
            // Cari video di setiap channel aktif
            foreach ($active_channels as $channel) {
                if ($total_request_count >= $max_total_requests) {
                    break;
                }
                
                $channel_videos = searchVideosInChannel($config, $channel, $search_query, $max_requests_per_channel, $total_request_count);
                $all_videos = array_merge($all_videos, $channel_videos);
                
                // Jeda kecil antara channel untuk menghindari rate limit
                usleep(200000); // 0.2 detik
            }
        }
    } else {
        // Fallback ke single channel (backward compatibility)
        $channel = [
            'id' => $config['channel_id'],
            'name' => 'Default Channel',
            'url' => ''
        ];
        $all_videos = searchVideosInChannel($config, $channel, $search_query, $max_requests_per_channel, $total_request_count);
    }
    
    // Filter video berdasarkan relevansi dengan query pencarian
    $relevant_videos = [];
    foreach ($all_videos as $video) {
        $title_contains = stripos($video['title'], $search_query) !== false;
        $desc_contains = stripos($video['description'], $search_query) !== false;
        
        // Hanya masukkan video yang mengandung kata kunci di judul atau deskripsi
        if ($title_contains || $desc_contains) {
            $relevant_videos[] = $video;
        }
    }
    
    // Urutkan video berdasarkan relevansi (relevance) dan tanggal
    usort($relevant_videos, function($a, $b) {
        return strtotime($b['published_at']) - strtotime($a['published_at']);
    });
    
    $all_videos = $relevant_videos;
    
    // Pagination untuk hasil pencarian
    $videos_per_page = $config['max_results'];
    $total_videos = count($all_videos);
    $total_pages = ceil($total_videos / $videos_per_page);
    
    // Validasi halaman
    $page = max(1, min($page, $total_pages));
    
    // Hitung offset
    $offset = ($page - 1) * $videos_per_page;
    
    // Ambil video untuk halaman tertentu
    $videos = array_slice($all_videos, $offset, $videos_per_page);
    
    return [
        'videos' => $videos,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_videos' => $total_videos,
            'videos_per_page' => $videos_per_page,
            'has_previous' => $page > 1,
            'has_next' => $page < $total_pages,
            'previous_page' => $page > 1 ? $page - 1 : null,
            'next_page' => $page < $total_pages ? $page + 1 : null
        ]
    ];
}

// Fungsi helper untuk mencari video di channel tertentu
function searchVideosInChannel($config, $channel, $search_query, $max_requests_per_channel, &$total_request_count) {
    $channel_videos = [];
    $next_page_token = null;
    $request_count = 0;
    
    do {
        $request_count++;
        $total_request_count++;
        
        $params = [
            'key' => $config['api_key'],
            'q' => $search_query,
            'part' => 'snippet,id',
            'order' => 'relevance',
            'maxResults' => 50,
            'type' => 'video',
            'channelId' => $channel['id'], // Cari di channel tertentu
            'relevanceLanguage' => 'id'
        ];
        
        // Tambahkan page token jika ada
        if ($next_page_token) {
            $params['pageToken'] = $next_page_token;
        }
        
        $url = "https://www.googleapis.com/youtube/v3/search?" . http_build_query($params);
        
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'user_agent' => 'Gereja-Website/1.0'
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response === false) {
            error_log('YouTube Search API request failed for channel: ' . $channel['id']);
            break;
        }
        
        $data = json_decode($response, true);
        
        if (isset($data['error'])) {
            error_log('YouTube Search API Error for channel ' . $channel['id'] . ': ' . json_encode($data['error']));
            break;
        }
        
        if (isset($data['items']) && !empty($data['items'])) {
            foreach ($data['items'] as $item) {
                if ($item['id']['kind'] === 'youtube#video') {
                    $channel_videos[] = [
                        'id' => $item['id']['videoId'],
                        'title' => $item['snippet']['title'],
                        'thumbnail' => $item['snippet']['thumbnails']['high']['url'],
                        'published_at' => $item['snippet']['publishedAt'],
                        'description' => $item['snippet']['description'],
                        'channel_title' => $item['snippet']['channelTitle'],
                        'channel_id' => $channel['id'],
                        'channel_name' => $channel['name']
                    ];
                }
            }
        }
        
        // Cek apakah ada halaman berikutnya
        $next_page_token = isset($data['nextPageToken']) ? $data['nextPageToken'] : null;
        
        // Hentikan jika sudah mencapai batas request
        if ($request_count >= $max_requests_per_channel) {
            break;
        }
        
        // Jeda kecil untuk menghindari rate limit
        if ($next_page_token) {
            usleep(100000); // 0.1 detik
        }
        
    } while ($next_page_token);
    
    return $channel_videos;
}

/**
 * Fungsi untuk generate pagination HTML dengan search query dan channel filter
 */
function generatePaginationHTML($pagination, $base_url = '', $search_query = '', $channel_filter = '') {
    if ($pagination['total_pages'] <= 1) {
        return '';
    }
    
    $html = '<div class="flex justify-center items-center space-x-2 mt-8 pagination-container">';
    
    // Previous button
    if ($pagination['has_previous']) {
        $prev_url = $base_url . '?page=' . $pagination['previous_page'];
        if (!empty($search_query)) {
            $prev_url .= '&search=' . urlencode($search_query);
        }
        if (!empty($channel_filter)) {
            $prev_url .= '&channel_filter=' . urlencode($channel_filter);
        }
        $html .= '<a href="' . $prev_url . '" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors pagination-btn" data-page="' . $pagination['previous_page'] . '">';
        $html .= '<i class="fas fa-chevron-left mr-2"></i>Sebelumnya';
        $html .= '</a>';
    } else {
        $html .= '<span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">';
        $html .= '<i class="fas fa-chevron-left mr-2"></i>Sebelumnya';
        $html .= '</span>';
    }
    
    // Page numbers
    $start_page = max(1, $pagination['current_page'] - 2);
    $end_page = min($pagination['total_pages'], $pagination['current_page'] + 2);
    
    // First page
    if ($start_page > 1) {
        $first_url = $base_url . '?page=1';
        if (!empty($search_query)) {
            $first_url .= '&search=' . urlencode($search_query);
        }
        if (!empty($channel_filter)) {
            $first_url .= '&channel_filter=' . urlencode($channel_filter);
        }
        $html .= '<a href="' . $first_url . '" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-amber-100 hover:text-amber-700 transition-colors pagination-btn" data-page="1">1</a>';
        if ($start_page > 2) {
            $html .= '<span class="px-2 py-2 text-gray-500">...</span>';
        }
    }
    
    // Page numbers
    for ($i = $start_page; $i <= $end_page; $i++) {
        if ($i == $pagination['current_page']) {
            $html .= '<span class="px-3 py-2 bg-amber-600 text-white rounded-lg font-semibold">' . $i . '</span>';
        } else {
            $page_url = $base_url . '?page=' . $i;
            if (!empty($search_query)) {
                $page_url .= '&search=' . urlencode($search_query);
            }
            if (!empty($channel_filter)) {
                $page_url .= '&channel_filter=' . urlencode($channel_filter);
            }
            $html .= '<a href="' . $page_url . '" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-amber-100 hover:text-amber-700 transition-colors pagination-btn" data-page="' . $i . '">' . $i . '</a>';
        }
    }
    
    // Last page
    if ($end_page < $pagination['total_pages']) {
        if ($end_page < $pagination['total_pages'] - 1) {
            $html .= '<span class="px-2 py-2 text-gray-500">...</span>';
        }
        $last_url = $base_url . '?page=' . $pagination['total_pages'];
        if (!empty($search_query)) {
            $last_url .= '&search=' . urlencode($search_query);
        }
        if (!empty($channel_filter)) {
            $last_url .= '&channel_filter=' . urlencode($channel_filter);
        }
        $html .= '<a href="' . $last_url . '" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-amber-100 hover:text-amber-700 transition-colors pagination-btn" data-page="' . $pagination['total_pages'] . '">' . $pagination['total_pages'] . '</a>';
    }
    
    // Next button
    if ($pagination['has_next']) {
        $next_url = $base_url . '?page=' . $pagination['next_page'];
        if (!empty($search_query)) {
            $next_url .= '&search=' . urlencode($search_query);
        }
        if (!empty($channel_filter)) {
            $next_url .= '&channel_filter=' . urlencode($channel_filter);
        }
        $html .= '<a href="' . $next_url . '" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors pagination-btn" data-page="' . $pagination['next_page'] . '">';
        $html .= 'Selanjutnya<i class="fas fa-chevron-right ml-2"></i>';
        $html .= '</a>';
    } else {
        $html .= '<span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed">';
        $html .= 'Selanjutnya<i class="fas fa-chevron-right ml-2"></i>';
        $html .= '</span>';
    }
    
    $html .= '</div>';
    
    // Info pagination
    $html .= '<div class="text-center mt-4 text-sm text-gray-600">';
    $html .= 'Menampilkan ' . (($pagination['current_page'] - 1) * $pagination['videos_per_page'] + 1) . ' - ' . min($pagination['current_page'] * $pagination['videos_per_page'], $pagination['total_videos']) . ' dari ' . $pagination['total_videos'] . ' video';
    if (!empty($search_query)) {
        $html .= ' untuk pencarian: <strong>"' . htmlspecialchars($search_query) . '"</strong>';
    }
    $html .= '</div>';
    
    return $html;
}
?>
