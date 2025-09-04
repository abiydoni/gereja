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
    // Gunakan method query() dengan parameter langsung
    try {
        $query = "SELECT id, judul, tanggal_publish, konten FROM renungan WHERE status = 'published' AND DATE(tanggal_publish) = :tanggal ORDER BY tanggal_publish DESC LIMIT 1";
        $renunganList = $db->fetchAll($query, [':tanggal' => $selectedDate]);
    } catch (Exception $dateError) {
        // Jika DATE() function tidak didukung, gunakan LIKE sebagai fallback
        error_log("DATE() function not supported, using LIKE fallback: " . $dateError->getMessage());
        $query = "SELECT id, judul, tanggal_publish, konten FROM renungan WHERE status = 'published' AND tanggal_publish LIKE :tanggal ORDER BY tanggal_publish DESC LIMIT 1";
        $renunganList = $db->fetchAll($query, [':tanggal' => $selectedDate . '%']);
    }
    
    // Debug: log query yang dijalankan
    error_log("Query executed successfully, found " . count($renunganList) . " results");
    
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
        // Tidak ada renungan pada tanggal tersebut
        $message = 'Tidak ada renungan yang dipublish pada tanggal ' . date('d/m/Y', strtotime($selectedDate));
        
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error loading renungan: ' . $e->getMessage()
    ]);
}
?>
