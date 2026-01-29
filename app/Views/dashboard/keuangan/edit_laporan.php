<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-full mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
        <div class="mb-6 border-b border-slate-100 pb-4">
            <h3 class="text-lg font-bold text-slate-800">Edit Laporan Keuangan</h3>
        </div>

        <form action="<?= base_url('dashboard/keuangan/update_laporan/'.$keuangan['id']) ?>" method="post">
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Tanggal</label>
                <input type="date" name="tanggal" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required value="<?= $keuangan['tanggal'] ?>">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Keterangan</label>
                    <input type="text" name="keterangan" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" required value="<?= $keuangan['keterangan'] ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Reff (Opsional)</label>
                    <input type="text" name="reff" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" value="<?= $keuangan['reff'] ?>">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Debet / Masuk (Rp)</label>
                    <input type="number" name="debet" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" value="<?= (int)$keuangan['debet'] ?>">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Kredit / Keluar (Rp)</label>
                    <input type="number" name="kredit" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none transition" value="<?= (int)$keuangan['kredit'] ?>">
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="<?= base_url('dashboard/keuangan') ?>" class="px-5 py-2.5 bg-slate-100 text-slate-600 font-medium rounded-lg hover:bg-slate-200 transition">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
