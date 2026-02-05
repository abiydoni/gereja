<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-slate-800">Manajemen Galeri Multimedia</h3>
        <a href="<?= base_url('dashboard/galeri/create') ?>" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition flex items-center">
            <ion-icon name="add-outline" class="mr-2 text-lg"></ion-icon>
            Tambah Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">No</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Kategori</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Judul / Preview</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Link ID</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Status</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($items)): ?>
                    <tr>
                        <td colspan="6" class="py-4 text-center text-slate-500 text-sm">Belum ada item galeri.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($items as $item): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-800 text-sm"><?= $no++ ?></td>
                        <td class="py-3 px-4">
                            <?php if($item['kategori'] == 'youtube'): ?>
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold uppercase flex w-fit items-center"><ion-icon name="logo-youtube" class="mr-1"></ion-icon> YouTube</span>
                            <?php elseif($item['kategori'] == 'drive_img'): ?>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase flex w-fit items-center"><ion-icon name="images-outline" class="mr-1"></ion-icon> Drive IMG</span>
                            <?php elseif($item['kategori'] == 'drive_audio'): ?>
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-bold uppercase flex w-fit items-center"><ion-icon name="musical-notes-outline" class="mr-1"></ion-icon> Drive Audio</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-slate-800 font-medium">
                            <div class="flex items-center space-x-3">
                                <?php if($item['kategori'] == 'youtube'): ?>
                                    <img src="https://img.youtube.com/vi/<?= $item['link_media'] ?>/default.jpg" class="h-10 w-14 object-cover rounded shadow-sm">
                                <?php else: ?>
                                    <div class="h-10 w-14 bg-slate-100 rounded flex items-center justify-center text-slate-400">
                                        <ion-icon name="folder-open-outline" class="text-xl"></ion-icon>
                                    </div>
                                <?php endif; ?>
                                <div class="truncate max-w-xs" title="<?= $item['judul'] ?>"><?= esc($item['judul']) ?></div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-sm">
                            <code class="bg-slate-100 px-2 py-1 rounded max-w-[100px] truncate block" title="<?= $item['link_media'] ?>"><?= $item['link_media'] ?></code>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <label class="toggle-switch">
                                    <input type="checkbox" <?= (strtolower($item['status']) == 'aktif') ? 'checked' : '' ?> 
                                           onchange="toggleStatus('galeri', <?= $item['id_galeri'] ?>, this)">
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label text-[10px] font-bold uppercase <?= (strtolower($item['status']) == 'aktif') ? 'text-emerald-500' : 'text-slate-400' ?>">
                                    <?= (strtolower($item['status']) == 'aktif') ? 'Aktif' : 'Non-Aktif' ?>
                                </span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="<?= base_url('dashboard/galeri/edit/'.$item['id_galeri']) ?>" class="p-1.5 bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200 transition">
                                    <ion-icon name="create-outline"></ion-icon>
                                </a>
                                <a href="<?= base_url('dashboard/galeri/delete/'.$item['id_galeri']) ?>" class="p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition btn-delete" data-confirm="Yakin ingin menghapus item galeri ini?">
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
