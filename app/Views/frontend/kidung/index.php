<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<section class="py-8 px-6 min-h-screen">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-8 gap-4" data-aos="fade-up">
            <div class="space-y-3">
                <div class="flex items-center space-x-2">
                    <div class="h-1 w-6 bg-accent"></div>
                    <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-accent">Pujian & Penyembahan</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-primary font-heading">Kidung Jemaat</h2>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-8" data-aos="fade-up" data-aos-delay="100">
            <form action="<?= base_url('kidung') ?>" method="get" class="relative group">
                <input type="text" name="q" value="<?= esc($keyword) ?>" placeholder="Cari nomor atau judul lagu..." 
                       class="w-full pl-12 pr-4 py-4 bg-white rounded-2xl border border-slate-100 shadow-xl shadow-primary/5 focus:ring-2 focus:ring-accent/20 focus:border-accent outline-none transition-all font-medium text-slate-600">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl group-focus-within:text-accent transition-colors">
                    <ion-icon name="search-outline"></ion-icon>
                </div>
                <?php if($keyword): ?>
                    <a href="<?= base_url('kidung') ?>" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition-colors">
                        <ion-icon name="close-circle" class="text-xl"></ion-icon>
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- List -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" data-aos="fade-up" data-aos-delay="200">
            <?php if(!empty($kidung)): ?>
                <?php foreach($kidung as $k): ?>
                    <a href="<?= base_url('kidung/' . $k['nomor']) ?>" class="group bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-primary/5 hover:border-accent/30 transition-all duration-300 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-400 group-hover:bg-accent/10 group-hover:text-accent flex items-center justify-center font-heading font-bold transition-colors">
                                KJ <?= $k['nomor'] ?>
                            </div>
                            <div>
                                <h3 class="font-bold text-primary group-hover:text-accent transition-colors"><?= esc($k['judul']) ?></h3>
                                <?php if($k['nada_dasar']): ?>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"><?= esc($k['nada_dasar']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-slate-300 group-hover:text-accent group-hover:translate-x-1 transition-all">
                            <ion-icon name="chevron-forward" class="text-xl"></ion-icon>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full py-20 text-center">
                    <ion-icon name="musical-notes-outline" class="text-6xl text-slate-100 mb-4"></ion-icon>
                    <p class="text-slate-400 font-medium">Lagu tidak ditemukan.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            <?= $pager->links('kidung', 'frontend_full') ?>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
