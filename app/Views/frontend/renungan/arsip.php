<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-24 pb-32 border-b border-white/5 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <span class="text-xs font-bold uppercase tracking-[0.4em] text-slate-400 mb-4 block">Koleksi Lengkap</span>
        <h1 class="text-2xl md:text-3xl font-extrabold text-white font-heading"><?= esc($title) ?></h1>
        <p class="text-slate-400 mt-4 font-medium max-w-xl mx-auto text-xs">Menjelajahi arsip renungan harian untuk menemukan kembali inspirasi iman masa lalu.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 lg:px-8 -mt-16 mb-24 relative z-10">
    <?php if(empty($renungan)): ?>
        <div class="bg-white p-16 rounded-[40px] shadow-xl text-center text-slate-400 font-medium italic border border-slate-100" data-aos="zoom-in">
            Belum ada renungan yang diarsipkan.
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach($renungan as $index => $r): ?>
            <div class="group bg-white rounded-[32px] p-6 sm:p-8 shadow-lg shadow-primary/5 border border-slate-100 flex flex-col md:flex-row items-start md:items-center gap-6 hover:shadow-xl transition-all duration-300" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>">
                <?php if(!empty($r['gambar'])): ?>
                    <div class="flex-shrink-0 w-full sm:w-32 h-32 rounded-2xl overflow-hidden relative shadow-md">
                         <img src="<?= base_url('uploads/renungan/'.$r['gambar']) ?>" alt="<?= $r['judul'] ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    </div>
                <?php else: ?>
                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-primary/5 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                        <ion-icon name="book-outline" class="text-3xl"></ion-icon>
                    </div>
                <?php endif; ?>
                <div class="flex-grow">
                    <div class="flex items-center space-x-3 text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mb-2">
                        <ion-icon name="calendar-clear-outline"></ion-icon> 
                        <span><?= date('d F Y', strtotime($r['tanggal'])) ?></span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 mb-2 group-hover:text-primary transition-colors">
                        <a href="<?= base_url('renungan/'.$r['id_renungan']) ?>"><?= esc($r['judul']) ?></a>
                    </h3>
                    <p class="text-slate-500 text-xs line-clamp-1">
                        <?= strip_tags($r['isi']) ?>
                    </p>
                </div>
                <a href="<?= base_url('renungan/'.$r['id_renungan']) ?>" class="flex-shrink-0 w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 group-hover:bg-primary group-hover:border-primary group-hover:text-white transition-all duration-300">
                    <ion-icon name="chevron-forward-outline"></ion-icon>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($pager->getPageCount() > 1): ?>
            <div class="mt-12">
                <?= $pager->links('renungan', 'frontend_full') ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>

<?= $this->endSection() ?>
