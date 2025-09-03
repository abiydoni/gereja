<?php
// Start session di awal sebelum ada output HTML
session_start();

// Function untuk mendapatkan logo path langsung dari database
function getLogoPath() {
    try {
        require_once 'includes/config.php';
        require_once 'includes/database.php';
        
        $db = new Database();
        $db->query("SELECT nilai FROM pengaturan_sistem WHERE nama_pengaturan = 'logo_gereja'");
        $db->execute();
        $result = $db->single();
        
        if ($result && isset($result->nilai) && $result->nilai) {
            $logo_path = 'assets/images/' . $result->nilai;
            if (file_exists($logo_path)) {
                return $logo_path;
            }
        }
        
        // Fallback ke logo default
        return 'assets/images/logo.png';
        
    } catch (Exception $e) {
        // Fallback ke logo default jika ada error
        return 'assets/images/logo.png';
    }
}

// Function untuk mendapatkan nama gereja dari database
function getNamaGereja() {
    try {
        require_once 'includes/config.php';
        require_once 'includes/database.php';
        
        $db = new Database();
        $db->query("SELECT nilai FROM pengaturan_sistem WHERE nama_pengaturan = 'nama_gereja'");
        $db->execute();
        $result = $db->single();
        
        if ($result && isset($result->nilai) && $result->nilai) {
            return $result->nilai;
        }
        
        // Fallback ke nama default
        return 'Gereja Kristen Jawa';
        
    } catch (Exception $e) {
        // Fallback ke nama default jika ada error
        return 'Gereja Kristen Jawa';
    }
}

// Function untuk mendapatkan alamat gereja dari database
function getAlamatGereja() {
    try {
        require_once 'includes/config.php';
        require_once 'includes/database.php';
        
        $db = new Database();
        $db->query("SELECT nilai FROM pengaturan_sistem WHERE nama_pengaturan = 'alamat_gereja'");
        $db->execute();
        $result = $db->single();
        
        if ($result && isset($result->nilai) && $result->nilai) {
            return $result->nilai;
        }
        
        // Fallback ke alamat default
        return 'Salatiga, Jawa Tengah';
        
    } catch (Exception $e) {
        // Fallback ke alamat default jika ada error
        return 'Salatiga, Jawa Tengah';
    }
}

// Function untuk mendapatkan data kontak dari database
function getKontakGereja($jenis) {
    try {
        require_once 'includes/config.php';
        require_once 'includes/database.php';
        
        $db = new Database();
        $db->query("SELECT nilai FROM pengaturan_sistem WHERE nama_pengaturan = ?");
        $db->bind(1, $jenis);
        $db->execute();
        $result = $db->single();
        
        if ($result && isset($result->nilai) && $result->nilai) {
            return $result->nilai;
        }
        
        // Fallback berdasarkan jenis kontak
        switch ($jenis) {
            case 'email_gereja':
                return 'info@gkjranduares.com';
            case 'telepon_gereja':
                return '+62 298 1234 5678';
            case 'alamat_gereja':
                return 'Salatiga, Jawa Tengah';
            case 'jam_pelayanan':
                return 'Senin - Jumat: 08:00 - 17:00 WIB';
            default:
                return 'Informasi tidak tersedia';
        }
        
    } catch (Exception $e) {
        // Fallback ke nilai default jika ada error
        switch ($jenis) {
            case 'email_gereja':
                return 'info@gkjranduares.com';
            case 'telepon_gereja':
                return '+62 298 1234 5678';
            case 'alamat_gereja':
                return 'Salatiga, Jawa Tengah';
            case 'jam_pelayanan':
                return 'Senin - Jumat: 08:00 - 17:00 WIB';
            default:
                return 'Informasi tidak tersedia';
        }
    }
}

// Ambil data jadwal ibadah langsung
$jadwal_ibadah = [];
try {
    // Pastikan database class sudah dimuat
    if (!class_exists('Database')) {
        require_once 'includes/database.php';
    }
    
    $db = new Database();
    $db->query("SELECT * FROM jadwal_ibadah ORDER BY waktu_mulai ASC LIMIT 4");
    $jadwal_ibadah = $db->resultSet();
    error_log("Jadwal ibadah loaded: " . count($jadwal_ibadah) . " records");
} catch (Exception $e) {
    error_log("Error getting jadwal ibadah: " . $e->getMessage());
    $jadwal_ibadah = [];
}

