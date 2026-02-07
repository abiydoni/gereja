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
        <div class="px-6 py-4 flex justify-end">
            <a href="<?= base_url('dashboard/konfigurasi/create') ?>" class="px-4 py-2 bg-indigo-600 text-white font-bold text-xs rounded-lg shadow-md shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <ion-icon name="add-circle-outline" class="text-lg"></ion-icon>
                <span>Tambah Konfigurasi</span>
            </a>
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
                            <th class="px-6 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider text-right w-32">Aksi</th>
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
                                </label>
                            </td>
                            <td class="px-6 py-1.5 text-right">
                                <div class="flex justify-end gap-1 opacity-100">
                                    <a href="<?= base_url('dashboard/konfigurasi/edit/'.$k['id']) ?>" class="h-7 w-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-all border border-transparent hover:border-amber-100" title="Edit">
                                        <ion-icon name="create-outline" class="text-base"></ion-icon>
                                    </a>
                                    <button type="button" onclick="confirmDelete(<?= $k['id'] ?>)" class="h-7 w-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all border border-transparent hover:border-rose-100" title="Hapus">
                                        <ion-icon name="trash-outline" class="text-base"></ion-icon>
                                    </button>
                                    <form id="delete-form-<?= $k['id'] ?>" action="<?= base_url('dashboard/konfigurasi/delete/'.$k['id']) ?>" method="post" class="hidden">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                </div>
                            </td>
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

function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Konfigurasi?',
        text: "Konfigurasi ini akan dihapus permanen.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}
</script>
<?= $this->endSection() ?>
