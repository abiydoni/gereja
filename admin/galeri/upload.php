<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../partials/header.php';

if (!isAdminLoggedIn()) { redirect('../login.php'); }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $judul = trim($_POST['judul'] ?? '');
        $kategori = trim($_POST['kategori'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');

        $allowed = ['image/png','image/jpeg','image/jpg','image/gif'];
        $mime = $_FILES['file']['type'];
        if (!in_array($mime, $allowed)) {
            $error = 'Tipe file tidak didukung.';
        } else {
            $uploadDir = __DIR__ . '/../../assets/images/';
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $newName = uniqid('galeri_') . '.' . $ext;
            $dest = $uploadDir . $newName;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
                try {
                    $db = new Database();
                    $db->query("INSERT INTO galeri (judul, deskripsi, nama_file, path_file, ukuran_file, tipe_file, kategori, tanggal_upload, uploaded_by, status, created_at)
                                VALUES (:judul, :deskripsi, :nama, :path, :ukuran, :tipe, :kategori, CURDATE(), NULL, 'aktif', NOW())");
                    $db->bind(':judul', $judul ?: $newName);
                    $db->bind(':deskripsi', $deskripsi !== '' ? $deskripsi : null);
                    $db->bind(':nama', $newName);
                    $db->bind(':path', 'assets/images/' . $newName);
                    $db->bind(':ukuran', $_FILES['file']['size']);
                    $db->bind(':tipe', $mime);
                    $db->bind(':kategori', $kategori !== '' ? $kategori : null);
                    $db->execute();
                    header('Location: ' . rtrim(APP_URL,'/') . '/admin/galeri/?success=1');
                    exit;
                } catch (Exception $e) { $error = 'Gagal simpan DB: ' . $e->getMessage(); }
            } else { $error = 'Gagal upload file.'; }
        }
    } else { $error = 'Pilih file terlebih dahulu.'; }
}
?>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Upload Galeri</h1>
            <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/galeri/" class="btn-secondary">Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <?php if ($error): ?>
                <div class="alert-error mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input name="judul" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <input name="kategori" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" class="w-full rounded-lg border-gray-300"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">File Gambar</label>
                    <input type="file" name="file" accept="image/*" class="w-full">
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button type="submit" class="btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


