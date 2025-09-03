<?php
// Dashboard admin sederhana
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Database connection
$host = 'localhost';
$dbname = 'appsbeem_gereja';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get statistics
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM renungan");
    $total_renungan = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM renungan WHERE status = 'published'");
    $published_renungan = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM renungan WHERE status = 'draft'");
    $draft_renungan = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("SELECT SUM(views) as total FROM renungan");
    $total_views = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
<?php require_once __DIR__ . '/partials/header.php'; ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Selamat Datang di Admin Panel</h1>
            <p class="text-gray-600 mt-2">Kelola konten website gereja dengan mudah</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Renungan -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-2xl text-amber-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Renungan</p>
                        <p class="text-2xl font-bold text-gray-900"><?= number_format($total_renungan) ?></p>
                    </div>
                </div>
            </div>
            <!-- Published Renungan -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-2xl text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Published</p>
                        <p class="text-2xl font-bold text-gray-900"><?= number_format($published_renungan) ?></p>
                    </div>
                </div>
            </div>
            <!-- Draft Renungan -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-2xl text-gray-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Draft</p>
                        <p class="text-2xl font-bold text-gray-900"><?= number_format($draft_renungan) ?></p>
                    </div>
                </div>
            </div>
            <!-- Total Views -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-eye text-2xl text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Views</p>
                        <p class="text-2xl font-bold text-gray-900"><?= number_format($total_views) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Manage Renungan -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-book mr-2 text-amber-600"></i>
                        Kelola Renungan
                    </h3>
                </div>
                <p class="text-gray-600 mb-4">
                    Buat, edit, dan kelola renungan harian dengan rich text editor yang lengkap
                </p>
                <div class="space-y-2">
                    <a href="renungan_form.php" class="block w-full bg-amber-600 hover:bg-amber-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Renungan Baru
                    </a>
                    <a href="renungan_list.php" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 text-center py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Semua Renungan
                    </a>
                </div>
            </div>
            
            <!-- Manage Sejarah -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-scroll mr-2 text-purple-600"></i>
                        Kelola Sejarah
                    </h3>
                </div>
                <p class="text-gray-600 mb-4">
                    Edit dan perbarui sejarah gereja dengan rich text editor
                </p>
                <div class="space-y-2">
                    <a href="sejarah_edit.php" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Sejarah Gereja
                    </a>
                    <a href="../index.php#tentang" target="_blank" class="block w-full bg-purple-100 hover:bg-purple-200 text-purple-700 text-center py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat di Website
                    </a>
                </div>
            </div>
            
            <!-- Website Info -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-globe mr-2 text-blue-600"></i>
                        Website Info
                    </h3>
                </div>
                <p class="text-gray-600 mb-4">
                    Informasi dan link penting untuk website gereja
                </p>
                <div class="space-y-2">
                    <a href="../index.php" target="_blank" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Lihat Website
                    </a>
                    <a href="../pages/renungan.php" target="_blank" class="block w-full bg-green-100 hover:bg-green-200 text-green-700 text-center py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-book-open mr-2"></i>
                        Halaman Renungan
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-clock mr-2 text-purple-600"></i>
                Aktivitas Terbaru
            </h3>
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-chart-line text-4xl mb-4 block"></i>
                <p>Fitur aktivitas terbaru akan ditampilkan di sini</p>
            </div>
        </div>
    </div>
<?php require_once __DIR__ . '/partials/footer.php'; ?>
