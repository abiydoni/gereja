<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../partials/header.php';

if (!isAdminLoggedIn()) { redirect('../login.php'); }

$error = '';
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

try {
    $db = new Database();
    $db->query('SELECT * FROM keuangan WHERE id = :id LIMIT 1');
    $db->bind(':id', $id);
    $data = $db->single();
    if (!$data) { $error = 'Data tidak ditemukan.'; }
} catch (Exception $e) { $error = 'Gagal memuat data.'; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $tanggal = trim($_POST['tanggal'] ?? '');
    $jenis = trim($_POST['jenis'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $jumlah = trim($_POST['jumlah'] ?? '');
    $metode = trim($_POST['metode_pembayaran'] ?? 'tunai');
    $status = trim($_POST['status'] ?? 'diterima');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($tanggal === '' || $jenis === '' || $kategori === '' || $jumlah === '') { $error = 'Tanggal, Jenis, Kategori, dan Jumlah wajib diisi.'; }
    else {
        try {
            $db->query("UPDATE keuangan SET tanggal=:tanggal, jenis=:jenis, kategori=:kategori, jumlah=:jumlah, metode_pembayaran=:metode, status=:status, deskripsi=:deskripsi WHERE id=:id");
            $db->bind(':tanggal', $tanggal);
            $db->bind(':jenis', $jenis);
            $db->bind(':kategori', $kategori);
            $db->bind(':jumlah', $jumlah);
            $db->bind(':metode', $metode);
            $db->bind(':status', $status);
            $db->bind(':deskripsi', $deskripsi !== '' ? $deskripsi : null);
            $db->bind(':id', $id);
            $db->execute();
            header('Location: ' . rtrim(APP_URL,'/') . '/admin/keuangan/?success=1');
            exit;
        } catch (Exception $e) { $error = 'Gagal menyimpan: ' . $e->getMessage(); }
    }
}
?>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Edit Transaksi</h1>
            <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/keuangan/" class="btn-secondary">Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <?php if ($error): ?>
                <div class="alert-error mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!$error): ?>
            <form method="post" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID</label>
                        <input value="<?php echo htmlspecialchars($data->id); ?>" class="w-full rounded-lg border-gray-300 bg-gray-100" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="<?php echo htmlspecialchars($data->tanggal); ?>" class="w-full rounded-lg border-gray-300" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                        <select name="jenis" class="w-full rounded-lg border-gray-300" required>
                            <?php $opts=['pemasukan','pengeluaran']; foreach($opts as $opt){ $sel=$data->jenis===$opt?'selected':''; echo '<option value="'.$opt.'" '.$sel.'>'.ucfirst($opt).'</option>'; } ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <input name="kategori" value="<?php echo htmlspecialchars($data->kategori); ?>" class="w-full rounded-lg border-gray-300" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input type="number" step="0.01" name="jumlah" value="<?php echo htmlspecialchars($data->jumlah); ?>" class="w-full rounded-lg border-gray-300" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="w-full rounded-lg border-gray-300">
                            <?php $mopts=['tunai','transfer','cek','lainnya']; foreach($mopts as $opt){ $sel=$data->metode_pembayaran===$opt?'selected':''; echo '<option value="'.$opt.'" '.$sel.'>'.ucfirst($opt).'</option>'; } ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300">
                            <?php $sopts=['diterima','pending','ditolak']; foreach($sopts as $opt){ $sel=$data->status===$opt?'selected':''; echo '<option value="'.$opt.'" '.$sel.'>'.ucfirst($opt).'</option>'; } ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" class="w-full rounded-lg border-gray-300"><?php echo htmlspecialchars($data->deskripsi ?? ''); ?></textarea>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


