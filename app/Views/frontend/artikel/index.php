<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-10 pb-16 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <span class="text-[9px] font-bold uppercase tracking-[0.4em] text-accent mb-3 block">Warta & Inspirasi</span>
        <h1 class="text-xl md:text-2xl font-extrabold text-white font-heading">Artikel & Berita</h1>
        <p class="text-slate-400 mt-3 font-medium max-w-xl mx-auto text-xs">Kumpulan tulisan inspiratif, warta gereja, dan kabar pelayanan terbaru.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-8 mb-12 relative z-10">
    <?php if(empty($artikels)): ?>
        <div class="bg-white p-16 rounded-[40px] shadow-xl text-center text-slate-400 font-medium italic border border-slate-100" data-aos="zoom-in">
            Belum ada artikel yang diterbitkan.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach($artikels as $index => $a): ?>
            <article class="group bg-white rounded-[40px] shadow-xl shadow-primary/5 border border-slate-100 overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl h-full flex flex-col" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <div class="relative h-56 overflow-hidden">
                    <?php if($a['gambar']): ?>
                        <img src="<?= base_url('uploads/artikel/'.$a['gambar']) ?>" alt="<?= $a['judul'] ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <?php else: ?>
                        <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                            <ion-icon name="image-outline" class="text-6xl"></ion-icon>
                        </div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex items-center space-x-2 text-[9px] text-accent font-bold uppercase tracking-[0.2em] mb-3">
                        <ion-icon name="calendar-outline" class="text-base"></ion-icon> 
                        <span><?= date('d F Y', strtotime($a['created_at'])) ?></span>
                    </div>
                    <h3 class="text-sm font-bold text-primary group-hover:text-accent transition-colors font-heading leading-tight mb-3 flex-grow">
                        <?= $a['judul'] ?>
                    </h3>
                    <p class="text-slate-500 text-xs line-clamp-3 mb-4 font-medium leading-relaxed">
                        <?= strip_tags($a['isi']) ?>
                    </p>
                    <a href="<?= base_url('artikel/'.$a['slug']) ?>" class="inline-flex items-center text-primary font-bold text-xs group/btn space-x-2">
                        <span>Baca Selengkapnya</span>
                        <ion-icon name="arrow-forward" class="text-base group-hover/btn:translate-x-1 transition-transform"></ion-icon>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
