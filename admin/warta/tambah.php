<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../partials/header.php';

if (!isAdminLoggedIn()) { redirect('../login.php'); }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'berita');
    $status = trim($_POST['status'] ?? 'draft');
    $tanggal_publish = trim($_POST['tanggal_publish'] ?? '');
    $ringkasan = trim($_POST['ringkasan'] ?? '');
    $konten = trim($_POST['konten'] ?? '');

    if ($judul === '' || $konten === '') {
        $error = 'Judul dan konten wajib diisi.';
    } else {
        try {
            $db = new Database();
            $db->query("INSERT INTO warta (judul, konten, ringkasan, gambar, kategori, status, tanggal_publish, penulis, tags, views, created_at, updated_at)
                        VALUES (:judul, :konten, :ringkasan, NULL, :kategori, :status, :tgl, :penulis, NULL, 0, NOW(), NOW())");
            $db->bind(':judul', $judul);
            $db->bind(':konten', $konten);
            $db->bind(':ringkasan', $ringkasan !== '' ? $ringkasan : null);
            $db->bind(':kategori', $kategori);
            $db->bind(':status', $status);
            $db->bind(':tgl', $tanggal_publish !== '' ? $tanggal_publish : null);
            $db->bind(':penulis', $_SESSION['admin_nama'] ?? 'Admin');
            $db->execute();
            header('Location: ' . rtrim(APP_URL,'/') . '/admin/warta/?success=1');
            exit;
        } catch (Exception $e) { $error = 'Gagal menyimpan: ' . $e->getMessage(); }
    }
}
?>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Tulis Warta</h1>
            <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/warta/" class="btn-secondary">Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <?php if ($error): ?>
                <div class="alert-error mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" class="space-y-6">
                <div>
                    <label class="form-label">Judul</label>
                    <input name="judul" class="form-input" placeholder="Judul warta" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-input">
                            <option value="berita">Berita</option>
                            <option value="pengumuman">Pengumuman</option>
                            <option value="acara">Acara</option>
                            <option value="renungan">Renungan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Tanggal Publish</label>
                        <input type="date" name="tanggal_publish" class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Ringkasan</label>
                    <textarea name="ringkasan" class="form-input" rows="3"></textarea>
                </div>

                <div>
                    <label class="form-label">Konten</label>
                    <textarea id="konten" name="konten" class="form-input" rows="12" required></textarea>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/warta/" class="btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            tinymce.init({ selector: '#konten', height: 420, menubar: false, plugins: 'link lists advlist autoresize', toolbar: 'undo redo | bold italic underline | bullist numlist | link | removeformat', branding: false });
        });
    </script>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


