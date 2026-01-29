<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 font-heading">Jadwal Ibadah</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola jadwal ibadah dan petugas pelayanan.</p>
    </div>
    <a href="<?= base_url('dashboard/jadwal_pelayanan/create') ?>" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-0.5 transition-all flex items-center gap-2">
        <ion-icon name="add-circle-outline" class="text-xl"></ion-icon>
        <span>Tambah Jadwal</span>
    </a>
</div>

<?php if(session()->getFlashdata('success')): ?>
<div class="p-4 mb-6 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center gap-3">
    <ion-icon name="checkmark-circle" class="text-xl"></ion-icon>
    <span class="font-bold"><?= session()->getFlashdata('success') ?></span>
</div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
<div class="p-4 mb-6 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center gap-3">
    <ion-icon name="alert-circle" class="text-xl"></ion-icon>
    <span class="font-bold"><?= session()->getFlashdata('error') ?></span>
</div>
<?php endif; ?>

<div class="bg-white rounded-[20px] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
    <!-- Filter Bar (Optional) -->
    <div class="p-5 border-b border-slate-100 flex items-center justify-between gap-4 bg-slate-50/30">
        <div class="relative w-full max-w-xs">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <ion-icon name="search-outline" class="text-slate-400"></ion-icon>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition sm:text-sm" placeholder="Cari jadwal...">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-widest">
                    <th class="py-4 px-6 w-16 text-center">#</th>
                    <th class="py-4 px-6">Waktu & Tanggal</th>
                    <th class="py-4 px-6">Nama Ibadah</th>
                    <th class="py-4 px-6">Tema</th>
                    <th class="py-4 px-6 w-32">Status</th>
                    <th class="py-4 px-6 w-32 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($jadwal)): ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <ion-icon name="calendar-outline" class="text-4xl mb-2 opacity-50"></ion-icon>
                                <p class="text-sm font-medium">Belum ada jadwal ibadah.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($jadwal as $j): ?>
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="py-4 px-6 text-center text-slate-400 text-sm font-medium"><?= $no++ ?></td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col">
                                <span class="text-slate-800 font-bold text-sm"><?= date('d M Y', strtotime($j['tanggal'])) ?></span>
                                <span class="text-xs text-indigo-600 font-medium"><?= date('H:i', strtotime($j['jam'])) ?> WIB</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-700 font-medium text-sm">
                            <?= $j['nama_ibadah'] ?>
                        </td>
                        <td class="py-4 px-6 text-sm text-slate-500 italic">
                            "<?= $j['tema'] ?: '-' ?>"
                        </td>
                        <td class="py-4 px-6">
                            <?php if($j['status'] === 'aktif'): ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            <?php elseif($j['status'] === 'selesai'): ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-slate-100 text-slate-500 border border-slate-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                    Selesai
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-rose-50 text-rose-600 border border-rose-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                    Batal
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-6 text-right">
                             <div class="flex justify-end gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
                                <a href="<?= base_url('dashboard/jadwal_pelayanan/edit/'.$j['id_jadwal_utama']) ?>" class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-all border border-transparent hover:border-amber-100" title="Edit">
                                    <ion-icon name="create-outline" class="text-lg"></ion-icon>
                                </a>
                                <a href="<?= base_url('dashboard/jadwal_pelayanan/delete/'.$j['id_jadwal_utama']) ?>" class="h-8 w-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all border border-transparent hover:border-rose-100" onclick="return confirm('Apakah anda yakin?')" title="Hapus">
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
</div>

<?= $this->endSection() ?>
