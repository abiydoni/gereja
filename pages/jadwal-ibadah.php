<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Ambil jadwal ibadah
try {
    $db = new Database();
    $db->query("SELECT * FROM jadwal_ibadah WHERE tanggal >= CURRENT_DATE() ORDER BY tanggal ASC, waktu_mulai ASC");
    $jadwal_ibadah = $db->resultSet();
} catch (Exception $e) {
    $jadwal_ibadah = [];
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
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <a href="../index.php" class="flex items-center space-x-2 text-gray-600 hover:text-purple-600 transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                        <span class="text-lg font-semibold">Kembali ke Beranda</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <i class="fas fa-church text-3xl text-purple-600"></i>
                    <span class="text-xl font-bold text-gray-800">Jadwal Ibadah</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <section class="bg-gradient-to-r from-purple-600 to-blue-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4" data-aos="fade-up">
                Jadwal Ibadah & Kegiatan
            </h1>
            <p class="text-xl opacity-90" data-aos="fade-up" data-aos-delay="200">
                Informasi lengkap jadwal ibadah, kegiatan, dan acara gereja
            </p>
        </div>
    </section>

    <!-- Jadwal Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <?php if (empty($jadwal_ibadah)): ?>
            <!-- Tidak ada jadwal -->
            <div class="text-center py-16" data-aos="fade-up">
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-calendar-times text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-600 mb-2">Belum Ada Jadwal</h3>
                <p class="text-gray-500">Saat ini belum ada jadwal ibadah yang dijadwalkan</p>
            </div>
            <?php else: ?>
            
            <!-- Filter dan Pencarian -->
            <div class="mb-8" data-aos="fade-up">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Cari jadwal ibadah..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div class="flex gap-2">
                        <select id="filterJenis" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Semua Jenis</option>
                            <option value="ibadah_minggu">Ibadah Minggu</option>
                            <option value="ibadah_doa">Ibadah Doa</option>
                            <option value="ibadah_pemuda">Ibadah Pemuda</option>
                            <option value="ibadah_anak">Ibadah Anak</option>
                            <option value="ibadah_khusus">Ibadah Khusus</option>
                        </select>
                        <select id="filterStatus" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="akan_datang">Akan Datang</option>
                            <option value="sedang_berlangsung">Sedang Berlangsung</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Daftar Jadwal -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="jadwalContainer">
                <?php foreach ($jadwal_ibadah as $jadwal): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden jadwal-card" 
                     data-aos="fade-up" 
                     data-jenis="<?php echo $jadwal->jenis_ibadah; ?>"
                     data-status="<?php echo $jadwal->status; ?>"
                     data-judul="<?php echo strtolower($jadwal->judul); ?>">
                    
                    <!-- Header Card -->
                    <div class="bg-gradient-to-r from-purple-500 to-blue-500 text-white p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium opacity-90">
                                <?php echo ucfirst(str_replace('_', ' ', $jadwal->jenis_ibadah)); ?>
                            </span>
                            <?php
                            $status_color = '';
                            $status_text = '';
                            switch($jadwal->status) {
                                case 'akan_datang':
                                    $status_color = 'bg-blue-500';
                                    $status_text = 'Akan Datang';
                                    break;
                                case 'sedang_berlangsung':
                                    $status_color = 'bg-green-500';
                                    $status_text = 'Sedang Berlangsung';
                                    break;
                                case 'selesai':
                                    $status_color = 'bg-gray-500';
                                    $status_text = 'Selesai';
                                    break;
                                case 'dibatalkan':
                                    $status_color = 'bg-red-500';
                                    $status_text = 'Dibatalkan';
                                    break;
                            }
                            ?>
                            <span class="px-3 py-1 text-xs font-medium <?php echo $status_color; ?> rounded-full">
                                <?php echo $status_text; ?>
                            </span>
                        </div>
                        <h3 class="text-xl font-bold"><?php echo htmlspecialchars($jadwal->judul); ?></h3>
                    </div>
                    
                    <!-- Content Card -->
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Tanggal dan Waktu -->
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-purple-600 w-5"></i>
                                <span class="ml-3 text-gray-700">
                                    <?php echo formatTanggalIndonesia($jadwal->tanggal); ?>
                                </span>
                            </div>
                            
                            <div class="flex items-center">
                                <i class="fas fa-clock text-purple-600 w-5"></i>
                                <span class="ml-3 text-gray-700">
                                    <?php echo $jadwal->waktu_mulai; ?>
                                    <?php if ($jadwal->waktu_selesai): ?>
                                        - <?php echo $jadwal->waktu_selesai; ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            
                            <!-- Tempat -->
                            <?php if ($jadwal->tempat): ?>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-purple-600 w-5"></i>
                                <span class="ml-3 text-gray-700"><?php echo htmlspecialchars($jadwal->tempat); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Pemimpin Ibadah -->
                            <?php if ($jadwal->pemimpin_ibadah): ?>
                            <div class="flex items-center">
                                <i class="fas fa-user text-purple-600 w-5"></i>
                                <span class="ml-3 text-gray-700"><?php echo htmlspecialchars($jadwal->pemimpin_ibadah); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Khotbah -->
                            <?php if ($jadwal->khotbah): ?>
                            <div class="flex items-center">
                                <i class="fas fa-bible text-purple-600 w-5"></i>
                                <span class="ml-3 text-gray-700"><?php echo htmlspecialchars($jadwal->khotbah); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Deskripsi -->
                        <?php if ($jadwal->deskripsi): ?>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($jadwal->deskripsi); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Catatan -->
                        <?php if ($jadwal->catatan): ?>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-gray-600 text-sm">
                                <strong>Catatan:</strong> <?php echo htmlspecialchars($jadwal->catatan); ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 Sistem Gereja. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script>
        // Inisialisasi AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        // Filter dan Pencarian
        const searchInput = document.getElementById('searchInput');
        const filterJenis = document.getElementById('filterJenis');
        const filterStatus = document.getElementById('filterStatus');
        const jadwalContainer = document.getElementById('jadwalContainer');
        const jadwalCards = document.querySelectorAll('.jadwal-card');

        function filterJadwal() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedJenis = filterJenis.value;
            const selectedStatus = filterStatus.value;

            jadwalCards.forEach(card => {
                const judul = card.dataset.judul;
                const jenis = card.dataset.jenis;
                const status = card.dataset.status;

                const matchesSearch = judul.includes(searchTerm);
                const matchesJenis = !selectedJenis || jenis === selectedJenis;
                const matchesStatus = !selectedStatus || status === selectedStatus;

                if (matchesSearch && matchesJenis && matchesStatus) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeIn 0.5s ease-in';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Event listeners
        searchInput.addEventListener('input', filterJadwal);
        filterJenis.addEventListener('change', filterJadwal);
        filterStatus.addEventListener('change', filterJadwal);

        // CSS Animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
