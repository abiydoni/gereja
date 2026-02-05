<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-24 pb-32 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
         <div class="inline-flex items-center space-x-2 text-xs font-bold uppercase tracking-[0.2em] text-accent mb-6 bg-accent/10 px-4 py-2 rounded-full backdrop-blur-sm border border-accent/20">
            <ion-icon name="calendar-outline"></ion-icon> 
            <span><?= date('d F Y', strtotime($renungan['tanggal'])) ?></span>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-white font-heading leading-tight"><?= esc($renungan['judul']) ?></h1>
    </div>
</div>

<div class="max-w-4xl mx-auto px-6 lg:px-8 -mt-20 mb-24 relative z-10">
    <div class="bg-white rounded-[40px] shadow-2xl p-8 md:p-12 mb-12 border border-slate-100 relative overflow-hidden">
        <?php if(!empty($renungan['gambar'])): ?>
            <div class="-mx-8 md:-mx-12 -mt-8 md:-mt-12 mb-8 md:mb-12 h-64 md:h-96 relative overflow-hidden">
                 <img src="<?= base_url('uploads/renungan/'.$renungan['gambar']) ?>" class="w-full h-full object-cover">
                 <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"></div>
            </div>
        <?php endif; ?>
        
        <!-- Decorative quote -->
        <ion-icon name="chatbox-ellipses" class="absolute top-8 right-8 text-9xl text-slate-50 rotate-12 -z-0"></ion-icon>
        
        <div class="prose prose-sm prose-slate max-w-none relative z-10 text-xs">
            <?= $renungan['isi'] ?>
        </div>
        
        <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
            <a href="<?= base_url('renungan') ?>" class="text-slate-500 font-bold text-xs hover:text-primary px-5 py-3 rounded-xl hover:bg-slate-50 transition-all flex items-center space-x-2">
                <ion-icon name="arrow-back"></ion-icon>
                <span>Kembali ke Daftar</span>
            </a>
            
             <a href="<?= base_url('renungan/arsip') ?>" class="text-primary font-bold text-xs hover:text-white px-6 py-3 rounded-xl border border-primary/20 hover:bg-primary transition-all flex items-center space-x-2 shadow-lg shadow-primary/5 hover:shadow-primary/20">
                <ion-icon name="library-outline"></ion-icon>
                <span>Lihat Arsip</span>
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
