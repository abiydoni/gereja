<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
$pageTitle = 'Kelola Warta';
require_once __DIR__ . '/../partials/header.php';

// Cek login admin
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor = isset($_POST['nomor']) ? trim($_POST['nomor']) : null;
    $tanggal = isset($_POST['tanggal']) ? trim($_POST['tanggal']) : date('Y-m-d');
    $ucapan = isset($_POST['ucapan']) ? trim($_POST['ucapan']) : '';

    try {
        $db = new Database();

        // Simpan warta (upsert berdasarkan tanggal)
        try {
            $db->query("INSERT INTO warta (tanggal, nomor) VALUES (:tanggal, :nomor)
                        ON DUPLICATE KEY UPDATE nomor = VALUES(nomor)");
            $db->bind(':tanggal', $tanggal);
            $db->bind(':nomor', $nomor);
            $db->execute();
        } catch (Exception $e) {}

        // Simpan ucapan (gunakan tabel ucapan_selamat)
        try {
            $db->query("INSERT INTO ucapan_selamat (konten, updated_at) VALUES (:konten, NOW())");
            $db->bind(':konten', $ucapan);
            $db->execute();
        } catch (Exception $e) {}

        $message = 'Berhasil disimpan.';
    } catch (Exception $e) {
        $message = 'Gagal menyimpan.';
    }
}

// Muat data terakhir
$nomor = '';
$tanggal = date('Y-m-d');
$ucapan = '';
try {
    $db = new Database();
    $db->query("SELECT nomor, tanggal FROM warta ORDER BY tanggal DESC LIMIT 1");
    $w = $db->single();
    if ($w) { $nomor = $w->nomor ?: ''; $tanggal = $w->tanggal ?: $tanggal; }

    $db->query("SELECT konten FROM ucapan_selamat ORDER BY updated_at DESC LIMIT 1");
    $u = $db->single();
    if ($u) { $ucapan = $u->konten; }
} catch (Exception $e) {}
?>

    <?php
    $listWarta = [];
    try {
        $dbList = new Database();
        $dbList->query("SELECT id, judul, kategori, status, tanggal_publish, views FROM warta ORDER BY COALESCE(tanggal_publish, created_at) DESC, id DESC LIMIT 200");
        $listWarta = $dbList->resultSet();
    } catch (Exception $e) { $listWarta = []; }
    ?>
    <div class="max-w-5xl mx-auto px-4 py-8 space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">Kelola Warta</h1>
                <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/warta/tambah.php" class="btn-secondary">Tulis Warta</a>
            </div>
            <section class="bg-white rounded-xl shadow p-6">
                <h1 class="text-2xl font-bold text-amber-800 mb-4">Form Warta</h1>

                <?php if ($message): ?>
                    <div class="mb-4 p-3 rounded border <?php echo ($message==='Berhasil disimpan.') ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-red-200 bg-red-50 text-red-700'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form method="post" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Warta</label>
                            <input type="text" name="nomor" value="<?php echo htmlspecialchars($nomor); ?>" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600" placeholder="Misal: 05/2025">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" name="tanggal" value="<?php echo htmlspecialchars($tanggal); ?>" class="w-full rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ucapan Selamat Datang</label>
                        <textarea id="ucapan" name="ucapan" class="w-full rounded-lg border-gray-300"><?php echo htmlspecialchars($ucapan); ?></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="../../pages/warta.php" target="_blank" class="px-4 py-2 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200">Lihat Halaman Warta</a>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-amber-600 text-white hover:bg-amber-700">Simpan</button>
                    </div>
                </form>
            </section>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-amber-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Views</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($listWarta)): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-6 text-center text-gray-500">Belum ada warta</td>
                                </tr>
                            <?php else: foreach ($listWarta as $w): ?>
                                <tr class="hover:bg-amber-50">
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($w->judul); ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-700"><?php echo htmlspecialchars($w->kategori); ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-700"><?php echo ucfirst($w->status); ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-700"><?php echo $w->tanggal_publish ? formatTanggalIndonesia($w->tanggal_publish) : '-'; ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-700"><?php echo (int)$w->views; ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-700">
                                        <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/warta/edit.php?id=<?php echo (int)$w->id; ?>" class="text-amber-700 hover:underline mr-3">Edit</a>
                                        <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/warta/hapus.php?id=<?php echo (int)$w->id; ?>" class="text-red-600 hover:underline" onclick="return confirm('Hapus warta ini?');">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>


