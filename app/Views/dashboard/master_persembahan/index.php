<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Master Jenis Persembahan</h3>
            <p class="text-xs text-slate-400 mt-1">Kelola kategori persembahan yang akan muncul di laporan.</p>
        </div>
        <button type="button" onclick="openModal('add')" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition flex items-center">
            <ion-icon name="add-outline" class="mr-2 text-lg"></ion-icon>
            Tambah Jenis
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 uppercase tracking-wider">
                    <th class="py-3 px-4 text-[10px] font-black text-slate-400 w-16">No</th>
                    <th class="py-3 px-4 text-[10px] font-black text-slate-400">Nama Persembahan</th>
                    <th class="py-3 px-4 text-[10px] font-black text-slate-400">Keterangan</th>
                    <th class="py-3 px-4 text-[10px] font-black text-slate-400">Status</th>
                    <th class="py-3 px-4 text-[10px] font-black text-slate-400 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($jenis)): ?>
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500 text-sm italic">Belum ada data jenis persembahan.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($jenis as $j): ?>
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="py-3 px-4 text-slate-400 text-xs font-bold"><?= $no++ ?></td>
                        <td class="py-3 px-4">
                            <span class="text-sm font-bold text-slate-700"><?= $j['nama_persembahan'] ?></span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-xs text-slate-500"><?= $j['keterangan'] ?: '-' ?></span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <label class="toggle-switch">
                                    <input type="checkbox" <?= (strtolower($j['status']) == 'aktif') ? 'checked' : '' ?> 
                                           onchange="toggleStatus('master_persembahan', <?= $j['id_jenis'] ?>, this)">
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label text-[10px] font-bold uppercase <?= (strtolower($j['status']) == 'aktif') ? 'text-emerald-500' : 'text-slate-400' ?>">
                                    <?= (strtolower($j['status']) == 'aktif') ? 'Aktif' : 'Non-Aktif' ?>
                                </span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex justify-end space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" 
                                        onclick="openModal('edit', <?= htmlspecialchars(json_encode($j)) ?>)"
                                        class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition"
                                        title="Edit">
                                    <ion-icon name="create-outline"></ion-icon>
                                </button>
                                <a href="<?= base_url('dashboard/master_persembahan/delete/'.$j['id_jenis']) ?>" 
                                   class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition btn-delete" 
                                   data-confirm="Yakin ingin menghapus jenis persembahan ini?"
                                   title="Hapus">
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

<!-- Modal -->
<div id="modal-jenis" class="fixed inset-0 z-[99] hidden">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-95 opacity-0 duration-200" id="modal-content">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 id="modal-title" class="text-lg font-bold text-slate-800">Tambah Jenis Persembahan</h3>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <ion-icon name="close-outline" class="text-2xl"></ion-icon>
                </button>
            </div>
            <form id="form-jenis" method="post" action="<?= base_url('dashboard/master_persembahan/store') ?>" class="p-6 space-y-4">
                <input type="hidden" name="id_jenis" id="id_jenis">
                
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Persembahan</label>
                    <input type="text" name="nama_persembahan" id="nama_persembahan" required
                           class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm font-medium"
                           placeholder="Contoh: Persembahan Minggu">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm font-medium resize-none"
                              placeholder="Penjelasan singkat..."></textarea>
                </div>

                <div id="status-container" class="hidden">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Status</label>
                    <select name="status" id="status" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all text-sm font-medium">
                        <option value="aktif">Aktif</option>
                        <option value="non-aktif">Non-Aktif</option>
                    </select>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all flex items-center justify-center gap-2">
                        <ion-icon name="save-outline"></ion-icon>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(mode, data = null) {
    const modal = document.getElementById('modal-jenis');
    const content = document.getElementById('modal-content');
    const title = document.getElementById('modal-title');
    const form = document.getElementById('form-jenis');
    const statusContainer = document.getElementById('status-container');

    if (mode === 'add') {
        title.innerText = 'Tambah Jenis Persembahan';
        form.action = '<?= base_url('dashboard/master_persembahan/store') ?>';
        form.reset();
        document.getElementById('id_jenis').value = '';
        statusContainer.classList.add('hidden');
    } else {
        title.innerText = 'Edit Jenis Persembahan';
        form.action = '<?= base_url('dashboard/master_persembahan/update') ?>/' + data.id_jenis;
        document.getElementById('id_jenis').value = data.id_jenis;
        document.getElementById('nama_persembahan').value = data.nama_persembahan;
        document.getElementById('keterangan').value = data.keterangan;
        document.getElementById('status').value = data.status;
        statusContainer.classList.remove('hidden');
    }

    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('modal-jenis');
    const content = document.getElementById('modal-content');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

window.onclick = function(event) {
    const modal = document.getElementById('modal-jenis');
    if (event.target === modal.querySelector('.absolute.inset-0.bg-slate-900\\/40')) {
        closeModal();
    }
}
</script>

<?= $this->endSection() ?>
