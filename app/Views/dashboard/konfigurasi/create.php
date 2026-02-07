<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 font-heading">Tambah Konfigurasi</h1>
        <p class="text-slate-500 text-sm mt-1">Buat konfigurasi frontend baru.</p>
    </div>
    <a href="<?= base_url('dashboard/konfigurasi') ?>" class="px-4 py-2 bg-white text-slate-600 font-bold text-sm rounded-xl shadow-sm border border-slate-200 hover:bg-slate-50 transition-all flex items-center gap-2">
        <ion-icon name="arrow-back-outline"></ion-icon>
        <span>Kembali</span>
    </a>
</div>

<?php if(session()->getFlashdata('error')): ?>
<div class="p-4 mb-6 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center gap-3">
    <ion-icon name="alert-circle" class="text-xl"></ion-icon>
    <span class="font-bold"><?= session()->getFlashdata('error') ?></span>
</div>
<?php endif; ?>

<div class="bg-white rounded-[20px] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden max-w-2xl">
    <form action="<?= base_url('dashboard/konfigurasi/store') ?>" method="post" class="p-6">
        <?= csrf_field() ?>

        <div class="space-y-5">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Label Konfigurasi</label>
                <input type="text" name="label" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-medium text-slate-800" placeholder="Contoh: Menu Warta" value="<?= old('label') ?>" required>
                <p class="text-[11px] text-slate-400 mt-1.5">* Nama yang akan muncul sebagai indikator konfigurasi.</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Slug (Kunci Unik)</label>
                <input type="text" name="slug" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-medium text-slate-800 font-mono" placeholder="Contoh: menu_warta" value="<?= old('slug') ?>" required>
                <p class="text-[11px] text-slate-400 mt-1.5">* Gunakan huruf kecil dan garis bawah (_). Tidak boleh ada spasi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kategori (Group)</label>
                    <div class="relative">
                        <select name="group" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-medium text-slate-800 appearance-none">
                            <option value="menu" <?= old('group') == 'menu' ? 'selected' : '' ?>>Menu</option>
                            <option value="section" <?= old('group') == 'section' ? 'selected' : '' ?>>Section (Bagian Halaman)</option>
                            <option value="social" <?= old('group') == 'social' ? 'selected' : '' ?>>Social Media</option>
                            <option value="contact" <?= old('group') == 'contact' ? 'selected' : '' ?>>Contact</option>
                            <option value="other" <?= old('group') == 'other' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                            <ion-icon name="chevron-down-outline"></ion-icon>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Urutan</label>
                    <input type="number" name="urutan" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-medium text-slate-800" placeholder="0" value="<?= old('urutan') ?? 0 ?>">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Status Awal</label>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="status" value="aktif" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" checked>
                        <span class="text-sm font-bold text-slate-600 group-hover:text-indigo-600 transition-colors">Aktif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="status" value="tidak aktif" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        <span class="text-sm font-bold text-slate-600 group-hover:text-rose-600 transition-colors">Tidak Aktif</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <ion-icon name="save-outline" class="text-lg"></ion-icon>
                <span>Simpan Konfigurasi</span>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
