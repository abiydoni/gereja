<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h3 class="text-2xl font-bold text-slate-800 font-heading">Forum Diskusi Jemaat</h3>
        <p class="text-slate-500 text-sm mt-1">Pantau dan moderasi percakapan komunitas gereja.</p>
    </div>
</div>

<div class="space-y-4">
    <?php if(empty($topics)): ?>
        <div class="bg-white rounded-xl p-12 text-center border border-slate-100 shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4 text-slate-400">
                <ion-icon name="chatbubbles-outline" class="text-3xl"></ion-icon>
            </div>
            <h3 class="text-lg font-medium text-slate-900">Belum ada topik diskusi</h3>
            <p class="text-slate-500 mt-1">Jemaat belum memulai percakapan apapun.</p>
        </div>
    <?php else: ?>
        <?php foreach($topics as $t): ?>
        <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
             <!-- Status Stripe -->
            <div class="absolute left-0 top-0 bottom-0 w-1 <?= $t['status'] == 'aktif' ? 'bg-emerald-500' : 'bg-slate-300' ?>"></div>
            
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Avatar / Icon -->
                <div class="flex-shrink-0 hidden md:block">
                    <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <span class="font-bold text-lg"><?= substr($t['penulis'], 0, 1) ?></span>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-grow">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-lg font-bold text-slate-800 hover:text-indigo-600 transition-colors">
                                <a href="<?= base_url('dashboard/diskusi/replies/'.$t['id_diskusi']) ?>">
                                    <?= esc($t['judul']) ?>
                                </a>
                            </h4>
                            <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
                                <span class="font-medium text-slate-700"><?= esc($t['penulis']) ?></span>
                                <span>&bull;</span>
                                <span><?= date('d M Y, H:i', strtotime($t['created_at'])) ?></span>
                                <span>&bull;</span>
                                <span class="<?= $t['status'] == 'aktif' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-600 bg-slate-100' ?> px-2 py-0.5 rounded-full font-bold uppercase text-[10px]">
                                    <?= $t['status'] ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                             <!-- Action Buttons -->
                             <a href="<?= base_url('dashboard/diskusi/update_status/'.$t['id_diskusi']) ?>" 
                                class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-amber-500 transition" 
                                title="<?= $t['status'] == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                <ion-icon name="<?= $t['status'] == 'aktif' ? 'eye-off-outline' : 'eye-outline' ?>" class="text-lg"></ion-icon>
                            </a>
                             <a href="<?= base_url('dashboard/diskusi/delete_topic/'.$t['id_diskusi']) ?>" 
                                class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-red-500 transition btn-delete" 
                                title="Hapus Topik">
                                <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                            </a>
                        </div>
                    </div>

                    <p class="text-slate-600 text-sm mt-3 line-clamp-2">
                        <?= strip_tags($t['isi']) ?>
                    </p>

                    <div class="mt-4 flex items-center gap-4">
                        <a href="<?= base_url('dashboard/diskusi/replies/'.$t['id_diskusi']) ?>" class="flex items-center gap-2 text-sm text-indigo-600 font-medium hover:text-indigo-700">
                            <ion-icon name="chatbox-ellipses-outline"></ion-icon>
                            <span>Lihat Percakapan</span>
                        </a>
                        
                        <!-- Reply Count (Placeholder logic if counts available) -->
                        <!-- <span class="text-xs text-slate-400 flex items-center gap-1">
                            <ion-icon name="people-outline"></ion-icon> 5 Balasan
                        </span> -->
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
