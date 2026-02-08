<!-- Global Loader -->
<!-- Only shows as a Glass Loader for navigation/actions. No initial splash to avoid double-loading effect. -->
<div id="global-loader" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/40 backdrop-blur-[2px] opacity-0 transition-opacity duration-300">
    <div class="relative">
        <!-- Outer Rotating Ring -->
        <div class="w-16 h-16 rounded-full border-4 border-accent/30 border-t-accent animate-spin"></div>
        
        <!-- Inner Icon -->
        <div class="absolute inset-0 flex items-center justify-center">
            <?php if(!empty($gereja['logo'])): ?>
                <img src="<?= base_url('uploads/'.$gereja['logo']) ?>" class="w-8 h-8 rounded-full object-cover gold-filter opacity-90" alt="L">
            <?php else: ?>
                <ion-icon name="refresh" class="text-accent text-xl animate-pulse"></ion-icon>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Navigation Loader State */
    #global-loader.show {
        display: flex !important;
        opacity: 1 !important;
        pointer-events: auto !important;
    }

    /* Prevent interaction when loading */
    body.loading-active {
        overflow: hidden !important;
        pointer-events: none !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const loader = document.getElementById('global-loader');
        
        // Navigation Loader Logic
        window.showLoader = function() {
            if (loader) {
                loader.classList.add('show');
                document.body.classList.add('loading-active');
            }
        };

        window.hideLoader = function() {
            if (loader) {
                loader.classList.remove('show');
                document.body.classList.remove('loading-active');
            }
        };

        // Note: We DO NOT show loader on initial Window Load anymore
        // to prevent the "Double Splash Screen" effect with the PWA Native Splash.
        
        // Auto-cleanup just in case
        window.addEventListener('load', function() {
            hideLoader();
        });

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
