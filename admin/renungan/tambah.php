<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../partials/header.php';

if (!isAdminLoggedIn()) { redirect('../login.php'); }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $ayat = trim($_POST['ayat_alkitab'] ?? '');
    $konten = trim($_POST['konten'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $status = trim($_POST['status'] ?? 'draft');
    $tanggal_publish = trim($_POST['tanggal_publish'] ?? '');

    if ($judul === '' || $konten === '') { $error = 'Judul dan konten wajib diisi.'; }
    else {
        try {
            $db = new Database();
            $db->query("INSERT INTO renungan (judul, ayat_alkitab, konten, penulis, kategori, status, tanggal_publish, created_at, updated_at)
                        VALUES (:judul, :ayat, :konten, :penulis, :kategori, :status, :tgl, NOW(), NOW())");
            $db->bind(':judul', $judul);
            $db->bind(':ayat', $ayat !== '' ? $ayat : null);
            $db->bind(':konten', $konten);
            $db->bind(':penulis', $_SESSION['admin_nama'] ?? 'Admin');
            $db->bind(':kategori', $kategori !== '' ? $kategori : null);
            $db->bind(':status', $status);
            $db->bind(':tgl', $tanggal_publish !== '' ? $tanggal_publish : null);
            $db->execute();
            header('Location: ' . rtrim(APP_URL,'/') . '/admin/renungan/?success=1');
            exit;
        } catch (Exception $e) { $error = 'Gagal menyimpan: ' . $e->getMessage(); }
    }
}
?>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Tulis Renungan</h1>
            <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/renungan/" class="btn-secondary">Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <?php if ($error): ?>
                <div class="alert-error mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input name="judul" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ayat Alkitab</label>
                        <input name="ayat_alkitab" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <input name="kategori" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publish</label>
                        <input type="date" name="tanggal_publish" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                    <textarea id="konten" name="konten" class="w-full rounded-lg border-gray-300" rows="12" required></textarea>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>document.addEventListener('DOMContentLoaded',()=>{tinymce.init({selector:'#konten',height:420,menubar:false,plugins:'link lists advlist autoresize',toolbar:'undo redo | bold italic underline | bullist numlist | link | removeformat',branding:false});});</script>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


