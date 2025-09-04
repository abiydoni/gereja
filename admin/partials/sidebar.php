<?php
// Sidebar partial untuk halaman Admin
?>
<?php $baseAdminUrl = rtrim(APP_URL, '/') . '/admin/';
    $currentPath = $_SERVER['REQUEST_URI'] ?? '';
    $isActive = function(string $needle) use ($currentPath) {
        return strpos($currentPath, $needle) !== false ? ' sidebar-menu-item active' : ' sidebar-menu-item';
    };
?>
<aside class="w-64 bg-white shadow-lg hidden md:block">
    <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Menu Admin</h3>
        <nav class="space-y-2">
            <a href="<?php echo $baseAdminUrl; ?>dashboard.php" class="<?php echo $isActive('/admin/dashboard.php'); ?>">
                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
            </a>
            <a href="<?php echo $baseAdminUrl; ?>jemaat/" class="<?php echo $isActive('/admin/jemaat/'); ?>">
                <i class="fas fa-users mr-3"></i>Data Jemaat
            </a>
            <a href="<?php echo $baseAdminUrl; ?>jadwal_ibadah/" class="<?php echo $isActive('/admin/jadwal_ibadah/'); ?>">
                <i class="fas fa-calendar mr-3"></i>Jadwal Ibadah
            </a>
            <a href="<?php echo $baseAdminUrl; ?>keuangan/" class="<?php echo $isActive('/admin/keuangan/'); ?>">
                <i class="fas fa-coins mr-3"></i>Keuangan
            </a>
            <a href="<?php echo $baseAdminUrl; ?>jadwal_pelayanan/" class="<?php echo $isActive('/admin/jadwal_pelayanan/'); ?>">
                <i class="fas fa-people-carry-box mr-3"></i>Jadwal Pelayanan
            </a>
            <a href="<?php echo $baseAdminUrl; ?>warta/" class="<?php echo $isActive('/admin/warta/'); ?>">
                <i class="fas fa-newspaper mr-3"></i>Warta
            </a>
            <a href="<?php echo $baseAdminUrl; ?>galeri/" class="<?php echo $isActive('/admin/galeri/'); ?>">
                <i class="fas fa-images mr-3"></i>Galeri
            </a>
            <a href="<?php echo $baseAdminUrl; ?>renungan/" class="<?php echo $isActive('/admin/renungan/'); ?>">
                <i class="fas fa-pray mr-3"></i>Renungan
            </a>
            <a href="<?php echo $baseAdminUrl; ?>sejarah_edit.php" class="<?php echo $isActive('/admin/sejarah_edit.php'); ?>">
                <i class="fas fa-scroll mr-3"></i>Sejarah Gereja
            </a>
            <a href="<?php echo $baseAdminUrl; ?>kegiatan/" class="<?php echo $isActive('/admin/kegiatan/'); ?>">
                <i class="fas fa-hands-helping mr-3"></i>Kegiatan Kerohanian
            </a>
            <a href="<?php echo $baseAdminUrl; ?>update_logo.php" class="<?php echo $isActive('/admin/update_logo.php'); ?>">
                <i class="fas fa-cog mr-3"></i>Pengaturan
            </a>
            <a href="<?php echo $baseAdminUrl; ?>system_config_manager.php" class="<?php echo $isActive('/admin/system_config_manager.php'); ?>">
                <i class="fas fa-server mr-3"></i>Pengaturan Sistem
            </a>
            <a href="<?php echo $baseAdminUrl; ?>majelis_manager.php" class="<?php echo $isActive('/admin/majelis_manager.php'); ?>">
                <i class="fas fa-sitemap mr-3"></i>Manajemen Majelis
            </a>
        </nav>
    </div>
</aside>

