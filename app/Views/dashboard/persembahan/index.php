<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="space-y-8">
    
    <!-- Data Persembahan Ibadah -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Manajemen Persembahan Ibadah</h3>
                <p class="text-xs text-slate-500 mt-1">Kelola data persembahan jemaat berdasarkan tanggal ibadah.</p>
            </div>
            <a href="<?= base_url('dashboard/persembahan/create') ?>" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition flex items-center">
                <ion-icon name="add-outline" class="mr-2 text-lg"></ion-icon>
                Input Persembahan
            </a>
        </div>

        <!-- Filter & Summary Row -->
        <div class="flex flex-col lg:flex-row gap-6 mb-8">
            <!-- Filter Form -->
            <div class="flex-1 bg-slate-50 rounded-2xl p-4 border border-slate-100">
                <form action="<?= base_url('dashboard/persembahan') ?>" method="get" class="flex flex-col md:flex-row items-end gap-3">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Cari Nama Ibadah</label>
                        <div class="relative">
                            <input type="text" name="search" value="<?= $search ?>" placeholder="Cari nama atau deskripsi..." 
                                   class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                            <ion-icon name="search-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></ion-icon>
                        </div>
                    </div>
                    <div class="w-full md:w-36">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Bulan</label>
                        <select name="bulan" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                            <option value="">Semua Bulan</option>
                            <?php
                            $months = [
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            ];
                            foreach ($months as $m => $name): ?>
                                <option value="<?= $m ?>" <?= ($bulan == $m) ? 'selected' : '' ?>><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="w-full md:w-28">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Tahun</label>
                        <select name="tahun" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                            <option value="">Semua</option>
                            <?php 
                            $currentYear = date('Y');
                            for ($y = $currentYear; $y >= $currentYear - 5; $y--): ?>
                                <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 flex items-center justify-center">
                            <ion-icon name="filter-outline" class="text-xl"></ion-icon>
                        </button>
                        <?php if ($bulan || $tahun || $search): ?>
                            <a href="<?= base_url('dashboard/persembahan') ?>" class="p-2.5 bg-white border border-slate-200 text-slate-500 rounded-xl hover:bg-slate-100 transition flex items-center justify-center">
                                <ion-icon name="refresh-outline" class="text-xl"></ion-icon>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Summary Stats Card -->
            <div class="w-full lg:w-72 p-4 bg-indigo-50 rounded-2xl border border-indigo-100 flex items-center">
                <div class="flex items-center space-x-3 w-full">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xl shrink-0">
                        <ion-icon name="stats-chart"></ion-icon>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-none mb-1">Total Terkumpul</p>
                        <p class="text-xl font-extrabold text-indigo-600 leading-none">Rp <?= number_format($total_terkumpul, 0, ',', '.') ?></p>
                        <p class="text-[9px] text-slate-400 mt-1 uppercase font-bold tracking-tighter">*Sesuai Filter</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600">Tanggal</th>
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600">Nama Ibadah / Kategori</th>
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600 text-center">Kehadiran (P/W)</th>
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600 text-right">Jumlah (Rp)</th>
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600">Status</th>
                        <th class="py-3 px-4 text-sm font-semibold text-slate-600 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($persembahan)): ?>
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-2 text-slate-400">
                                    <ion-icon name="search-outline" class="text-4xl"></ion-icon>
                                    <p class="text-sm italic">Belum ada data persembahan atau hasil tidak ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($persembahan as $p): ?>
                        <tr class="hover:bg-slate-50 group">
                            <td class="py-4 px-4 text-slate-600 text-sm">
                                <?= !empty($p['tanggal']) ? date('d/m/Y', strtotime($p['tanggal'])) : '-' ?>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-slate-800 font-bold"><?= $p['judul'] ?></div>
                                <div class="text-[10px] text-slate-400 italic truncate max-w-[200px]"><?= $p['deskripsi'] ?></div>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <?php if($p['jumlah_pria'] > 0 || $p['jumlah_wanita'] > 0): ?>
                                    <div class="flex items-center justify-center space-x-2 text-xs font-bold">
                                        <span class="text-blue-600 bg-blue-50 px-2 py-1 rounded" title="Pria">P: <?= $p['jumlah_pria'] ?></span>
                                        <span class="text-pink-600 bg-pink-50 px-2 py-1 rounded" title="Wanita">W: <?= $p['jumlah_wanita'] ?></span>
                                    </div>
                                    <div class="text-[9px] text-slate-400 mt-1 font-bold">Total: <?= $p['jumlah_pria'] + $p['jumlah_wanita'] ?></div>
                                <?php else: ?>
                                    <span class="text-xs text-slate-300">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4 text-right font-mono text-indigo-600 font-extrabold text-lg">
                                <?= number_format($p['jumlah'], 0, ',', '.') ?>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <label class="toggle-switch">
                                        <input type="checkbox" <?= (strtolower($p['status']) == 'aktif') ? 'checked' : '' ?> 
                                               onchange="toggleStatus('informasi_persembahan', <?= $p['id_persembahan'] ?>, this)">
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span class="toggle-label text-[10px] font-bold uppercase <?= (strtolower($p['status']) == 'aktif') ? 'text-emerald-500' : 'text-slate-400' ?>">
                                        <?= (strtolower($p['status']) == 'aktif') ? 'Aktif' : 'Hidden' ?>
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex justify-end items-center space-x-2">
                                    <?php if (!$p['is_posted']): ?>
                                        <!-- Post Button -->
                                        <button type="button"
                                           onclick="confirmPosting('<?= $p['id_persembahan'] ?>', '<?= addslashes($p['judul']) ?>')"
                                           class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-lg hover:bg-indigo-600 hover:text-white transition-all flex items-center">
                                            <ion-icon name="paper-plane-outline" class="mr-1.5 text-sm"></ion-icon>
                                            POSTING
                                        </button>

                                        <!-- Edit/Delete for unposted -->
                                        <div class="flex space-x-1 ml-2 border-l border-slate-100 pl-2">
                                            <a href="<?= base_url('dashboard/persembahan/edit/'.$p['id_persembahan']) ?>" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                                <ion-icon name="create-outline" class="text-lg"></ion-icon>
                                            </a>
                                            <a href="<?= base_url('dashboard/persembahan/delete/'.$p['id_persembahan']) ?>" 
                                               class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" 
                                               onclick="return confirm('Hapus data persembahan ini?')" title="Hapus">
                                                <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <!-- Locked Icon for posted -->
                                        <div class="flex items-center text-slate-300 px-3 py-1.5" title="Data sudah terkunci (posted)">
                                            <ion-icon name="lock-closed-outline" class="text-sm mr-1"></ion-icon>
                                            <span class="text-[10px] font-bold uppercase tracking-widest">LOCKED</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            <?= $pager->links('persembahan', 'default_full') ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmPosting(id, judul) {
    Swal.fire({
        title: 'Posting Persembahan',
        html: `Anda akan memposting <b>${judul}</b> ke Buku Kas/Keuangan.<br><br>Pilih akun tujuan:`,
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: '<ion-icon name="wallet-outline" class="mr-1"></ion-icon> KAS',
        denyButtonText: '<ion-icon name="card-outline" class="mr-1"></ion-icon> BANK',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#0F172A',
        denyButtonColor: '#4F46E5',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // User choosing KAS
            window.location.href = `<?= base_url('dashboard/persembahan/post') ?>/${id}?reff=KAS`;
        } else if (result.isDenied) {
            // User choosing BANK
            window.location.href = `<?= base_url('dashboard/persembahan/post') ?>/${id}?reff=BANK`;
        }
    });
}
</script>
<?= $this->endSection() ?>
