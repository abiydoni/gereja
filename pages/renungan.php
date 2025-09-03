<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Filter tanggal
$filter_tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';
$filter_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$filter_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

// Query untuk renungan dengan filter
try {
    $db = new Database();
    
    $where_conditions = ["status = 'published'"];
    $params = [];
    
    if (!empty($filter_tahun)) {
        $where_conditions[] = "YEAR(tanggal_publish) = ?";
        $params[] = $filter_tahun;
    }
    
    if (!empty($filter_bulan)) {
        $where_conditions[] = "MONTH(tanggal_publish) = ?";
        $params[] = $filter_bulan;
    }
    
    if (!empty($filter_tanggal)) {
        $where_conditions[] = "DATE(tanggal_publish) = ?";
        $params[] = $filter_tanggal;
    }
    
    $where_clause = implode(' AND ', $where_conditions);
    $query = "SELECT * FROM renungan WHERE $where_clause ORDER BY tanggal_publish DESC";
    
    // Execute query with parameters
    $renungan_list = $db->fetchAll($query, $params);
} catch (Exception $e) {
    $renungan_list = [];
}

// Get unique years and months for filter
try {
    $db_years = new Database();
    $years = $db_years->fetchAll("SELECT DISTINCT YEAR(tanggal_publish) as tahun FROM renungan WHERE status = 'published' AND tanggal_publish IS NOT NULL ORDER BY tahun DESC");
    
    $db_months = new Database();
    $months = $db_months->fetchAll("SELECT DISTINCT MONTH(tanggal_publish) as bulan, MONTHNAME(tanggal_publish) as nama_bulan FROM renungan WHERE status = 'published' AND tanggal_publish IS NOT NULL ORDER BY bulan");
} catch (Exception $e) {
    $years = [];
    $months = [];
}

