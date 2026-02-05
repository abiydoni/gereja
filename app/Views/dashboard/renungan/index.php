<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-slate-800">Daftar Renungan</h3>
        <a href="<?= base_url('dashboard/renungan/create') ?>" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition flex items-center">
            <ion-icon name="add-outline" class="mr-2 text-lg"></ion-icon>
            Tambah Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">No</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Judul</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Status</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($renungan)): ?>
                    <tr>
                        <td colspan="4" class="py-4 text-center text-slate-500 text-sm">Belum ada data renungan.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($renungan as $r): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-800 text-sm"><?= $no++ ?></td>
                        <td class="py-3 px-4 text-slate-800 font-medium">
                            <div class="truncate max-w-xs" title="<?= $r['judul'] ?>"><?= $r['judul'] ?></div>
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-sm"><?= date('d/m/Y', strtotime($r['tanggal'])) ?></td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <label class="toggle-switch">
                                    <input type="checkbox" <?= (strtolower($r['status']) == 'aktif') ? 'checked' : '' ?> 
                                           onchange="toggleStatus('renungan', <?= $r['id_renungan'] ?>, this)">
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label text-[10px] font-bold uppercase <?= (strtolower($r['status']) == 'aktif') ? 'text-emerald-500' : 'text-slate-400' ?>">
                                    <?= (strtolower($r['status']) == 'aktif') ? 'Aktif' : 'Non-Aktif' ?>
                                </span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="<?= base_url('dashboard/renungan/edit/'.$r['id_renungan']) ?>" class="p-1.5 bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200 transition">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>
                                <a href="<?= base_url('dashboard/renungan/delete/'.$r['id_renungan']) ?>" class="p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition btn-delete" data-confirm="Yakin ingin menghapus renungan ini?">
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
