<?php
// Halaman detail renungan yang sederhana
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header('Location: renungan.php');
    exit;
}

try {
    // Database connection
    $host = 'localhost';
    $dbname = 'appsbeem_gereja';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get renungan data
    $stmt = $pdo->prepare("SELECT * FROM renungan WHERE id = ? AND status = 'published'");
    $stmt->execute([$id]);
    $renungan = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$renungan) {
        header('Location: renungan.php');
        exit;
    }
    
    // Update view count
    $stmt = $pdo->prepare("UPDATE renungan SET views = views + 1 WHERE id = ?");
    $stmt->execute([$id]);
    
} catch (Exception $e) {
    header('Location: renungan.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($renungan['judul']) ?> - Renungan</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .content h1, .content h2, .content h3 { 
            color: #92400e; 
            margin: 1.5rem 0 1rem 0;
            font-weight: 600;
        }
        .content h1 { font-size: 1.75rem; }
        .content h2 { font-size: 1.5rem; }
        .content h3 { font-size: 1.25rem; }
        .content p { 
            margin-bottom: 1rem; 
            line-height: 1.7;
            color: #374151;
        }
        .content strong { color: #92400e; font-weight: 600; }
        .content ul, .content ol { 
            margin: 1rem 0; 
            padding-left: 1.5rem; 
        }
        .content li { margin-bottom: 0.5rem; }
        .content blockquote {
            border-left: 4px solid #f59e0b;
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            background: #fef3c7;
            border-radius: 0.5rem;
            font-style: italic;
            color: #92400e;
        }
    </style>
</head>
<body class="bg-gray-50" style="padding-top: 80px;">
    
    <!-- Navigation - diseragamkan dengan renungan.php -->
    <nav class="fixed top-0 left-0 right-0 bg-white shadow-lg z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <a href="renungan.php" class="flex items-center space-x-2 text-gray-600 hover:text-amber-600 transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                        <span class="text-lg font-semibold">Kembali ke Renungan</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <img src="../assets/images/logo.png" alt="Logo Gereja" class="w-10 h-10 object-contain logo-amber" style="filter: invert(33%) sepia(85%) saturate(900%) hue-rotate(8deg) brightness(92%) contrast(95%) !important;">
                    <span class="text-xl font-bold text-gray-800">Renungan</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Section - diseragamkan dengan renungan.php -->
    <section class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Renungan Harian</h1>
            <p class="text-base md:text-lg opacity-90">Bahan perenungan untuk pertumbuhan iman</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 py-8">
        
        <!-- Article -->
        <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            
            <!-- Article Header -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white p-6">
                <h1 class="text-2xl md:text-3xl font-bold mb-4">
                    <?= htmlspecialchars($renungan['judul']) ?>
                </h1>
                
                <!-- Meta Info -->
                <div class="flex flex-wrap gap-4 text-sm">
                    <?php if (!empty($renungan['penulis'])): ?>
                    <div class="flex items-center">
                        <i class="fas fa-user mr-2"></i>
                        <span><?= htmlspecialchars($renungan['penulis']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="flex items-center">
                        <i class="fas fa-calendar mr-2"></i>
                        <span>
                            <?php
                            $tanggal = new DateTime($renungan['tanggal_publish']);
                            $nama_bulan = [
                                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                                5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Ags',
                                9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                            ];
                            echo $tanggal->format('d') . ' ' . $nama_bulan[(int)$tanggal->format('n')] . ' ' . $tanggal->format('Y');
                            ?>
                        </span>
                    </div>
                    
                    <div class="flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        <span><?= number_format($renungan['views']) ?> dibaca</span>
                    </div>
                </div>
            </div>
            
            <!-- Bible Verse -->
            <?php if (!empty($renungan['ayat_alkitab'])): ?>
            <div class="p-6 bg-green-50 border-l-4 border-green-400">
                <div class="flex items-start">
                    <i class="fas fa-bible text-green-600 text-xl mr-3 mt-1"></i>
                    <div>
                        <h3 class="font-semibold text-green-800 mb-2">Ayat Alkitab</h3>
                        <p class="text-green-700 italic">"<?= htmlspecialchars($renungan['ayat_alkitab']) ?>"</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Content -->
            <div class="p-6">
                <div class="content">
                    <?php
                    // Bersihkan konten dari HTML tags yang tidak diinginkan
                    $konten = $renungan['konten'];
                    
                    if (!empty($konten)) {
                        // Buat DOMDocument
                        $dom = new DOMDocument();
                        libxml_use_internal_errors(true);
                        
                        // Load HTML content
                        $dom->loadHTML('<?xml encoding="UTF-8">' . $konten, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                        libxml_clear_errors();
                        
                        // Hapus attributes yang tidak diinginkan
                        $xpath = new DOMXPath($dom);
                        $elements = $xpath->query('//*');
                        
                        foreach ($elements as $element) {
                            $element->removeAttribute('class');
                            $element->removeAttribute('data-fontsize');
                            $element->removeAttribute('style');
                            $element->removeAttribute('id');
                        }
                        
                        // Get cleaned HTML
                        $konten_clean = $dom->saveHTML();
                        $konten_clean = str_replace('<?xml encoding="UTF-8">', '', $konten_clean);
                        $konten_clean = str_replace(['&nbsp;'], [' '], $konten_clean);
                        $konten_clean = preg_replace('/\s+/', ' ', $konten_clean);
                        $konten_clean = trim($konten_clean);
                    } else {
                        $konten_clean = '';
                    }
                    
                    echo $konten_clean;
                    ?>
                </div>
            </div>
            
            <!-- Article Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-calendar mr-2"></i>
                        Dipublikasikan <?= $tanggal->format('d M Y') ?>
                    </div>
                    
                    <a href="renungan.php" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2 rounded-lg transition-colors text-sm font-medium">
                        <i class="fas fa-list mr-2"></i>Lihat Semua Renungan
                    </a>
                </div>
            </div>
            
        </article>
        
        <!-- Share Section -->
        <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">Bagikan Renungan</h3>
            <div class="flex justify-center gap-4">
                <button onclick="shareRenungan()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-share mr-2"></i>Bagikan
                </button>
                <button onclick="copyLink()" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-copy mr-2"></i>Salin Link
                </button>
            </div>
        </div>
        
    </main>
    
    <!-- Footer - diseragamkan dengan renungan.php -->
    <footer class="bg-amber-900 text-amber-50 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2025 Gereja Kristen Jawa Randuares. Semua hak dilindungi. | <a href="https://appsbee.my.id" target="_blank" class="text-amber-200 hover:text-amber-100 transition-colors">appsBee</a></p>
        </div>
    </footer>
    
    <script>
        function shareRenungan() {
            if (navigator.share) {
                navigator.share({
                    title: '<?= htmlspecialchars($renungan['judul']) ?>',
                    text: 'Renungan harian yang menguatkan iman',
                    url: window.location.href
                });
            } else {
                copyLink();
            }
        }
        
        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert('Link renungan telah disalin!');
            });
        }
    </script>
    
</body>
</html>
