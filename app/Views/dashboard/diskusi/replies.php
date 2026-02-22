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
                <div class="space-y-6">
                    <?php foreach($replies as $r): ?>
                        <?php 
                            $isAdmin = strpos($r['penulis'], '(Admin)') !== false; 
                            $align = $isAdmin ? 'ml-auto' : 'mr-auto';
                            $bgColor = $isAdmin ? 'bg-indigo-50 border-indigo-100' : 'bg-white border-slate-100';
                            $textColor = $isAdmin ? 'text-indigo-900' : 'text-slate-700';
                        ?>
                        <div class="flex flex-col <?= $isAdmin ? 'items-end' : 'items-start' ?> max-w-4xl <?= $align ?>">
                            <div class="flex items-center gap-2 mb-1 px-1">
                                <span class="text-xs font-bold <?= $isAdmin ? 'text-indigo-600' : 'text-slate-600' ?>"><?= esc($r['penulis']) ?></span>
                                <span class="text-[10px] text-slate-400"><?= date('d M Y, H:i', strtotime($r['created_at'])) ?></span>
                            </div>
                            <div class="relative group p-4 rounded-2xl border shadow-sm <?= $bgColor ?> <?= $textColor ?> min-w-[300px]">
                                <p class="text-sm leading-relaxed whitespace-pre-line"><?= esc($r['isi']) ?></p>
                                
                                <!-- Delete Button (Visible on Hover) -->
                                <a href="<?= base_url('dashboard/diskusi/delete_reply/'.$r['id_jawaban']) ?>" 
                                   class="absolute -top-2 -right-2 w-6 h-6 bg-white rounded-full shadow border border-slate-200 flex items-center justify-center text-slate-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity btn-delete" 
                                   title="Hapus Pesan">
                                    <ion-icon name="close-outline"></ion-icon>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Admin Reply Form -->
        <div class="bg-white border-t border-slate-100 p-6">
            <form action="<?= base_url('dashboard/diskusi/reply/'.$topic['id_diskusi']) ?>" method="post">
                <div class="mb-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Balas sebagai Admin</label>
                    <textarea name="isi" required rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="Tulis tanggapan atau jawaban..."></textarea>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <p class="text-xs text-slate-500 italic">Balasan akan ditandai sebagai "Admin".</p>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 flex items-center gap-2">
                        <ion-icon name="send"></ion-icon>
                        <span>Kirim Balasan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
