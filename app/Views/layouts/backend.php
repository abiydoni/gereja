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

    <?php
        $role = strtolower(trim(session()->get('role') ?? 'guest'));
        // Normalizing role 'bendahara' to 'keuangan'
        if ($role == 'bendahara') $role = 'keuangan';
    ?>

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
                <?php
                    // Define Menu Structure with Permissions
                    // Roles: superadmin, admin, user, keuangan
                    
                    $menuItems = [
                        [
                            'type' => 'link',
                            'title' => 'Dashboard',
                            'url' => 'dashboard',
                            'icon' => 'grid-outline',
                            'allowed' => ['superadmin', 'admin', 'user', 'keuangan']
                        ],
                        [
                            'type' => 'header',
                            'title' => 'Sistem & Pengaturan',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Pengaturan Profil',
                            'url' => 'dashboard/gereja',
                            'icon' => 'business-outline',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Konfigurasi Frontend',
                            'url' => 'dashboard/konfigurasi',
                            'icon' => 'settings-outline',
                            'allowed' => ['superadmin'] // Only superadmin
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Daftar Admin',
                            'url' => 'dashboard/users',
                            'icon' => 'people-circle-outline',
                            'allowed' => ['superadmin', 'admin'] // Hided for user & keuangan
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Data Jemaat',
                            'url' => 'dashboard/jemaat',
                            'icon' => 'people-outline',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'header',
                            'title' => 'Manajemen Konten',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Informasi',
                            'url' => 'dashboard/informasi',
                            'icon' => 'information-circle-outline',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Artikel & Berita',
                            'url' => 'dashboard/artikel',
                            'icon' => 'newspaper-outline',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Renungan',
                            'url' => 'dashboard/renungan',
                            'icon' => 'book-outline',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Galeri & Youtube',
                            'url' => 'dashboard/galeri',
                            'icon' => 'images-outline',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Diskusi Jemaat',
                            'url' => 'dashboard/diskusi',
                            'icon' => 'chatbubbles-outline',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'header',
                            'title' => 'Pelayanan',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Jadwal Rutin',
                            'url' => 'dashboard/jadwal_rutin',
                            'icon' => 'time-outline',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Jadwal Pelayanan',
                            'url' => 'dashboard/jadwal_pelayanan',
                            'icon' => 'calendar-outline',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Kegiatan',
                            'url' => 'dashboard/kegiatan',
                            'icon' => 'people-outline',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Liturgi',
                            'url' => 'dashboard/liturgi',
                            'icon' => 'document-text-outline',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Majelis',
                            'url' => 'dashboard/majelis',
                            'icon' => 'people-circle-outline',
                            'allowed' => ['superadmin', 'admin', 'user']
                        ],
                        [
                            'type' => 'header',
                            'title' => 'Keuangan',
                            'allowed' => ['superadmin', 'admin', 'keuangan', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Master Jenis Persembahan',
                            'url' => 'dashboard/master_persembahan',
                            'icon' => 'list-outline',
                            'allowed' => ['superadmin', 'admin', 'keuangan', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Persembahan Ibadah',
                            'url' => 'dashboard/persembahan',
                            'icon' => 'stats-chart-outline',
                            'allowed' => ['superadmin', 'admin', 'keuangan', 'user']
                        ],
                        [
                            'type' => 'link',
                            'title' => 'Keuangan',
                            'url' => 'dashboard/keuangan',
                            'icon' => 'wallet-outline',
                            'allowed' => ['superadmin', 'admin', 'keuangan']
                        ],
                    ];
                ?>

                <nav class="space-y-0.5">
                    <?php foreach($menuItems as $menu): ?>
                        <?php 
                            // Check permission
                            if (!in_array($role, $menu['allowed'])) continue;
                        ?>

                        <?php if($menu['type'] == 'header'): ?>
                            <div class="pt-4 pb-1 px-6">
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.2em]"><?= $menu['title'] ?></p>
                            </div>
                        <?php elseif($menu['type'] == 'link'): ?>
                            <a href="<?= base_url($menu['url']) ?>" class="flex items-center space-x-3 px-6 py-2 transition-all duration-200 <?= (uri_string() == $menu['url'] || strpos(uri_string(), $menu['url'].'/') === 0) ? 'nav-item-active' : 'nav-item text-slate-400' ?>">
                                <ion-icon name="<?= $menu['icon'] ?>" class="text-lg"></ion-icon>
                                <span class="font-medium text-sm"><?= $menu['title'] ?></span>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </nav>
            </div>
            
            <!-- Logout Footer Removed -->
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

                    <!-- Logout Button -->
                    <a href="<?= base_url('logout') ?>" onclick="confirmLogout(event)" data-no-loader="true" class="w-10 h-10 rounded-full flex items-center justify-center text-red-400 hover:bg-red-50 hover:text-red-600 transition-all group" title="Logout">
                        <ion-icon name="log-out-outline" class="text-xl group-hover:translate-x-1 transition-transform"></ion-icon>
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

        // Global Logout Confirmation
        window.confirmLogout = function(e) {
            e.preventDefault();
            const href = e.currentTarget.getAttribute('href');
            
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: "Apakah Anda yakin ingin keluar dari sistem?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                borderRadius: '1.5rem'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        };

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
