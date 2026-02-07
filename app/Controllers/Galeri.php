<?php

namespace App\Controllers;

use App\Models\GaleriModel;
use App\Models\GerejaModel;

class Galeri extends BaseController
{
    protected $galeriModel;
    protected $gerejaModel;

    public function __construct()
    {
        $this->galeriModel = new GaleriModel();
        $this->gerejaModel = new GerejaModel();
    }

    public function index()
    {
        $gereja = $this->gerejaModel->first();
        
        // Fetch DB items (The "Collections" or "Rooms")
        $dbItems = $this->galeriModel->where('status', 'aktif')->orderBy('created_at', 'DESC')->findAll();
        
        $collections = [];
        
        foreach ($dbItems as $item) {
            $processedItem = $item;
            $processedItem['children'] = []; // Container for RSS items
            
            // Check if it's a YouTube Channel ID
            if ($item['kategori'] == 'youtube' && (strpos($item['link_media'], 'UC') === 0 || strpos($item['link_media'], '@') !== false)) {
                 
                 // Handle explicit ID or legacy handle (though we fixed DB, good to be safe)
                 $channelId = $item['link_media']; 
                 // If it's still a handle, we might fail, but we assume it's fixed or we just try.
                 // RSS URL
                 $rssUrl = 'https://www.youtube.com/feeds/videos.xml?channel_id=' . $channelId;
                 
                 try {
                     $feed = @simplexml_load_file($rssUrl);
                     if ($feed && isset($feed->entry)) {
                         foreach ($feed->entry as $entry) {
                             $pubDate = strtotime((string)$entry->published);
                             $processedItem['children'][] = [
                                 'id_galeri' => 'yt_' . (string)$entry->id,
                                 'judul' => (string)$entry->title,
                                 'kategori' => 'youtube_video', // Sub-category
                                 'link_media' => (string)$entry->children('http://www.youtube.com/xml/schemas/2015')->videoId,
                                 'keterangan' => 'Uploaded: ' . date('d M Y', $pubDate),
                                 'timestamp' => $pubDate
                             ];
                         }
                         
                         // Sort children by date
                         usort($processedItem['children'], function($a, $b) {
                             return $b['timestamp'] - $a['timestamp'];
                         });
                     }
                 } catch (\Exception $e) {
                     } catch (\Exception $e) {
                         // Keep children empty regarding error
                     }
                } elseif ($item['kategori'] == 'upload_audio') {
                    // Fetch from GaleriItemsModel
                    $galeriItemsModel = new \App\Models\GaleriItemsModel();
                    $processedItem['children'] = $galeriItemsModel
                        ->where('id_galeri', $item['id_galeri'])
                        ->orderBy('judul', 'ASC') // Group by Folder Name
                        ->orderBy('sort_order', 'ASC')
                        ->findAll();
                } 
            
            $collections[] = $processedItem;
        }

        // Group by Title Prefix (Format "TabName: SectionName" or just "TabName")
        $groupedCollections = [];
        
        foreach ($collections as $item) {
            $fullTitle = $item['judul'];
            $tabName = '';
            $sectionName = '';

            if (strpos($fullTitle, ':') !== false) {
                // Has separator, e.g. "Dokumentasi Audio: KJ"
                $parts = explode(':', $fullTitle, 2);
                $tabName = trim($parts[0]);
                $sectionName = trim($parts[1]);
            } else {
                // No separator, determine Tab based on Category
                if ($item['kategori'] == 'youtube' || $item['kategori'] == 'youtube_video') {
                    $tabName = 'Video';
                } elseif ($item['kategori'] == 'drive_img') {
                    $tabName = 'Foto';
                } elseif ($item['kategori'] == 'drive_audio' || $item['kategori'] == 'upload_audio') {
                    $tabName = 'Audio';
                } else {
                    $tabName = 'Lainnya';
                }
                $sectionName = $fullTitle; // Use full title as section name
            }

            if (!isset($groupedCollections[$tabName])) {
                $groupedCollections[$tabName] = [
                    'tab_name' => $tabName,
                    'items' => []
                ];
            }
            
            $item['display_title'] = $sectionName; 
            $groupedCollections[$tabName]['items'][] = $item;
        }
        
        // Sort items within each Group by Sub-Title (Alphabetical A-Z)
        foreach ($groupedCollections as &$group) {
            usort($group['items'], function($a, $b) {
                return strcasecmp($a['display_title'], $b['display_title']);
            });
        }
        unset($group); // Break reference
        
        // Sort Top-Level Tabs to prioritize Video
        uksort($groupedCollections, function($keyA, $keyB) {
            // Helper to determine score (Lower is better/first)
            $getScore = function($key) {
                $k = strtolower($key);
                if (strpos($k, 'video') !== false || strpos($k, 'youtube') !== false || strpos($k, 'ibadah') !== false) return 1;
                if (strpos($k, 'foto') !== false || strpos($k, 'gambar') !== false) return 2;
                if (strpos($k, 'audio') !== false || strpos($k, 'musik') !== false || strpos($k, 'suara') !== false) return 3;
                return 4; // Others
            };
            
            $scoreA = $getScore($keyA);
            $scoreB = $getScore($keyB);
            
            if ($scoreA === $scoreB) {
                return strcasecmp($keyA, $keyB); // If same priority, sort alphabetical
            }
            return $scoreA - $scoreB;
        });

        $data = [
            'title'     => 'Galeri Multimedia',
            'gereja'    => $gereja,
            'collections' => $groupedCollections, // Pass grouped data
        ];

        return view('frontend/galeri/index', $data);
    }
}
