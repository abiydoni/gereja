<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../partials/header.php';

if (!isAdminLoggedIn()) { redirect('../login.php'); }

$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Muat data
try {
    $db = new Database();
    $db->query('SELECT * FROM warta WHERE id = :id LIMIT 1');
    $db->bind(':id', $id);
    $data = $db->single();
    if (!$data) { $error = 'Data tidak ditemukan.'; }
} catch (Exception $e) { $error = 'Gagal memuat data.'; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $judul = trim($_POST['judul'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'berita');
    $status = trim($_POST['status'] ?? 'draft');
    $tanggal_publish = trim($_POST['tanggal_publish'] ?? '');
    $ringkasan = trim($_POST['ringkasan'] ?? '');
    $konten = trim($_POST['konten'] ?? '');

    if ($judul === '' || $konten === '') { $error = 'Judul dan konten wajib diisi.'; }
    else {
        try {
            $db->query("UPDATE warta SET judul=:judul, konten=:konten, ringkasan=:ringkasan, kategori=:kategori, status=:status, tanggal_publish=:tgl, penulis=:penulis WHERE id=:id");
            $db->bind(':judul', $judul);
            $db->bind(':konten', $konten);
            $db->bind(':ringkasan', $ringkasan !== '' ? $ringkasan : null);
            $db->bind(':kategori', $kategori);
            $db->bind(':status', $status);
            $db->bind(':tgl', $tanggal_publish !== '' ? $tanggal_publish : null);
            $db->bind(':penulis', $_SESSION['admin_nama'] ?? 'Admin');
            $db->bind(':id', $id);
            $db->execute();
            header('Location: ' . rtrim(APP_URL,'/') . '/admin/warta/?success=1');
            exit;
        } catch (Exception $e) { $error = 'Gagal menyimpan: ' . $e->getMessage(); }
    }
}
?>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Edit Warta</h1>
            <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/warta/" class="btn-secondary">Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <?php if ($error): ?>
                <div class="alert-error mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!$error): ?>
            <form method="post" class="space-y-6">
                <div>
                    <label class="form-label">Judul</label>
                    <input name="judul" value="<?php echo htmlspecialchars($data->judul); ?>" class="form-input" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-input">
                            <?php $opts=['berita','pengumuman','acara','renungan','lainnya']; foreach($opts as $opt){ $sel=$data->kategori===$opt?'selected':''; echo '<option value="'.$opt.'" '.$sel.'>'.ucfirst($opt).'</option>'; } ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input">
                            <?php $sopts=['draft','published','archived']; foreach($sopts as $opt){ $sel=$data->status===$opt?'selected':''; echo '<option value="'.$opt.'" '.$sel.'>'.ucfirst($opt).'</option>'; } ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Tanggal Publish</label>
                        <input type="date" name="tanggal_publish" value="<?php echo htmlspecialchars($data->tanggal_publish ?? ''); ?>" class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Ringkasan</label>
                    <textarea name="ringkasan" class="form-input" rows="3"><?php echo htmlspecialchars($data->ringkasan ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="form-label">Konten</label>
                    <textarea id="konten" name="konten" class="form-input" rows="12" required><?php echo htmlspecialchars($data->konten); ?></textarea>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/warta/" class="btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>document.addEventListener('DOMContentLoaded',()=>{tinymce.init({selector:'#konten',height:420,menubar:false,plugins:'link lists advlist autoresize',toolbar:'undo redo | bold italic underline | bullist numlist | link | removeformat',branding:false});});</script>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


