<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Admin - Modern Grace</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Animate On Scroll (AOS) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#0F172A',
                        'accent': '#D4AF37',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-gradient {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
        }
        .btn-gold {
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }
        .text-gradient {
            background: linear-gradient(135deg, #D4AF37 0%, #F3E5AB 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gradient min-h-screen flex items-center justify-center p-6 overflow-hidden relative">
    
    <!-- Animated Shapes -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-accent/5 rounded-full blur-[120px] -mr-64 -mt-64"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-primary/20 rounded-full blur-[120px] -ml-64 -mb-64"></div>

    <div class="w-full max-w-md relative z-10" data-aos="zoom-in">
        <div class="bg-white/95 backdrop-blur-xl rounded-[40px] shadow-2xl overflow-hidden border border-white/20">
            <div class="p-8 md:p-12">
                <div class="text-center mb-10">
                    <div class="w-20 h-20 bg-primary rounded-3xl flex items-center justify-center text-accent text-4xl mx-auto mb-6 shadow-xl shadow-primary/20">
                        <ion-icon name="shield-checkmark"></ion-icon>
                    </div>
                    <h1 class="text-3xl font-extrabold text-primary font-heading tracking-tight underline decoration-accent/30 decoration-4 underline-offset-8">Portal Admin</h1>
                    <p class="text-slate-400 mt-6 font-bold text-xs uppercase tracking-[0.2em]">Management Experience</p>
                </div>

                <?php if(session()->getFlashdata('error')): ?>
                <div class="bg-rose-50 text-rose-600 p-4 rounded-2xl mb-8 text-sm font-bold flex items-center border border-rose-100 animate-pulse">
                    <ion-icon name="alert-circle" class="text-xl mr-2"></ion-icon>
                    <?= session()->getFlashdata('error') ?>
                </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/loginProcess') ?>" method="post" class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-4">Username</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-accent transition-colors">
                                <ion-icon name="person-outline"></ion-icon>
                            </div>
                            <input type="text" name="username" class="w-full pl-12 pr-4 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-accent/20 focus:bg-white focus:border-accent transition-all font-medium" placeholder="Identity Name" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest ml-4">Security Key</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-accent transition-colors">
                                <ion-icon name="lock-closed-outline"></ion-icon>
                            </div>
                            <input type="password" name="password" class="w-full pl-12 pr-4 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-accent/20 focus:bg-white focus:border-accent transition-all font-medium" placeholder="Secret Password" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full btn-gold text-primary font-bold py-4 rounded-2xl transition hover:scale-[1.02] active:scale-[0.98] shadow-lg flex items-center justify-center space-x-2">
                            <span>Grant Access</span>
                            <ion-icon name="key" class="text-lg"></ion-icon>
                        </button>
                    </div>
                </form>
            </div>
            <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 text-center">
                <a href="<?= base_url() ?>" class="text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.3em] hover:text-primary transition-colors flex items-center justify-center space-x-2">
                    <ion-icon name="arrow-back-outline"></ion-icon>
                    <span>Back to Sanctuary</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        AOS.init({
            duration: 1000,
            once: true
        });
    </script>
</body>
</html>
