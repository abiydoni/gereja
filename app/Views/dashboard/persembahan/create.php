<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-full mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="mb-6 border-b border-slate-100 pb-4">
            <h3 class="text-lg font-bold text-slate-800">Input Persembahan Ibadah</h3>
        </div>

        <form action="<?= base_url('dashboard/persembahan/store') ?>" method="post">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal Ibadah</label>
                    <input type="date" name="tanggal" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required value="<?= date('Y-m-d') ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Jenis Persembahan</label>
                    <select name="judul" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required>
                        <option value="">-- Pilih Jenis --</option>
                        <?php foreach($master_jenis as $m): ?>
                            <option value="<?= $m['nama_persembahan'] ?>"><?= $m['nama_persembahan'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Jumlah Persembahan (Rp)</label>
                    <input type="number" name="jumlah" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required placeholder="0">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Kehadiran Pria</label>
                    <input type="number" name="jumlah_pria" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Kehadiran Wanita</label>
                    <input type="number" name="jumlah_wanita" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="0">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <option value="aktif">Tampilkan di Website</option>
                        <option value="tidak aktif">Sembunyikan</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Catatan Tambahan (Opsional)</label>
                <textarea id="editor" name="deskripsi" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="Contoh: Keterangan lebih lanjut tentang persembahan ini..."></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="<?= base_url('dashboard/persembahan') ?>" class="px-5 py-2.5 bg-slate-100 text-slate-600 font-medium rounded-lg hover:bg-slate-200 transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#editor',
        language: 'id',
        language_url: 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/id.js',
        plugins: 'advlist anchor autolink charmap code codesample emoticons fullscreen help image insertdatetime link lists media preview searchreplace table visualblocks wordcount',
        menubar: 'edit insert view format table tools help',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | align lineheight | numlist bullist indent outdent | link image table emoticons charmap | removeformat code fullscreen',
        toolbar_mode: 'sliding',
        height: 300,
        branding: false,
        promotion: false,
        content_style: 'body { font-family:Inter,ui-sans-serif,system-ui,-apple-system,sans-serif; font-size:16px }'
    });

    // Validasi Kehadiran
    const tanggalInput = document.querySelector('input[name="tanggal"]');
    const priaInput = document.querySelector('input[name="jumlah_pria"]');
    const wanitaInput = document.querySelector('input[name="jumlah_wanita"]');
    const kehadiranContainer = priaInput.closest('.grid');

    async function checkKehadiran(tgl) {
        if(!tgl) return;
        try {
            const response = await fetch(`<?= base_url('dashboard/persembahan/check-kehadiran') ?>?tanggal=${tgl}`);
            const result = await response.json();
            
            if(result.status && result.data.is_filled) {
                priaInput.disabled = true;
                wanitaInput.disabled = true;
                priaInput.value = '';
                wanitaInput.value = '';
                priaInput.placeholder = 'Sudah diisi';
                wanitaInput.placeholder = 'Sudah diisi';
                
                // Show Warning Message
                if(!document.getElementById('kehadiran-warning')) {
                    const warning = document.createElement('div');
                    warning.id = 'kehadiran-warning';
                    warning.className = 'col-span-1 md:col-span-2 text-xs text-amber-600 bg-amber-50 p-3 rounded-lg border border-amber-100 flex items-center gap-2 mt-2';
                    warning.innerHTML = `<ion-icon name="alert-circle" class="text-lg"></ion-icon> <span>Data kehadiran untuk tanggal <b>${tgl}</b> sudah tercatat (Total: ${result.data.total}). Tidak perlu diisi lagi.</span>`;
                    kehadiranContainer.appendChild(warning);
                } else {
                    document.getElementById('kehadiran-warning').querySelector('span').innerHTML = `Data kehadiran untuk tanggal <b>${tgl}</b> sudah tercatat (Total: ${result.data.total}). Tidak perlu diisi lagi.`;
                }
            } else {
                priaInput.disabled = false;
                wanitaInput.disabled = false;
                priaInput.placeholder = '0';
                wanitaInput.placeholder = '0';
                const warning = document.getElementById('kehadiran-warning');
                if(warning) warning.remove();
            }
        } catch(e) {
            console.error('Error checking kehadiran:', e);
        }
    }

    // Check on Load
    if(tanggalInput.value) {
        checkKehadiran(tanggalInput.value);
    }

    // Check on Change
    tanggalInput.addEventListener('change', (e) => {
        checkKehadiran(e.target.value);
    });
</script>
<?= $this->endSection() ?>
