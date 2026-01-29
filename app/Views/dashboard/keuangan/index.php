<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <!-- Header & Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <!-- Summary Cards (3 cols on md) -->
        <div class="md:col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Total Debet -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl">
                    <ion-icon name="trending-up-outline"></ion-icon>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Debet</p>
                    <p class="text-lg font-extrabold text-slate-800 font-mono">Rp <?= number_format($total_debet, 0, ',', '.') ?></p>
                </div>
            </div>

            <!-- Total Kredit -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl">
                    <ion-icon name="trending-down-outline"></ion-icon>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Kredit</p>
                    <p class="text-lg font-extrabold text-slate-800 font-mono">Rp <?= number_format($total_kredit, 0, ',', '.') ?></p>
                </div>
            </div>

            <!-- Saldo -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl">
                    <ion-icon name="wallet-outline"></ion-icon>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Saldo Akhir</p>
                    <p class="text-lg font-extrabold text-indigo-600 font-mono">Rp <?= number_format($saldo, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <!-- Add Button (1 col on md) -->
        <div class="flex justify-end h-full items-center">
            <a href="<?= base_url('dashboard/keuangan/create_laporan') ?>" class="w-full md:w-auto px-6 py-4 bg-primary text-white font-bold rounded-2xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 flex items-center justify-center space-x-2">
                <ion-icon name="add-circle" class="text-xl"></ion-icon>
                <span>CATAT TRANSAKSI</span>
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <form action="<?= base_url('dashboard/keuangan') ?>" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <!-- Search -->
            <div class="flex-1 w-full space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Pencarian</label>
                <div class="relative">
                    <ion-icon name="search-outline" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></ion-icon>
                    <input type="text" name="search" value="<?= $search ?>" placeholder="Cari keterangan atau reff..." 
                           class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                </div>
            </div>

            <!-- Month Filter -->
            <div class="w-full md:w-48 space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Bulan</label>
                <select name="bulan" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none appearance-none">
                    <option value="">Semua Bulan</option>
                    <?php
                    $months = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                    ];
                    foreach($months as $m => $name): ?>
                        <option value="<?= $m ?>" <?= $bulan == $m ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Year Filter -->
            <div class="w-full md:w-32 space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Tahun</label>
                <select name="tahun" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none appearance-none">
                    <option value="">Semua</option>
                    <?php 
                    $currentYear = date('Y');
                    for($y = $currentYear; $y >= $currentYear - 5; $y--): ?>
                        <option value="<?= $y ?>" <?= $tahun == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Account Filter (Reff) -->
            <div class="w-full md:w-32 space-y-1.5">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Akun</label>
                <select name="reff" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none appearance-none">
                    <option value="">Semua</option>
                    <option value="KAS" <?= $reff == 'KAS' ? 'selected' : '' ?>>KAS</option>
                    <option value="BANK" <?= $reff == 'BANK' ? 'selected' : '' ?>>BANK</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all flex items-center justify-center">
                    <ion-icon name="filter-outline" class="text-lg"></ion-icon>
                </button>
                <a href="<?= base_url('dashboard/keuangan') ?>" class="flex-1 md:flex-none px-6 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all flex items-center justify-center">
                    <ion-icon name="refresh-outline" class="text-lg"></ion-icon>
                </a>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Keterangan</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Reff</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Debet (Rp)</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Kredit (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if(empty($keuangan)): ?>
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 italic text-sm">
                                <ion-icon name="receipt-outline" class="text-4xl block mx-auto mb-2 opacity-20"></ion-icon>
                                Belum ada data transaksi untuk filter ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($keuangan as $k): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="py-4 px-6 text-sm text-slate-500">
                                <?= date('d/m/Y', strtotime($k['tanggal'])) ?>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm font-bold text-slate-800"><?= $k['keterangan'] ?></div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 bg-slate-100 text-slate-500 rounded text-[9px] font-bold uppercase tracking-wider">
                                    <?= $k['reff'] ?: '-' ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-mono font-bold text-emerald-600">
                                <?= $k['debet'] > 0 ? number_format($k['debet'], 0, ',', '.') : '-' ?>
                            </td>
                            <td class="py-4 px-6 text-right font-mono font-bold text-rose-500">
                                <?= $k['kredit'] > 0 ? number_format($k['kredit'], 0, ',', '.') : '-' ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($pager): ?>
            <div class="px-6 py-4 border-t border-slate-50 flex justify-center">
                <?= $pager->links('keuangan', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
