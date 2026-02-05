<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="bg-primary pt-32 pb-48 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
    <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-down">
        <div class="flex items-center justify-center space-x-3 text-[10px] text-accent font-bold uppercase tracking-[0.4em] mb-6">
            <ion-icon name="person-circle" class="text-lg"></ion-icon> 
            <span>Oleh: <?= $topic['penulis'] ?></span>
            <span class="opacity-30">•</span>
            <span><?= date('d F Y', strtotime($topic['created_at'])) ?></span>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-white font-heading leading-tight"><?= $topic['judul'] ?></h1>
    </div>
</div>

<div class="max-w-4xl mx-auto px-6 lg:px-8 -mt-32 mb-24 relative z-10">
    <!-- Topik Utama -->
    <div class="bg-white rounded-[40px] shadow-2xl shadow-primary/10 overflow-hidden border border-slate-100 mb-12" data-aos="fade-up">
        <div class="p-10 md:p-16">
            <div class="prose prose-slate prose-sm max-w-none font-medium leading-relaxed text-xs">
                <?= nl2br(esc($topic['isi'])) ?>
            </div>
        </div>
    </div>

    <!-- Daftar Jawaban -->
    <div class="space-y-8 mb-16">
        <h2 class="text-lg font-bold text-primary font-heading flex items-center mb-8">
            <ion-icon name="chatbubbles" class="mr-4 text-accent text-2xl"></ion-icon>
            Jawaban & Diskusi (<?= count($replies) ?>)
        </h2>

        <?php if(empty($replies)): ?>
            <div class="bg-slate-50 p-12 rounded-[40px] text-center text-slate-400 font-medium italic border border-slate-100">
                Belum ada jawaban. Jadilah yang pertama menanggapi!
            </div>
        <?php else: ?>
            <?php foreach($replies as $index => $r): ?>
            <div class="bg-white p-8 md:p-10 rounded-[40px] shadow-lg shadow-primary/5 border border-slate-100" data-aos="fade-up">
                <div class="flex items-start space-x-4 mb-4">
                    <div class="h-12 w-12 rounded-2xl bg-slate-50 flex items-center justify-center text-primary border border-slate-100">
                        <ion-icon name="person" class="text-2xl"></ion-icon>
                    </div>
                    <div>
                        <h4 class="font-bold text-primary"><?= $r['penulis'] ?></h4>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest"><?= date('d M Y, H:i', strtotime($r['created_at'])) ?></span>
                    </div>
                </div>
                <div class="text-slate-600 font-medium leading-relaxed pl-16 text-xs">
                    <?= nl2br(esc($r['isi'])) ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Form Jawaban -->
    <div class="bg-slate-900 p-10 md:p-16 rounded-[40px] shadow-2xl" data-aos="zoom-in">
        <div class="flex items-center space-x-4 mb-10">
            <div class="h-12 w-12 rounded-2xl bg-accent flex items-center justify-center text-primary">
                <ion-icon name="return-down-forward" class="text-2xl"></ion-icon>
            </div>
            <h3 class="text-xl font-bold text-white font-heading">Tulis Jawaban</h3>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-6 py-4 rounded-3xl mb-8 flex items-center">
                 <ion-icon name="checkmark-circle" class="text-2xl mr-3"></ion-icon>
                 <span class="font-bold"><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('diskusi/submit_reply/'.$topic['id_diskusi']) ?>" method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Nama Anda</label>
                    <input type="text" name="penulis" required class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl focus:ring-2 focus:ring-accent focus:bg-white focus:text-primary text-white outline-none transition-all font-bold placeholder:text-slate-600" placeholder="Masukkan nama panggilan...">
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Komentar / Jawaban</label>
                <textarea name="isi" required rows="4" class="w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl focus:ring-2 focus:ring-accent focus:bg-white focus:text-primary text-white outline-none transition-all font-bold placeholder:text-slate-600" placeholder="Tuliskan jawaban atau tanggapan Anda..."></textarea>
            </div>
            <button type="submit" class="inline-flex items-center space-x-3 px-10 py-5 bg-accent text-primary font-black rounded-2xl shadow-xl shadow-accent/20 hover:bg-gold-light transition-all hover:-translate-y-1">
                <span>KIRIM JAWABAN</span>
                <ion-icon name="send" class="text-xl"></ion-icon>
            </button>
        </form>
    </div>

    <div class="mt-12 text-center">
        <a href="<?= base_url('diskusi') ?>" class="inline-flex items-center space-x-3 text-slate-500 hover:text-primary font-bold transition-colors">
            <ion-icon name="arrow-back" class="text-xl"></ion-icon>
            <span>Kembali ke Daftar Diskusi</span>
        </a>
    </div>
</div>

<?= $this->endSection() ?>
