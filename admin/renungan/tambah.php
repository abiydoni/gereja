<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
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
            $db->query(
                "INSERT INTO renungan (judul, ayat_alkitab, konten, penulis, kategori, status, tanggal_publish, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
                [
                    $judul,
                    $ayat !== '' ? $ayat : null,
                    $konten,
                    $_SESSION['admin_nama'] ?? 'Admin',
                    $kategori !== '' ? $kategori : null,
                    $status,
                    $tanggal_publish !== '' ? $tanggal_publish : null
                ]
            );
            // Redirect setelah insert berhasil (gunakan relatif agar aman)
            header('Location: index.php?success=1');
            exit;
        } catch (Exception $e) { $error = 'Gagal menyimpan: ' . $e->getMessage(); }
    }
}
?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>
    <div class="max-w-full mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Tulis Renungan</h1>
            <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/renungan/" class="btn-secondary">Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <?php if ($error): ?>
                <div class="alert-error mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" class="space-y-6">
                <div>
                    <label class="form-label">Judul</label>
                    <input name="judul" class="form-input" placeholder="Judul renungan" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Ayat Alkitab</label>
                        <input name="ayat_alkitab" class="form-input" placeholder="Misal: Mazmur 23:1-6">
                    </div>
                    <div>
                        <label class="form-label">Kategori</label>
                        <input name="kategori" class="form-input" placeholder="Misal: Pengharapan">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    <label class="form-label">Konten</label>                    
                    <textarea id="konten" name="konten" class="ckeditor" rows="12"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/renungan/" class="btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom CSS untuk CKEditor Theme -->
    <style>
        /* Override warna biru CKEditor menjadi tema amber/orange */
        .cke_chrome {
            border-color: #f59e0b !important;
        }
        
        .cke_top {
            background: white !important;
            border-bottom-color: #f59e0b !important;
        }
        
        .cke_bottom {
            background: white !important;
            border-top-color: #f59e0b !important;
        }
        
        .cke_toolgroup {
            background: white !important;
            border-color: #f59e0b !important;
        }
        
        .cke_button {
            background: transparent !important;
        }
        
        .cke_button:hover {
            background: #f59e0b !important;
            color: white !important;
        }
        
        .cke_button_on {
            background: #f59e0b !important;
            color: white !important;
        }
        
        .cke_combo_button {
            background: white !important;
            border-color: #f59e0b !important;
        }
        
        .cke_combo_button:hover {
            background: #f59e0b !important;
            color: white !important;
        }
        
        .cke_panel {
            background: white !important;
            border-color: #f59e0b !important;
        }
        
        .cke_panel_listItem.cke_selected {
            background: #f59e0b !important;
            color: white !important;
        }
        
        .cke_panel_listItem:hover {
            background: #fef3c7 !important;
        }
    </style>

    <!-- CKEditor Rich Text Editor -->
    <script src="../ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        // CKEditor akan otomatis menginisialisasi textarea dengan class 'ckeditor'
        // Sama seperti di was/admin/footer.php
    </script>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


