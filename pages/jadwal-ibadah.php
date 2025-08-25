<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Ambil jadwal ibadah
$jadwal_ibadah = [];

try {
    $db = new Database();
    $db->query("SELECT * FROM jadwal_ibadah ORDER BY tanggal IS NULL, tanggal = '0000-00-00', tanggal ASC, waktu_mulai ASC");
    $jadwal_ibadah = $db->resultSet();
} catch (Exception $e) {
    $jadwal_ibadah = [];
}

// Bagi dua: rutin vs per tanggal
$rutin = [];
$perTanggal = [];
foreach ($jadwal_ibadah as $j) {
    if (!$j->tanggal || $j->tanggal == '0000-00-00' || $j->tanggal == 'NULL') {
        $rutin[] = $j;
    } else {
        $perTanggal[] = $j;
    }
}

// Kelompokkan per tanggal berdasarkan bulan-tahun
$perBulan = [];
foreach ($perTanggal as $j) {
    $key = date('F Y', strtotime($j->tanggal)); // contoh: March 2025
    if (!isset($perBulan[$key])) $perBulan[$key] = [];
    $perBulan[$key][] = $j;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Ibadah - Sistem Gereja</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
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
                    <span class="text-xl font-bold text-gray-800">Jadwal Ibadah</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <section class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4" data-aos="fade-up">
                Jadwal Ibadah
            </h1>
            <p class="text-xl opacity-90" data-aos="fade-up" data-aos-delay="200">
                Dibedakan antara Jadwal Rutin Mingguan dan Jadwal Per Tanggal (per Bulan)
            </p>
        </div>
    </section>

    <!-- Jadwal Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 space-y-12">
            <?php if (empty($jadwal_ibadah)): ?>
            <!-- Tidak ada jadwal -->
            <div class="text-center py-16" data-aos="fade-up">
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-calendar-times text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-600 mb-2">Belum Ada Jadwal</h3>
                <p class="text-gray-500">Saat ini belum ada data jadwal ibadah</p>
            </div>
            <?php else: ?>

            <!-- Tabel: Jadwal Rutin Mingguan -->
            <div class="space-y-4" data-aos="fade-up">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-amber-800">Jadwal Rutin Mingguan</h2>
                </div>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-amber-600">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Jenis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Waktu Mulai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tempat</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (empty($rutin)): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada jadwal rutin</td>
                                </tr>
                                <?php else: $no=1; foreach ($rutin as $jadwal): ?>
                                <tr class="hover:bg-amber-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $no++; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($jadwal->judul); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo ucfirst(str_replace('_', ' ', $jadwal->jenis_ibadah)); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo date('H:i', strtotime($jadwal->waktu_mulai)); ?> WIB</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($jadwal->tempat ?: '-'); ?></td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tabel: Jadwal Per Tanggal (Per Bulan) -->
            <div class="space-y-6" data-aos="fade-up">
                <h2 class="text-2xl font-bold text-amber-800">Jadwal Per Tanggal (Per Bulan)</h2>
                <?php if (empty($perBulan)): ?>
                <div class="bg-white rounded-xl shadow p-6 text-center text-gray-500">Tidak ada jadwal per tanggal</div>
                <?php else: ?>
                    <?php foreach ($perBulan as $bulan => $items): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 bg-amber-100 border-b border-amber-200">
                            <h3 class="text-lg font-semibold text-amber-900"><?php echo $bulan; ?></h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-amber-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Judul</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Jenis</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Waktu Mulai</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Tempat</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $no=1; foreach ($items as $jadwal): ?>
                                    <tr class="hover:bg-amber-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo $no++; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo formatTanggalIndonesia($jadwal->tanggal); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($jadwal->judul); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo ucfirst(str_replace('_', ' ', $jadwal->jenis_ibadah)); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo date('H:i', strtotime($jadwal->waktu_mulai)); ?> WIB</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($jadwal->tempat ?: '-'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-amber-900 text-amber-50 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2025 Gereja Kristen Jawa Randuares. Semua hak dilindungi. | <a href="https://appsbee.my.id" target="_blank" class="text-amber-200 hover:text-amber-100 transition-colors">appsBee</a></p>
        </div>
    </footer>

    <script>
        // Inisialisasi AOS untuk animasi ringan (opsional)
        AOS.init({ duration: 800, once: true });
    </script>
</body>
</html>
