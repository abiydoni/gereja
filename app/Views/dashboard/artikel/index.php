<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 font-heading">Artikel</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola konten berita dan renungan gereja.</p>
    </div>
    <a href="<?= base_url('dashboard/artikel/create') ?>" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-0.5 transition-all flex items-center gap-2">
        <ion-icon name="add-circle-outline" class="text-xl"></ion-icon>
        <span>Buat Artikel</span>
    </a>
</div>

<div class="bg-white rounded-[20px] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
    <!-- Filter Bar (Optional) -->
    <div class="p-5 border-b border-slate-100 flex items-center justify-between gap-4 bg-slate-50/30">
        <div class="relative w-full max-w-xs">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <ion-icon name="search-outline" class="text-slate-400"></ion-icon>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition sm:text-sm" placeholder="Cari artikel...">
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sort:</span>
            <select class="form-select block w-full pl-3 pr-10 py-1.5 text-base border-slate-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-xs rounded-lg">
                <option>Terbaru</option>
                <option>Terlama</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-widest">
                    <th class="py-4 px-6 w-16 text-center">#</th>
                    <th class="py-4 px-6 w-24">Cover</th>
                    <th class="py-4 px-6">Info Artikel</th>
                    <th class="py-4 px-6 w-40">Penulis</th>
                    <th class="py-4 px-6 w-40">Status</th>
                    <th class="py-4 px-6 w-32 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($artikels)): ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <ion-icon name="document-text-outline" class="text-4xl mb-2 opacity-50"></ion-icon>
                                <p class="text-sm font-medium">Belum ada artikel yang dibuat.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($artikels as $a): ?>
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="py-4 px-6 text-center text-slate-400 text-sm font-medium"><?= $no++ ?></td>
                        <td class="py-4 px-6">
                            <div class="h-12 w-16 rounded-lg overflow-hidden border border-slate-100 shadow-sm relative shrink-0">
                                <?php if ($a['gambar']): ?>
                                    <img src="<?= base_url('uploads/artikel/'.$a['gambar']) ?>" class="h-full w-full object-cover">
                                <?php else: ?>
                                    <div class="h-full w-full bg-slate-50 flex items-center justify-center text-slate-300">
                                        <ion-icon name="image" class="text-lg"></ion-icon>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col max-w-sm">
                                <span class="text-slate-800 font-bold text-sm line-clamp-1 group-hover:text-indigo-600 transition-colors" title="<?= $a['judul'] ?>"><?= $a['judul'] ?></span>
                                <span class="text-[11px] text-slate-400 mt-0.5 font-mono truncate"><?= $a['slug'] ?></span>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <span class="text-[10px] text-slate-400 flex items-center gap-1 bg-slate-100 px-1.5 py-0.5 rounded">
                                        <ion-icon name="calendar-outline"></ion-icon>
                                        <?= date('d M Y', strtotime($a['created_at'])) ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <div class="h-6 w-6 rounded-full bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center text-[10px] font-bold text-white uppercase shadow-sm">
                                    <?= substr($a['penulis'], 0, 1) ?>
                                </div>
                                <span class="text-sm text-slate-600 font-medium"><?= $a['penulis'] ?></span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <label class="toggle-switch">
                                    <input type="checkbox" <?= (strtolower($a['status']) == 'aktif') ? 'checked' : '' ?> 
                                           onchange="toggleStatus('artikel', <?= $a['id_artikel'] ?>, this)">
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label text-[10px] font-bold uppercase <?= (strtolower($a['status']) == 'aktif') ? 'text-emerald-500' : 'text-slate-400' ?>">
                                    <?= (strtolower($a['status']) == 'aktif') ? 'Published' : 'Draft' ?>
                                </span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex justify-end gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
                                <a href="<?= base_url('artikel/'.$a['slug']) ?>" target="_blank" class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all border border-transparent hover:border-indigo-100" title="Lihat">
                                    <ion-icon name="open-outline" class="text-lg"></ion-icon>
                                </a>
                                <a href="<?= base_url('dashboard/artikel/edit/'.$a['id_artikel']) ?>" class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-all border border-transparent hover:border-amber-100" title="Edit">
                                    <ion-icon name="create-outline" class="text-lg"></ion-icon>
                                </a>
                                <a href="<?= base_url('dashboard/artikel/delete/'.$a['id_artikel']) ?>" class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all border border-transparent hover:border-rose-100 btn-delete" data-confirm="Yakin ingin menghapus artikel ini?" title="Hapus">
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
    
    <!-- Pagination (Placeholder) -->
    <div class="p-4 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between">
        <span class="text-xs text-slate-500">Menampilkan <?= count($artikels) ?> artikel</span>
        <!-- Add pagination links if available later -->
    </div>
</div>

<?= $this->endSection() ?>
