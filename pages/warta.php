<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Muat data warta (fallback aman bila tabel belum ada)
$kopJudul = getNamaGereja();
$tanggalWarta = date('Y-m-d');
$nomorWarta = null;
$ucapanSelamat = 'Selamat datang dalam persekutuan ibadah. Kiranya damai dan kasih Kristus menyertai kita semua.';
$renunganSingkat = null;
$jadwalMajelis = [];
$jadwalIbadah = [];
$pengumuman = [];

try {
    $db = new Database();

    // Nomor & tanggal warta
    try {
        $db->query("SELECT nomor, tanggal FROM warta ORDER BY tanggal DESC LIMIT 1");
        $warta = $db->single();
        if ($warta) {
            $tanggalWarta = $warta->tanggal ?: $tanggalWarta;
            $nomorWarta = $warta->nomor ?: null;
        }
    } catch (Exception $e) {}

    // Ucapan selamat datang
    try {
        $db->query("SELECT konten FROM ucapan_selamat ORDER BY updated_at DESC LIMIT 1");
        $row = $db->single();
        if ($row && !empty($row->konten)) {
            $ucapanSelamat = $row->konten;
        }
    } catch (Exception $e) {}

    // Renungan singkat
    try {
        $db->query("SELECT judul, ayat, konten FROM renungan ORDER BY tanggal DESC LIMIT 1");
        $ren = $db->single();
        if ($ren) {
            $renunganSingkat = $ren;
        }
    } catch (Exception $e) {}

    // Jadwal Majelis bertugas
    try {
        $db->query("SELECT tugas, nama, keterangan FROM jadwal_majelis WHERE tanggal = :tgl ORDER BY id ASC");
        $db->bind(':tgl', $tanggalWarta);
        $jadwalMajelis = $db->resultSet();
    } catch (Exception $e) { $jadwalMajelis = []; }

    // Jadwal ibadah (gabung rutin mingguan + per tanggal terdekat)
    try {
        $db->query("SELECT * FROM jadwal_ibadah ORDER BY tanggal IS NULL, tanggal = '0000-00-00', tanggal ASC, waktu_mulai ASC");
        $jadwalIbadah = $db->resultSet();
    } catch (Exception $e) { $jadwalIbadah = []; }

    // Pengumuman terbaru
    try {
        $db->query("SELECT kategori, judul, isi FROM pengumuman ORDER BY created_at DESC LIMIT 20");
        $pengumuman = $db->resultSet();
    } catch (Exception $e) { $pengumuman = []; }
} catch (Exception $e) {
    // koneksi gagal, pakai fallback default
}

