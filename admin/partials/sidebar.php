<?php
// Sidebar partial untuk halaman Admin
?>
<?php $baseAdminUrl = rtrim(APP_URL, '/') . '/admin/'; ?>
<aside class="w-64 bg-white shadow-lg hidden md:block">
    <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Menu Admin</h3>
        <nav class="space-y-2">
            <a href="<?php echo $baseAdminUrl; ?>dashboard.php" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
            </a>
            <a href="<?php echo $baseAdminUrl; ?>jemaat/" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                <i class="fas fa-users mr-3"></i>Data Jemaat
            </a>
            <a href="<?php echo $baseAdminUrl; ?>jadwal/" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                <i class="fas fa-calendar mr-3"></i>Jadwal Ibadah
            </a>
            <a href="<?php echo $baseAdminUrl; ?>keuangan/" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                <i class="fas fa-coins mr-3"></i>Keuangan
            </a>
            <a href="<?php echo $baseAdminUrl; ?>warta/" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                <i class="fas fa-newspaper mr-3"></i>Warta
            </a>
            <a href="<?php echo $baseAdminUrl; ?>galeri/" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                <i class="fas fa-images mr-3"></i>Galeri
            </a>
            <a href="<?php echo $baseAdminUrl; ?>renungan/" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                <i class="fas fa-pray mr-3"></i>Renungan
            </a>
            <a href="<?php echo $baseAdminUrl; ?>kegiatan/" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                <i class="fas fa-calendar-check mr-3"></i>Kegiatan
            </a>
            <a href="<?php echo $baseAdminUrl; ?>update_logo.php" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">
                <i class="fas fa-cog mr-3"></i>Pengaturan
            </a>
        </nav>
    </div>
</aside>

