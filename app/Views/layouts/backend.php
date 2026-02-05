<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - Admin Gereja</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- PWA Settings -->
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="#0F172A">
    <link rel="apple-touch-icon" href="/uploads/gkj.png">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?= base_url('sw.js') ?>')
                    .then(reg => console.log('SW Registered', reg))
                    .catch(err => console.log('SW Failed', err));
            });
        }
    </script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#0F172A', // Deep Navy
                        'secondary': '#1E293B', // Slate
                        'accent': '#D4AF37', // Elegant Gold
                        'gold-light': '#F3E5AB',
                        'soft-bg': '#F8FAFC',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #F8FAFC;
        }
        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        .glass-sidebar {
            background: linear-gradient(180deg, #0F172A 0%, #1E293B 100%);
        }
        .nav-item-active {
            background: rgba(212, 175, 55, 0.15);
            color: #D4AF37;
            border-right: 3px solid #D4AF37;
        }
        .nav-item:hover:not(.nav-item-active) {
            background: rgba(255, 255, 255, 0.05);
            color: #F3E5AB;
        }
    </style>
</head>
<body class="text-slate-800 antialiased overflow-x-hidden text-xs">
    <?= $this->include('partials/loader') ?>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-primary/40 backdrop-blur-sm z-40 hidden md:hidden transition-all duration-300"></div>

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 w-72 glass-sidebar text-white flex flex-col shadow-2xl z-50 transform -translate-x-full md:translate-x-0 md:static transition-transform duration-300 ease-in-out">
            <div class="h-16 flex items-center px-6 border-b border-white/10 flex-shrink-0">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-accent to-amber-200 flex items-center justify-center text-primary shadow-lg">
                            <ion-icon name="business" class="text-lg"></ion-icon>
                        </div>
                        <div>
                            <span class="block font-heading font-bold text-base tracking-wide uppercase">Admin Panel</span>
                        </div>
                    </div>
                    <!-- Close button for mobile -->
                    <button id="close-sidebar" class="md:hidden text-slate-400 hover:text-white transition-colors">
                        <ion-icon name="close-outline" class="text-2xl"></ion-icon>
                    </button>
                </div>
            </div>
            
            <div class="flex-grow overflow-y-auto py-6 custom-scrollbar">
                <nav class="space-y-0.5">
                    <a href="<?= base_url('dashboard') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= uri_string() == 'dashboard' ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="grid-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Dashboard</span>
                    </a>

                    <div class="pt-4 pb-1 px-6">
                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.2em]">Sistem & Pengaturan</p>
                    </div>

                    <a href="<?= base_url('dashboard/gereja') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/gereja') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="business-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Pengaturan Profil</span>
                    </a>
                     <a href="<?= base_url('dashboard/users') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/users') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="people-circle-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Daftar Admin</span>
                    </a>

                    <a href="<?= base_url('dashboard/jemaat') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/jemaat') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="people-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Data Jemaat</span>
                    </a>
                    
                    <div class="pt-4 pb-1 px-6">
                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.2em]">Manajemen Konten</p>
                    </div>

                    <a href="<?= base_url('dashboard/informasi') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/informasi') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="information-circle-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Informasi</span>
                    </a>

                    <a href="<?= base_url('dashboard/artikel') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/artikel') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="newspaper-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Artikel & Berita</span>
                    </a>

                    <a href="<?= base_url('dashboard/renungan') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/renungan') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="book-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Renungan</span>
                    </a>
                    
                    <a href="<?= base_url('dashboard/galeri') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/galeri') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="images-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Galeri & Youtube</span>
                    </a>

                    <a href="<?= base_url('dashboard/diskusi') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/diskusi') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="chatbubbles-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Diskusi Jemaat</span>
                    </a>

                    <div class="pt-4 pb-1 px-6">
                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.2em]">Pelayanan</p>
                    </div>
                    
                    <a href="<?= base_url('dashboard/jadwal_rutin') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/jadwal_rutin') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="time-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Jadwal Rutin</span>
                    </a>

                    <a href="<?= base_url('dashboard/jadwal_pelayanan') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/jadwal_pelayanan') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="calendar-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Jadwal Pelayanan</span>
                    </a>

                    <a href="<?= base_url('dashboard/kegiatan') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/kegiatan') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="people-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Kegiatan</span>
                    </a>
                    
                    <a href="<?= base_url('dashboard/liturgi') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/liturgi') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="document-text-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Liturgi</span>
                    </a>

                    <a href="<?= base_url('dashboard/majelis') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/majelis') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="people-circle-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Majelis</span>
                    </a>


                    <a href="<?= base_url('dashboard/master_persembahan') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/master_persembahan') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="list-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Master Jenis Persembahan</span>
                    </a>

                    <a href="<?= base_url('dashboard/persembahan') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/persembahan') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="stats-chart-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Persembahan Ibadah</span>
                    </a>

                    <a href="<?= base_url('dashboard/keuangan') ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= strpos(uri_string(), 'dashboard/keuangan') === 0 ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                        <ion-icon name="wallet-outline" class="text-lg"></ion-icon>
                        <span class="font-medium text-sm">Keuangan</span>
                    </a>
                </nav>
            </div>
            
            <div class="p-6 border-t border-white/10 bg-primary/50 backdrop-blur-sm">
                <a href="<?= base_url('logout') ?>" class="flex items-center justify-center space-x-2 px-4 py-2.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition-all duration-300 w-full group">
                    <ion-icon name="log-out-outline" class="text-lg group-hover:-translate-x-1 transition-transform"></ion-icon>
                    <span class="font-bold text-xs uppercase tracking-wider">Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-soft-bg relative">
            
            <!-- Navbar Header -->
            <header class="bg-white/80 backdrop-blur-md sticky top-0 z-30 border-b border-slate-200/60 h-20 flex items-center justify-between px-6 md:px-8 shadow-sm">
                <div class="flex items-center">
                    <button id="toggle-sidebar" class="text-slate-500 hover:text-primary transition-colors focus:outline-none p-2 md:hidden">
                        <ion-icon name="menu-outline" class="text-2xl"></ion-icon>
                    </button>
                    <h2 class="ml-2 md:ml-0 font-heading font-bold text-xl md:text-2xl text-primary tracking-tight"><?= $title ?? 'Dashboard' ?></h2>
                </div>
                
                <div class="flex items-center space-x-3 md:space-x-6">
                     <a href="<?= base_url() ?>" target="_blank" class="hidden sm:flex items-center space-x-2 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-full hover:bg-indigo-600 hover:text-white transition-all duration-300 text-sm font-semibold">
                        <ion-icon name="open-outline" class="text-lg"></ion-icon>
                        <span class="hidden lg:inline">Lihat Website</span>
                    </a>

                    <!-- Fast Settings Link -->
                    <a href="<?= base_url('dashboard/gereja') ?>" class="w-10 h-10 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-primary transition-all group" title="Pengaturan">
                        <ion-icon name="settings-outline" class="text-xl group-hover:rotate-45 transition-transform"></ion-icon>
                    </a>
                    
                    <div class="flex items-center space-x-3 border-l border-slate-200 pl-4 md:pl-6">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-bold text-primary"><?= session()->get('username') ?? 'Admin' ?></p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider"><?= session()->get('role') ?? 'Super Admin' ?></p>
                        </div>
                        <div class="h-9 w-9 md:h-10 md:w-10 rounded-full bg-gradient-to-br from-primary to-slate-800 flex items-center justify-center text-accent shadow-md ring-2 ring-white">
                            <ion-icon name="person" class="text-lg"></ion-icon>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto overflow-x-hidden p-6 md:p-10 custom-scrollbar relative">
                <!-- Background Decorative Elements -->
                <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-50 rounded-full filter blur-[100px] opacity-70 pointer-events-none -z-10 translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-fuchsia-50 rounded-full filter blur-[80px] opacity-70 pointer-events-none -z-10 -translate-x-1/2 translate-y-1/2"></div>

                <?php if(session()->getFlashdata('success')): ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: '<?= session()->getFlashdata('success') ?>',
                        confirmButtonColor: '#0F172A',
                        timer: 3000,
                        timerProgressBar: true
                    });
                </script>
                <?php endif; ?>
                
                <?php if(session()->getFlashdata('error')): ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: '<?= session()->getFlashdata('error') ?>',
                        confirmButtonColor: '#EF4444'
                    });
                </script>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <!-- Ionicons -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('toggle-sidebar');
        const closeBtn = document.getElementById('close-sidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        if(toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
        if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if(overlay) overlay.addEventListener('click', toggleSidebar);

        // Global SweetAlert Confirmation
        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.btn-delete');
            if (deleteBtn) {
                e.preventDefault();
                const href = deleteBtn.getAttribute('href');
                const message = deleteBtn.getAttribute('data-confirm') || 'Yakin ingin menghapus data ini?';
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    borderRadius: '1.5rem'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.showLoader(); // Show global loader if available
                        window.location.href = href;
                    }
                });
            }
        });

        // Global Status Toggle function
        window.toggleStatus = function(module, id, element) {
            const isChecked = element.checked;
            const originalState = !isChecked;
            
            // Show subtle saving indicator if needed, but SweetAlert is better for confirmation/feedback
            fetch(`<?= base_url('dashboard/system/toggleStatus') ?>/${module}/${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Success feedback
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                    
                    Toast.fire({
                        icon: 'success',
                        title: 'Status diperbarui'
                    });

                    // Update the label if it exists near the toggle
                    const label = element.closest('label').querySelector('.toggle-label');
                    if (label) {
                        label.textContent = isChecked ? data.active_label : data.inactive_label;
                        label.className = `toggle-label text-[10px] font-bold uppercase ${isChecked ? 'text-emerald-500' : 'text-slate-400'}`;
                    }
                } else {
                    // Revert on error
                    element.checked = originalState;
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Gagal memperbarui status',
                        confirmButtonColor: '#EF4444'
                    });
                }
            })
            .catch(error => {
                element.checked = originalState;
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan sistem',
                    confirmButtonColor: '#EF4444'
                });
            });
        };
    </script>

    <style>
        /* Custom Toggle Switch Styles */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 34px;
            height: 20px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e2e8f0;
            transition: .4s;
            border-radius: 20px;
        }
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        input:checked + .toggle-slider {
            background-color: #10b981;
        }
        input:focus + .toggle-slider {
            box-shadow: 0 0 1px #10b981;
        }
        input:checked + .toggle-slider:before {
            transform: translateX(14px);
        }
    </style>

    <!-- Scripts -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
