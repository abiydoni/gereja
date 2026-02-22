<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>
<div class="mb-6 flex items-center space-x-3">
    <a href="<?= base_url('dashboard/kidung') ?>" class="w-10 h-10 rounded-full flex items-center justify-center bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-primary transition-all">
        <ion-icon name="arrow-back-outline" class="text-xl"></ion-icon>
    </a>
    <div>
        <h2 class="text-2xl font-bold text-slate-800 font-heading">Edit Lagu</h2>
        <p class="text-slate-500 text-sm mt-1">Perbarui informasi lagu Kidung Jemaat.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden max-w-4xl">
    <div class="p-6 md:p-8">
        <form action="<?= base_url('dashboard/kidung/update/' . $song['id']) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Lagu <span class="text-red-500">*</span></label>
                    <input type="number" name="nomor" value="<?= old('nomor', $song['nomor']) ?>" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none" placeholder="Contoh: 1">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Lagu <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="<?= old('judul', $song['judul']) ?>" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none" placeholder="Contoh: Haleluya! Pujilah">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nada Dasar</label>
                    <input type="text" name="nada_dasar" value="<?= old('nada_dasar', $song['nada_dasar']) ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none" placeholder="Contoh: DO = F (4 Ketuk)">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pengarang / Terjemahan</label>
                    <input type="text" name="pengarang" value="<?= old('pengarang', $song['pengarang']) ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none" placeholder="M. Karosekali">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Isi Lagu / Lirik <span class="text-red-500">*</span></label>
                <div class="text-xs text-slate-400 mb-3 bg-blue-50 p-3 rounded-lg border border-blue-100 italic">
                    <p class="font-bold text-blue-600 mb-1">Tips Format:</p>
                    <p>- Tuliskan persis seperti buku nyanyian.</p>
                    <p>- Pisahkan bait dengan garis baru kosong.</p>
                    <p>- Awal ayat bisa diberi label "Ayat 1", "Bait 1" agar ditebalkan otomatis di aplikasi utama.</p>
                </div>
                <textarea name="isi" id="editor" required rows="15" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-accent focus:border-accent transition-all text-sm outline-none whitespace-pre-wrap font-mono" placeholder="Ayat 1&#10;Haleluya! Pujilah&#10;..."><?= old('isi', $song['isi']) ?></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100">
                <a href="<?= base_url('dashboard/kidung') ?>" class="px-6 py-2.5 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 hover:text-slate-800 font-medium transition-colors text-sm">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl hover:bg-slate-800 font-medium transition-colors flex items-center space-x-2 text-sm shadow-md shadow-primary/20">
                    <ion-icon name="save-outline" class="text-lg"></ion-icon>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
