<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-slate-800">Daftar Kegiatan</h3>
        <a href="<?= base_url('dashboard/kegiatan/create') ?>" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition flex items-center">
            <ion-icon name="add-outline" class="mr-2 text-lg"></ion-icon>
            Tambah Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">No</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Nama Kegiatan</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Waktu</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Status</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($kegiatan)): ?>
                    <tr>
                        <td colspan="5" class="py-4 text-center text-slate-500 text-sm">Belum ada data kegiatan.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($kegiatan as $k): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-800 text-sm"><?= $no++ ?></td>
                        <td class="py-3 px-4 text-slate-800 font-medium">
                            <?= $k['nama_kegiatan'] ?>
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-sm">
                            <div class="flex flex-col">
                                <span><?= date('d/m/Y H:i', strtotime($k['tanggal_mulai'])) ?></span>
                                <span class="text-xs text-slate-400">s/d <?= date('H:i', strtotime($k['tanggal_selesai'])) ?></span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-sm"><?= $k['lokasi'] ?></td>
                        <td class="py-3 px-4 text-sm">
                            <?php if ($k['status'] == 'aktif'): ?>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Aktif</span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="<?= base_url('dashboard/kegiatan/edit/'.$k['id_kegiatan']) ?>" class="p-1.5 bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200 transition">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>
                                <a href="<?= base_url('dashboard/kegiatan/delete/'.$k['id_kegiatan']) ?>" class="p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition" onclick="return confirm('Yakin ingin menghapus?')">
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
