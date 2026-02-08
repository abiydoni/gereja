<!-- Global Loader -->
<!-- Initially opaque (splash screen) for first load, then transforms to glass loader -->
<div id="global-loader" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-primary transition-all duration-700">
    
    <!-- Splash Screen Content (Large Logo) -->
    <div id="splash-content" class="flex flex-col items-center">
        <div class="relative w-32 h-32 mb-6">
            <!-- Glowing effect behind logo -->
            <div class="absolute inset-0 bg-accent/20 rounded-full blur-xl animate-pulse"></div>
            <?php if(!empty($gereja['logo'])): ?>
                <img src="<?= base_url('uploads/'.$gereja['logo']) ?>" 
                     class="relative w-full h-full object-contain drop-shadow-2xl animate-[float_3s_ease-in-out_infinite]" 
                     alt="Loading...">
            <?php else: ?>
                <ion-icon name="church" class="text-accent text-6xl animate-bounce"></ion-icon>
            <?php endif; ?>
        </div>
        
        <!-- Loading Text -->
        <div class="space-y-2 text-center">
            <h3 class="font-heading font-bold text-xl text-white tracking-widest uppercase">
                <?= $gereja['nama_gereja'] ?? 'GEREJA' ?>
            </h3>
            <div class="flex items-center justify-center space-x-1">
                <div class="w-2 h-2 bg-accent rounded-full animate-bounce" style="animation-delay: 0s"></div>
                <div class="w-2 h-2 bg-accent rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                <div class="w-2 h-2 bg-accent rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div>
        </div>
    </div>

    <!-- Navigation Loader Content (Spinner) - Hidden initially -->
    <div id="nav-loader-content" class="hidden absolute inset-0 items-center justify-center">
        <div class="relative">
            <!-- Outer Ring -->
            <div class="w-16 h-16 rounded-full border-4 border-accent/20 border-t-accent animate-spin"></div>
            <!-- Inner Icon -->
            <div class="absolute inset-0 flex items-center justify-center">
                <?php if(!empty($gereja['logo'])): ?>
                    <img src="<?= base_url('uploads/'.$gereja['logo']) ?>" class="w-8 h-8 rounded-full object-cover gold-filter opacity-80" alt="L">
                <?php else: ?>
                    <ion-icon name="refresh" class="text-accent text-xl animate-pulse"></ion-icon>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    /* Splash Screen State (Initial) */
    .splash-active {
        opacity: 1;
        pointer-events: auto;
    }

    /* Navigation Loader State */
    .nav-loader-active {
        background-color: rgba(15, 23, 42, 0.4) !important; /* Glass effect */
        backdrop-filter: blur(4px);
        opacity: 1 !important;
        pointer-events: auto !important;
    }

    /* Hide splash content when not in splash mode */
    .hide-content {
        display: none !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const loader = document.getElementById('global-loader');
        const splashContent = document.getElementById('splash-content');
        const navContent = document.getElementById('nav-loader-content');
        
        // 1. Initial Load Handling (Splash Screen)
        // Loader starts visible (opacity 1) and bg-primary
        
        window.addEventListener('load', () => {
            // Fade out immediately when page is ready for a faster transition
            loader.classList.add('opacity-0', 'pointer-events-none');
            
            // After fade out transition (700ms), switch to Nav Mode configuration
            setTimeout(() => {
                // Prepare for navigation loading
                splashContent.classList.add('hide-content');
                navContent.classList.remove('hidden');
                navContent.classList.add('flex');
                loader.classList.remove('bg-primary'); // Remove solid bg
            }, 700);
        });

        // 2. Navigation Handling (Glass Loader)
        window.showLoader = function() {
            // Ensure we are in Nav Mode styling
            loader.classList.add('nav-loader-active');
            loader.classList.remove('opacity-0', 'pointer-events-none');
        };

        window.hideLoader = function() {
            loader.classList.remove('nav-loader-active');
            loader.classList.add('opacity-0', 'pointer-events-none');
        };

        // Handle standard loader triggers
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            const isModifierKey = e.ctrlKey || e.shiftKey || e.metaKey || e.altKey;
            
            if (link && 
                link.href && 
                !link.href.includes('#') && 
                !link.target && 
                link.hostname === window.location.hostname &&
                !link.hasAttribute('data-no-loader') &&
                !link.classList.contains('btn-delete') &&
                !isModifierKey) {
                
                showLoader();
            }
        });

        document.addEventListener('submit', function(e) {
            if (!e.target.hasAttribute('data-no-loader')) {
                showLoader();
            }
        });

        // Handle bfcache
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                hideLoader();
            }
        });
    });
</script>
