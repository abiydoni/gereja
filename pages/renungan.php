<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Ambil renungan (contoh sederhana)
try {
    $db = new Database();
    $db->query("SELECT * FROM renungan ORDER BY tanggal DESC");
    $renungan_list = $db->resultSet();
} catch (Exception $e) {
    $renungan_list = [];
}
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

    <!-- Konten renungan -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <?php if (empty($renungan_list)): ?>
            <div class="text-center py-12 bg-white rounded-xl shadow" data-aos="fade-up">
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-book-open text-2xl text-amber-700"></i>
                </div>
                <p class="text-gray-600">Belum ada renungan.</p>
            </div>
            <?php else: foreach ($renungan_list as $renungan): ?>
            <article class="bg-white rounded-xl shadow border border-amber-200 p-6 mb-6" data-aos="fade-up">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-xl font-bold text-amber-900"><?php echo htmlspecialchars($renungan->judul); ?></h2>
                    <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-800">
                        <?php echo formatTanggalIndonesia(date('Y-m-d', strtotime($renungan->tanggal))); ?>
                    </span>
                </div>
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    <?php echo nl2br(htmlspecialchars($renungan->isi)); ?>
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
