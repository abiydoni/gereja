<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 font-heading">Data Jemaat</h1>
        <p class="text-slate-500 text-sm mt-1">Manajemen basis data jemaat gereja secara lengkap.</p>
    </div>
    <a href="<?= base_url('dashboard/jemaat/create') ?>" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-0.5 transition-all flex items-center gap-2">
        <ion-icon name="person-add-outline" class="text-xl"></ion-icon>
        <span>Tambah Jemaat</span>
    </a>
</div>

<div class="bg-white rounded-[20px] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
    <!-- Filter Bar -->
    <div class="p-5 border-b border-slate-100 flex items-center justify-between gap-4 bg-slate-50/30">
        <div class="relative w-full max-w-xs">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <ion-icon name="search-outline" class="text-slate-400"></ion-icon>
            </div>
            <input type="text" id="jemaatSearch" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition sm:text-sm" placeholder="Cari jemaat (Nama/NIJ)...">
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Wilayah:</span>
            <select class="form-select block w-full pl-3 pr-10 py-1.5 text-base border-slate-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-xs rounded-lg">
                <option value="">Semua Wilayah</option>
                <!-- Wilayah options could be dynamic -->
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="jemaatTable">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-widest">
                    <th class="py-4 px-6 w-16 text-center">No</th>
                    <th class="py-4 px-6 w-20">Foto</th>
                    <th class="py-4 px-6 w-40">NIJ / NIK</th>
                    <th class="py-4 px-6">Nama Lengkap</th>
                    <th class="py-4 px-6 w-32 text-center">L/P</th>
                    <th class="py-4 px-6 w-40">Wilayah</th>
                    <th class="py-4 px-6 w-32">Status</th>
                    <th class="py-4 px-6 w-32 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($jemaat)): ?>
                    <tr>
                        <td colspan="8" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <ion-icon name="people-outline" class="text-4xl mb-2 opacity-50"></ion-icon>
                                <p class="text-sm font-medium">Belum ada data jemaat.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($jemaat as $j): ?>
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="py-4 px-6 text-center text-slate-400 text-sm font-medium"><?= $no++ ?></td>
                        <td class="py-4 px-6">
                            <div class="h-10 w-10 rounded-full overflow-hidden border border-slate-100 shadow-sm relative shrink-0">
                                <?php if ($j['foto']): ?>
                                    <img src="<?= base_url('uploads/jemaat/'.$j['foto']) ?>" class="h-full w-full object-cover">
                                <?php else: ?>
                                    <div class="h-full w-full bg-slate-50 flex items-center justify-center text-slate-300">
                                        <ion-icon name="person" class="text-xl"></ion-icon>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col">
                                <span class="text-slate-800 font-bold text-sm"><?= $j['nij'] ?: '-' ?></span>
                                <span class="text-[10px] text-slate-400 font-medium"><?= $j['nik'] ?: '-' ?></span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col">
                                <span class="text-slate-900 font-bold text-sm group-hover:text-indigo-600 transition-colors"><?= $j['nama_lengkap'] ?></span>
                                <span class="text-[11px] text-slate-500"><?= $j['nama_panggilan'] ?></span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="text-sm font-medium text-slate-600"><?= $j['jenis_kelamin'] ?></span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-sm text-slate-600 font-medium"><?= $j['wilayah_rayon'] ?: '-' ?></span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <label class="toggle-switch">
                                    <input type="checkbox" <?= (strtolower($j['status_jemaat']) == 'aktif') ? 'checked' : '' ?> 
                                           onchange="toggleStatus('jemaat', <?= $j['id_jemaat'] ?>, this)">
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label text-[10px] font-bold uppercase <?= (strtolower($j['status_jemaat']) == 'aktif') ? 'text-emerald-500' : 'text-slate-400' ?>">
                                    <?= $j['status_jemaat'] ?>
                                </span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex justify-end gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
                                <a href="<?= base_url('dashboard/jemaat/edit/'.$j['id_jemaat']) ?>" class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-all border border-transparent hover:border-amber-100" title="Edit">
                                    <ion-icon name="create-outline" class="text-lg"></ion-icon>
                                </a>
                                <a href="<?= base_url('dashboard/jemaat/delete/'.$j['id_jemaat']) ?>" class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all border border-transparent hover:border-rose-100 btn-delete" data-confirm="Yakin ingin menghapus data jemaat ini? Data yang dihapus tidak dapat dikembalikan." title="Hapus">
                                    <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between">
        <span class="text-xs text-slate-500">Total: <?= count($jemaat) ?> Jemaat</span>
    </div>
</div>

<script>
    document.getElementById('jemaatSearch').addEventListener('keyup', function() {
        let searchValue = this.value.toLowerCase();
        let rows = document.querySelectorAll('#jemaatTable tbody tr');
        
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            if (text.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<?= $this->endSection() ?>