// Ambil data sejarah (single row)
$sejarah = null;
$tahunMelayani = null;
try {
    if (!isset($db)) { $db = new Database(); }
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
    <title><?php echo getNamaGereja(); ?> - Sistem Gereja</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
         <!-- Google Fonts -->
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
     
     <!-- Custom CSS -->
     <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-50" style="overflow-x: hidden;">
    <!-- Navigation -->
    <nav class="navbar-transparent shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img src="<?php echo getLogoPath(); ?>" alt="Logo Gereja" class="navbar-logo w-10 h-10 md:w-12 md:h-12 object-contain">
                    </div>
                    <span class="text-lg md:text-xl font-bold text-gray-800"><?php echo getNamaGereja(); ?></span>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex nav-menu">
                    <a href="#beranda" class="text-gray-700 hover:text-amber-600 transition-colors font-medium">Beranda</a>
                    <a href="#tentang" class="text-gray-700 hover:text-amber-600 transition-colors font-medium">Tentang</a>
                    <a href="#aktivitas" class="text-gray-700 hover:text-amber-600 transition-colors font-medium">Aktivitas</a>
                    <a href="#jadwal-ibadah" class="text-gray-700 hover:text-amber-600 transition-colors font-medium">Jadwal</a>
                    <a href="#galeri" class="text-gray-700 hover:text-amber-600 transition-colors font-medium">Galeri</a>
                    <a href="#kontak" class="text-gray-700 hover:text-amber-600 transition-colors font-medium">Kontak</a>
                    
                    <!-- Menu berdasarkan session -->
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <!-- Menu untuk user yang sudah login -->
                        <a href="pages/dashboard.php" class="text-gray-700 hover:text-amber-600 transition-colors font-medium">Dashboard</a>
                        <a href="pages/jemaat.php" class="text-gray-700 hover:text-amber-600 transition-colors font-medium">Data Jemaat</a>
                        <a href="pages/keuangan.php" class="text-gray-700 hover:text-amber-600 transition-colors font-medium">Keuangan</a>
                        <a href="pages/warta.php" class="text-gray-700 hover:text-amber-600 transition-colors font-medium">Warta Gereja</a>
                        <a href="pages/renungan.php" class="text-gray-700 hover:text-amber-600 transition-colors font-medium">Renungan</a>
                    <?php endif; ?>
                </div>
                
                <!-- Desktop Login/User Menu -->
                <div class="hidden md:flex space-x-4">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <!-- User Menu -->
                        <div class="relative group">
                            <button class="user-menu-button flex items-center space-x-2 text-amber-800 px-4 py-2 rounded-full transition-all duration-200">
                                <i class="fas fa-user-circle"></i>
                                <span><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-2">
                                    <a href="admin/profile.php" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                                        <i class="fas fa-user mr-2"></i>Profile
                                    </a>
                                    <a href="admin/settings.php" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                                        <i class="fas fa-cog mr-2"></i>Pengaturan
                                    </a>
                                    <div class="border-t border-gray-200 my-1"></div>
                                    <a href="admin/logout.php" class="block px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Login Button -->
                        <a href="admin/login.php" class="cta-button text-white px-6 py-3 rounded-full font-semibold">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login Admin
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-amber-600 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200 py-4">
                <div class="flex flex-col space-y-4">
                    <a href="#beranda" class="text-gray-700 hover:text-amber-600 transition-colors font-medium px-4 py-2">Beranda</a>
                    <a href="#tentang" class="text-gray-700 hover:text-amber-600 transition-colors font-medium px-4 py-2">Tentang</a>
                    <a href="#aktivitas" class="text-gray-700 hover:text-amber-600 transition-colors font-medium px-4 py-2">Aktivitas</a>
                    <a href="#jadwal-ibadah" class="text-gray-700 hover:text-amber-600 transition-colors font-medium px-4 py-2">Jadwal</a>
                    <a href="#galeri" class="text-gray-700 hover:text-amber-600 transition-colors font-medium px-4 py-2">Galeri</a>
                    <a href="#kontak" class="text-gray-700 hover:text-amber-600 transition-colors font-medium px-4 py-2">Kontak</a>
                    
                    <!-- Menu berdasarkan session untuk mobile -->
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <!-- Menu untuk user yang sudah login -->
                        <a href="pages/dashboard.php" class="text-gray-700 hover:text-amber-600 transition-colors font-medium px-4 py-2">Dashboard</a>
                        <a href="pages/jemaat.php" class="text-gray-700 hover:text-amber-600 transition-colors font-medium px-4 py-2">Data Jemaat</a>
                        <a href="pages/keuangan.php" class="text-gray-700 hover:text-amber-600 transition-colors font-medium px-4 py-2">Keuangan</a>
                        <a href="pages/warta.php" class="text-gray-700 hover:text-amber-600 transition-colors font-medium px-4 py-2">Warta Gereja</a>
                        <a href="pages/renungan.php" class="text-gray-700 hover:text-amber-600 transition-colors font-medium px-4 py-2">Renungan</a>
                        
                        <!-- User Info Panel -->
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="mobile-user-info px-4 py-3">
                                <div class="flex items-center space-x-2 mb-3">
                                    <i class="fas fa-user-circle text-amber-600"></i>
                                    <span class="font-medium text-gray-800"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                                </div>
                                <a href="admin/profile.php" class="block text-gray-700 hover:text-amber-600 transition-colors py-2">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                                <a href="admin/settings.php" class="block text-gray-700 hover:text-amber-600 transition-colors py-2">
                                    <i class="fas fa-cog mr-2"></i>Pengaturan
                                </a>
                                <a href="admin/logout.php" class="block text-red-600 hover:text-red-700 transition-colors py-2">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Login Button untuk Guest -->
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <a href="admin/login.php" class="cta-button text-white px-6 py-3 rounded-full font-semibold inline-block text-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login Admin
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="hero-section text-white min-h-screen flex items-center justify-center relative overflow-hidden pt-20">
        <!-- Background Slideshow -->
        <div class="background-slideshow">
            <div class="slide active" style="background-image: url('assets/images/bg/bg1.jpg')"></div>
            <div class="slide" style="background-image: url('assets/images/bg/bg2.jpg')"></div>
            <div class="slide" style="background-image: url('assets/images/bg/bg3.jpg')"></div>
            <div class="slide" style="background-image: url('assets/images/bg/bg4.jpg')"></div>
            <div class="slide" style="background-image: url('assets/images/bg/bg5.jpg')"></div>
        </div>
        
        <!-- Overlay untuk text readability -->
        <div class="hero-overlay"></div>
        
        <!-- Slideshow Indicators -->
        <div class="slideshow-indicators absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 flex space-x-3">
            <button class="indicator active w-3 h-3 rounded-full bg-white bg-opacity-60 hover:bg-opacity-100 transition-all duration-300" data-slide="0"></button>
            <button class="indicator w-3 h-3 rounded-full bg-white bg-opacity-60 hover:bg-opacity-100 transition-all duration-300" data-slide="1"></button>
            <button class="indicator w-3 h-3 rounded-full bg-white bg-opacity-60 hover:bg-opacity-100 transition-all duration-300" data-slide="2"></button>
            <button class="indicator w-3 h-3 rounded-full bg-white bg-opacity-60 hover:bg-opacity-100 transition-all duration-300" data-slide="3"></button>
            <button class="indicator w-3 h-3 rounded-full bg-white bg-opacity-60 hover:bg-opacity-100 transition-all duration-300" data-slide="4"></button>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-7xl font-bold mb-6 leading-tight text-white drop-shadow-2xl" data-aos="fade-up" data-aos-delay="400">
                <?php echo getNamaGereja(); ?>
            </h1>
            <p class="text-sm sm:text-xl md:text-2xl lg:text-3xl text-amber-100 font-semibold drop-shadow-lg mb-8" data-aos="fade-up" data-aos-delay="600">
                <?php echo getAlamatGereja(); ?>
            </p>
            
            <p class="text-lg sm:text-xl md:text-2xl mb-8 md:mb-12 text-amber-50 max-w-3xl mx-auto leading-relaxed px-4" data-aos="fade-up" data-aos-delay="800">
                Menggabungkan tradisi Jawa yang luhur dengan iman Kristen yang teguh. 
                Membangun persekutuan yang hangat dalam kasih Kristus.
            </p>
            
            <!-- Quick Access Buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 max-w-4xl mx-auto px-4" data-aos="fade-up" data-aos-delay="1000">
                <a href="pages/warta.php" class="bg-amber-100 bg-opacity-20 backdrop-blur-sm border border-amber-200 border-opacity-30 rounded-xl p-3 md:p-4 hover:bg-amber-100 hover:bg-opacity-30 transition-all duration-300 group">
                    <div class="flex items-center justify-center space-x-2 md:space-x-3">
                        <i class="fas fa-newspaper text-xl md:text-2xl text-amber-300 group-hover:scale-110 transition-transform"></i>
                        <span class="font-semibold text-sm md:text-base">Warta Gereja</span>
                    </div>
                </a>
                <a href="pages/jadwal-ibadah.php" class="bg-amber-100 bg-opacity-20 backdrop-blur-sm border border-amber-200 border-opacity-30 rounded-xl p-3 md:p-4 hover:bg-amber-100 hover:bg-opacity-30 transition-all duration-300 group">
                    <div class="flex items-center justify-center space-x-2 md:space-x-3">
                        <i class="fas fa-calendar-alt text-xl md:text-2xl text-amber-300 group-hover:scale-110 transition-transform"></i>
                        <span class="font-semibold text-sm md:text-base">Jadwal Ibadah</span>
                    </div>
                </a>
                <a href="pages/renungan.php" class="bg-amber-100 bg-opacity-20 backdrop-blur-sm border border-amber-200 border-opacity-30 rounded-xl p-3 md:p-4 hover:bg-amber-100 hover:bg-opacity-30 transition-all duration-300 group sm:col-span-2 md:col-span-1">
                    <div class="flex items-center justify-center space-x-2 md:space-x-3">
                        <i class="fas fa-book-open text-xl md:text-2xl text-amber-300 group-hover:scale-110 transition-transform"></i>
                        <span class="font-semibold text-sm md:text-base">Renungan Harian</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-20 bg-gradient-to-br from-amber-50 to-amber-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 gap-12 items-start">
                <!-- Konten Sejarah: Satu Kolom -->
                <div data-aos="fade-up">
                    <div class="text-center mb-10">
                        <div class="mx-auto w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center shadow-sm mb-4">
                            <i class="fas fa-scroll text-amber-700 text-xl"></i>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-amber-900 tracking-tight">Sejarah Gereja</h2>
                        <p class="text-amber-700 mt-2 max-w-2xl mx-auto">Perjalanan dan perkembangan gereja dari masa ke masa</p>
                    </div>

                    <?php if ($sejarah): ?>
                    <h3 class="text-2xl font-semibold text-amber-900 mb-5 leading-snug"><?php echo htmlspecialchars($sejarah['judul']); ?></h3>

                    <?php 
                        $konten_full = $sejarah['konten'] ?? '';
                        $limit = 500; // karakter ringkas di beranda
                        $konten_singkat = mb_substr($konten_full, 0, $limit);
                        if (mb_strlen($konten_full) > $limit) {
                            $konten_singkat .= '...';
                        }
                    ?>
                    <!-- Kartu konten dengan border gradient dan ring -->
                    <div class="relative rounded-2xl p-[1px] bg-gradient-to-br from-amber-300 via-amber-200 to-amber-100 shadow-xl">
                        <div class="bg-white/95 rounded-2xl p-6 md:p-7">
                            <div class="prose max-w-none text-amber-900 leading-relaxed">
                                <?php echo $konten_singkat; ?>
                            </div>
                            <div class="mt-5 flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-2 text-xs font-medium px-3 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                                    <i class="fas fa-calendar-day"></i>
                                    Didirikan <?php echo htmlspecialchars($sejarah['tahun_didirikan'] ?? '—'); ?>
                                </span>
                                <?php if ($tahunMelayani !== null): ?>
                                <span class="inline-flex items-center gap-2 text-xs font-medium px-3 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                                    <i class="fas fa-hourglass-half"></i>
                                    <?php echo $tahunMelayani; ?>+ Tahun Melayani
                                </span>
                                <?php endif; ?>
                                <?php if (!empty($sejarah['updated_at'])): ?>
                                <span class="inline-flex items-center gap-2 text-xs font-medium px-3 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                                    <i class="fas fa-pen"></i>
                                    Diperbarui: <?php echo date('d M Y', strtotime($sejarah['updated_at'])); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="mt-6">
                                <a href="pages/sejarah.php" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-amber-600 text-white font-semibold shadow hover:bg-amber-700 active:bg-amber-800 transition-colors">
                                    <i class="fas fa-book-open"></i>
                                    Baca Sejarah Lengkap
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="bg-white bg-opacity-90 p-6 rounded-2xl border border-amber-200 shadow-sm">
                        <p class="text-amber-800">Data sejarah belum tersedia.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Struktur Organisasi Majelis Gereja (Sederhana) -->
    <section id="struktur-organisasi" class="py-20 bg-amber-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10" data-aos="fade-up">
                <div class="mx-auto w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center shadow-sm mb-4">
                    <i class="fas fa-sitemap text-amber-700 text-xl"></i>
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-amber-900 tracking-tight">Struktur Organisasi Majelis Gereja</h2>
                <p class="text-amber-700 mt-2 max-w-2xl mx-auto">Penataan jabatan dan pengurus komisi untuk melayani jemaat</p>
            </div>
            <?php
            // Ambil data sederhana: jabatan dan nama
            try {
                if (!isset($db)) { $db = new Database(); }
                $db->query("SELECT mj.level_hierarki, mj.urutan_tampil, mj.nama_jabatan, ma.nama_lengkap, ma.nama_panggilan
                            FROM majelis_struktur ms
                            JOIN majelis_jabatan mj ON ms.jabatan_id = mj.id
                            JOIN majelis_anggota ma ON ms.anggota_id = ma.id
                            WHERE ms.status = 'aktif'
                            ORDER BY mj.level_hierarki ASC, mj.urutan_tampil ASC");
                $struktur_sederhana = $db->resultSet();
            } catch (Exception $e) {
                $struktur_sederhana = [];
            }
            ?>
            <div class="bg-white rounded-lg shadow border border-amber-200 p-6" data-aos="fade-up" data-aos-delay="100">
                <?php if (!empty($struktur_sederhana)): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-amber-800 border-b">
                                <th class="py-2 pr-2 w-6 text-center"></th>
                                <th class="py-2 pr-4">Jabatan</th>
                                <th class="py-2">Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($struktur_sederhana as $row): ?>
                            <tr class="border-b last:border-none">
                                <td class="py-2 pr-2 align-middle text-center w-6">
                                    <?php
                                        $j = strtolower($row['nama_jabatan']);
                                        $icon = 'fa-user';
                                        if (strpos($j,'pendeta')!==false) { $icon = 'fa-cross'; }
                                        elseif (strpos($j,'ketua')!==false && strpos($j,'wakil')===false) { $icon = 'fa-crown'; }
                                        elseif (strpos($j,'wakil')!==false) { $icon = 'fa-user-tie'; }
                                        elseif (strpos($j,'sekretaris')!==false) { $icon = 'fa-file-alt'; }
                                        elseif (strpos($j,'bendahara')!==false) { $icon = 'fa-coins'; }
                                    ?>
                                    <span class="inline-flex items-center justify-center w-4"><i class="fas <?php echo $icon; ?> text-amber-700"></i></span>
                                </td>
                                <td class="py-2 pr-4 text-amber-900 font-medium"><?php echo htmlspecialchars($row['nama_jabatan']); ?></td>
                                <td class="py-2 text-amber-800"><?php echo htmlspecialchars($row['nama_lengkap']); ?><?php echo $row['nama_panggilan'] ? ' ('.htmlspecialchars($row['nama_panggilan']).')' : ''; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-amber-800">Data struktur organisasi belum tersedia.</p>
                <?php endif; ?>
            </div>

            <?php
            // Ambil data komisi secara sederhana
            try {
                if (!isset($db)) { $db = new Database(); }
                $db->query("SELECT mk.nama_komisi,
                                   ketua.nama_lengkap AS ketua_nama,
                                   wakil.nama_lengkap AS wakil_nama,
                                   mk.anggota_id
                             FROM majelis_komisi mk
                             LEFT JOIN majelis_anggota ketua ON mk.ketua_id = ketua.id
                             LEFT JOIN majelis_anggota wakil ON mk.wakil_ketua_id = wakil.id
                             WHERE mk.status_aktif = 'aktif'
                             ORDER BY mk.nama_komisi ASC");
                $komisi_sederhana = $db->resultSet();
            } catch (Exception $e) {
                $komisi_sederhana = [];
            }
            ?>

            <div class="bg-white rounded-lg shadow border border-amber-200 p-6 mt-8" data-aos="fade-up" data-aos-delay="150">
                <h3 class="text-xl font-semibold text-amber-900 mb-4 text-center"><i class="fas fa-handshake mr-2 text-amber-700"></i>Komisi Pelayanan</h3>
                <?php if (!empty($komisi_sederhana)): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-amber-800 border-b">
                                <th class="py-2 pr-2 w-6 text-center"></th>
                                <th class="py-2 pr-4">Komisi</th>
                                <th class="py-2 pr-4">Ketua</th>
                                <th class="py-2 pr-4">Wakil</th>
                                <th class="py-2">Anggota</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($komisi_sederhana as $k): ?>
                            <tr class="border-b last:border-none">
                                <td class="py-2 pr-2 align-middle text-center w-6">
                                    <span class="inline-flex items-center justify-center w-4"><i class="fas fa-handshake text-amber-700"></i></span>
                                </td>
                                <td class="py-2 pr-4 text-amber-900 font-medium"><?php echo htmlspecialchars($k['nama_komisi']); ?></td>
                                <td class="py-2 pr-4 text-amber-800"><?php echo htmlspecialchars($k['ketua_nama'] ?? '—'); ?></td>
                                <td class="py-2 pr-4 text-amber-800"><?php echo htmlspecialchars($k['wakil_nama'] ?? '—'); ?></td>
                                <td class="py-2 text-amber-800 align-top">
                                    <?php 
                                        $anggotaCsv = trim((string)($k['anggota_id'] ?? ''));
                                        if ($anggotaCsv === '') {
                                            echo '—';
                                        } else {
                                            // Pecah ID, ambil nama dari tabel majelis_anggota, dan tampilkan berurutan ke bawah
                                            $ids = array_filter(array_map('intval', explode(',', $anggotaCsv)));
                                            if (empty($ids)) { echo '—'; }
                                            else {
                                                // Ambil nama-nama berdasarkan ID
                                                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                                                $namaMap = [];
                                                try {
                                                    if (!isset($db)) { $db = new Database(); }
                                                    $dbTmp = new Database();
                                                    $dbTmp->query("SELECT id, nama_lengkap FROM majelis_anggota WHERE id IN ($placeholders)", $ids);
                                                    $rowsNm = $dbTmp->resultSet();
                                                    foreach ($rowsNm as $rNm) {
                                                        $rid = is_array($rNm) ? ($rNm['id'] ?? null) : ($rNm->id ?? null);
                                                        $rname = is_array($rNm) ? ($rNm['nama_lengkap'] ?? '') : ($rNm->nama_lengkap ?? '');
                                                        if ($rid !== null) { $namaMap[(int)$rid] = $rname; }
                                                    }
                                                } catch (Exception $e) {}

                                                echo '<ol class="list-decimal ml-5 space-y-1">';
                                                foreach ($ids as $rid) {
                                                    $nm = $namaMap[$rid] ?? ('ID ' . (int)$rid);
                                                    echo '<li>'.htmlspecialchars($nm).'</li>';
                                                }
                                                echo '</ol>';
                                            }
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-amber-800">Data komisi belum tersedia.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Pelayanan & Aktivitas Section -->
    <section id="aktivitas" class="py-20 bg-gradient-to-br from-amber-50 to-amber-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-amber-900 mb-4">Pelayanan & Aktivitas</h2>
                <p class="text-xl text-amber-800 max-w-3xl mx-auto">
                    Berbagai program pelayanan dan aktivitas kerohanian yang diselenggarakan gereja
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-amber-50 p-8 rounded-xl shadow-lg border-2 border-amber-200" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-hands-helping text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="text-xl font-bold text-amber-900 mb-4 text-center">Pelayanan Sosial</h3>
                    <p class="text-amber-800 text-center mb-6">
                        Program bantuan sosial, bakti sosial, dan pelayanan kepada masyarakat yang membutuhkan.
                    </p>
                    <div class="text-center">
                        <span class="inline-block bg-amber-200 text-amber-800 text-sm px-3 py-1 rounded-full">Aktif</span>
                    </div>
                </div>
                
                <div class="bg-amber-50 p-8 rounded-xl shadow-lg border-2 border-amber-200" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-music text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="text-xl font-bold text-amber-900 mb-4 text-center">Paduan Suara</h3>
                    <p class="text-amber-800 text-center mb-6">
                        Tim paduan suara yang melayani dalam ibadah dan acara-acara khusus gereja.
                    </p>
                    <div class="text-center">
                        <span class="inline-block bg-amber-200 text-amber-800 text-sm px-3 py-1 rounded-full">Aktif</span>
                    </div>
                </div>
                
                <div class="bg-amber-50 p-8 rounded-xl shadow-lg border-2 border-amber-200" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-child text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="text-xl font-bold text-amber-900 mb-4 text-center">Sekolah Minggu</h3>
                    <p class="text-amber-800 text-center mb-6">
                        Program pendidikan rohani untuk anak-anak dan remaja setiap hari Minggu.
                    </p>
                    <div class="text-center">
                        <span class="text-amber-800 text-sm">Setiap Minggu</span>
                    </div>
                </div>
            </div>
            
            <!-- Tombol Lihat Detail Aktivitas -->
            <div class="text-center mt-8" data-aos="fade-up" data-aos-delay="400">
                <a href="pages/kegiatan.php" class="inline-flex items-center px-6 py-3 bg-amber-700 text-white font-semibold rounded-full hover:bg-amber-800 transition-colors">
                    <i class="fas fa-activity mr-2"></i>
                    Lihat Semua Aktivitas
                </a>
            </div>
        </div>
    </section>





    <!-- Jadwal Ibadah Mingguan Section -->
    <section id="jadwal-ibadah" class="py-20 bg-gradient-to-br from-amber-50 to-amber-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-amber-900 mb-4">Jadwal Ibadah Mingguan</h2>
                <p class="text-xl text-amber-800 max-w-2xl mx-auto">
                    Informasi lengkap jadwal ibadah dan kegiatan gereja setiap minggu
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php if (empty($jadwal_ibadah)): ?>
                <!-- Fallback data statis jika database kosong -->
                <div class="bg-white bg-opacity-90 p-6 rounded-xl shadow-lg text-center border-2 border-amber-200" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-sun text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="font-bold text-amber-900 mb-2">Ibadah Minggu</h3>
                    <p class="text-amber-800 text-sm mb-3">Pukul 09:00 WIB</p>
                    <p class="text-amber-700 text-xs">Ibadah Umum</p>
                </div>
                
                <div class="bg-white bg-opacity-90 p-6 rounded-xl shadow-lg text-center border-2 border-amber-200" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-child text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="font-bold text-amber-900 mb-2">Sekolah Minggu</h3>
                    <p class="text-amber-800 text-sm mb-3">Pukul 10:30 WIB</p>
                    <p class="text-amber-700 text-xs">Anak & Remaja</p>
                </div>
                
                <div class="bg-white bg-opacity-90 p-6 rounded-xl shadow-lg text-center border-2 border-amber-200" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-pray text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="font-bold text-amber-900 mb-2">Doa Malam</h3>
                    <p class="text-amber-800 text-sm mb-3">Pukul 19:00 WIB</p>
                    <p class="text-amber-700 text-xs">Setiap Rabu</p>
                </div>
                
                <div class="bg-white bg-opacity-90 p-6 rounded-xl shadow-lg text-center border-2 border-amber-200" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="font-bold text-amber-900 mb-2">Persekutuan</h3>
                    <p class="text-amber-800 text-sm mb-3">Pukul 19:00 WIB</p>
                    <p class="text-amber-700 text-xs">Setiap Jumat</p>
                </div>
                <?php else: ?>
                <!-- Data dinamis dari database -->
                <?php 
                $delay = 100;
                foreach ($jadwal_ibadah as $jadwal): 
                    // Tentukan icon berdasarkan jenis ibadah
                    $icon = 'fas fa-church'; // default
                    switch(strtolower($jadwal['jenis_ibadah'])) {
                        case 'ibadah_minggu':
                            $icon = 'fas fa-sun';
                            break;
                        case 'ibadah_anak':
                        case 'sekolah_minggu':
                            $icon = 'fas fa-child';
                            break;
                        case 'ibadah_doa':
                            $icon = 'fas fa-pray';
                            break;
                        case 'ibadah_pemuda':
                            $icon = 'fas fa-users';
                            break;
                        default:
                            $icon = 'fas fa-church';
                    }
                    
                    // Format waktu - hanya waktu_mulai
                    $waktu_mulai = date('H:i', strtotime($jadwal['waktu_mulai']));
                    $waktu_display = $waktu_mulai . ' WIB';
                ?>
                <div class="bg-white bg-opacity-90 p-6 rounded-xl shadow-lg text-center border-2 border-amber-200" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="<?php echo $icon; ?> text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="font-bold text-amber-900 mb-2"><?php echo htmlspecialchars($jadwal['judul']); ?></h3>
                    <p class="text-amber-800 text-sm mb-3">Pukul <?php echo $waktu_display; ?></p>
                    <p class="text-amber-700 text-xs"><?php echo htmlspecialchars($jadwal['deskripsi'] ?: ucfirst(str_replace('_', ' ', $jadwal['jenis_ibadah']))); ?></p>
                </div>
                <?php 
                    $delay += 100;
                endforeach; 
                ?>
                <?php endif; ?>
            </div>
            
            <div class="text-center mt-8" data-aos="fade-up" data-aos-delay="500">
                <a href="pages/jadwal-ibadah.php" class="inline-flex items-center px-6 py-3 bg-amber-700 text-white font-semibold rounded-full hover:bg-amber-800 transition-colors">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Lihat Jadwal Lengkap
                </a>
            </div>
        </div>
    </section>

    <!-- Galeri Section -->
    <section id="galeri" class="py-20 bg-gradient-to-br from-amber-100 to-amber-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-amber-900 mb-4">Galeri Video YouTube</h2>
                <p class="text-xl text-amber-800 max-w-3xl mx-auto">
                    Video terbaru dari channel YouTube gereja kami
                </p>
            </div>
            
            <?php
            // Ambil 4 video YouTube terbaru
            try {
                require_once 'includes/youtube_config.php';
                
                // Ambil konfigurasi YouTube
                $youtube_config = getYouTubeConfig();
                
                if ($youtube_config && !empty($youtube_config['api_key'])) {
                    // Cek apakah ada channels yang aktif
                    if (!empty($youtube_config['channels'])) {
                        // Ambil video dari channel pertama yang aktif
                        $channel = $youtube_config['channels'][0];
                        $videos = getYouTubeVideos($channel['id'], 4); // Ambil 4 video saja
                        
                        // Debug: Tampilkan jumlah video yang diambil
                        echo '<!-- Debug: Jumlah video yang diambil: ' . count($videos) . ' -->';
                        
                        if (!empty($videos)) {
                            echo '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">';
                            
                            foreach ($videos as $index => $video) {
                                $delay = ($index + 1) * 100;
                                ?>
                                <div class="video-card bg-white rounded-lg shadow-md border border-amber-200 overflow-hidden hover:shadow-lg hover:border-amber-400 transition-all duration-300" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                                    <!-- Thumbnail Video -->
                                    <div class="relative overflow-hidden">
                                        <img src="<?php echo htmlspecialchars($video['thumbnail']); ?>" 
                                             alt="<?php echo htmlspecialchars($video['title']); ?>" 
                                             class="w-full h-32 object-cover hover:scale-105 transition-transform duration-300">
                                        
                                        <!-- Play Button Overlay -->
                                        <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                            <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-red-700 transition-colors duration-300 opacity-0 hover:opacity-100" 
                                                 onclick="openVideoModal('<?php echo htmlspecialchars($video['video_id']); ?>', '<?php echo htmlspecialchars(addslashes($video['title'])); ?>')">
                                                <i class="fas fa-play text-white text-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Info Video -->
                                    <div class="p-4">
                                        <h3 class="font-semibold text-amber-900 mb-2 text-sm line-clamp-2" title="<?php echo htmlspecialchars($video['title']); ?>">
                                            <?php echo htmlspecialchars($video['title']); ?>
                                        </h3>
                                        
                                        <!-- Stats -->
                                        <div class="space-y-1">
                                            <div class="flex items-center text-amber-700 text-xs">
                                                <i class="fas fa-calendar-alt mr-2 w-3 text-center"></i>
                                                <span><?php echo date('d M Y', strtotime($video['published_at'])); ?></span>
                                            </div>
                                            <div class="flex items-center text-amber-600 text-xs">
                                                <i class="fas fa-eye mr-2 w-3 text-center"></i>
                                                <span><?php echo $video['view_count'] > 0 ? number_format($video['view_count']) . ' views' : 'Views tidak tersedia'; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            
                            echo '</div>';
                            
                            // Tombol Lihat Semua Video
                            echo '<div class="text-center mt-8" data-aos="fade-up" data-aos-delay="500">';
                            echo '<a href="pages/galeri.php" class="inline-flex items-center px-6 py-3 bg-amber-700 text-white font-semibold rounded-full hover:bg-amber-800 transition-colors">';
                            echo '<i class="fas fa-play-circle mr-2"></i>';
                            echo 'Lihat Semua Video';
                            echo '</a>';
                            echo '</div>';
                            
                        } else {
                            echo '<div class="text-center py-12">';
                            echo '<div class="w-24 h-24 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">';
                            echo '<i class="fas fa-video text-4xl text-amber-600"></i>';
                            echo '</div>';
                            echo '<h3 class="text-2xl font-semibold text-amber-800 mb-2">Belum Ada Video</h3>';
                            echo '<p class="text-amber-700">Video YouTube akan muncul di sini setelah dikonfigurasi</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="text-center py-12">';
                        echo '<div class="w-24 h-24 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">';
                        echo '<i class="fas fa-users text-4xl text-amber-600"></i>';
                        echo '</div>';
                        echo '<h3 class="text-2xl font-semibold text-amber-800 mb-2">Belum Ada Channel</h3>';
                        echo '<p class="text-amber-700">Silakan tambahkan channel YouTube di admin panel</p>';
                        echo '<a href="admin/system_config_manager.php" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg mt-4 hover:bg-amber-700 transition-colors">';
                        echo '<i class="fas fa-users mr-2"></i>';
                        echo 'Tambah Channel YouTube';
                        echo '</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="text-center py-12">';
                    echo '<div class="w-24 h-24 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">';
                    echo '<i class="fas fa-cog text-4xl text-amber-600"></i>';
                    echo '</div>';
                    echo '<h3 class="text-2xl font-semibold text-amber-800 mb-2">Konfigurasi YouTube</h3>';
                    echo '<p class="text-amber-700">Silakan konfigurasi YouTube API Key di admin panel</p>';
                    echo '<a href="admin/system_config_manager.php" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg mt-4 hover:bg-amber-700 transition-colors">';
                    echo '<i class="fas fa-cog mr-2"></i>';
                    echo 'Konfigurasi YouTube';
                    echo '</a>';
                    echo '</div>';
                }
            } catch (Exception $e) {
                echo '<div class="text-center py-12">';
                echo '<div class="w-24 h-24 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">';
                echo '<i class="fas fa-exclamation-triangle text-4xl text-amber-600"></i>';
                echo '</div>';
                echo '<h3 class="text-2xl font-semibold text-amber-800 mb-2">Error</h3>';
                echo '<p class="text-amber-700">Gagal memuat video YouTube: ' . htmlspecialchars($e->getMessage()) . '</p>';
                echo '</div>';
            }
            ?>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="py-20 bg-gradient-to-br from-amber-50 to-amber-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-amber-900 mb-4">Hubungi Kami</h2>
                <p class="text-xl text-amber-800 max-w-2xl mx-auto">
                    Ada pertanyaan atau butuh bantuan? Jangan ragu untuk menghubungi tim kami.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="text-xl font-bold text-amber-900 mb-2">Email</h3>
                    <p class="text-amber-800"><?php echo getKontakGereja('email_gereja'); ?></p>
                </div>
                
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-phone text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="text-xl font-bold text-amber-900 mb-2">Telepon</h3>
                    <p class="text-amber-800"><?php echo getKontakGereja('telepon_gereja'); ?></p>
                </div>
                
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-map-marker-alt text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="text-xl font-bold text-amber-900 mb-2">Alamat</h3>
                    <p class="text-amber-800"><?php echo getKontakGereja('alamat_gereja'); ?></p>
                </div>
                
                <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-2xl text-amber-700"></i>
                    </div>
                    <h3 class="text-xl font-bold text-amber-900 mb-2">Jam Pelayanan</h3>
                    <p class="text-amber-800 text-sm"><?php echo getKontakGereja('jam_pelayanan'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-amber-900 text-amber-50 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="<?php echo getLogoPath(); ?>" alt="Logo Gereja" class="navbar-logo object-contain">
                        <span class="text-xl font-bold"><?php echo getNamaGereja(); ?></span>
                    </div>
                                         <p class="text-amber-200 text-sm">
                         <?php echo getAlamatGereja(); ?>
                     </p>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Fitur Utama</h4>
                    <ul class="space-y-2 text-sm text-amber-200">
                        <li><a href="#tentang" class="hover:text-amber-100 transition-colors">Tentang</a></li>
                        <li><a href="#aktivitas" class="hover:text-amber-100 transition-colors">Aktivitas</a></li>
                        <li><a href="#jadwal-ibadah" class="hover:text-amber-100 transition-colors">Jadwal Ibadah</a></li>
                        <li><a href="#galeri" class="hover:text-amber-100 transition-colors">Galeri</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Dukungan</h4>
                    <ul class="space-y-2 text-sm text-amber-200">
                        <li><a href="#kontak" class="hover:text-amber-100 transition-colors">Kontak</a></li>
                        <li><a href="https://appsbee.my.id" target="_blank" class="hover:text-amber-100 transition-colors">appsBee</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Ikuti Kami</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-amber-800 rounded-full flex items-center justify-center hover:bg-amber-600 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-amber-800 rounded-full flex items-center justify-center hover:bg-amber-600 transition-colors">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-amber-800 rounded-full flex items-center justify-center hover:bg-amber-600 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-amber-800 rounded-full flex items-center justify-center hover:bg-amber-600 transition-colors">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-amber-700 my-8"></div>
            
            <div class="text-center text-amber-300 text-sm">
                <p>&copy; 2025 Gereja Kristen Jawa Randuares. Semua hak dilindungi. | <a href="https://appsbee.my.id" target="_blank" class="text-amber-200 hover:text-amber-100 transition-colors">appsBee</a></p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
    
    <!-- SweetAlert untuk Link yang Belum Berfungsi -->
    <script>
        // Fungsi untuk menampilkan SweetAlert "Fitur Belum Tersedia"
        function showFeatureNotAvailable(message = 'Fitur ini sedang dalam pengembangan') {
            Swal.fire({
                title: 'Fitur Belum Tersedia',
                text: message,
                icon: 'info',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#f59e0b',
                showCancelButton: false
            });
        }
        
        // Fungsi untuk menampilkan SweetAlert "Halaman Belum Tersedia"
        function showPageNotAvailable(pageName) {
            Swal.fire({
                title: 'Halaman Belum Tersedia',
                text: `Halaman ${pageName} sedang dalam pengembangan. Silakan cek kembali nanti.`,
                icon: 'info',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#f59e0b',
                showCancelButton: false
            });
        }
        
        // Event listener untuk link yang sudah berfungsi
        document.addEventListener('DOMContentLoaded', function() {
            // Link di Footer - Social Media (masih belum tersedia)
            const socialMediaLinks = document.querySelectorAll('footer a[href="#"]');
            socialMediaLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    showFeatureNotAvailable('Media sosial belum tersedia saat ini');
                });
            });
            
            // Link Profile dan Settings (jika belum berfungsi)
            const profileLinks = document.querySelectorAll('a[href="admin/profile.php"]');
            profileLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    showPageNotAvailable('Profile');
                });
            });
            
            const settingsLinks = document.querySelectorAll('a[href="admin/settings.php"]');
            settingsLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    showPageNotAvailable('Pengaturan');
                });
            });
        });
    </script>
    
    <!-- Floating Social FAB -->
    <style>
      .fab-shadow{box-shadow:0 10px 25px rgba(245,158,11,.35)}
      .fab-item{transform:translateY(10px);opacity:0;transition:transform .25s ease,opacity .25s ease,box-shadow .25s ease}
      .fab-item.show{transform:translateY(0);opacity:1}
      .fab-item:hover{transform:translateX(-6px) scale(1.07); box-shadow:0 14px 30px rgba(245,158,11,.45)}
      .fab-tooltip{transform:translateX(6px);opacity:0;transition:all .2s ease}
      .fab-link:hover .fab-tooltip{transform:translateX(0);opacity:1}
      /* Hilangkan border/outline saat klik/focus */
      .fab-link, .fab-item{ -webkit-tap-highlight-color: transparent; }
      .fab-link:focus, .fab-link:active, .fab-item:focus, .fab-item:active{ outline:none !important; box-shadow:none; }
      
      /* Simple CSS untuk Video Cards */
      .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }
      
      /* Simple hover effects */
      .video-card {
        transition: all 0.3s ease;
      }
      
      .video-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      }
      
      .play-button {
        transition: all 0.3s ease;
      }
      
      .play-button:hover {
        transform: scale(1.1);
      }
      
      /* Responsive adjustments */
      @media (max-width: 768px) {
        .video-card:hover {
          transform: translateY(-4px) scale(1.01);
        }
        
        .video-card .action-buttons {
          opacity: 1;
          transform: translateY(0);
        }
      }
      
      /* Focus states for accessibility */
      .video-card:focus-within {
        outline: 2px solid rgba(245, 158, 11, 0.5);
        outline-offset: 2px;
      }
      
      /* Loading state animation */
      .video-card.loading {
        animation: cardShimmer 1.5s infinite;
      }
      
      @keyframes cardShimmer {
        0% {
          background-position: -200px 0;
        }
        100% {
          background-position: calc(200px + 100%) 0;
        }
      }
    </style>
    <div id="fabSocial" class="fixed right-6 top-1/2 -translate-y-1/2 transform z-50">
      <div id="fabMenu" class="flex flex-col items-end space-y-3 pointer-events-auto">
        <a href="#" target="_blank" class="fab-link flex items-center">
          <span class="fab-tooltip mr-2 px-2 py-1 rounded text-xs bg-amber-600 text-white shadow">Facebook</span>
          <span class="fab-item w-12 h-12 rounded-full bg-white border border-amber-300 text-amber-700 flex items-center justify-center fab-shadow show">
            <i class="fab fa-facebook-f"></i>
          </span>
        </a>
        <a href="#" target="_blank" class="fab-link flex items-center">
          <span class="fab-tooltip mr-2 px-2 py-1 rounded text-xs bg-amber-600 text-white shadow">Whatsapp</span>
          <span class="fab-item w-12 h-12 rounded-full bg-white border border-amber-300 text-amber-700 flex items-center justify-center fab-shadow show">
            <i class="fab fa-whatsapp"></i>
          </span>
        </a>
        <a href="#" target="_blank" class="fab-link flex items-center">
          <span class="fab-tooltip mr-2 px-2 py-1 rounded text-xs bg-amber-600 text-white shadow">Instagram</span>
          <span class="fab-item w-12 h-12 rounded-full bg-white border border-amber-300 text-amber-700 flex items-center justify-center fab-shadow show">
            <i class="fab fa-instagram"></i>
          </span>
        </a>
        <a href="#" target="_blank" class="fab-link flex items-center">
          <span class="fab-tooltip mr-2 px-2 py-1 rounded text-xs bg-amber-600 text-white shadow">YouTube</span>
          <span class="fab-item w-12 h-12 rounded-full bg-white border border-amber-300 text-amber-700 flex items-center justify-center fab-shadow show">
            <i class="fab fa-youtube"></i>
          </span>
        </a>
      </div>
    </div>

    <!-- Video Modal -->
    <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <h3 id="videoModalTitle" class="text-lg font-semibold text-gray-900"></h3>
                    <button onclick="closeVideoModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Video Player -->
                <div class="relative">
                    <div id="videoPlayer" class="w-full aspect-video bg-gray-900"></div>
                </div>
                
                <!-- Modal Footer -->
                <div class="p-4 border-t border-gray-200 text-center">
                    <button onclick="closeVideoModal()" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
      (function(){
        const menu=document.getElementById('fabMenu');
        const items=[...menu.querySelectorAll('.fab-item')];
        // Play simple staggered reveal on load
        items.forEach((el,idx)=>{
          el.classList.remove('show');
          setTimeout(()=>el.classList.add('show'), idx*80 + 150);
        });
      })();
      
      // Video Modal Functions
      function openVideoModal(videoId, title) {
          const modal = document.getElementById('videoModal');
          const modalTitle = document.getElementById('videoModalTitle');
          const videoPlayer = document.getElementById('videoPlayer');
          
          // Set title
          modalTitle.textContent = title;
          
          // Create YouTube iframe
          videoPlayer.innerHTML = `
              <iframe 
                  src="https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0" 
                  frameborder="0" 
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                  allowfullscreen
                  class="w-full h-full">
              </iframe>
          `;
          
          // Show modal
          modal.classList.remove('hidden');
          document.body.style.overflow = 'hidden';
      }
      
      function closeVideoModal() {
          const modal = document.getElementById('videoModal');
          const videoPlayer = document.getElementById('videoPlayer');
          
          // Clear video player
          videoPlayer.innerHTML = '';
          
          // Hide modal
          modal.classList.add('hidden');
          document.body.style.overflow = 'auto';
      }
      
      // Close modal when clicking outside
      document.getElementById('videoModal').addEventListener('click', function(e) {
          if (e.target === this) {
              closeVideoModal();
          }
      });
      
             // Close modal with Escape key
       document.addEventListener('keydown', function(e) {
           if (e.key === 'Escape') {
               closeVideoModal();
           }
       });
       
       // Video Card Interactive Effects
       document.addEventListener('DOMContentLoaded', function() {
           const videoCards = document.querySelectorAll('.video-card');
           
           videoCards.forEach(card => {
               // Simple hover effect
               card.addEventListener('mouseenter', function() {
                   this.style.transform = 'translateY(-4px)';
               });
               
               card.addEventListener('mouseleave', function() {
                   this.style.transform = 'translateY(0)';
               });
               
               // Simple click effect
               card.addEventListener('click', function(e) {
                   if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
                       return;
                   }
                   
                   this.style.transform = 'scale(0.98)';
                   setTimeout(() => {
                       this.style.transform = 'translateY(-4px)';
                   }, 150);
               });
           });
       });
     </script>
</body>
</html>