// Array nama bulan dalam bahasa Indonesia
$nama_bulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renungan - <?php echo getNamaGereja(); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Custom CSS untuk Modal Renungan -->
    <link rel="stylesheet" href="../assets/css/renungan-modal.css">
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
                    <span class="text-xl font-bold text-gray-800">Renungan</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <section class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4" data-aos="fade-up">Renungan Harian</h1>
            <p class="text-xl opacity-90" data-aos="fade-up" data-aos-delay="150">Bahan perenungan untuk pertumbuhan iman</p>
            
            
        </div>
    </section>

    <!-- Filter Section -->
    <section class="bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4">
            
            <!-- Success/Error Messages -->
            <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="ml-2">Renungan berhasil dipublish.</span>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['draft'])): ?>
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <strong class="font-bold">Tersimpan!</strong>
                        <span class="ml-2">Renungan berhasil disimpan sebagai draft.</span>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong class="font-bold">Error!</strong>
                        <span class="ml-2"><?php echo htmlspecialchars($_GET['error']); ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="bg-amber-50 rounded-xl p-6 w-full" data-aos="fade-up">
                    <h3 class="text-lg font-semibold text-amber-900 mb-4">
                        <i class="fas fa-filter mr-2"></i>Filter Renungan
                    </h3>
                    
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Filter Tahun -->
                        <div>
                            <label class="block text-sm font-medium text-amber-800 mb-2">Tahun</label>
                            <select name="tahun" class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                <option value="">Semua Tahun</option>
                                <?php foreach ($years as $year): ?>
                                <option value="<?php echo $year['tahun']; ?>" <?php echo ($filter_tahun == $year['tahun']) ? 'selected' : ''; ?>>
                                    <?php echo $year['tahun']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Filter Bulan -->
                        <div>
                            <label class="block text-sm font-medium text-amber-800 mb-2">Bulan</label>
                            <select name="bulan" class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                <option value="">Semua Bulan</option>
                                <?php foreach ($months as $month): ?>
                                <option value="<?php echo $month['bulan']; ?>" <?php echo ($filter_bulan == $month['bulan']) ? 'selected' : ''; ?>>
                                    <?php echo $nama_bulan[$month['bulan']]; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Filter Tanggal -->
                        <div>
                            <label class="block text-sm font-medium text-amber-800 mb-2">Tanggal</label>
                            <input type="date" name="tanggal" value="<?php echo $filter_tanggal; ?>" 
                                   class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>
                        
                        <!-- Tombol Filter -->
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                <i class="fas fa-search mr-2"></i>Filter
                            </button>
                        </div>
                    </form>
                    
                    <!-- Tombol Reset -->
                    <?php if (!empty($filter_tanggal) || !empty($filter_bulan) || !empty($filter_tahun)): ?>
                    <div class="mt-4 text-center">
                        <a href="renungan.php" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-times mr-2"></i>Reset Filter
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Konten renungan -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Info Filter Aktif -->
            <?php if (!empty($filter_tanggal) || !empty($filter_bulan) || !empty($filter_tahun)): ?>
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4" data-aos="fade-up">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-3 text-xl"></i>
                    <div>
                        <h4 class="font-medium text-blue-900">Filter Aktif:</h4>
                        <p class="text-blue-700">
                            <?php
                            $filter_info = [];
                            if (!empty($filter_tahun)) $filter_info[] = "Tahun: $filter_tahun";
                            if (!empty($filter_bulan)) $filter_info[] = "Bulan: " . $nama_bulan[(int)$filter_bulan];
                            if (!empty($filter_tanggal)) $filter_info[] = "Tanggal: " . formatTanggalIndonesia($filter_tanggal);
                            echo implode(', ', $filter_info);
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (empty($renungan_list)): ?>
            <div class="text-center py-12 bg-white rounded-xl shadow" data-aos="fade-up">
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-book-open text-2xl text-amber-700"></i>
                </div>
                <p class="text-gray-600 mb-2">
                    <?php if (!empty($filter_tanggal) || !empty($filter_bulan) || !empty($filter_tahun)): ?>
                        Tidak ada renungan yang ditemukan dengan filter yang dipilih.
                    <?php else: ?>
                        Belum ada renungan yang dipublikasikan.
                    <?php endif; ?>
                </p>
                <?php if (!empty($filter_tanggal) || !empty($filter_bulan) || !empty($filter_tahun)): ?>
                <a href="renungan.php" class="text-amber-600 hover:text-amber-700 underline">Lihat semua renungan</a>
                <?php endif; ?>
            </div>
            <?php else: ?>
            
            <!-- Jumlah hasil -->
            <div class="mb-6 text-center">
                <p class="text-gray-600">
                    <i class="fas fa-book mr-2"></i>
                    Ditemukan <span class="font-semibold text-amber-600"><?php echo count($renungan_list); ?></span> renungan
                </p>
            </div>
            
                         <?php foreach ($renungan_list as $renungan): ?>
             <article class="bg-white rounded-xl shadow border border-amber-200 p-6 mb-6 hover:shadow-lg transition-shadow" data-aos="fade-up" data-renungan-id="<?php echo $renungan['id']; ?>">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-amber-900"><?php echo htmlspecialchars($renungan['judul']); ?></h2>
                    <div class="flex items-center space-x-2">
                        <?php if (!empty($renungan['kategori'])): ?>
                        <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                            <?php echo htmlspecialchars($renungan['kategori']); ?>
                        </span>
                        <?php endif; ?>
                    <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-800">
                            <?php echo formatTanggalIndonesia($renungan['tanggal_publish']); ?>
                        </span>
                    </div>
                </div>
                
                <?php if (!empty($renungan['ayat_alkitab'])): ?>
                <div class="mb-3 p-3 bg-green-50 border-l-4 border-green-400 rounded">
                    <p class="text-sm text-green-800">
                        <i class="fas fa-bible mr-2"></i>
                        <strong>Ayat Alkitab:</strong> <?php echo htmlspecialchars($renungan['ayat_alkitab']); ?>
                    </p>
                </div>
                <?php endif; ?>
                
                <div class="prose max-w-none text-gray-700 leading-relaxed mb-4">
                    <?php 
                    $konten = $renungan['konten'];
                    
                    // Gunakan DOMDocument untuk membersihkan HTML attributes yang tidak diinginkan
                    if (!empty($konten)) {
                        // Buat DOMDocument
                        $dom = new DOMDocument();
                        
                        // Suppress warnings untuk HTML yang tidak valid
                        libxml_use_internal_errors(true);
                        
                        // Load HTML content
                        $dom->loadHTML('<?xml encoding="UTF-8">' . $konten, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                        
                        // Clear libxml errors
                        libxml_clear_errors();
                        
                        // Hapus attributes yang tidak diinginkan dari semua elements
                        $xpath = new DOMXPath($dom);
                        $elements = $xpath->query('//*');
                        
                        foreach ($elements as $element) {
                            // Hapus attributes yang tidak diinginkan
                            $element->removeAttribute('class');
                            $element->removeAttribute('data-fontsize');
                            $element->removeAttribute('style');
                            $element->removeAttribute('id');
                            $element->removeAttribute('data-fontsize');
                        }
                        
                        // Get cleaned HTML
                        $konten_clean = $dom->saveHTML();
                        
                        // Hapus XML declaration yang ditambahkan
                        $konten_clean = str_replace('<?xml encoding="UTF-8">', '', $konten_clean);
                        
                        // Hapus HTML entities yang tidak diinginkan
                        $konten_clean = str_replace(['&nbsp;'], [' '], $konten_clean);
                        
                        // Hapus multiple spaces dan newlines yang berlebihan
                        $konten_clean = preg_replace('/\s+/', ' ', $konten_clean);
                        
                        // Trim whitespace
                        $konten_clean = trim($konten_clean);
                    } else {
                        $konten_clean = '';
                    }
                    
                    // Tampilkan hanya 300 karakter pertama, lalu tambahkan "..." jika lebih panjang
                    if (strlen($konten_clean) > 300) {
                        echo substr($konten_clean, 0, 300) . '...';
                    } else {
                        echo $konten_clean;
                    }
                    ?>
                </div>
                
                <div class="relative flex items-center pt-4 border-t border-gray-100 pr-32">
                    <div class="flex items-center space-x-4 text-sm text-gray-600 min-w-0 max-w-[70%]">
                        <?php if (!empty($renungan['penulis'])): ?>
                        <span class="truncate">
                            <i class="fas fa-user mr-1"></i>
                            <?php echo htmlspecialchars($renungan['penulis']); ?>
                        </span>
                        <?php endif; ?>
                        <span class="view-count whitespace-nowrap">
                            <i class="fas fa-eye mr-1"></i>
                            <?php echo number_format($renungan['views']); ?> dilihat
                    </span>
                </div>
                    
                    <a href="renungan_detail.php?id=<?php echo $renungan['id']; ?>" 
                       class="absolute right-6 bottom-2 md:bottom-6 inline-flex items-center whitespace-nowrap bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg transition-colors btn-baca-lengkap shadow">
                        <i class="fas fa-book-open mr-2"></i>Baca Lengkap
                    </a>
                </div>
            </article>
            <?php endforeach; endif; ?>
        </div>
    </section>



    <footer class="bg-amber-900 text-amber-50 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2025 Gereja Kristen Jawa Randuares. Semua hak dilindungi. | <a href="https://appsbee.my.id" target="_blank" class="text-amber-200 hover:text-amber-100 transition-colors">appsBee</a></p>
        </div>
    </footer>

    <script>
        AOS.init({ duration: 800, once: true });
    </script>
</body>
</html>
