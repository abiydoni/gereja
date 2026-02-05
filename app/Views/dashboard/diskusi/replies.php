<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="max-w-full mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800">Detail Topik</h3>
            <a href="<?= base_url('dashboard/diskusi') ?>" class="text-slate-400 hover:text-slate-600 transition flex items-center space-x-2 font-bold text-xs uppercase">
                <ion-icon name="arrow-back-outline"></ion-icon>
                <span>Kembali</span>
            </a>
        </div>
        <div class="p-8">
            <div class="flex items-center space-x-3 text-[10px] text-indigo-600 font-bold uppercase tracking-widest mb-4">
                <ion-icon name="person-circle" class="text-lg"></ion-icon>
                <span>Oleh: <?= esc($topic['penulis']) ?></span>
                <span class="opacity-30">•</span>
                <span><?= date('d F Y', strtotime($topic['created_at'])) ?></span>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 mb-4"><?= esc($topic['judul']) ?></h2>
            <p class="text-slate-600 leading-relaxed font-medium"><?= nl2br(esc($topic['isi'])) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Daftar Jawaban (<?= count($replies) ?>)</h3>
        </div>
        <div class="p-0">
            <?php if(empty($replies)): ?>
                <div class="p-12 text-center text-slate-400 italic font-medium">Belum ada jawaban untuk topik ini.</div>
            <?php else: ?>
                <div class="divide-y divide-slate-100">
                    <?php foreach($replies as $r): ?>
                    <div class="p-6 hover:bg-slate-50 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-indigo-600 text-sm"><?= esc($r['penulis']) ?></span>
                                <span class="text-[10px] text-slate-400 font-bold"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></span>
                            </div>
                            <a href="<?= base_url('dashboard/diskusi/delete_reply/'.$r['id_jawaban']) ?>" class="text-red-400 hover:text-red-600 transition text-xl btn-delete" data-confirm="Hapus jawaban ini?">
                                <ion-icon name="close-circle-outline"></ion-icon>
                            </a>
                        </div>
                        <p class="text-slate-600 text-sm font-medium leading-relaxed"><?= nl2br(esc($r['isi'])) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
