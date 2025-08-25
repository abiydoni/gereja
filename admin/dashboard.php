<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Cek login admin
if (!isAdminLoggedIn()) {
    redirect('login.php');
}

// Ambil data statistik
try {
    $db = new Database();
    
    // Total jemaat
    $db->query("SELECT COUNT(*) as total FROM jemaat WHERE status_jemaat = 'aktif'");
    $total_jemaat = $db->single()->total;
    
    // Total jadwal ibadah bulan ini
    $db->query("SELECT COUNT(*) as total FROM jadwal_ibadah WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())");
    $total_jadwal = $db->single()->total;
    
    // Total keuangan bulan ini
    $db->query("SELECT COALESCE(SUM(CASE WHEN jenis = 'pemasukan' THEN jumlah ELSE 0 END), 0) as pemasukan, 
                       COALESCE(SUM(CASE WHEN jenis = 'pengeluaran' THEN jumlah ELSE 0 END), 0) as pengeluaran 
                FROM keuangan 
                WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())");
    $keuangan = $db->single();
    
    // Total warta
    $db->query("SELECT COUNT(*) as total FROM warta WHERE status = 'published'");
    $total_warta = $db->single()->total;
    
} catch (Exception $e) {
    $error = 'Gagal mengambil data statistik';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Gereja</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-church text-3xl text-purple-600"></i>
                    <span class="text-xl font-bold text-gray-800">Sistem Gereja</span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">
                        <i class="fas fa-user mr-2"></i>
                        <?php echo htmlspecialchars($_SESSION['admin_nama']); ?>
                    </span>
                    <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg min-h-screen">
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Menu Admin</h3>
                <nav class="space-y-2">
                    <a href="dashboard.php" class="flex items-center px-4 py-2 text-purple-600 bg-purple-50 rounded-lg">
                        <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                    </a>
                    <a href="jemaat/" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-users mr-3"></i>Data Jemaat
                    </a>
                    <a href="jadwal/" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-calendar mr-3"></i>Jadwal Ibadah
                    </a>
                    <a href="keuangan/" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-coins mr-3"></i>Keuangan
                    </a>
                    <a href="warta/" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-newspaper mr-3"></i>Warta
                    </a>
                    <a href="galeri/" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-images mr-3"></i>Galeri
                    </a>
                    <a href="renungan/" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-pray mr-3"></i>Renungan
                    </a>
                    <a href="kegiatan/" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-calendar-check mr-3"></i>Kegiatan
                    </a>
                    <a href="pengaturan/" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-cog mr-3"></i>Pengaturan
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard</h1>
                <p class="text-gray-600">Selamat datang di Panel Admin Sistem Gereja</p>
            </div>

            <!-- Flash Message -->
            <?php 
            $flash = getFlashMessage();
            if ($flash): 
            ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <i class="fas fa-check-circle mr-2"></i><?php echo $flash['message']; ?>
            </div>
            <?php endif; ?>

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Jemaat -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-2xl text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Jemaat</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format($total_jemaat); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Total Jadwal -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Jadwal Bulan Ini</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo number_format($total_jadwal); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Pemasukan -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-arrow-up text-2xl text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pemasukan Bulan Ini</p>
                            <p class="text-2xl font-bold text-gray-900">Rp <?php echo number_format($keuangan->pemasukan); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Pengeluaran -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-arrow-down text-2xl text-red-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pengeluaran Bulan Ini</p>
                            <p class="text-2xl font-bold text-gray-900">Rp <?php echo number_format($keuangan->pengeluaran); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik dan Tabel -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Grafik Keuangan -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Keuangan Bulan Ini</h3>
                    <canvas id="keuanganChart" width="400" height="200"></canvas>
                </div>

                <!-- Jadwal Terdekat -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Jadwal Ibadah Terdekat</h3>
                    <div class="space-y-3">
                        <?php
                        try {
                            $db->query("SELECT * FROM jadwal_ibadah WHERE tanggal >= CURRENT_DATE() ORDER BY tanggal ASC LIMIT 5");
                            $jadwal_terdekat = $db->resultSet();
                            
                            if ($jadwal_terdekat):
                                foreach ($jadwal_terdekat as $jadwal):
                        ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800"><?php echo htmlspecialchars($jadwal->judul); ?></p>
                                <p class="text-sm text-gray-600">
                                    <?php echo formatTanggalIndonesia($jadwal->tanggal); ?> • <?php echo $jadwal->waktu_mulai; ?>
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                <?php echo ucfirst(str_replace('_', ' ', $jadwal->jenis_ibadah)); ?>
                            </span>
                        </div>
                        <?php 
                                endforeach;
                            else:
                        ?>
                        <p class="text-gray-500 text-center py-4">Tidak ada jadwal ibadah terdekat</p>
                        <?php 
                            endif;
                        } catch (Exception $e) {
                            echo '<p class="text-red-500">Gagal memuat jadwal</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="jemaat/tambah.php" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <i class="fas fa-user-plus text-2xl text-blue-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-blue-800">Tambah Jemaat</p>
                            <p class="text-sm text-blue-600">Input data jemaat baru</p>
                        </div>
                    </a>
                    
                    <a href="jadwal/tambah.php" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <i class="fas fa-calendar-plus text-2xl text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-green-800">Tambah Jadwal</p>
                            <p class="text-sm text-green-600">Buat jadwal ibadah baru</p>
                        </div>
                    </a>
                    
                    <a href="warta/tambah.php" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <i class="fas fa-edit text-2xl text-purple-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-purple-800">Tulis Warta</p>
                            <p class="text-sm text-purple-600">Buat warta gereja baru</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Grafik Keuangan
        const ctx = document.getElementById('keuanganChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pemasukan', 'Pengeluaran'],
                datasets: [{
                    data: [<?php echo $keuangan->pemasukan; ?>, <?php echo $keuangan->pengeluaran; ?>],
                    backgroundColor: ['#10B981', '#EF4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // SweetAlert untuk notifikasi
        <?php if (isset($_GET['success'])): ?>
        Swal.fire({
            title: 'Berhasil!',
            text: '<?php echo htmlspecialchars($_GET['success']); ?>',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#8b5cf6'
        });
        <?php endif; ?>
    </script>
</body>
</html>
