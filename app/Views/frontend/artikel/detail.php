<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-10 pb-16 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <div class="inline-flex items-center space-x-2 text-xs font-bold uppercase tracking-[0.2em] text-accent mb-3 bg-accent/10 px-4 py-2 rounded-full backdrop-blur-sm border border-accent/20">
            <ion-icon name="calendar-outline"></ion-icon> 
            <span><?= date('d F Y', strtotime($artikel['created_at'])) ?></span>
            <span class="opacity-30">•</span>
            <span>Oleh: <?= $artikel['penulis'] ?? 'Admin Gereja' ?></span>
        </div>
        <h1 class="text-xl md:text-2xl font-extrabold text-white font-heading leading-tight"><?= $artikel['judul'] ?></h1>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-8 mb-12 relative z-10">
    <div class="bg-white rounded-[24px] md:rounded-[40px] shadow-2xl shadow-primary/10 overflow-hidden border border-slate-100" data-aos="fade-up">
        <?php if($artikel['gambar']): ?>
            <div class="relative h-48 md:h-96 group">
                <img src="<?= base_url('uploads/artikel/'.$artikel['gambar']) ?>" alt="<?= $artikel['judul'] ?>" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
            </div>
        <?php endif; ?>
        
        <div class="p-5 md:p-12 <?php if(!empty($artikel['gambar'])) echo '-mt-24 md:-mt-32 relative z-10'; ?>">
            <div class="prose prose-xs md:prose-sm max-w-none text-black leading-snug font-medium prose-p:my-1.5 prose-headings:mb-1.5 prose-headings:mt-3 text-[9px] md:text-sm prose-p:text-black prose-headings:text-black prose-strong:text-black prose-li:text-black prose-ul:text-black prose-ol:text-black">
                <?= nl2br($artikel['isi']) ?>
            </div>
            
            <div class="mt-8 md:mt-12 pt-8 border-t border-slate-100 flex flex-col md:flex-row justify-center items-center space-y-6 md:space-y-0 text-slate-400 text-xs font-bold uppercase tracking-widest text-center">
                <span>Semoga artikel ini menjadi berkat bagi kita semua. Tuhan Yesus Memberkati.</span>
            </div>
        </div>
    </div>
    
    <div class="mt-12 text-center">
        <a href="<?= base_url('artikel') ?>" class="inline-flex items-center space-x-3 text-slate-500 hover:text-primary font-bold transition-colors text-xs">
            <ion-icon name="arrow-back" class="text-xl"></ion-icon>
            <span>Kembali ke Daftar Artikel</span>
        </a>
    </div>
</div>

<?= $this->endSection() ?>
