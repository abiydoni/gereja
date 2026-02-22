<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 font-heading">Kidung Jemaat</h2>
        <p class="text-slate-500 text-sm mt-1">Kelola direktori lagu kidung jemaat.</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="<?= base_url('dashboard/kidung/create') ?>" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-xl hover:bg-slate-800 transition-colors text-sm font-medium">
            <ion-icon name="add-outline" class="mr-2"></ion-icon>
            Tambah Lagu
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <h3 class="font-bold text-slate-700">Daftar Lagu</h3>
        
        <form action="<?= base_url('dashboard/kidung') ?>" method="get" class="flex relative w-full md:w-auto">
            <input type="text" name="q" value="<?= esc($keyword) ?>" placeholder="Cari nomor/judul..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-accent focus:border-accent w-full md:w-64 transition-shadow">
            <ion-icon name="search-outline" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></ion-icon>
            <?php if($keyword): ?>
                <a href="<?= base_url('dashboard/kidung') ?>" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500">
                    <ion-icon name="close-circle"></ion-icon>
                </a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold w-24">Nomor</th>
                    <th class="px-6 py-4 font-semibold">Judul Lagu</th>
                    <th class="px-6 py-4 font-semibold border-x border-slate-100">Nada Dasar</th>
                    <th class="px-6 py-4 font-semibold border-x border-slate-100">Pengarang</th>
                    <th class="px-6 py-4 font-semibold text-right w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(!empty($kidung)): ?>
                    <?php foreach($kidung as $k): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                KJ <?= esc($k['nomor']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-slate-800"><?= esc($k['judul']) ?></p>
                        </td>
                        <td class="px-6 py-4 border-x border-slate-100">
                            <span class="text-sm text-slate-600"><?= $k['nada_dasar'] ? esc($k['nada_dasar']) : '-' ?></span>
                        </td>
                        <td class="px-6 py-4 border-x border-slate-100">
                            <span class="text-sm text-slate-600"><?= $k['pengarang'] ? esc($k['pengarang']) : '-' ?></span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="<?= base_url('kidung/'.$k['nomor']) ?>" target="_blank" class="p-1 px-2 text-indigo-500 hover:bg-indigo-50 rounded transition-colors tooltip" title="Lihat Frontend">
                                    <ion-icon name="eye-outline" class="text-lg"></ion-icon>
                                </a>
                                <a href="<?= base_url('dashboard/kidung/edit/' . $k['id']) ?>" class="p-1 px-2 text-blue-500 hover:bg-blue-50 rounded transition-colors tooltip" title="Edit">
                                    <ion-icon name="create-outline" class="text-lg"></ion-icon>
                                </a>
                                <a href="<?= base_url('dashboard/kidung/delete/' . $k['id']) ?>" class="btn-delete p-1 px-2 text-red-500 hover:bg-red-50 rounded transition-colors tooltip" title="Hapus">
                                    <ion-icon name="trash-outline" class="text-lg"></ion-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center">
                                <ion-icon name="musical-notes-outline" class="text-4xl text-slate-300 mb-2"></ion-icon>
                                <p>Belum ada data lagu.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pager->getPageCount() > 1): ?>
        <div class="p-4 border-t border-slate-100 flex justify-center">
            <?= $pager->links('kidung', 'admin_pagination') ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .tooltip { position: relative; }
    .tooltip:hover::after {
        content: attr(title);
        position: absolute;
        bottom: 100%; left: 50%; transform: translateX(-50%);
        background: #1e293b; color: #fff; padding: 4px 8px;
        border-radius: 4px; font-size: 10px; white-space: nowrap;
        margin-bottom: 4px; z-index: 10;
    }
</style>
<?= $this->endSection() ?>
