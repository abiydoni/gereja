<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-24 pb-32 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <span class="text-xs font-bold uppercase tracking-[0.4em] text-accent mb-4 block">Santapan Rohani</span>
        <h1 class="text-5xl md:text-6xl font-extrabold text-white font-heading"><?= esc($title) ?></h1>
        <p class="text-slate-400 mt-4 font-medium max-w-xl mx-auto">Renungkan firman Tuhan setiap hari untuk pertumbuhan iman kita.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-16 mb-24 relative z-10">
    <?php if(empty($renungan)): ?>
        <div class="bg-white p-16 rounded-[40px] shadow-xl text-center text-slate-400 font-medium italic border border-slate-100" data-aos="zoom-in">
            Belum ada renungan yang diterbitkan.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach($renungan as $index => $r): ?>
            <article class="group bg-white rounded-[40px] shadow-xl shadow-primary/5 border border-slate-100 overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl h-full flex flex-col" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <?php if(!empty($r['gambar'])): ?>
                    <div class="relative h-56 overflow-hidden">
                         <img src="<?= base_url('uploads/renungan/'.$r['gambar']) ?>" alt="<?= $r['judul'] ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                         <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                <?php else: ?>
                    <div class="h-4 bg-accent/20 w-full relative overflow-hidden">
                        <div class="absolute inset-0 bg-accent/40 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                    </div>
                <?php endif; ?>
                <div class="p-8 flex flex-col flex-grow">
                    <div class="flex items-center space-x-3 text-[10px] text-accent font-bold uppercase tracking-[0.2em] mb-4">
                        <ion-icon name="calendar-outline" class="text-lg"></ion-icon> 
                        <span><?= date('d F Y', strtotime($r['tanggal'])) ?></span>
                    </div>
                    <h3 class="text-2xl font-bold text-primary group-hover:text-accent transition-colors font-heading leading-tight mb-4 flex-grow">
                        <?= esc($r['judul']) ?>
                    </h3>
                    <p class="text-slate-500 text-sm line-clamp-3 mb-6 font-medium leading-relaxed">
                        <?= strip_tags($r['isi']) ?>
                    </p>
                    <a href="<?= base_url('renungan/'.$r['id_renungan']) ?>" class="inline-flex items-center text-primary font-bold text-sm group/btn space-x-2">
                        <span>Baca Selengkapnya</span>
                        <ion-icon name="arrow-forward" class="text-lg group-hover/btn:translate-x-1 transition-transform"></ion-icon>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <div class="mt-12">
            <?= $pager->links('renungan', 'default_full') ?>
        </div>

    <?php endif; ?>
</div>

<?= $this->endSection() ?>
