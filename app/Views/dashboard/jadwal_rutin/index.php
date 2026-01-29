<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="mb-5 flex justify-between items-center">
    <div>
        <h1 class="font-heading font-extrabold text-2xl text-slate-800">Manajemen Jadwal Rutin</h1>
        <p class="text-slate-500">Kelola informasi jadwal ibadah rutin mingguan.</p>
    </div>
    <a href="/dashboard/jadwal_rutin/create" class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary-dark transition-colors shadow-lg shadow-primary/30 flex items-center gap-2">
        <ion-icon name="add-circle-outline" class="text-xl"></ion-icon>
        Tambah Jadwal
    </a>
</div>

<?php if(session()->getFlashdata('success')): ?>
<div class="p-4 mb-6 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center gap-3">
    <ion-icon name="checkmark-circle" class="text-xl"></ion-icon>
    <span class="font-bold"><?= session()->getFlashdata('success') ?></span>
</div>
<?php endif; ?>

<div class="bg-white rounded-[20px] shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-bold">
                    <th class="p-5">Hari & Jam</th>
                    <th class="p-5">Nama Ibadah</th>
                    <th class="p-5">Lokasi</th>
                    <th class="p-5">Status</th>
                    <th class="p-5 text-right bg-slate-50 sticky right-0">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(!empty($jadwal)): ?>
                    <?php foreach($jadwal as $j): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="p-5 text-slate-700">
                            <div class="font-bold"><?= $j['hari'] ?></div>
                            <div class="text-sm text-slate-500"><?= date('H:i', strtotime($j['jam'])) ?> WIB</div>
                        </td>
                        <td class="p-5">
                            <span class="font-bold text-slate-800"><?= $j['nama_ibadah'] ?></span>
                        </td>
                         <td class="p-5 text-slate-600">
                            <?= $j['lokasi'] ?>
                        </td>
                        <td class="p-5">
                            <?php if($j['status'] === 'aktif'): ?>
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">Aktif</span>
                            <?php else: ?>
                                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">Non-Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-5 text-right sticky right-0 bg-white group-hover:bg-slate-50/50">
                            <div class="flex items-center justify-end gap-2">
                                <a href="/dashboard/jadwal_rutin/edit/<?= $j['id_jadwal'] ?>" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-colors" title="Edit">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>
                                <a href="/dashboard/jadwal_rutin/delete/<?= $j['id_jadwal'] ?>" onclick="return confirm('Apakah anda yakin ingin menghapus jadwal ini?')" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors" title="Hapus">
                                    <ion-icon name="trash-outline"></ion-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="p-10 text-center text-slate-400">Belum ada data jadwal rutin.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
