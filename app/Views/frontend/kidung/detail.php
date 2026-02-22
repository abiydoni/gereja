<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<section class="py-8 px-6 min-h-screen bg-slate-50/50">
    <div class="max-w-3xl mx-auto">
        <!-- Breadcrumb / Back -->
        <div class="mb-6" data-aos="fade-down">
            <a href="<?= base_url('kidung') ?>" class="inline-flex items-center space-x-2 text-slate-400 hover:text-accent transition-colors font-bold uppercase tracking-widest text-[10px]">
                <ion-icon name="arrow-back"></ion-icon>
                <span>Kembali ke Daftar</span>
            </a>
        </div>

        <!-- Content Card -->
        <div class="bg-white rounded-[32px] shadow-xl shadow-primary/5 border border-slate-100 overflow-hidden" data-aos="fade-up">
            <!-- Header Decor -->
            <div class="h-2 bg-gradient-to-r from-accent via-gold-light to-accent"></div>
            
            <div class="p-6 md:p-10">
                <div class="text-center mb-10 space-y-4">
                    <div class="inline-block px-4 py-1.5 rounded-full bg-accent/10 border border-accent/20 text-accent font-bold text-[10px] uppercase tracking-[0.2em]">
                        Kidung Jemaat No. <?= $song['nomor'] ?>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-primary font-heading leading-tight italic">
                        <?= esc($song['judul']) ?>
                    </h1>
                    
                    <?php if($song['nada_dasar']): ?>
                        <div class="flex items-center justify-center space-x-2 text-slate-400">
                            <ion-icon name="musical-note" class="text-accent"></ion-icon>
                            <span class="text-xs font-bold uppercase tracking-widest"><?= esc($song['nada_dasar']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Lyrics Body -->
                <div class="prose prose-slate max-w-none">
                    <div class="text-slate-600 leading-relaxed font-medium whitespace-pre-wrap text-center text-sm md:text-base selection:bg-accent/20">
                        <?= esc($song['isi']) ?>
                    </div>
                </div>

                <?php if($song['pengarang']): ?>
                    <div class="mt-12 pt-8 border-t border-slate-50 text-center">
                        <p class="text-[10px] text-slate-300 font-bold uppercase tracking-[0.3em]">Pengarang / Karya</p>
                        <p class="text-slate-500 font-bold mt-1"><?= esc($song['pengarang']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Footer Decor -->
            <div class="bg-slate-50 p-6 flex justify-center">
                <ion-icon name="heart" class="text-slate-200 text-xl"></ion-icon>
            </div>
        </div>
        
        <!-- Quick Nav -->
        <div class="mt-8 flex justify-between items-center px-4" data-aos="fade-up">
            <?php if($song['nomor'] > 1): ?>
                <a href="<?= base_url('kidung/' . ($song['nomor'] - 1)) ?>" class="flex items-center space-x-2 text-slate-400 hover:text-accent transition-colors font-bold text-[10px] uppercase tracking-wider">
                    <ion-icon name="chevron-back"></ion-icon>
                    <span>KJ <?= $song['nomor'] - 1 ?></span>
                </a>
            <?php else: ?>
                <span></span>
            <?php endif; ?>

            <a href="<?= base_url('kidung/' . ($song['nomor'] + 1)) ?>" class="flex items-center space-x-2 text-slate-400 hover:text-accent transition-colors font-bold text-[10px] uppercase tracking-wider">
                <span>KJ <?= $song['nomor'] + 1 ?></span>
                <ion-icon name="chevron-forward"></ion-icon>
            </a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