// Bantu split jadwal ibadah seperti halaman jadwal
$rutin = [];
$perTanggal = [];
foreach ($jadwalIbadah as $j) {
    if (!$j->tanggal || $j->tanggal == '0000-00-00' || $j->tanggal == 'NULL') {
        $rutin[] = $j;
    } else {
        $perTanggal[] = $j;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warta Gereja - <?php echo htmlspecialchars($kopJudul); ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
        @media print {
            nav, footer, .no-print { display: none !important; }
            body { padding-top: 0 !important; background: #ffffff !important; }
            .print-container { max-width: 100% !important; padding: 0 !important; }
            .card { box-shadow: none !important; border: 1px solid #e5e7eb !important; }
            .text-amber-800, .text-amber-900 { color: #000 !important; }
        }
    </style>
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
                    <span class="text-xl font-bold text-gray-800">Warta Gereja</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <section class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-2" data-aos="fade-up">Warta Gereja</h1>
            <p class="opacity-90" data-aos="fade-up" data-aos-delay="150">Profil: <?php echo htmlspecialchars($kopJudul); ?></p>
            <p class="opacity-90" data-aos="fade-up" data-aos-delay="200">
                Tanggal: <?php echo htmlspecialchars(formatTanggalIndonesia(date('Y-m-d', strtotime($tanggalWarta)))); ?>
                <?php if ($nomorWarta): ?> | Nomor: <?php echo htmlspecialchars($nomorWarta); ?><?php endif; ?>
            </p>
        </div>
    </section>

    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4 space-y-10 print-container">

            <!-- Toolbar -->
            <div class="flex items-center justify-between no-print" data-aos="fade-up">
                <div></div>
                <div class="flex items-center gap-3">
                    <a href="../admin/warta/" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 transition">
                        <i class="fas fa-pen"></i>
                        Kelola Warta
                    </a>
                    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-amber-600 text-white hover:bg-amber-700 transition">
                        <i class="fas fa-print"></i>
                        Cetak / PDF
                    </button>
                </div>
            </div>

            <!-- Ucapan Selamat Datang -->
            <section class="bg-white rounded-xl shadow p-6 card" data-aos="fade-up">
                <h2 class="text-xl font-bold text-amber-800 mb-3">Ucapan Selamat Datang</h2>
                <p class="text-gray-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($ucapanSelamat)); ?></p>
            </section>

            <!-- Renungan Singkat -->
            <section class="bg-white rounded-xl shadow p-6 card" data-aos="fade-up">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xl font-bold text-amber-800">Renungan Singkat</h2>
                </div>
                <?php if ($renunganSingkat): ?>
                <div>
                    <p class="text-gray-900 font-semibold mb-1"><?php echo htmlspecialchars($renunganSingkat->judul); ?></p>
                    <?php if (!empty($renunganSingkat->ayat)): ?>
                        <p class="text-amber-700 text-sm mb-2"><?php echo htmlspecialchars($renunganSingkat->ayat); ?></p>
                    <?php endif; ?>
                    <p class="text-gray-700 leading-relaxed"><?php echo nl2br(htmlspecialchars(mb_strimwidth($renunganSingkat->konten, 0, 600, '...'))); ?></p>
                </div>
                <?php else: ?>
                <p class="text-gray-500">Belum ada renungan.</p>
                <?php endif; ?>
            </section>

            <!-- Jadwal Majelis yang Bertugas -->
            <section class="bg-white rounded-xl shadow p-6 card" data-aos="fade-up">
                <h2 class="text-xl font-bold text-amber-800 mb-3">Majelis yang Bertugas</h2>
                <?php if (empty($jadwalMajelis)): ?>
                    <p class="text-gray-500">Belum ada data tugas majelis untuk tanggal ini.</p>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-amber-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Tugas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $no=1; foreach ($jadwalMajelis as $jm): ?>
                            <tr class="hover:bg-amber-50">
                                <td class="px-6 py-3 text-sm text-gray-700"><?php echo $no++; ?></td>
                                <td class="px-6 py-3 text-sm text-gray-700"><?php echo htmlspecialchars($jm->tugas); ?></td>
                                <td class="px-6 py-3 text-sm text-gray-900 font-medium"><?php echo htmlspecialchars($jm->nama); ?></td>
                                <td class="px-6 py-3 text-sm text-gray-700"><?php echo htmlspecialchars($jm->keterangan ?: '-'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </section>

            <!-- Jadwal Ibadah -->
            <section class="bg-white rounded-xl shadow p-6 card" data-aos="fade-up">
                <h2 class="text-xl font-bold text-amber-800 mb-4">Jadwal Ibadah</h2>

                <!-- Rutin Mingguan -->
                <div class="mb-6">
                    <h3 class="text-amber-900 font-semibold mb-2">Rutin Mingguan</h3>
                    <?php if (empty($rutin)): ?>
                        <p class="text-gray-500">Belum ada jadwal rutin.</p>
                    <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-amber-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Judul</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Jenis</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Waktu Mulai</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Tempat</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $no=1; foreach ($rutin as $jd): ?>
                                <tr class="hover:bg-amber-50">
                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo $no++; ?></td>
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($jd->judul); ?></td>
                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo ucfirst(str_replace('_',' ',$jd->jenis_ibadah)); ?></td>
                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo date('H:i', strtotime($jd->waktu_mulai)); ?> WIB</td>
                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo htmlspecialchars($jd->tempat ?: '-'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Per Tanggal -->
                <div>
                    <h3 class="text-amber-900 font-semibold mb-2">Per Tanggal</h3>
                    <?php if (empty($perTanggal)): ?>
                        <p class="text-gray-500">Belum ada jadwal per tanggal.</p>
                    <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-amber-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Judul</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Jenis</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Waktu Mulai</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-amber-900 uppercase tracking-wider">Tempat</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $no=1; foreach ($perTanggal as $jd): ?>
                                <tr class="hover:bg-amber-50">
                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo $no++; ?></td>
                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo formatTanggalIndonesia($jd->tanggal); ?></td>
                                    <td class="px-4 py-2 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($jd->judul); ?></td>
                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo ucfirst(str_replace('_',' ',$jd->jenis_ibadah)); ?></td>
                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo date('H:i', strtotime($jd->waktu_mulai)); ?> WIB</td>
                                    <td class="px-4 py-2 text-sm text-gray-700"><?php echo htmlspecialchars($jd->tempat ?: '-'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Pengumuman -->
            <section class="bg-white rounded-xl shadow p-6" data-aos="fade-up">
                <h2 class="text-xl font-bold text-amber-800 mb-3">Pengumuman</h2>
                <?php if (empty($pengumuman)): ?>
                    <p class="text-gray-500">Belum ada pengumuman.</p>
                <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($pengumuman as $p): ?>
                    <div class="border border-amber-100 rounded-lg p-4 hover:bg-amber-50 transition">
                        <div class="text-sm text-amber-700 font-semibold mb-1"><?php echo htmlspecialchars($p->kategori ?: 'Umum'); ?></div>
                        <div class="text-gray-900 font-semibold"><?php echo htmlspecialchars($p->judul); ?></div>
                        <?php if (!empty($p->isi)): ?>
                            <p class="text-gray-700 mt-1"><?php echo nl2br(htmlspecialchars($p->isi)); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-amber-900 text-amber-50 py-8 mt-10">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2025 Gereja Kristen Jawa Randuares. Semua hak dilindungi. | <a href="https://appsbee.my.id" target="_blank" class="text-amber-200 hover:text-amber-100 transition-colors">appsBee</a></p>
        </div>
    </footer>

    <script>
        AOS.init({ duration: 800, once: true });
    </script>
</body>
</html>
