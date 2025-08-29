<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../partials/header.php';

if (!isAdminLoggedIn()) { redirect('../login.php'); }

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id'] ?? '');
    $nij = trim($_POST['nij'] ?? '');
    $nama = trim($_POST['nama_lengkap'] ?? '');
    $jk = trim($_POST['jenis_kelamin'] ?? '');
    $status = trim($_POST['status_jemaat'] ?? 'aktif');

    if ($id === '' || $nij === '' || $nama === '' || ($jk !== 'L' && $jk !== 'P')) {
        $error = 'Isian wajib belum lengkap.';
    } else {
        try {
            $db = new Database();
            $db->query("INSERT INTO jemaat (id, nij, nama_lengkap, jenis_kelamin, status_jemaat, created_at, updated_at)
                        VALUES (:id, :nij, :nama, :jk, :status, NOW(), NOW())");
            $db->bind(':id', $id);
            $db->bind(':nij', $nij);
            $db->bind(':nama', $nama);
            $db->bind(':jk', $jk);
            $db->bind(':status', $status);
            $db->execute();
            header('Location: ' . rtrim(APP_URL,'/') . '/admin/jemaat/?success=1');
            exit;
        } catch (Exception $e) {
            $error = 'Gagal menyimpan: ' . $e->getMessage();
        }
    }
}
?>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Tambah Jemaat</h1>
            <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/jemaat/" class="btn-secondary">Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <?php if ($error): ?>
                <div class="alert-error mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID</label>
                        <input name="id" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIJ</label>
                        <input name="nij" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input name="nama_lengkap" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600" required>
                            <option value="">- Pilih -</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Jemaat</label>
                        <select name="status_jemaat" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="meninggal">Meninggal</option>
                            <option value="pindah">Pindah</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


