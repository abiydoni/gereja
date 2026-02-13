<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-32 pb-48 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <div class="flex items-center justify-center space-x-3 text-[10px] text-accent font-bold uppercase tracking-[0.4em] mb-6">
            <ion-icon name="calendar-outline" class="text-lg"></ion-icon> 
            <span><?= date('d F Y', strtotime($artikel['created_at'])) ?></span>
            <span class="opacity-30">•</span>
            <span>Oleh: <?= $artikel['penulis'] ?? 'Admin Gereja' ?></span>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-white font-heading leading-tight"><?= $artikel['judul'] ?></h1>
    </div>
</div>

<div class="max-w-4xl mx-auto px-6 lg:px-8 -mt-32 mb-24 relative z-10">
    <div class="bg-white rounded-[40px] shadow-2xl shadow-primary/10 overflow-hidden border border-slate-100" data-aos="fade-up">
        <?php if($artikel['gambar']): ?>
            <div class="relative h-[400px]">
                <img src="<?= base_url('uploads/artikel/'.$artikel['gambar']) ?>" alt="<?= $artikel['judul'] ?>" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-primary/40 to-transparent"></div>
            </div>
        <?php endif; ?>
        
        <div class="p-10 md:p-16">
            <style>
                .prose-content p, 
                .prose-content span, 
                .prose-content div, 
                .prose-content li,
                .prose-content strong,
                .prose-content em {
                    color: #000000 !important;
                }
            </style>
            <div class="prose prose-slate prose-sm max-w-none prose-headings:font-heading prose-headings:font-bold prose-p:leading-relaxed prose-p:font-medium prose-img:rounded-3xl prose-content text-black !important">
                <?= $artikel['isi'] ?>
            </div>
            
            <div class="mt-16 pt-8 border-t border-slate-100 flex flex-col md:flex-row justify-center items-center space-y-6 md:space-y-0 text-slate-400 text-xs font-bold uppercase tracking-widest text-center">
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
