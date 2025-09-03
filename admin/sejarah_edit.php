<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

// Cek apakah session sudah aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek login admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$message = '';
$sejarah = null;

// Load data sejarah
try {
    $db->query("SELECT * FROM sejarah WHERE id = 1");
    $sejarah = $db->single();
} catch (Exception $e) {
    $message = 'Gagal memuat data sejarah: ' . $e->getMessage();
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $konten = trim($_POST['konten'] ?? '');
    $tahun_didirikan = trim($_POST['tahun_didirikan'] ?? '');

    if (empty($judul) || empty($konten)) {
        $message = 'Judul dan konten harus diisi.';
    } else {
        try {
            if ($sejarah) {
                // Update existing
                $db->execute("UPDATE sejarah SET judul = ?, konten = ?, tahun_didirikan = ? WHERE id = 1", 
                    [$judul, $konten, $tahun_didirikan]);
            } else {
                // Insert new
                $db->execute("INSERT INTO sejarah (id, judul, konten, tahun_didirikan) VALUES (1, ?, ?, ?)", 
                    [$judul, $konten, $tahun_didirikan]);
            }
            $message = 'Sejarah gereja berhasil diperbarui.';
            
            // Reload data
            $db->query("SELECT * FROM sejarah WHERE id = 1");
            $sejarah = $db->single();
        } catch (Exception $e) {
            $message = 'Gagal menyimpan: ' . $e->getMessage();
        }
    }
}
?>
<?php require_once __DIR__ . '/partials/header.php'; ?>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Edit Sejarah Gereja</h1>
            <a href="dashboard.php" class="btn-secondary">Kembali ke Dashboard</a>
        </div>

        <?php if ($message): ?>
        <div class="mb-4 p-3 rounded border <?php echo strpos($message, 'berhasil') !== false ? 'border-green-300 bg-green-50 text-green-700' : 'border-red-300 bg-red-50 text-red-700'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow p-6">
            <form method="post" class="space-y-6">
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Sejarah</label>
                    <input type="text" id="judul" name="judul" 
                           value="<?php echo htmlspecialchars($sejarah['judul'] ?? ''); ?>"
                           class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600" 
                           placeholder="Contoh: RANDUARES DALAM SEJARAH" required>
                </div>

                <div>
                    <label for="tahun_didirikan" class="block text-sm font-medium text-gray-700 mb-2">Tahun Didirikan</label>
                    <input type="number" id="tahun_didirikan" name="tahun_didirikan" 
                           value="<?php echo htmlspecialchars($sejarah['tahun_didirikan'] ?? ''); ?>"
                           class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600" 
                           placeholder="Contoh: 1932" min="1800" max="2100">
                </div>

                <div>
                    <label for="konten" class="block text-sm font-medium text-gray-700 mb-2">Konten Sejarah</label>
                    <textarea id="konten" name="konten" class="ckeditor" rows="15" required><?php echo htmlspecialchars($sejarah['konten'] ?? ''); ?></textarea>
                    <p class="text-xs text-gray-500 mt-1">Gunakan editor untuk memformat teks sejarah gereja dengan baik.</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Sejarah
                    </button>
                </div>
            </form>
        </div>

        <?php if ($sejarah): ?>
        <div class="mt-6 bg-amber-50 rounded-xl p-4 border border-amber-200">
            <h3 class="text-sm font-medium text-amber-800 mb-2">Informasi Data</h3>
            <div class="text-xs text-amber-700 space-y-1">
                <p><strong>Terakhir diperbarui:</strong> <?php echo date('d M Y H:i', strtotime($sejarah['updated_at'])); ?></p>
                <p><strong>ID:</strong> <?php echo (int)$sejarah['id']; ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Custom CSS untuk CKEditor Theme (selaras dengan renungan/tambah.php) -->
    <style>
        .cke_chrome { border-color: #f59e0b !important; }
        .cke_top { background: #ffffff !important; border-bottom-color: #f59e0b !important; }
        .cke_bottom { background: #ffffff !important; border-top-color: #f59e0b !important; }
        .cke_toolgroup { background: #ffffff !important; border-color: #f59e0b !important; }
        .cke_button { background: transparent !important; }
        .cke_button:hover { background: #f59e0b !important; color: #ffffff !important; }
        .cke_button_on { background: #f59e0b !important; color: #ffffff !important; }
        .cke_combo_button { background: #ffffff !important; border-color: #f59e0b !important; }
        .cke_combo_button:hover { background: #f59e0b !important; color: #ffffff !important; }
        .cke_panel { background: #ffffff !important; border-color: #f59e0b !important; }
        .cke_panel_listItem.cke_selected { background: #f59e0b !important; color: #ffffff !important; }
        .cke_panel_listItem:hover { background: #fef3c7 !important; }
    </style>
    <!-- CKEditor 4 -->
    <script src="ckeditor/ckeditor.js"></script>
    <script>        // Inisialisasi CKEditor dengan toolbar lengkap
        CKEDITOR.replace('konten', {
            height: 200,
            width: '100%'
        });
    </script>
<?php require_once __DIR__ . '/partials/footer.php'; ?>
