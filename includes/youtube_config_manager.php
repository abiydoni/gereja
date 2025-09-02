<?php
/**
 * YouTube Config Manager - 100% Dinamis dari Database
 */

require_once 'database.php';

class YouTubeConfigManager {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Dapatkan konfigurasi YouTube dari database
     */
    public function getConfig() {
        try {
            // Ambil semua konfigurasi
            $configs = $this->db->fetchAll("SELECT config_key, config_value FROM youtube_config");
            
            // Convert ke array
            $config = [];
            foreach ($configs as $row) {
                $key = str_replace('youtube_', '', $row['config_key']);
                $value = $row['config_value'];
                
                // Convert boolean values
                if (in_array($key, ['enable_cache', 'search_enabled', 'multi_channel_enabled', 'fetch_all_videos'])) {
                    $value = (bool)$value;
                }
                
                // Convert numeric values
                if (in_array($key, ['max_results', 'total_videos', 'cache_duration'])) {
                    $value = (int)$value;
                }
                
                $config[$key] = $value;
            }
            
            // Ambil channels
            $channels = $this->db->fetchAll(
                "SELECT channel_id as id, channel_name as name, channel_url as url, is_active as active 
                 FROM youtube_channels 
                 WHERE is_active = 1 
                 ORDER BY sort_order"
            );
            
            $config['channels'] = $channels;
            
            // Set channel_id utama dari channel pertama
            if (!empty($channels)) {
                $config['channel_id'] = $channels[0]['id'];
            }
            
            return $config;
            
        } catch (Exception $e) {
            error_log("Error getting YouTube config: " . $e->getMessage());
            return $this->getDefaultConfig();
        }
    }
    
    /**
     * Update konfigurasi YouTube
     */
    public function updateConfig($data) {
        try {
            $this->db->getConnection()->beginTransaction();
            
            // Update konfigurasi
            $configs = [
                'youtube_api_key' => $data['youtube_api_key'] ?? '',
                'youtube_max_results' => intval($data['youtube_max_results'] ?? 12),
                'youtube_total_videos' => intval($data['youtube_total_videos'] ?? 500),
                'youtube_fetch_all_videos' => isset($data['youtube_fetch_all_videos']) ? '1' : '0',
                'youtube_cache_duration' => intval($data['youtube_cache_duration'] ?? 3600),
                'youtube_enable_cache' => isset($data['youtube_enable_cache']) ? '1' : '0',
                'youtube_search_enabled' => isset($data['youtube_search_enabled']) ? '1' : '0',
                'youtube_multi_channel_enabled' => isset($data['youtube_multi_channel_enabled']) ? '1' : '0'
            ];
            
            foreach ($configs as $key => $value) {
                $this->db->execute(
                    "INSERT INTO youtube_config (config_key, config_value) VALUES (?, ?) 
                     ON DUPLICATE KEY UPDATE config_value = ?",
                    [$key, $value, $value]
                );
            }
            
            // Update channels
            if (isset($data['channels']) && is_array($data['channels'])) {
                // Hapus semua channels lama
                $this->db->execute("DELETE FROM youtube_channels");
                
                // Insert channels baru
                foreach ($data['channels'] as $index => $channel) {
                    if (!empty($channel['id'])) {
                        $this->db->execute(
                            "INSERT INTO youtube_channels (channel_id, channel_name, channel_url, is_active, sort_order) 
                             VALUES (?, ?, ?, ?, ?)",
                            [
                                $channel['id'],
                                $channel['name'] ?? 'Channel',
                                $channel['url'] ?? '',
                                isset($channel['active']) ? 1 : 0,
                                $index + 1
                            ]
                        );
                    }
                }
            }
            
            $this->db->getConnection()->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            error_log("Error updating YouTube config: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Dapatkan channels dari database
     */
    public function getChannels() {
        try {
            return $this->db->fetchAll(
                "SELECT channel_id as id, channel_name as name, channel_url as url, is_active as active 
                 FROM youtube_channels 
                 WHERE is_active = 1 
                 ORDER BY sort_order"
            );
        } catch (Exception $e) {
            error_log("Error getting channels: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Tambah channel baru
     */
    public function addChannel($channelData) {
        try {
            $maxOrder = $this->db->fetchOne("SELECT MAX(sort_order) as max_order FROM youtube_channels");
            $nextOrder = ($maxOrder['max_order'] ?? 0) + 1;
            
            return $this->db->execute(
                "INSERT INTO youtube_channels (channel_id, channel_name, channel_url, is_active, sort_order) 
                 VALUES (?, ?, ?, ?, ?)",
                [
                    $channelData['id'],
                    $channelData['name'],
                    $channelData['url'] ?? '',
                    $channelData['active'] ?? 1,
                    $nextOrder
                ]
            );
        } catch (Exception $e) {
            error_log("Error adding channel: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Hapus channel
     */
    public function deleteChannel($channelId) {
        try {
            return $this->db->execute("DELETE FROM youtube_channels WHERE channel_id = ?", [$channelId]);
        } catch (Exception $e) {
            error_log("Error deleting channel: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Konfigurasi default jika database error
     */
    private function getDefaultConfig() {
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
?>
