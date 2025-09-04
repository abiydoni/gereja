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
    $db->query('SELECT * FROM jadwal_ibadah WHERE id = :id LIMIT 1');
    $db->bind(':id', $id);
    $data = $db->single();
    if (!$data) { $error = 'Data tidak ditemukan.'; }
} catch (Exception $e) { $error = 'Gagal memuat data.'; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $judul = trim($_POST['judul'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? '');
    $waktu_mulai = trim($_POST['waktu_mulai'] ?? '');
    $waktu_selesai = trim($_POST['waktu_selesai'] ?? '');
    $jenis = trim($_POST['jenis_ibadah'] ?? '');
    $tempat = trim($_POST['tempat'] ?? '');
    $pemimpin = trim($_POST['pemimpin_ibadah'] ?? '');
    $status = trim($_POST['status'] ?? 'akan_datang');

    if ($judul === '' || $waktu_mulai === '' || $jenis === '') { $error = 'Judul, waktu mulai, dan jenis wajib diisi.'; }
    else {
        try {
            $db->query("UPDATE jadwal_ibadah SET judul=:judul, tanggal=:tanggal, waktu_mulai=:mulai, waktu_selesai=:selesai, jenis_ibadah=:jenis, tempat=:tempat, pemimpin_ibadah=:pemimpin, status=:status WHERE id=:id");
            $db->bind(':judul', $judul);
            $db->bind(':tanggal', $tanggal !== '' ? $tanggal : '0000-00-00');
            $db->bind(':mulai', $waktu_mulai);
            $db->bind(':selesai', $waktu_selesai !== '' ? $waktu_selesai : null);
            $db->bind(':jenis', $jenis);
            $db->bind(':tempat', $tempat !== '' ? $tempat : null);
            $db->bind(':pemimpin', $pemimpin !== '' ? $pemimpin : null);
            $db->bind(':status', $status);
            $db->bind(':id', $id);
            $db->execute();
            header('Location: ' . rtrim(APP_URL,'/') . '/admin/jadwal_ibadah/?success=1');
            exit;
        } catch (Exception $e) { $error = 'Gagal menyimpan: ' . $e->getMessage(); }
    }
}
?>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Edit Jadwal Ibadah</h1>
            <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/jadwal_ibadah/" class="btn-secondary">Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <?php if ($error): ?>
                <div class="alert-error mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!$error): ?>
            <form method="post" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input name="judul" value="<?php echo htmlspecialchars($data->judul); ?>" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="<?php echo htmlspecialchars($data->tanggal !== '0000-00-00' ? $data->tanggal : ''); ?>" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan untuk Rutin Mingguan</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" value="<?php echo htmlspecialchars($data->waktu_mulai); ?>" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                        <input type="time" name="waktu_selesai" value="<?php echo htmlspecialchars($data->waktu_selesai ?? ''); ?>" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Ibadah</label>
                        <select name="jenis_ibadah" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600" required>
                            <?php $opts=['ibadah_minggu','ibadah_doa','ibadah_pemuda','ibadah_anak','ibadah_khusus']; foreach($opts as $opt){ $sel=$data->jenis_ibadah===$opt?'selected':''; echo '<option value="'.$opt.'" '.$sel.'>'.ucfirst(str_replace('_',' ',$opt)).'</option>'; } ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat</label>
                        <input name="tempat" value="<?php echo htmlspecialchars($data->tempat ?? ''); ?>" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pemimpin Ibadah</label>
                    <input name="pemimpin_ibadah" value="<?php echo htmlspecialchars($data->pemimpin_ibadah ?? ''); ?>" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
                        <?php $sopts=['akan_datang','sedang_berlangsung','selesai','dibatalkan']; foreach($sopts as $opt){ $sel=$data->status===$opt?'selected':''; echo '<option value="'.$opt.'" '.$sel.'>'.ucfirst(str_replace('_',' ',$opt)).'</option>'; } ?>
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


