<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../partials/header.php';

if (!isAdminLoggedIn()) { redirect('../login.php'); }

$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $db = new Database();
    $db->query('SELECT * FROM kegiatan_kerohanian WHERE id = :id LIMIT 1');
    $db->bind(':id', $id);
    $data = $db->single();
    if (!$data) { $error = 'Data tidak ditemukan.'; }
} catch (Exception $e) { $error = 'Gagal memuat data.'; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $nama = trim($_POST['nama_kegiatan'] ?? '');
    $jenis = trim($_POST['jenis_kegiatan'] ?? '');
    $tanggal_mulai = trim($_POST['tanggal_mulai'] ?? '');
    $tanggal_selesai = trim($_POST['tanggal_selesai'] ?? '');
    $tempat = trim($_POST['tempat'] ?? '');
    $status = trim($_POST['status'] ?? 'direncanakan');

    if ($nama === '' || $jenis === '') { $error = 'Nama dan Jenis wajib diisi.'; }
    else {
        try {
            $db->query("UPDATE kegiatan_kerohanian SET nama_kegiatan=:nama, jenis_kegiatan=:jenis, tanggal_mulai=:mulai, tanggal_selesai=:selesai, tempat=:tempat, status=:status WHERE id=:id");
            $db->bind(':nama', $nama);
            $db->bind(':jenis', $jenis);
            $db->bind(':mulai', $tanggal_mulai !== '' ? $tanggal_mulai : null);
            $db->bind(':selesai', $tanggal_selesai !== '' ? $tanggal_selesai : null);
            $db->bind(':tempat', $tempat !== '' ? $tempat : null);
            $db->bind(':status', $status);
            $db->bind(':id', $id);
            $db->execute();
            header('Location: ' . rtrim(APP_URL,'/') . '/admin/kegiatan/?success=1');
            exit;
        } catch (Exception $e) { $error = 'Gagal menyimpan: ' . $e->getMessage(); }
    }
}
?>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Edit Kegiatan</h1>
            <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/kegiatan/" class="btn-secondary">Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <?php if ($error): ?>
                <div class="alert-error mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!$error): ?>
            <form method="post" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan</label>
                    <input name="nama_kegiatan" value="<?php echo htmlspecialchars($data->nama_kegiatan); ?>" class="w-full rounded-lg border-gray-300" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                        <select name="jenis_kegiatan" class="w-full rounded-lg border-gray-300" required>
                            <?php $opts=['pelatihan','retreat','seminar','workshop','ibadah_khusus','lainnya']; foreach($opts as $opt){ $sel=$data->jenis_kegiatan===$opt?'selected':''; echo '<option value="'.$opt.'" '.$sel.'>'.ucfirst(str_replace('_',' ',$opt)).'</option>'; } ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat</label>
                        <input name="tempat" value="<?php echo htmlspecialchars($data->tempat ?? ''); ?>" class="w-full rounded-lg border-gray-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="<?php echo htmlspecialchars($data->tanggal_mulai ?? ''); ?>" class="w-full rounded-lg border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="<?php echo htmlspecialchars($data->tanggal_selesai ?? ''); ?>" class="w-full rounded-lg border-gray-300">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-300">
                        <?php $sopts=['direncanakan','pendaftaran','berlangsung','selesai','dibatalkan']; foreach($sopts as $opt){ $sel=$data->status===$opt?'selected':''; echo '<option value="'.$opt.'" '.$sel.'>'.ucfirst($opt).'</option>'; } ?>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


