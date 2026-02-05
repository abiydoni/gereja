<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-10 pb-16 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <span class="text-[9px] font-bold uppercase tracking-[0.4em] text-accent mb-3 block">Pranatan Lampahing Pangibadah</span>
        <h1 class="text-xl md:text-2xl font-extrabold text-white font-heading">Tata Ibadah & Liturgi</h1>
        <p class="text-slate-400 mt-3 font-medium max-w-xl mx-auto italic text-xs md:text-sm">Panduan urutan ibadah dan bahan pelayanan firman Tuhan.</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-6 lg:px-8 -mt-8 mb-12 relative z-10">
    <div class="space-y-6">
        <?php if(empty($liturgi)): ?>
            <div class="bg-white p-16 rounded-[40px] shadow-xl text-center text-slate-400 font-medium italic border border-slate-100" data-aos="zoom-in">
                Dereng wonten data liturgi ingkang kacathet.
            </div>
        <?php else: ?>
            <?php foreach($liturgi as $index => $l): ?>
            <a href="<?= base_url('liturgi/bi/'.$l['id_liturgi']) ?>" class="group block bg-white p-8 md:p-10 rounded-[40px] shadow-xl shadow-primary/5 border border-slate-100 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <div class="flex items-center justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2 text-[9px] text-accent font-bold uppercase tracking-[0.2em]">
                             <ion-icon name="calendar-clear" class="text-base"></ion-icon> 
                             <span><?= date('d F Y', strtotime($l['tanggal'])) ?></span>
                        </div>
                        <h3 class="text-sm md:text-base font-extrabold text-primary group-hover:text-accent transition-colors font-heading leading-tight"><?= $l['judul'] ?></h3>
                    </div>
                    <div class="h-14 w-14 shrink-0 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-primary group-hover:text-accent transition-all duration-500 shadow-sm">
                        <ion-icon name="chevron-forward" class="text-2xl group-hover:translate-x-1 transition-transform"></ion-icon>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
