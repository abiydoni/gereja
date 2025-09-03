<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';

// Set header untuk JSON response
header('Content-Type: application/json');

// Cek apakah request method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Ambil tanggal dari POST data
$selectedDate = isset($_POST['tanggal']) ? trim($_POST['tanggal']) : '';

if (empty($selectedDate)) {
    echo json_encode([
        'success' => false,
        'message' => 'Tanggal tidak boleh kosong'
    ]);
    exit;
}

try {
    $db = new Database();
    
    // Debug: log tanggal yang dicari
    error_log("Searching for renungan with date: " . $selectedDate);
    
    // Cari renungan yang publish pada tanggal yang dipilih
    $query = "SELECT id, judul, tanggal_publish, konten FROM renungan WHERE status = 'published' AND tanggal_publish LIKE :tanggal ORDER BY tanggal_publish DESC LIMIT 1";
    $db->query($query);
    $db->bind(':tanggal', $selectedDate . '%');
    
    // Debug: log query yang dijalankan
    error_log("Query executed: " . $query . " with parameter: " . $selectedDate . '%');
    
    $renunganList = $db->resultSet();
    
    if (!empty($renunganList)) {
        $renungan = $renunganList[0];
        $tanggalRenungan = $renungan['tanggal_publish'] ? date('d/m/Y', strtotime($renungan['tanggal_publish'])) : 'Belum dipublish';
        
        // Generate HTML untuk renungan
        $html = '<div class="space-y-2">';
        $html .= '<div class="flex items-center justify-between">';
        $html .= '<h4 class="font-medium text-gray-900">' . htmlspecialchars($renungan['judul']) . '</h4>';
        $html .= '<span class="text-sm text-gray-500">' . $tanggalRenungan . '</span>';
        $html .= '</div>';
        $html .= '<p class="text-sm text-gray-600 line-clamp-2">' . htmlspecialchars(substr(strip_tags($renungan['konten']), 0, 150)) . '...</p>';
        $html .= '</div>';
        
        // Tambahkan info filter
        $html .= '<div class="mt-2 text-xs text-amber-600 bg-amber-50 px-2 py-1 rounded">';
        $html .= '📅 Filter: Renungan untuk tanggal ' . date('d/m/Y', strtotime($selectedDate));
        $html .= '</div>';
        
        echo json_encode([
            'success' => true,
            'html' => $html,
            'message' => 'Renungan ditemukan'
        ]);
    } else {
        // Coba query alternatif yang lebih sederhana
        try {
            $db->query("SELECT id, judul, tanggal_publish, konten FROM renungan WHERE status = 'published' ORDER BY tanggal_publish DESC LIMIT 1");
            $fallbackList = $db->resultSet();
            
            if (!empty($fallbackList)) {
                $fallbackRenungan = $fallbackList[0];
                $message = 'Tidak ada renungan yang dipublish pada tanggal ' . date('d/m/Y', strtotime($selectedDate)) . '. Menampilkan renungan terbaru.';
                
                // Generate HTML untuk renungan fallback
                $html = '<div class="space-y-2">';
                $html .= '<div class="flex items-center justify-between">';
                $html .= '<h4 class="font-medium text-gray-900">' . htmlspecialchars($fallbackRenungan['judul']) . ' (Fallback)</h4>';
                $html .= '<span class="text-sm text-gray-500">' . date('d/m/Y', strtotime($fallbackRenungan['tanggal_publish'])) . '</span>';
                $html .= '</div>';
                $html .= '<p class="text-sm text-gray-600 line-clamp-2">' . htmlspecialchars(substr(strip_tags($fallbackRenungan['konten']), 0, 150)) . '...</p>';
                $html .= '</div>';
                
                // Tambahkan info fallback
                $html .= '<div class="mt-2 text-xs text-amber-600 bg-amber-50 px-2 py-1 rounded">';
                $html .= '⚠️ ' . $message;
                $html .= '</div>';
                
                echo json_encode([
                    'success' => true,
                    'html' => $html,
                    'message' => $message
                ]);
            } else {
                // Tidak ada renungan sama sekali
                $message = 'Tidak ada renungan yang dipublish pada tanggal ' . date('d/m/Y', strtotime($selectedDate));
                
                echo json_encode([
                    'success' => false,
                    'message' => $message
                ]);
            }
        } catch (Exception $fallbackError) {
            // Jika fallback juga gagal
            $message = 'Tidak ada renungan yang dipublish pada tanggal ' . date('d/m/Y', strtotime($selectedDate));
            
            echo json_encode([
                'success' => false,
                'message' => $message
            ]);
        }
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error loading renungan: ' . $e->getMessage()
    ]);
}
?>
