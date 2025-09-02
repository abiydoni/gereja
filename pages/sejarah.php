<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Ambil data sejarah (single row)
$sejarah = null;
$tahunMelayani = null;
try {
    $db = new Database();
    $db->query("SELECT * FROM sejarah WHERE id = 1");
    $sejarah = $db->single();
    if ($sejarah && !empty($sejarah['tahun_didirikan'])) {
        $tahunMelayani = (int)date('Y') - (int)$sejarah['tahun_didirikan'];
    }
} catch (Exception $e) {
    $sejarah = null;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sejarah Gereja - <?php echo getNamaGereja(); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<body class="bg-gray-50" style="padding-top: 80px;">
    <!-- Navigation -->
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
                    <span class="text-xl font-bold text-gray-800">Sejarah Gereja</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <section class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4" data-aos="fade-up">
                Sejarah <?php echo htmlspecialchars(getNamaGereja()); ?>
            </h1>
            <?php if ($tahunMelayani !== null): ?>
            <p class="text-lg opacity-90" data-aos="fade-up" data-aos-delay="150">Melayani sekitar <?php echo $tahunMelayani; ?>+ tahun sejak <?php echo htmlspecialchars($sejarah['tahun_didirikan']); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Konten Sejarah -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <?php if ($sejarah): ?>
            <div class="mb-6" data-aos="fade-up">
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                    <i class="fas fa-scroll mr-2"></i> Sejarah Gereja
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-amber-900 mt-3"><?php echo htmlspecialchars($sejarah['judul']); ?></h2>
                <div class="mt-3 flex flex-wrap gap-2 text-sm">
                    <?php if (!empty($sejarah['tahun_didirikan'])): ?>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                        <i class="fas fa-calendar-day"></i> Didirikan <?php echo htmlspecialchars($sejarah['tahun_didirikan']); ?>
                    </span>
                    <?php endif; ?>
                    <?php if ($tahunMelayani !== null): ?>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                        <i class="fas fa-hourglass-half"></i> <?php echo $tahunMelayani; ?>+ Tahun Melayani
                    </span>
                    <?php endif; ?>
                    <?php if (!empty($sejarah['updated_at'])): ?>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                        <i class="fas fa-pen"></i> Diperbarui: <?php echo date('d M Y', strtotime($sejarah['updated_at'])); ?>
                    </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="relative rounded-2xl p-[1px] bg-gradient-to-br from-amber-300 via-amber-200 to-amber-100 shadow-xl" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-white/95 rounded-2xl p-6 md:p-8">
                    <div class="prose max-w-none text-amber-900 leading-relaxed whitespace-pre-line">
                        <?php echo nl2br(htmlspecialchars($sejarah['konten'])); ?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="bg-white rounded-2xl border border-amber-200 shadow p-6 text-center text-amber-800" data-aos="fade-up">
                Data sejarah belum tersedia.
            </div>
            <?php endif; ?>

            <div class="mt-10" data-aos="fade-up" data-aos-delay="150">
                <a href="../" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-amber-600 text-white font-semibold shadow hover:bg-amber-700 transition-colors">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
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
