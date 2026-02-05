<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-slate-800">Daftar Majelis</h3>
        <a href="<?= base_url('dashboard/majelis/create') ?>" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition flex items-center">
            <ion-icon name="add-outline" class="mr-2 text-lg"></ion-icon>
            Tambah Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">No</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Nama</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Jabatan & Bidang</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">No HP</th>
                     <th class="py-3 px-4 text-sm font-semibold text-slate-600">Foto</th>
                     <th class="py-3 px-4 text-sm font-semibold text-slate-600">Status</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($majelis)): ?>
                    <tr>
                        <td colspan="6" class="py-4 text-center text-slate-500 text-sm">Belum ada data majelis.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($majelis as $m): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-800 text-sm"><?= $no++ ?></td>
                        <td class="py-3 px-4 text-slate-800 font-medium">
                            <?= $m['nama'] ?>
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-sm">
                            <div class="font-medium text-indigo-600"><?= $m['jabatan'] ?></div>
                            <div class="text-xs text-slate-500"><?= $m['bidang'] ?></div>
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-sm"><?= $m['no_hp'] ?></td>
                        <td class="py-3 px-4 text-slate-600 text-sm">
                             <?php if($m['foto']): ?>
                                <img src="<?= base_url('uploads/majelis/'.$m['foto']) ?>" class="h-10 w-10 rounded-full object-cover">
                            <?php else: ?>
                                <div class="h-10 w-10 bg-slate-200 rounded-full flex items-center justify-center text-slate-500 text-xs">No</div>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <label class="toggle-switch">
                                    <input type="checkbox" <?= (strtolower($m['status']) == 'aktif') ? 'checked' : '' ?> 
                                           onchange="toggleStatus('majelis', <?= $m['id_majelis'] ?>, this)">
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label text-[10px] font-bold uppercase <?= (strtolower($m['status']) == 'aktif') ? 'text-emerald-500' : 'text-slate-400' ?>">
                                    <?= (strtolower($m['status']) == 'aktif') ? 'Aktif' : 'Non-Aktif' ?>
                                </span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="<?= base_url('dashboard/majelis/edit/'.$m['id_majelis']) ?>" class="p-1.5 bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200 transition">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>
                                <a href="<?= base_url('dashboard/majelis/delete/'.$m['id_majelis']) ?>" class="p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition btn-delete" data-confirm="Yakin ingin menghapus data majelis ini?">
                                    <ion-icon name="trash-outline"></ion-icon>
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
