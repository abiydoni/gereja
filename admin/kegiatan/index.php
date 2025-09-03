<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../partials/header.php';

// Ambil daftar kegiatan
try {
    $db = new Database();
    $db->query("SELECT * FROM kegiatan_kerohanian ORDER BY created_at DESC");
    $rows = $db->resultSet();
} catch (Exception $e) {
    $rows = [];
}
?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Kegiatan Kerohanian</h1>
        <a href="form.php" class="btn-primary"><i class="fas fa-plus mr-2"></i>Tambah Kegiatan</a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <?php if (!empty($_GET['success'])): ?>
            <div class="mb-4 p-3 rounded border border-green-300 bg-green-50 text-green-700">Data berhasil disimpan.</div>
        <?php endif; ?>
        <?php if (!empty($_GET['deleted'])): ?>
            <div class="mb-4 p-3 rounded border border-green-300 bg-green-50 text-green-700">Data berhasil dihapus.</div>
        <?php endif; ?>

        <?php if (!empty($rows)): ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-amber-800 border-b">
                        <th class="py-2 pr-4">Nama Kegiatan</th>
                        <th class="py-2 pr-4">Tanggal</th>
                        <th class="py-2 pr-4">Waktu</th>
                        <th class="py-2 pr-4">Tempat</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r): ?>
                    <tr class="border-b last:border-none">
                        <td class="py-2 pr-4 font-medium text-amber-900"><?php echo htmlspecialchars($r['nama_kegiatan']); ?></td>
                        <td class="py-2 pr-4 text-amber-800"><?php echo htmlspecialchars(($r['tanggal_mulai'] ?? '')); ?><?php echo !empty($r['tanggal_selesai']) ? ' - ' . htmlspecialchars($r['tanggal_selesai']) : ''; ?></td>
                        <td class="py-2 pr-4 text-amber-800"><?php echo htmlspecialchars(($r['waktu_mulai'] ?? '')); ?><?php echo !empty($r['waktu_selesai']) ? ' - ' . htmlspecialchars($r['waktu_selesai']) : ''; ?></td>
                        <td class="py-2 pr-4 text-amber-800"><?php echo htmlspecialchars($r['tempat'] ?? ''); ?></td>
                        <td class="py-2 pr-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs border <?php echo ($r['status'] === 'berlangsung' ? 'bg-green-50 text-green-700 border-green-300' : 'bg-amber-50 text-amber-700 border-amber-300'); ?>">
                                <?php echo htmlspecialchars($r['status']); ?>
                            </span>
                        </td>
                        <td class="py-2 text-right space-x-2">
                            <a href="form.php?id=<?php echo (int)$r['id']; ?>" class="px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700"><i class="fas fa-edit"></i></a>
                            <a href="hapus.php?id=<?php echo (int)$r['id']; ?>" class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700" onclick="return confirm('Hapus kegiatan ini?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p class="text-amber-800">Belum ada data kegiatan.</p>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
