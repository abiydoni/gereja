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
    error_log("Searching for kegiatan with date: " . $selectedDate);
    
    // Cari kegiatan yang sama dengan dan lebih dari tanggal yang dipilih
    // Coba query yang lebih sederhana terlebih dahulu
    try {
        // Cek apakah tabel ada dan ambil semua kegiatan terlebih dahulu
        $kegiatanList = $db->fetchAll("SELECT id, nama_kegiatan, tanggal_mulai, waktu_mulai, tempat, deskripsi FROM kegiatan_kerohanian WHERE status IN ('direncanakan', 'pendaftaran', 'berlangsung') ORDER BY tanggal_mulai ASC, waktu_mulai ASC");
        
        // Filter di PHP untuk menghindari masalah database
        if (!empty($kegiatanList)) {
            $filteredKegiatan = [];
            foreach ($kegiatanList as $kegiatan) {
                if ($kegiatan['tanggal_mulai'] >= $selectedDate) {
                    $filteredKegiatan[] = $kegiatan;
                }
            }
            $kegiatanList = $filteredKegiatan;
        }
        
        error_log("Filtered kegiatan count: " . count($kegiatanList));
    } catch (Exception $e) {
        error_log("Error querying kegiatan: " . $e->getMessage());
        $kegiatanList = [];
    }
    
    // Debug: log query yang dijalankan
    error_log("Query executed successfully, found " . count($kegiatanList) . " kegiatan results");
    
    if (!empty($kegiatanList)) {
        // Generate HTML untuk tabel kegiatan
        $html = '<div class="overflow-x-auto">';
        $html .= '<table class="min-w-full divide-y divide-gray-200">';
        $html .= '<thead class="bg-amber-50">';
        $html .= '<tr>';
        $html .= '<th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">No</th>';
        $html .= '<th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Tanggal</th>';
        $html .= '<th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Waktu</th>';
        $html .= '<th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Kegiatan</th>';
        $html .= '<th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Tempat</th>';
        $html .= '<th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Keterangan</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody class="bg-white divide-y divide-gray-200">';
        
        $no = 1;
        foreach ($kegiatanList as $kegiatan) {
            $tanggalKegiatan = $kegiatan['tanggal_mulai'] ? date('d/m/Y', strtotime($kegiatan['tanggal_mulai'])) : '-';
            $waktu = $kegiatan['waktu_mulai'] ? date('H:i', strtotime($kegiatan['waktu_mulai'])) . ' WIB' : '-';
            $tempat = $kegiatan['tempat'] ?: '-';
            $deskripsi = $kegiatan['deskripsi'] ? substr(strip_tags($kegiatan['deskripsi']), 0, 100) . '...' : '-';
            
            $html .= '<tr class="hover:bg-amber-50">';
            $html .= '<td class="px-4 py-2 text-sm text-gray-700">' . $no++ . '</td>';
            $html .= '<td class="px-4 py-2 text-sm text-gray-700">' . $tanggalKegiatan . '</td>';
            $html .= '<td class="px-4 py-2 text-sm text-gray-700">' . $waktu . '</td>';
            $html .= '<td class="px-4 py-2 text-sm font-medium text-gray-900">' . htmlspecialchars($kegiatan['nama_kegiatan']) . '</td>';
            $html .= '<td class="px-4 py-2 text-sm text-gray-700">' . htmlspecialchars($tempat) . '</td>';
            $html .= '<td class="px-4 py-2 text-sm text-gray-700">' . htmlspecialchars($deskripsi) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        
        // Tambahkan info filter
        $html .= '<div class="mt-2 text-xs text-amber-600 bg-amber-50 px-2 py-1 rounded">';
        $html .= '📅 Jadwal kegiatan dari tanggal ' . date('d/m/Y', strtotime($selectedDate)) . ' ke depan';
        $html .= '</div>';
        
        echo json_encode([
            'success' => true,
            'html' => $html,
            'message' => 'Jadwal kegiatan ditemukan'
        ]);
    } else {
        // Tidak ada kegiatan pada tanggal tersebut
        $message = 'Tidak ada kegiatan yang dijadwalkan dari tanggal ' . date('d/m/Y', strtotime($selectedDate)) . ' ke depan';
        
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error loading jadwal kegiatan: ' . $e->getMessage()
    ]);
}
?>
