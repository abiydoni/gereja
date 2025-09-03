<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';

$db = new Database();
$message = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Load data jika edit
$kegiatan = null;
if ($id > 0) {
    try {
        $db->query("SELECT * FROM kegiatan_kerohanian WHERE id = ?", [$id]);
        $kegiatan = $db->single();
    } catch (Exception $e) {}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nama_kegiatan = trim($_POST['nama_kegiatan'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $tanggal_mulai = $_POST['tanggal_mulai'] ?: null;
    $tanggal_selesai = $_POST['tanggal_selesai'] ?: null;
    $waktu_mulai = $_POST['waktu_mulai'] ?: null;
    $waktu_selesai = $_POST['waktu_selesai'] ?: null;
    $tempat = trim($_POST['tempat'] ?? '');
    $jenis_kegiatan = $_POST['jenis_kegiatan'] ?? 'lainnya';
    $target_peserta = trim($_POST['target_peserta'] ?? '');
    $kuota_peserta = $_POST['kuota_peserta'] !== '' ? (int)$_POST['kuota_peserta'] : null;
    $biaya = $_POST['biaya'] !== '' ? (float)$_POST['biaya'] : null;
    $status = $_POST['status'] ?? 'direncanakan';

    try {
        if ($id > 0) {
            $db->execute("UPDATE kegiatan_kerohanian SET nama_kegiatan=?, deskripsi=?, tanggal_mulai=?, tanggal_selesai=?, waktu_mulai=?, waktu_selesai=?, tempat=?, jenis_kegiatan=?, target_peserta=?, kuota_peserta=?, biaya=?, status=? WHERE id=?",
                [$nama_kegiatan, $deskripsi, $tanggal_mulai, $tanggal_selesai, $waktu_mulai, $waktu_selesai, $tempat, $jenis_kegiatan, $target_peserta, $kuota_peserta, $biaya, $status, $id]);
        } else {
            $db->execute("INSERT INTO kegiatan_kerohanian (nama_kegiatan, deskripsi, tanggal_mulai, tanggal_selesai, waktu_mulai, waktu_selesai, tempat, jenis_kegiatan, target_peserta, kuota_peserta, biaya, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",
                [$nama_kegiatan, $deskripsi, $tanggal_mulai, $tanggal_selesai, $waktu_mulai, $waktu_selesai, $tempat, $jenis_kegiatan, $target_peserta, $kuota_peserta, $biaya, $status]);
        }
        header('Location: index.php?success=1');
        exit;
    } catch (Exception $e) {
        $message = 'Gagal menyimpan: ' . $e->getMessage();
    }
}

require_once __DIR__ . '/../partials/header.php';
?>
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800"><?php echo $id>0 ? 'Edit' : 'Tambah'; ?> Kegiatan</h1>
        <a href="index.php" class="btn-secondary">Kembali</a>
    </div>

    <?php if ($message): ?>
    <div class="mb-4 p-3 rounded border border-red-300 bg-red-50 text-red-700"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow p-6">
        <form method="post" class="space-y-5">
            <input type="hidden" name="id" value="<?php echo (int)$id; ?>">
            <div>
                <label class="form-label">Nama Kegiatan</label>
                <input name="nama_kegiatan" class="form-input" value="<?php echo htmlspecialchars($kegiatan['nama_kegiatan'] ?? ''); ?>" required>
            </div>
            <div>
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="ckeditor" rows="8"><?php echo htmlspecialchars($kegiatan['deskripsi'] ?? ''); ?></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-input" value="<?php echo htmlspecialchars($kegiatan['tanggal_mulai'] ?? ''); ?>">
                </div>
                <div>
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-input" value="<?php echo htmlspecialchars($kegiatan['tanggal_selesai'] ?? ''); ?>">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" class="form-input" value="<?php echo htmlspecialchars($kegiatan['waktu_mulai'] ?? ''); ?>">
                </div>
                <div>
                    <label class="form-label">Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" class="form-input" value="<?php echo htmlspecialchars($kegiatan['waktu_selesai'] ?? ''); ?>">
                </div>
            </div>
            <div>
                <label class="form-label">Tempat</label>
                <input name="tempat" class="form-input" value="<?php echo htmlspecialchars($kegiatan['tempat'] ?? ''); ?>">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Jenis Kegiatan</label>
                    <select name="jenis_kegiatan" class="form-input">
                        <?php $ops = ['pelatihan','retreat','seminar','workshop','ibadah_khusus','lainnya'];
                        $cur = $kegiatan['jenis_kegiatan'] ?? 'lainnya';
                        foreach ($ops as $op) {
                            $sel = $op === $cur ? 'selected' : '';
                            echo '<option value="'.$op.'" '.$sel.'>'.ucwords(str_replace('_',' ',$op)).'</option>';
                        }?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Target Peserta</label>
                    <input name="target_peserta" class="form-input" value="<?php echo htmlspecialchars($kegiatan['target_peserta'] ?? ''); ?>">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Kuota Peserta</label>
                    <input type="number" name="kuota_peserta" class="form-input" value="<?php echo htmlspecialchars($kegiatan['kuota_peserta'] ?? ''); ?>">
                </div>
                <div>
                    <label class="form-label">Biaya (Rp)</label>
                    <input type="number" step="0.01" name="biaya" class="form-input" value="<?php echo htmlspecialchars($kegiatan['biaya'] ?? ''); ?>">
                </div>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <?php $ops = ['direncanakan','pendaftaran','berlangsung','selesai','dibatalkan'];
                    $cur = $kegiatan['status'] ?? 'direncanakan';
                    foreach ($ops as $op) {
                        $sel = $op === $cur ? 'selected' : '';
                        echo '<option value="'.$op.'" '.$sel.'>'.ucfirst($op).'</option>';
                    }?>
                </select>
            </div>
            <div class="flex items-center justify-end gap-3">
                <a href="index.php" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
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

<!-- CKEditor -->
<script src="../ckeditor/ckeditor.js"></script>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
