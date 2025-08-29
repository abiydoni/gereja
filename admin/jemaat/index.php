<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../partials/header.php';

// Cek login admin
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

?>
    <?php
    $listJemaat = [];
    $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $perPage = 20;
    $totalRows = 0;

    try {
        $db = new Database();
        // Count total
        if ($q !== '') {
            $db->query("SELECT COUNT(*) as total FROM jemaat WHERE id LIKE :kw OR nij LIKE :kw OR nama_lengkap LIKE :kw OR email LIKE :kw OR no_telepon LIKE :kw");
            $db->bind(':kw', '%' . $q . '%');
        } else {
            $db->query("SELECT COUNT(*) as total FROM jemaat");
        }
        $totalRows = (int)($db->single()->total ?? 0);

        // Fetch page
        $offset = ($page - 1) * $perPage;
        if ($q !== '') {
            $db->query("SELECT id, nij, nama_lengkap, jenis_kelamin, status_jemaat, no_telepon, email
                        FROM jemaat
                        WHERE id LIKE :kw OR nij LIKE :kw OR nama_lengkap LIKE :kw OR email LIKE :kw OR no_telepon LIKE :kw
                        ORDER BY nama_lengkap ASC
                        LIMIT :limit OFFSET :offset");
            $db->bind(':kw', '%' . $q . '%');
        } else {
            $db->query("SELECT id, nij, nama_lengkap, jenis_kelamin, status_jemaat, no_telepon, email
                        FROM jemaat
                        ORDER BY nama_lengkap ASC
                        LIMIT :limit OFFSET :offset");
        }
        // Bind ints
        $db->bind(':limit', (int)$perPage);
        $db->bind(':offset', (int)$offset);
        $listJemaat = $db->resultSet();
    } catch (Exception $e) {
        $listJemaat = [];
    }

    $totalPages = max(1, (int)ceil($totalRows / $perPage));
    $baseUrl = rtrim(APP_URL,'/') . '/admin/jemaat/';
    ?>
    <div class="max-w-7xl mx-auto px-4 py-8 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">Data Jemaat</h1>
            <a href="<?php echo rtrim(APP_URL,'/'); ?>/admin/jemaat/tambah.php" class="btn-secondary">Tambah Jemaat</a>
        </div>
        <form method="get" class="flex items-center gap-3">
            <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Cari ID/NIJ/Nama/Email/Telepon" class="w-72 rounded-lg border-gray-300 focus:ring-amber-600 focus:border-amber-600">
            <button type="submit" class="btn-primary">Cari</button>
            <?php if ($q !== ''): ?><a href="<?php echo $baseUrl; ?>" class="btn-secondary">Reset</a><?php endif; ?>
        </form>
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-amber-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">NIJ</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">JK</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Telepon</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-amber-900 uppercase tracking-wider">Email</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($listJemaat)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-6 text-center text-gray-500">Belum ada data jemaat</td>
                            </tr>
                        <?php else: foreach ($listJemaat as $row): ?>
                            <tr class="hover:bg-amber-50">
                                <td class="px-6 py-3 text-sm text-gray-700"><?php echo htmlspecialchars($row->id); ?></td>
                                <td class="px-6 py-3 text-sm text-gray-700"><?php echo htmlspecialchars($row->nij); ?></td>
                                <td class="px-6 py-3 text-sm font-medium text-gray-900"><?php echo htmlspecialchars($row->nama_lengkap); ?></td>
                                <td class="px-6 py-3 text-sm text-gray-700"><?php echo htmlspecialchars($row->jenis_kelamin); ?></td>
                                <td class="px-6 py-3 text-sm text-gray-700"><?php echo htmlspecialchars($row->status_jemaat); ?></td>
                                <td class="px-6 py-3 text-sm text-gray-700"><?php echo htmlspecialchars($row->no_telepon ?: '-'); ?></td>
                                <td class="px-6 py-3 text-sm text-gray-700"><?php echo htmlspecialchars($row->email ?: '-'); ?></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex items-center justify-between text-sm text-gray-600">
            <div>
                Menampilkan <?php echo count($listJemaat); ?> dari <?php echo number_format($totalRows); ?> data
            </div>
            <div class="flex items-center gap-1">
                <?php
                $qs = $q !== '' ? ('&q=' . urlencode($q)) : '';
                if ($page > 1) {
                    echo '<a class="px-3 py-1 rounded border" href="'.$baseUrl.'?page='.($page-1).$qs.'">&laquo; Prev</a>';
                }
                // simple window
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                for ($p=$start;$p<=$end;$p++) {
                    $cls = $p===$page ? 'bg-amber-600 text-white' : 'border';
                    echo '<a class="px-3 py-1 rounded '.$cls.'" href="'.$baseUrl.'?page='.$p.$qs.'">'.$p.'</a>';
                }
                if ($page < $totalPages) {
                    echo '<a class="px-3 py-1 rounded border" href="'.$baseUrl.'?page='.($page+1).$qs.'">Next &raquo;</a>';
                }
                ?>
            </div>
        </div>
    </div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
