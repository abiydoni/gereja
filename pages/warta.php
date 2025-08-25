<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Ambil warta gereja
try {
    $db = new Database();
    $db->query("SELECT * FROM warta_gereja WHERE status = 'aktif' ORDER BY tanggal_posting DESC");
    $warta_list = $db->resultSet();
} catch (Exception $e) {
    $warta_list = [];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warta Gereja - <?php echo getNamaGereja(); ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
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

    <!-- Header Section -->
    <section class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4" data-aos="fade-up">
                Warta Gereja
            </h1>
            <p class="text-xl opacity-90" data-aos="fade-up" data-aos-delay="200">
                Informasi terbaru dan pengumuman penting gereja
            </p>
        </div>
    </section>

    <!-- Warta Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <?php if (empty($warta_list)): ?>
            <!-- Tidak ada warta -->
            <div class="text-center py-16" data-aos="fade-up">
                <div class="w-24 h-24 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-newspaper text-4xl text-amber-600"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-600 mb-2">Belum Ada Warta</h3>
                <p class="text-gray-500">Saat ini belum ada warta gereja yang diposting</p>
            </div>
            <?php else: ?>
            
            <!-- Filter dan Pencarian -->
            <div class="mb-8" data-aos="fade-up">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Cari warta gereja..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>
                    <div class="flex gap-2">
                        <select id="filterKategori" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            <option value="">Semua Kategori</option>
                            <option value="pengumuman">Pengumuman</option>
                            <option value="acara">Acara</option>
                            <option value="berita">Berita</option>
                            <option value="informasi">Informasi</option>
                        </select>
                        <select id="filterUrutan" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            <option value="terbaru">Terbaru</option>
                            <option value="terlama">Terlama</option>
                            <option value="judul">Judul A-Z</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Daftar Warta -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="wartaContainer">
                <?php foreach ($warta_list as $warta): ?>
                <div class="bg-white rounded-xl shadow-lg border border-amber-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-block bg-amber-100 text-amber-800 text-xs px-2 py-1 rounded-full">
                                <?php echo ucfirst($warta->kategori ?? 'Umum'); ?>
                            </span>
                            <span class="text-xs text-gray-500">
                                <?php echo date('d M Y', strtotime($warta->tanggal_posting ?? date('Y-m-d'))); ?>
                            </span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-800 mb-3 line-clamp-2">
                            <?php echo htmlspecialchars($warta->judul ?? 'Judul Warta'); ?>
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            <?php echo htmlspecialchars($warta->isi_singkat ?? $warta->isi ?? 'Deskripsi warta tidak tersedia'); ?>
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">
                                <i class="fas fa-user mr-1"></i>
                                <?php echo htmlspecialchars($warta->penulis ?? 'Admin Gereja'); ?>
                            </span>
                            
                            <button class="text-amber-600 hover:text-amber-700 text-sm font-medium transition-colors"
                                    onclick="showWartaDetail('<?php echo htmlspecialchars($warta->judul ?? ''); ?>', '<?php echo htmlspecialchars($warta->isi ?? ''); ?>', '<?php echo htmlspecialchars($warta->kategori ?? ''); ?>', '<?php echo htmlspecialchars($warta->penulis ?? ''); ?>', '<?php echo date('d M Y', strtotime($warta->tanggal_posting ?? date('Y-m-d'))); ?>')">
                                Baca Selengkapnya
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Quick Actions Section -->
    <section class="py-16 bg-gradient-to-br from-amber-50 to-amber-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-amber-900 mb-4">Akses Cepat</h2>
                <p class="text-lg text-amber-800">Layanan dan informasi penting gereja</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="jadwal-ibadah.php" class="bg-white p-6 rounded-xl shadow-lg border-2 border-amber-200 hover:border-amber-300 transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-alt text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="text-xl font-bold text-amber-900 mb-2 text-center">Jadwal Ibadah</h3>
                    <p class="text-amber-800 text-center">Lihat jadwal ibadah dan kegiatan gereja</p>
                </a>
                
                <a href="renungan.php" class="bg-white p-6 rounded-xl shadow-lg border-2 border-amber-200 hover:border-amber-300 transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-book-open text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="text-xl font-bold text-amber-900 mb-2 text-center">Renungan Harian</h3>
                    <p class="text-amber-800 text-center">Baca renungan rohani harian</p>
                </a>
                
                <a href="galeri.php" class="bg-white p-6 rounded-xl shadow-lg border-2 border-amber-200 hover:border-amber-300 transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-images text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="text-xl font-bold text-amber-900 mb-2 text-center">Galeri Foto</h3>
                    <p class="text-amber-800 text-center">Lihat dokumentasi kegiatan gereja</p>
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

    <!-- Modal Warta Detail -->
    <div id="wartaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 id="modalTitle" class="text-2xl font-bold text-gray-800"></h2>
                        <button onclick="closeWartaModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <span id="modalKategori" class="inline-block bg-amber-100 text-amber-800 px-2 py-1 rounded-full"></span>
                            <span id="modalTanggal"></span>
                            <span id="modalPenulis"></span>
                        </div>
                    </div>
                    
                    <div id="modalContent" class="text-gray-700 leading-relaxed mb-6"></div>
                    
                    <div class="text-right">
                        <button onclick="closeWartaModal()" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const wartaCards = document.querySelectorAll('#wartaContainer > div');
            
            wartaCards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const content = card.querySelector('p').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || content.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Filter functionality
        document.getElementById('filterKategori').addEventListener('change', function() {
            filterWarta();
        });

        document.getElementById('filterUrutan').addEventListener('change', function() {
            filterWarta();
        });

        function filterWarta() {
            const kategori = document.getElementById('filterKategori').value;
            const urutan = document.getElementById('filterUrutan').value;
            const wartaCards = document.querySelectorAll('#wartaContainer > div');
            
            wartaCards.forEach(card => {
                const cardKategori = card.querySelector('span').textContent.toLowerCase();
                
                if (!kategori || cardKategori.includes(kategori.toLowerCase())) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Sort functionality bisa ditambahkan di sini
        }

        // Modal functionality
        function showWartaDetail(title, content, kategori, penulis, tanggal) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalKategori').textContent = kategori;
            document.getElementById('modalTanggal').textContent = tanggal;
            document.getElementById('modalPenulis').textContent = 'Oleh: ' + penulis;
            document.getElementById('modalContent').innerHTML = content.replace(/\n/g, '<br>');
            document.getElementById('wartaModal').classList.remove('hidden');
        }

        function closeWartaModal() {
            document.getElementById('wartaModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('wartaModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeWartaModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeWartaModal();
            }
        });
    </script>
</body>
</html>
