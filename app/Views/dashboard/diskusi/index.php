<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-slate-800">Moderasi Diskusi Jemaat</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">No</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Penulis</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Topik</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Tanggal</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600">Status</th>
                    <th class="py-3 px-4 text-sm font-semibold text-slate-600 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($topics)): ?>
                    <tr>
                        <td colspan="6" class="py-4 text-center text-slate-500 text-sm">Belum ada topik diskusi.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($topics as $t): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-800 text-sm"><?= $no++ ?></td>
                        <td class="py-3 px-4 text-slate-800 font-bold"><?= esc($t['penulis']) ?></td>
                        <td class="py-3 px-4 text-slate-800 font-medium">
                            <div class="truncate max-w-xs" title="<?= $t['judul'] ?>"><?= esc($t['judul']) ?></div>
                            <p class="text-[10px] text-slate-400 mt-1 line-clamp-1 italic"><?= strip_tags($t['isi']) ?></p>
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-sm"><?= date('d/m/Y', strtotime($t['created_at'])) ?></td>
                        <td class="py-3 px-4 text-sm">
                            <a href="<?= base_url('dashboard/diskusi/update_status/'.$t['id_diskusi']) ?>" class="px-3 py-1 rounded-full text-xs font-bold transition-all <?= $t['status'] == 'aktif' ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' ?>">
                                <?= $t['status'] == 'aktif' ? 'Aktif' : 'Nonaktif' ?>
                            </a>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="<?= base_url('dashboard/diskusi/replies/'.$t['id_diskusi']) ?>" class="p-1.5 bg-indigo-100 text-indigo-600 rounded hover:bg-indigo-200 transition flex items-center space-x-1 px-3">
                                    <ion-icon name="chatbubbles-outline"></ion-icon>
                                    <span class="text-[10px] font-bold">REPLIES</span>
                                </a>
                                <a href="<?= base_url('dashboard/diskusi/delete_topic/'.$t['id_diskusi']) ?>" class="p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition" onclick="return confirm('Yakin ingin menghapus topik ini beserta semua jawabannya?')">
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
