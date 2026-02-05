<?= $this->extend('layouts/backend') ?>

<?= $this->section('content') ?>

<!-- Welcome Section -->
<div class="mb-10" data-aos="fade-down">
    <h1 class="font-heading font-extrabold text-3xl text-slate-800 mb-2">Selamat Datang, Admin! 👋</h1>
    <p class="text-slate-500 text-lg">Berikut ringkasan aktivitas gereja terbaru.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
    <!-- Renungan Card -->
    <div class="relative group bg-white p-8 rounded-[32px] shadow-xl shadow-indigo-100/50 border border-slate-100 overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
        <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-125 transition-transform duration-500">
            <ion-icon name="book" class="text-9xl text-indigo-600"></ion-icon>
        </div>
        <div class="relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                <ion-icon name="book" class="text-2xl"></ion-icon>
            </div>
            <p class="text-slate-500 font-bold text-sm uppercase tracking-wider mb-1">Total Renungan</p>
            <h3 class="font-heading font-extrabold text-4xl text-slate-800"><?= $total_renungan ?></h3>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-gradient-to-r from-indigo-400 to-purple-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
    </div>

    <!-- Jadwal Card -->
    <div class="relative group bg-white p-8 rounded-[32px] shadow-xl shadow-emerald-100/50 border border-slate-100 overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
        <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-125 transition-transform duration-500">
            <ion-icon name="calendar" class="text-9xl text-emerald-600"></ion-icon>
        </div>
        <div class="relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                <ion-icon name="calendar" class="text-2xl"></ion-icon>
            </div>
            <p class="text-slate-500 font-bold text-sm uppercase tracking-wider mb-1">Jadwal Ibadah</p>
            <h3 class="font-heading font-extrabold text-4xl text-slate-800"><?= $total_jadwal ?></h3>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-400 to-teal-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
    </div>

    <!-- Jemaat Card -->
    <div class="relative group bg-white p-8 rounded-[32px] shadow-xl shadow-amber-100/50 border border-slate-100 overflow-hidden transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
        <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-125 transition-transform duration-500">
            <ion-icon name="people" class="text-9xl text-amber-600"></ion-icon>
        </div>
        <div class="relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 mb-6 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                <ion-icon name="people" class="text-2xl"></ion-icon>
            </div>
            <p class="text-slate-500 font-bold text-sm uppercase tracking-wider mb-1">Jemaat Terdaftar</p>
            <h3 class="font-heading font-extrabold text-4xl text-slate-800"><?= number_format($total_jemaat, 0, ',', '.') ?></h3> 
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-400 to-orange-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
    </div>
</div>

<!-- Jemaat Growth Chart -->
<div class="grid grid-cols-1 gap-8 mb-10" data-aos="fade-up">
    <div class="bg-white p-8 md:p-10 rounded-[40px] shadow-xl border border-slate-100 overflow-hidden relative group">
        <div class="absolute top-0 right-0 p-10 opacity-5 group-hover:scale-110 transition-transform duration-700">
            <ion-icon name="trending-up" class="text-[200px] text-indigo-600"></ion-icon>
        </div>
        <div class="relative z-10">
            <h2 class="font-heading font-extrabold text-2xl text-slate-800 mb-1">Pertumbuhan Jemaat</h2>
            <p class="text-slate-500 font-medium mb-10">Data akumulatif jemaat terdaftar 12 bulan terakhir.</p>
            <div class="h-[300px] w-full">
                <canvas id="growthChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Gender & Age Distribution -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10" data-aos="fade-up">
    <!-- Gender Distribution -->
    <div class="bg-white p-8 md:p-10 rounded-[40px] shadow-xl border border-slate-100 overflow-hidden relative group">
        <div class="relative z-10">
            <h2 class="font-heading font-extrabold text-2xl text-slate-800 mb-1">Komposisi Pria & Wanita</h2>
            <p class="text-slate-500 font-medium mb-10">Perbandingan jumlah jemaat berdasar jenis kelamin.</p>
            <div class="h-[300px] w-full flex justify-center">
                <canvas id="genderChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Age Distribution -->
    <div class="bg-white p-8 md:p-10 rounded-[40px] shadow-xl border border-slate-100 overflow-hidden relative group">
        <div class="relative z-10">
            <h2 class="font-heading font-extrabold text-2xl text-slate-800 mb-1">Distribusi Usia</h2>
            <p class="text-slate-500 font-medium mb-10">Pengelompokan jemaat berdasarkan rentang umur.</p>
            <div class="h-[300px] w-full">
                <canvas id="ageChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Financial Overview Section -->
