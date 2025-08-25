<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Sample data untuk demo
$kegiatan_list = [
    [
        'id' => 1,
        'nama_kegiatan' => 'Ibadah Minggu',
        'deskripsi' => 'Ibadah minggu yang diadakan setiap hari Minggu pukul 09:00 WIB',
        'kategori' => 'Ibadah Rutin',
        'tanggal_kegiatan' => '2025-01-19',
        'waktu_mulai' => '09:00',
        'waktu_selesai' => '11:00',
        'lokasi' => 'Gedung Gereja',
        'status' => 'akan_datang',
        'target_peserta' => 'Semua Jemaat',
        'penanggung_jawab' => 'Pdt. John Doe'
    ],
    [
        'id' => 2,
        'nama_kegiatan' => 'Sekolah Minggu',
        'deskripsi' => 'Program pendidikan rohani untuk anak-anak dan remaja',
        'kategori' => 'Pendidikan',
        'tanggal_kegiatan' => '2025-01-19',
        'waktu_mulai' => '10:30',
        'waktu_selesai' => '12:00',
        'lokasi' => 'Ruang Sekolah Minggu',
        'status' => 'akan_datang',
        'target_peserta' => 'Anak & Remaja',
        'penanggung_jawab' => 'Sdr. Jane Smith'
    ]
];

// Function untuk mendapatkan logo path
function getLogoPath() {
    return '../assets/images/logo.png';
}

// Function untuk mendapatkan nama gereja
function getNamaGereja() {
    return 'Gereja Kristen Jawa';
}

// Function untuk mendapatkan status badge
function getStatusBadge($status) {
    switch ($status) {
        case 'akan_datang':
            return '<span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Akan Datang</span>';
        case 'sedang_berlangsung':
            return '<span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Sedang Berlangsung</span>';
        case 'selesai':
            return '<span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">Selesai</span>';
        default:
            return '<span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">Tidak Diketahui</span>';
    }
}

