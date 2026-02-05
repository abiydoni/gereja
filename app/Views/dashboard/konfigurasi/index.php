<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-full mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Konfigurasi Tampilan Frontend</h3>
                <p class="text-xs text-slate-500 mt-1">Atur visibilitas menu dan fitur pada website utama.</p>
            </div>
            <div class="p-2 bg-indigo-50 rounded-lg">
                <ion-icon name="settings-outline" class="text-xl text-indigo-600 block"></ion-icon>
            </div>
        </div>

        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider w-16 text-center">No</th>
                            <th class="px-6 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Fitur / Menu</th>
                            <th class="px-6 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php $no = 1; foreach($konfigurasi as $k): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-1.5 text-center text-[11px] font-medium text-slate-400"><?= $no++ ?></td>
                            <td class="px-6 py-1.5">
                                <div class="flex flex-col">
                                    <span class="text-[11px] md:text-sm font-bold text-slate-700 leading-tight"><?= $k['label'] ?></span>
                                    <span class="text-[9px] text-slate-400 font-mono uppercase tracking-tighter"><?= $k['slug'] ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-1.5">
                                <span class="px-1.5 py-0.5 rounded text-[8px] md:text-[9px] font-bold uppercase tracking-wider <?= $k['group'] == 'menu' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' ?>">
                                    <?= $k['group'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-1.5 text-right">
                                <label class="relative inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" value="" class="sr-only peer" <?= $k['status'] == 'aktif' ? 'checked' : '' ?> 
                                           onchange="toggleConfig(<?= $k['id'] ?>, this)">
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-100 flex items-start space-x-3">
        <ion-icon name="alert-circle" class="text-xl text-amber-500 shrink-0 mt-0.5"></ion-icon>
        <p class="text-xs text-amber-700 leading-relaxed font-medium">
            <span class="font-bold">Catatan:</span> Perubahan pada konfigurasi ini akan langsung berdampak pada tampilan website utama. Pastikan Anda tidak menonaktifkan menu krusial yang sedang digunakan.
        </p>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleConfig(id, element) {
    const loader = Swal.fire({
        title: 'Memproses...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`<?= base_url('dashboard/konfigurasi/toggle') ?>/${id}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.status === 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: data.message
            });
        } else {
            element.checked = !element.checked; // Revert checkbox
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        element.checked = !element.checked; // Revert checkbox
        Swal.close();
        Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
    });
}
</script>
<?= $this->endSection() ?>
