/* =====================================================
   PRELOADER / SPLASHSCREEN SCRIPT
   ===================================================== */

/**
 * Preloader Handler
 * - Shows preloader on page load
 * - Hides preloader after content is ready
 * - Provides smooth transitions
 */

(function() {
    'use strict';
    
    // Configuration
    const PRELOADER_CONFIG = {
        minDisplayTime: 2000,      // Minimum display time in ms (for loading bar animation)
        fadeOutDuration: 500,      // Fade out animation duration in ms
        autoHideTimeout: 5000      // Fallback auto-hide timeout in ms
    };
    
    // DOM Ready state
    let isDOMReady = false;
    let isWindowLoaded = false;
    let preloaderStartTime = Date.now();
    
    /**
     * Initialize preloader
     */
    function initPreloader() {
        const preloader = document.getElementById('preloader');
        
        if (!preloader) {
            console.log('Preloader: Element not found, skipping initialization');
            return;
        }
        
        console.log('Preloader: Initializing...');
        preloaderStartTime = Date.now();
        
        // Set up event listeners
        setupEventListeners();
        
        // Fallback: Auto-hide after timeout (in case of any issues)
        setTimeout(function() {
            if (preloader && !preloader.classList.contains('hidden')) {
                console.log('Preloader: Fallback auto-hide triggered');
                hidePreloader();
            }
        }, PRELOADER_CONFIG.autoHideTimeout);
    }
    
    /**
     * Set up event listeners
     */
    function setupEventListeners() {
        // DOM Content Loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                isDOMReady = true;
                checkAndHidePreloader();
            });
        } else {
            isDOMReady = true;
        }
        
        // Window Load (all resources loaded)
        if (document.readyState === 'complete') {
            isWindowLoaded = true;
            checkAndHidePreloader();
        } else {
            window.addEventListener('load', function() {
                isWindowLoaded = true;
                checkAndHidePreloader();
            });
        }
    }
    
    /**
     * Check conditions and hide preloader
     */
    function checkAndHidePreloader() {
        const elapsedTime = Date.now() - preloaderStartTime;
        const remainingTime = Math.max(0, PRELOADER_CONFIG.minDisplayTime - elapsedTime);
        
        // Wait for minimum display time to complete loading animation
        setTimeout(function() {
            hidePreloader();
        }, remainingTime);
    }
    
    /**
     * Hide preloader with smooth transition
     */
    function hidePreloader() {
        const preloader = document.getElementById('preloader');
        
        if (!preloader) return;
        if (preloader.classList.contains('fade-out')) return; // Already hiding
        
        console.log('Preloader: Hiding...');
        
        // Start fade out
        preloader.classList.add('fade-out');
        
        // Remove from DOM after fade animation
        setTimeout(function() {
            preloader.classList.add('hidden');
            preloader.style.display = 'none';
            
            // Dispatch custom event for other scripts
            document.dispatchEvent(new CustomEvent('preloader:hidden'));
            
            console.log('Preloader: Hidden successfully');
        }, PRELOADER_CONFIG.fadeOutDuration);
    }
    
    /**
     * Show preloader (for programmatic use)
     */
    function showPreloader() {
        const preloader = document.getElementById('preloader');
        
        if (!preloader) return;
        
        preloader.classList.remove('hidden', 'fade-out');
        preloader.style.display = 'flex';
        preloaderStartTime = Date.now();
        
        console.log('Preloader: Shown');
    }
    
    // Initialize immediately
    initPreloader();
    
    // Expose functions globally for programmatic control
    window.TunasMotorPreloader = {
        hide: hidePreloader,
        show: showPreloader,
        config: PRELOADER_CONFIG
    };
    
})();
