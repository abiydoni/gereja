<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

$db = new Database();

// Ambil data struktur organisasi
$db->query("SELECT ms.id, mj.nama_jabatan, mj.level_hierarki, mj.urutan_tampil, 
            ma.nama_lengkap, ma.nama_panggilan, ma.foto, ms.periode_mulai, ms.status
            FROM majelis_struktur ms 
            JOIN majelis_jabatan mj ON ms.jabatan_id = mj.id 
            JOIN majelis_anggota ma ON ms.anggota_id = ma.id 
            WHERE ms.status = 'aktif'
            ORDER BY mj.level_hierarki ASC, mj.urutan_tampil ASC");
$struktur_list = $db->resultSet();

// Ambil data komisi
$db->query("SELECT mk.id, mk.nama_komisi, mk.deskripsi,
            ketua.nama_lengkap as ketua_nama, ketua.nama_panggilan as ketua_panggilan,
            wakil.nama_lengkap as wakil_nama, wakil.nama_panggilan as wakil_panggilan,
            sekretaris.nama_lengkap as sekretaris_nama, sekretaris.nama_panggilan as sekretaris_panggilan,
            bendahara.nama_lengkap as bendahara_nama, bendahara.nama_panggilan as bendahara_panggilan
            FROM majelis_komisi mk
            LEFT JOIN majelis_anggota ketua ON mk.ketua_id = ketua.id
            LEFT JOIN majelis_anggota wakil ON mk.wakil_ketua_id = wakil.id
            LEFT JOIN majelis_anggota sekretaris ON mk.sekretaris_id = sekretaris.id
            LEFT JOIN majelis_anggota bendahara ON mk.bendahara_id = bendahara.id
            WHERE mk.status_aktif = 'aktif'
            ORDER BY mk.nama_komisi");
$komisi_list = $db->resultSet();

// Ambil data periode aktif
$db->query("SELECT * FROM majelis_periode WHERE status = 'aktif' ORDER BY tahun_mulai DESC LIMIT 1");
$periode_aktif = $db->single();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struktur Organisasi Majelis Gereja - <?php echo getNamaGereja(); ?></title>
    <meta name="description" content="Struktur organisasi dan kepengurusan Majelis Gereja <?php echo getNamaGereja(); ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        .org-chart {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .org-level {
            display: flex;
            justify-content: center;
            margin: 20px 0;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .org-item {
            background: white;
            border: 2px solid #f59e0b;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            min-width: 200px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .org-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
            border-color: #d97706;
        }
        
        .org-item.pendeta {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-color: #f59e0b;
        }
        
        .org-item.ketua {
            background: linear-gradient(135deg, #dbeafe, #93c5fd);
            border-color: #3b82f6;
        }
        
        .org-item.wakil {
            background: linear-gradient(135deg, #e0e7ff, #a5b4fc);
            border-color: #6366f1;
        }
        
        .org-item.sekretaris {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-color: #f59e0b;
        }
        
        .org-item.bendahara {
            background: linear-gradient(135deg, #dcfce7, #86efac);
            border-color: #22c55e;
        }
        
        .org-item.anggota {
            background: linear-gradient(135deg, #f3e8ff, #c4b5fd);
            border-color: #8b5cf6;
        }
        
        .org-item::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 20px;
            background: #f59e0b;
        }
        
        .org-item:first-child::before {
            display: none;
        }
        
        .org-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px;
            border: 3px solid white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .org-name {
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .org-position {
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 5px;
            font-size: 12px;
        }
        
        .org-nickname {
            color: #9ca3af;
            font-size: 11px;
        }
        
        .komisi-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-left: 4px solid #f59e0b;
        }
        
        .komisi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .komisi-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .komisi-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 20px;
        }
        
        .komisi-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }
        
        .komisi-description {
            color: #6b7280;
            margin-bottom: 20px;
            font-style: italic;
        }
        
        .komisi-structure {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .position-item {
            background: #f9fafb;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }
        
        .position-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .position-name {
            color: #1f2937;
            font-weight: 500;
            font-size: 13px;
        }
        
        .position-nickname {
            color: #6b7280;
            font-size: 11px;
            font-style: italic;
        }
        
        .empty-position {
            color: #9ca3af;
            font-style: italic;
            font-size: 12px;
        }
        
        @media (max-width: 768px) {
            .org-level {
                flex-direction: column;
                align-items: center;
            }
            
            .org-item {
                min-width: 180px;
            }
            
            .komisi-structure {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-gradient-to-r from-amber-600 to-amber-700 text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="<?php echo getLogoPath(); ?>" alt="Logo Gereja" class="w-12 h-12 rounded-full">
                    <div>
                        <h1 class="text-2xl font-bold"><?php echo getNamaGereja(); ?></h1>
                        <p class="text-amber-100">Struktur Organisasi Majelis Gereja</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-amber-100">Periode</p>
                    <p class="font-semibold"><?php echo $periode_aktif ? $periode_aktif['nama_periode'] : 'Tidak ada periode aktif'; ?></p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Struktur Organisasi -->
        <section class="mb-12" data-aos="fade-up">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-sitemap text-amber-600 mr-3"></i>
                    Struktur Organisasi Majelis Gereja
                </h2>
                <p class="text-gray-600 max-w-3xl mx-auto">
                    Struktur kepengurusan Majelis Jemaat yang bertugas melayani dan mengelola 
                    gereja sesuai dengan periode kepengurusan yang sedang berjalan.
                </p>
            </div>

            <!-- Organization Chart -->
            <div class="org-chart">
                <?php
                $current_level = 0;
                $level_items = [];
                
                // Group items by level
                foreach ($struktur_list as $item) {
                    $level = $item['level_hierarki'];
                    if (!isset($level_items[$level])) {
                        $level_items[$level] = [];
                    }
                    $level_items[$level][] = $item;
                }
                
                // Display each level
                foreach ($level_items as $level => $items) {
                    echo '<div class="org-level">';
                    foreach ($items as $item) {
                        $css_class = '';
                        if (strpos(strtolower($item['nama_jabatan']), 'pendeta') !== false) {
                            $css_class = 'pendeta';
                        } elseif (strpos(strtolower($item['nama_jabatan']), 'ketua') !== false) {
                            $css_class = 'ketua';
                        } elseif (strpos(strtolower($item['nama_jabatan']), 'wakil') !== false) {
                            $css_class = 'wakil';
                        } elseif (strpos(strtolower($item['nama_jabatan']), 'sekretaris') !== false) {
                            $css_class = 'sekretaris';
                        } elseif (strpos(strtolower($item['nama_jabatan']), 'bendahara') !== false) {
                            $css_class = 'bendahara';
                        } else {
                            $css_class = 'anggota';
                        }
                        
                        echo '<div class="org-item ' . $css_class . '">';
                        if ($item['foto'] && file_exists('../' . $item['foto'])) {
                            echo '<img src="../' . $item['foto'] . '" alt="' . $item['nama_lengkap'] . '" class="org-photo">';
                        } else {
                            echo '<div class="org-photo bg-gray-300 flex items-center justify-center text-gray-600">';
                            echo '<i class="fas fa-user text-2xl"></i>';
                            echo '</div>';
                        }
                        echo '<div class="org-name">' . htmlspecialchars($item['nama_lengkap']) . '</div>';
                        echo '<div class="org-position">' . htmlspecialchars($item['nama_jabatan']) . '</div>';
                        echo '<div class="org-nickname">' . htmlspecialchars($item['nama_panggilan']) . '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </section>

        <!-- Komisi Pelayanan -->
        <section class="mb-12" data-aos="fade-up" data-aos-delay="200">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-users text-amber-600 mr-3"></i>
                    Komisi Pelayanan
                </h2>
                <p class="text-gray-600 max-w-3xl mx-auto">
                    Komisi-komisi yang bertugas melayani berbagai aspek pelayanan gereja 
                    dengan struktur kepengurusan yang terorganisir.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <?php foreach ($komisi_list as $komisi): ?>
                <div class="komisi-card" data-aos="fade-up" data-aos-delay="<?php echo $loop * 100; ?>">
                    <div class="komisi-header">
                        <div class="komisi-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3 class="komisi-title"><?php echo htmlspecialchars($komisi['nama_komisi']); ?></h3>
                    </div>
                    
                    <?php if ($komisi['deskripsi']): ?>
                    <p class="komisi-description"><?php echo htmlspecialchars($komisi['deskripsi']); ?></p>
                    <?php endif; ?>
                    
                    <div class="komisi-structure">
                        <div class="position-item">
                            <div class="position-title">Ketua</div>
                            <?php if ($komisi['ketua_nama']): ?>
                            <div class="position-name"><?php echo htmlspecialchars($komisi['ketua_nama']); ?></div>
                            <div class="position-nickname"><?php echo htmlspecialchars($komisi['ketua_panggilan']); ?></div>
                            <?php else: ?>
                            <div class="empty-position">Belum diisi</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="position-item">
                            <div class="position-title">Wakil Ketua</div>
                            <?php if ($komisi['wakil_nama']): ?>
                            <div class="position-name"><?php echo htmlspecialchars($komisi['wakil_nama']); ?></div>
                            <div class="position-nickname"><?php echo htmlspecialchars($komisi['wakil_panggilan']); ?></div>
                            <?php else: ?>
                            <div class="empty-position">Belum diisi</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="position-item">
                            <div class="position-title">Sekretaris</div>
                            <?php if ($komisi['sekretaris_nama']): ?>
                            <div class="position-name"><?php echo htmlspecialchars($komisi['sekretaris_nama']); ?></div>
                            <div class="position-nickname"><?php echo htmlspecialchars($komisi['sekretaris_panggilan']); ?></div>
                            <?php else: ?>
                            <div class="empty-position">Belum diisi</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="position-item">
                            <div class="position-title">Bendahara</div>
                            <?php if ($komisi['bendahara_nama']): ?>
                            <div class="position-name"><?php echo htmlspecialchars($komisi['bendahara_nama']); ?></div>
                            <div class="position-nickname"><?php echo htmlspecialchars($komisi['bendahara_panggilan']); ?></div>
                            <?php else: ?>
                            <div class="empty-position">Belum diisi</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Informasi Tambahan -->
        <section class="bg-white rounded-lg shadow-md p-8" data-aos="fade-up" data-aos-delay="400">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-info-circle text-amber-600 mr-3"></i>
                    Informasi Struktur Organisasi
                </h3>
                <p class="text-gray-600 mb-6">
                    Struktur organisasi ini akan diperbarui sesuai dengan periode kepengurusan yang sedang berjalan. 
                    Untuk informasi terbaru, silakan menghubungi Sekretaris Majelis Jemaat.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-alt text-amber-600 text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Periode Aktif</h4>
                        <p class="text-gray-600"><?php echo $periode_aktif ? $periode_aktif['nama_periode'] : 'Tidak ada periode aktif'; ?></p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Total Anggota</h4>
                        <p class="text-gray-600"><?php echo count($struktur_list); ?> orang</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-handshake text-green-600 text-xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Total Komisi</h4>
                        <p class="text-gray-600"><?php echo count($komisi_list); ?> komisi</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; <?php echo date('Y'); ?> <?php echo getNamaGereja(); ?>. Semua hak dilindungi.</p>
            <p class="text-gray-400 mt-2">Struktur Organisasi Majelis Gereja</p>
        </div>
    </footer>

    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>
</body>
</html>