// Function untuk mendapatkan kategori badge
function getKategoriBadge($kategori) {
    $colors = [
        'Ibadah Rutin' => 'bg-purple-100 text-purple-800',
        'Pendidikan' => 'bg-blue-100 text-blue-800',
        'Doa' => 'bg-green-100 text-green-800',
        'Pemuda' => 'bg-yellow-100 text-yellow-800',
        'Musik' => 'bg-pink-100 text-pink-800',
        'Pelayanan' => 'bg-indigo-100 text-indigo-800'
    ];
    
    $color = $colors[$kategori] ?? 'bg-gray-100 text-gray-800';
    return '<span class="inline-block ' . $color . ' text-xs px-2 py-1 rounded-full">' . $kategori . '</span>';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kegiatan Gereja - <?php echo getNamaGereja(); ?></title>
    
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
                <span class="text-xl font-bold text-gray-800">Kegiatan Gereja</span>
            </div>
        </div>
    </div>
</nav>

    <!-- Header Section -->
    <section class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4" data-aos="fade-up">
                Kegiatan Gereja
            </h1>
            <p class="text-xl opacity-90" data-aos="fade-up" data-aos-delay="200">
                Berbagai program dan aktivitas kerohanian yang diselenggarakan gereja
            </p>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 bg-gradient-to-br from-amber-50 to-amber-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center bg-white bg-opacity-90 p-6 rounded-xl shadow-lg border border-amber-200" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-3xl font-bold text-amber-700 mb-2"><?php echo count($kegiatan_list); ?></div>
                    <p class="text-amber-800 font-medium">Total Kegiatan</p>
                </div>
                <div class="text-center bg-white bg-opacity-90 p-6 rounded-xl shadow-lg border border-amber-200" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-3xl font-bold text-amber-700 mb-2">6</div>
                    <p class="text-amber-800 font-medium">Kategori</p>
                </div>
                <div class="text-center bg-white bg-opacity-90 p-6 rounded-xl shadow-lg border border-amber-200" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-3xl font-bold text-amber-700 mb-2">7</div>
                    <p class="text-amber-800 font-medium">Hari dalam Seminggu</p>
                </div>
                <div class="text-center bg-white bg-opacity-90 p-6 rounded-xl shadow-lg border border-amber-200" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-3xl font-bold text-amber-700 mb-2">24/7</div>
                    <p class="text-amber-800 font-medium">Akses Informasi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kegiatan Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Filter dan Pencarian -->
            <div class="mb-8" data-aos="fade-up">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Cari kegiatan..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>
                    <div class="flex gap-2">
                        <select id="filterKategori" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            <option value="">Semua Kategori</option>
                            <option value="Ibadah Rutin">Ibadah Rutin</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Doa">Doa</option>
                            <option value="Pemuda">Pemuda</option>
                            <option value="Musik">Musik</option>
                            <option value="Pelayanan">Pelayanan</option>
                        </select>
                        <select id="filterStatus" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="akan_datang">Akan Datang</option>
                            <option value="sedang_berlangsung">Sedang Berlangsung</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Daftar Kegiatan -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="kegiatanContainer">
                <?php foreach ($kegiatan_list as $kegiatan): ?>
                <div class="bg-white rounded-xl shadow-lg border border-amber-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <?php echo getKategoriBadge($kegiatan['kategori']); ?>
                            <?php echo getStatusBadge($kegiatan['status']); ?>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-800 mb-3 line-clamp-2">
                            <?php echo htmlspecialchars($kegiatan['nama_kegiatan']); ?>
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            <?php echo htmlspecialchars($kegiatan['deskripsi']); ?>
                        </p>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-calendar mr-2 text-amber-600"></i>
                                <span><?php echo date('d M Y', strtotime($kegiatan['tanggal_kegiatan'])); ?></span>
                            </div>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-clock mr-2 text-amber-600"></i>
                                <span><?php echo $kegiatan['waktu_mulai']; ?> - <?php echo $kegiatan['waktu_selesai']; ?> WIB</span>
                            </div>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-map-marker-alt mr-2 text-amber-600"></i>
                                <span><?php echo htmlspecialchars($kegiatan['lokasi']); ?></span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">
                                <i class="fas fa-user mr-1"></i>
                                <?php echo htmlspecialchars($kegiatan['penanggung_jawab']); ?>
                            </span>
                            
                            <button class="text-amber-600 hover:text-amber-700 text-sm font-medium transition-colors"
                                    onclick="showKegiatanDetail('<?php echo htmlspecialchars($kegiatan['nama_kegiatan']); ?>', '<?php echo htmlspecialchars($kegiatan['deskripsi']); ?>', '<?php echo htmlspecialchars($kegiatan['kategori']); ?>', '<?php echo htmlspecialchars($kegiatan['penanggung_jawab']); ?>', '<?php echo date('d M Y', strtotime($kegiatan['tanggal_kegiatan'])); ?>', '<?php echo $kegiatan['waktu_mulai']; ?>', '<?php echo $kegiatan['waktu_selesai']; ?>', '<?php echo htmlspecialchars($kegiatan['lokasi']); ?>', '<?php echo htmlspecialchars($kegiatan['target_peserta']); ?>')">
                                Lihat Detail
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

         <!-- Footer -->
     <footer class="bg-amber-900 text-amber-50 py-8">
         <div class="max-w-7xl mx-auto px-4 text-center">
             <p>&copy; 2025 Gereja Kristen Jawa Randuares. Semua hak dilindungi. | <a href="https://appsbee.my.id" target="_blank" class="text-amber-200 hover:text-amber-100 transition-colors">appsBee</a></p>
         </div>
     </footer>

    <!-- Modal Kegiatan Detail -->
    <div id="kegiatanModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 id="modalTitle" class="text-2xl font-bold text-gray-800"></h2>
                        <button onclick="closeKegiatanModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <span id="modalKategori"></span>
                            <span id="modalTanggal"></span>
                        </div>
                    </div>
                    
                    <div id="modalContent" class="text-gray-700 leading-relaxed mb-6"></div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                            <h4 class="font-bold text-amber-900 mb-2">Waktu</h4>
                            <p id="modalWaktu" class="text-amber-800"></p>
                        </div>
                        <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                            <h4 class="font-bold text-amber-900 mb-2">Lokasi</h4>
                            <p id="modalLokasi" class="text-amber-800"></p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                            <h4 class="font-bold text-amber-900 mb-2">Target Peserta</h4>
                            <p id="modalTarget" class="text-amber-800"></p>
                        </div>
                        <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                            <h4 class="font-bold text-amber-900 mb-2">Penanggung Jawab</h4>
                            <p id="modalPenanggungJawab" class="text-amber-800"></p>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <button onclick="closeKegiatanModal()" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
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
            const kegiatanCards = document.querySelectorAll('#kegiatanContainer > div');
            
            kegiatanCards.forEach(card => {
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
            filterKegiatan();
        });

        document.getElementById('filterStatus').addEventListener('change', function() {
            filterKegiatan();
        });

        function filterKegiatan() {
            const kategori = document.getElementById('filterKategori').value;
            const status = document.getElementById('filterStatus').value;
            const kegiatanCards = document.querySelectorAll('#kegiatanContainer > div');
            
            kegiatanCards.forEach(card => {
                const cardKategori = card.querySelector('span').textContent;
                const cardStatus = card.querySelectorAll('span')[1].textContent;
                
                const kategoriMatch = !kategori || cardKategori.includes(kategori);
                const statusMatch = !status || cardStatus.toLowerCase().includes(status.toLowerCase());
                
                if (kategoriMatch && statusMatch) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Modal functionality
        function showKegiatanDetail(nama, deskripsi, kategori, penanggungJawab, tanggal, waktuMulai, waktuSelesai, lokasi, target, penanggungJawab) {
            document.getElementById('modalTitle').textContent = nama;
            document.getElementById('modalKategori').innerHTML = getKategoriBadge(kategori);
            document.getElementById('modalTanggal').textContent = tanggal;
            document.getElementById('modalContent').innerHTML = deskripsi.replace(/\n/g, '<br>');
            document.getElementById('modalWaktu').textContent = waktuMulai + ' - ' + waktuSelesai + ' WIB';
            document.getElementById('modalLokasi').textContent = lokasi;
            document.getElementById('modalTarget').textContent = target;
            document.getElementById('modalPenanggungJawab').textContent = penanggungJawab;
            
            document.getElementById('kegiatanModal').classList.remove('hidden');
        }

        function closeKegiatanModal() {
            document.getElementById('kegiatanModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('kegiatanModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeKegiatanModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeKegiatanModal();
            }
        });

        // Helper function untuk kategori badge
        function getKategoriBadge(kategori) {
            const colors = {
                'Ibadah Rutin': 'bg-purple-100 text-purple-800',
                'Pendidikan': 'bg-blue-100 text-blue-800',
                'Doa': 'bg-green-100 text-green-800',
                'Pemuda': 'bg-yellow-100 text-yellow-800',
                'Musik': 'bg-pink-100 text-pink-800',
                'Pelayanan': 'bg-indigo-100 text-indigo-800'
            };
            
            const color = colors[kategori] || 'bg-gray-100 text-gray-800';
            return '<span class="inline-block ' + color + ' text-xs px-2 py-1 rounded-full">' + kategori + '</span>';
        }
    </script>
</body>
</html>
