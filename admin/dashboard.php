<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/partials/header.php';

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

    <!-- Navigation di header partial -->

        <!-- Main Content -->
        <div class="p-8">
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
                    <div style="height:240px">
                        <canvas id="keuanganChart"></canvas>
                    </div>
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
                                <p class="font-medium text-gray-800"><?php echo htmlspecialchars($jadwal['judul']); ?></p>
                                <p class="text-sm text-gray-600">
                                    <?php echo formatTanggalIndonesia($jadwal['tanggal']); ?> • <?php echo $jadwal['waktu_mulai']; ?>
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                <?php echo ucfirst(str_replace('_', ' ', $jadwal['jenis_ibadah'])); ?>
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="<?php echo rtrim(APP_URL, '/'); ?>/admin/jemaat/" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <i class="fas fa-user-plus text-2xl text-blue-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-blue-800">Tambah Jemaat</p>
                            <p class="text-sm text-blue-600">Input data jemaat baru</p>
                        </div>
                    </a>
                    
                    <a href="<?php echo rtrim(APP_URL, '/'); ?>/admin/jadwal/" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <i class="fas fa-calendar-plus text-2xl text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-green-800">Tambah Jadwal</p>
                            <p class="text-sm text-green-600">Buat jadwal ibadah baru</p>
                        </div>
                    </a>
                    
                    <a href="<?php echo rtrim(APP_URL, '/'); ?>/admin/warta/" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <i class="fas fa-edit text-2xl text-purple-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-purple-800">Tulis Warta</p>
                            <p class="text-sm text-purple-600">Buat warta gereja baru</p>
                        </div>
                    </a>
                    
                    <a href="<?php echo rtrim(APP_URL, '/'); ?>/admin/system_config_manager.php" class="flex items-center p-4 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors">
                        <i class="fas fa-cog text-2xl text-amber-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-amber-800">Pengaturan Sistem</p>
                            <p class="text-sm text-amber-600">Konfigurasi YouTube & Cache</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                maintainAspectRatio: true,
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
<?php require_once __DIR__ . '/partials/footer.php'; ?>