<div class="grid grid-cols-1 gap-8 mb-10" data-aos="fade-up">
    <div class="bg-white p-8 md:p-10 rounded-[40px] shadow-xl border border-slate-100 overflow-hidden relative group">
        <div class="absolute top-0 right-0 p-10 opacity-5 group-hover:scale-110 transition-transform duration-700">
            <ion-icon name="stats-chart" class="text-[200px] text-primary"></ion-icon>
        </div>
        
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h2 class="font-heading font-extrabold text-2xl text-slate-800 mb-1">Laporan Arus Kas</h2>
                    <p class="text-slate-500 font-medium">Ringkasan pemasukan dan pengeluaran 12 bulan terakhir.</p>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="text-right">
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Saldo Saat Ini</p>
                        <h4 class="font-heading font-extrabold text-2xl text-primary">Rp <?= number_format($saldo_realtime, 0, ',', '.') ?></h4>
                    </div>
                    <div class="h-12 w-12 rounded-2xl bg-primary flex items-center justify-center text-accent shadow-lg shadow-primary/20">
                        <ion-icon name="wallet" class="text-2xl"></ion-icon>
                    </div>
                </div>
            </div>

            <div class="h-[350px] w-full">
                <canvas id="financialChart"></canvas>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('financialChart').getContext('2d');
        const financialData = <?= json_encode($financialData) ?>;
        
        const labels = financialData.map(item => item.bulan);
        const incomeData = financialData.map(item => item.masuk);
        const expenseData = financialData.map(item => item.keluar);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: incomeData,
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        borderColor: '#10B981',
                        borderWidth: 2,
                        borderRadius: 8,
                        hoverBackgroundColor: '#10B981',
                        transition: 'all 0.3s'
                    },
                    {
                        label: 'Pengeluaran',
                        data: expenseData,
                        backgroundColor: 'rgba(239, 68, 68, 0.2)',
                        borderColor: '#EF4444',
                        borderWidth: 2,
                        borderRadius: 8,
                        hoverBackgroundColor: '#EF4444',
                        transition: 'all 0.3s'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Inter',
                                size: 12,
                                weight: '600'
                            },
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { family: 'Outfit', size: 14 },
                        bodyFont: { family: 'Inter', size: 13 },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: 'Inter', size: 11 },
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { family: 'Inter', size: 11, weight: '600' }
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart'
                }
            }
        });

        // 2. Growth Chart (Line)
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        const growthData = <?= json_encode($growthData) ?>;
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: growthData.map(d => d.bulan),
                datasets: [{
                    label: 'Total Jemaat',
                    data: growthData.map(d => d.total),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#6366f1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 3. Gender Chart (Pie)
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        const genderData = <?= json_encode($genderData) ?>;
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pria', 'Wanita'],
                datasets: [{
                    data: [genderData.pria, genderData.wanita],
                    backgroundColor: ['#3b82f6', '#ec4899'],
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, font: { family: 'Inter', weight: '600' } }
                    }
                },
                cutout: '70%'
            }
        });

        // 4. Age Chart (Bar)
        const ageCtx = document.getElementById('ageChart').getContext('2d');
        const ageLabels = <?= json_encode(array_keys($ageData)) ?>;
        const ageValues = <?= json_encode(array_values($ageData)) ?>;
        new Chart(ageCtx, {
            type: 'bar',
            data: {
                labels: ageLabels,
                datasets: [{
                    label: 'Jumlah Jemaat',
                    data: ageValues,
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderColor: '#6366f1',
                    borderWidth: 2,
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                    y: { grid: { display: false } }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
