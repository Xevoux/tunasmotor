// App.js - Main JavaScript file for Tunas Motor

/* =====================================================
   CSRF TOKEN MANAGEMENT
   Handles session expiration and token refresh for production
   ===================================================== */

// Global CSRF token refresh interval (refresh every 30 minutes)
let csrfRefreshInterval = null;

// Initialize CSRF token management
function initializeCsrfManagement() {
    // Set up axios/fetch interceptor for CSRF errors
    setupCsrfErrorHandler();
    
    // Refresh CSRF token periodically (every 30 minutes)
    csrfRefreshInterval = setInterval(refreshCsrfToken, 30 * 60 * 1000);
    
    // Refresh on page visibility change (when user returns to tab)
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            refreshCsrfToken();
        }
    });
}

// Refresh CSRF token from server
function refreshCsrfToken() {
    fetch('/csrf-token', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        }
        throw new Error('Failed to refresh CSRF token');
    })
    .then(data => {
        if (data.token) {
            // Update the meta tag
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                csrfMeta.setAttribute('content', data.token);
            }
            // Update all hidden CSRF inputs in forms
            document.querySelectorAll('input[name="_token"]').forEach(input => {
                input.value = data.token;
            });
            console.log('CSRF token refreshed successfully');
        }
    })
    .catch(error => {
        console.warn('Could not refresh CSRF token:', error.message);
    });
}

// Handle CSRF/419 errors globally
function setupCsrfErrorHandler() {
    // Store original fetch
    const originalFetch = window.fetch;
    
    // Override fetch to handle 419 errors
    window.fetch = function(...args) {
        return originalFetch.apply(this, args)
            .then(response => {
                if (response.status === 419) {
                    // Session expired - refresh token and retry once, or redirect
                    console.warn('Session expired (419). Attempting to refresh...');
                    
                    // Show user-friendly notification
                    if (typeof showNotification === 'function') {
                        showNotification('Session kedaluwarsa. Memuat ulang...', 'warning');
                    }
                    
                    // Try to refresh the page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
                return response;
            });
    };
}

// Get current CSRF token
function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Tunas Motor App Loaded');
    
    // Initialize CSRF token management for production
    initializeCsrfManagement();
    
    // Check if we're on a page that needs user authentication
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    // Initialize header functionality with a small delay to ensure session is ready
    // This prevents race condition with login redirect
    if (csrfToken) {
        // If we just logged in (detected by referrer or session), add extra delay
        const isFromLogin = document.referrer && document.referrer.includes('/login');
        const delay = isFromLogin ? 500 : 100;
        
        setTimeout(function() {
            initializeHeaderCounts();
        }, delay);
    }
    
    // Smooth scroll for navigation links
    const navLinks = document.querySelectorAll('a[href^="#"]');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
    // Add active class to nav links on scroll (only for anchor links)
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('section[id]');
        const scrollPosition = window.scrollY + 100;
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                // Only update active class for anchor links (starting with #)
                // Don't touch nav-links that are page links (like Beranda, Produk, Riwayat)
                document.querySelectorAll('.nav-link[href^="#"]').forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === '#' + sectionId) {
                        link.classList.add('active');
                    }
                });
            }
        });
    });

    // Handle subscribe form submission
    const subscribeForm = document.getElementById('subscribe-form');
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', handleSubscribe);
    }

    // Handle profile subscribe form submission
    const profileSubscribeForm = document.getElementById('profile-subscribe-form');
    if (profileSubscribeForm) {
        profileSubscribeForm.addEventListener('submit', handleSubscribe);
    }

    // Handle unsubscribe form submission
    const unsubscribeForm = document.getElementById('unsubscribe-form');
    if (unsubscribeForm) {
        unsubscribeForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const submitButton = form.querySelector('.btn-unsubscribe');
            const originalText = submitButton.textContent;

            // Disable button and show loading
            submitButton.disabled = true;
            submitButton.textContent = 'Memproses...';

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Reload page to update subscription status
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showNotification(data.message || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
            })
            .finally(() => {
                // Re-enable button
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });
    }

    // Image lazy loading fallback
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
    
    // Form validation helper
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const inputs = form.querySelectorAll('input[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('error');
                } else {
                    input.classList.remove('error');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Mohon lengkapi semua field yang wajib diisi', 'error');
            }
        });
    });
    
    // Favorite functionality (database based)
    initializeFavoriteButtons();
});

/* =====================================================
   HEADER INITIALIZATION FUNCTIONS
   ===================================================== */

// Initialize all header counts and user data
function initializeHeaderCounts() {
    // Common headers for AJAX requests
    const headers = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    };
    
    // Update Cart Count
    fetch('/cart/count', { 
        method: 'GET',
        headers: headers
    })
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.error('Non-JSON response from cart/count');
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data) {
                const cartCount = document.getElementById('cartCount');
                if (cartCount) {
                    cartCount.textContent = data.count;
                }
            }
        })
        .catch(error => console.error('Error fetching cart count:', error));

    // Update Favorite Count
    fetch('/favorites/count', {
        method: 'GET',
        headers: headers
    })
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.error('Non-JSON response from favorites/count');
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data) {
                const favoriteCount = document.getElementById('favoriteCount');
                if (favoriteCount) {
                    favoriteCount.textContent = data.count;
                }
            }
        })
        .catch(error => console.error('Error fetching favorite count:', error));

    // Load user favorites for highlighting
    fetch('/favorites/list', {
        method: 'GET',
        headers: headers
    })
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.error('Non-JSON response from favorites/list');
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success && data.favorites) {
                window.userFavorites = data.favorites;
                highlightFavoriteProducts();
            }
        })
        .catch(error => console.error('Error fetching favorites list:', error));
}

/* =====================================================
   FAVORITE FUNCTIONS (Database-based)
   ===================================================== */

// User's favorite product IDs (loaded from server)
window.userFavorites = window.userFavorites || [];

// Initialize favorite button click handlers
function initializeFavoriteButtons() {
    document.querySelectorAll('.btn-favorite').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productCard = this.closest('.product-card');
            if (!productCard) return;
            
            const productId = productCard.dataset.productId;
            if (productId) {
                toggleFavorite(productId, this);
            }
        });
    });
}

// Toggle favorite status via AJAX
function toggleFavorite(productId, button) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showNotification('Silakan login terlebih dahulu', 'error');
        return;
    }
    
    // Optimistic UI update - toggle classes
    button.classList.toggle('active');
    toggleFavoriteIcon(button);
    
    fetch('/favorites/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken.content
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            
            // Update local favorites array
            const productIdInt = parseInt(productId);
            if (data.action === 'added') {
                if (!window.userFavorites.includes(productIdInt)) {
                    window.userFavorites.push(productIdInt);
                }
            } else {
                window.userFavorites = window.userFavorites.filter(id => id !== productIdInt);
            }
            
            // Update favorite count in header
            updateFavoriteCount();
        } else {
            // Revert UI on failure
            button.classList.toggle('active');
            toggleFavoriteIcon(button);
            showNotification(data.message || 'Gagal mengubah favorit', 'error');
        }
    })
    .catch(error => {
        console.error('Error toggling favorite:', error);
        // Revert UI on error
        button.classList.toggle('active');
        toggleFavoriteIcon(button);
        showNotification('Terjadi kesalahan', 'error');
    });
}

// Toggle Font Awesome heart icon between outline and solid
function toggleFavoriteIcon(button) {
    const icon = button.querySelector('i');
    if (!icon) return;
    
    if (button.classList.contains('active')) {
        // Change to solid (filled) heart
        icon.classList.remove('fa-regular');
        icon.classList.add('fa-solid');
    } else {
        // Change to regular (outline) heart
        icon.classList.remove('fa-solid');
        icon.classList.add('fa-regular');
    }
}

// Update favorite count in header
function updateFavoriteCount() {
    fetch('/favorites/count', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.error('Non-JSON response from favorites/count');
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data) {
                const favoriteCount = document.getElementById('favoriteCount');
                if (favoriteCount) {
                    favoriteCount.textContent = data.count;
                }
            }
        })
        .catch(error => console.error('Error fetching favorite count:', error));
}

// Load and highlight user's favorites
function loadFavorites() {
    fetch('/favorites/list', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.error('Non-JSON response from favorites/list');
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success && data.favorites) {
                window.userFavorites = data.favorites;
                highlightFavoriteProducts();
            }
        })
        .catch(error => console.error('Error loading favorites:', error));
}

// Highlight favorite products on page
function highlightFavoriteProducts() {
    document.querySelectorAll('.product-card').forEach(card => {
        const productId = parseInt(card.dataset.productId);
        const favoriteBtn = card.querySelector('.btn-favorite');
        
        if (favoriteBtn) {
            const icon = favoriteBtn.querySelector('i');
            
            if (window.userFavorites && window.userFavorites.includes(productId)) {
                // Product is favorited - show solid heart
                favoriteBtn.classList.add('active');
                if (icon) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                }
            } else {
                // Product is not favorited - show outline heart
                favoriteBtn.classList.remove('active');
                if (icon) {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                }
            }
        }
    });
}

// Remove favorite from favorites page
function removeFavorite(favoriteId, element) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) return;
    
    if (!confirm('Hapus produk dari favorit?')) return;
    
    fetch(`/favorites/${favoriteId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken.content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            
            // Remove the item from DOM
            const favoriteItem = element.closest('.favorite-item');
            if (favoriteItem) {
                favoriteItem.style.opacity = '0';
                favoriteItem.style.transform = 'translateX(20px)';
                setTimeout(() => {
                    favoriteItem.remove();
                    
                    // Check if no more favorites
                    const remainingItems = document.querySelectorAll('.favorite-item');
                    if (remainingItems.length === 0) {
                        const emptyState = document.getElementById('emptyFavorites');
                        const favoritesGrid = document.getElementById('favoritesGrid');
                        if (emptyState) emptyState.style.display = 'block';
                        if (favoritesGrid) favoritesGrid.style.display = 'none';
                    }
                }, 300);
            }
            
            // Update count
            updateFavoriteCount();
        } else {
            showNotification(data.message || 'Gagal menghapus favorit', 'error');
        }
    })
    .catch(error => {
        console.error('Error removing favorite:', error);
        showNotification('Terjadi kesalahan', 'error');
    });
}

// Format currency helper
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

// Show notification helper - Toast notification dengan icon
function showNotification(message, type = 'info') {
    // Remove existing notifications to prevent stacking
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(n => n.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    // Get icon based on type
    let iconSvg = '';
    if (type === 'success') {
        iconSvg = '<svg class="notification-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    } else if (type === 'error') {
        iconSvg = '<svg class="notification-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    } else if (type === 'warning') {
        iconSvg = '<svg class="notification-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
    } else {
        iconSvg = '<svg class="notification-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    }
    
    notification.innerHTML = `
        <div class="notification-content">
            ${iconSvg}
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Show notification with animation
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Auto-hide after 4 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 4000);
}

// Placeholder wishlist handler to prevent ReferenceError before feature exists
function toggleWishlist() {
    console.warn('toggleWishlist is not implemented yet.');
}

// Export functions for use in other scripts
window.tunasMotor = {
    formatCurrency,
    showNotification,
    toggleWishlist
};

/* =====================================================
   CART PAGE FUNCTIONS
   ===================================================== */

// Update Quantity
function updateQuantity(cartId, change) {
    const input = document.getElementById('qty-' + cartId);
    if (!input) return;
    
    let currentValue = parseInt(input.value);
    let newValue = currentValue + change;
    
    if (newValue < 1) return;
    
    input.value = newValue;
    saveQuantity(cartId, newValue);
}

function updateQuantityInput(cartId) {
    const input = document.getElementById('qty-' + cartId);
    if (!input) return;
    
    let value = parseInt(input.value);
    
    if (value < 1) {
        input.value = 1;
        value = 1;
    }
    
    saveQuantity(cartId, value);
}

function saveQuantity(cartId, quantity) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) return;
    
    fetch(`/cart/${cartId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken.content
        },
        body: JSON.stringify({ jumlah: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Remove Item
function removeItem(cartId) {
    if (!confirm('Apakah Anda yakin ingin menghapus produk ini?')) return;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) return;
    
    fetch(`/cart/${cartId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken.content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}


// Clear All Cart Items
function clearAll() {
    if (!confirm('Apakah Anda yakin ingin menghapus semua produk?')) return;
    
    const cartItems = document.querySelectorAll('.cart-item');
    cartItems.forEach(item => {
        const cartId = item.dataset.cartId;
        removeItem(cartId);
    });
}

// Proceed to Checkout
function proceedToCheckout() {
    window.location.href = '/checkout';
}

/* =====================================================
   HOME PAGE FUNCTIONS
   ===================================================== */

// Countdown Timer
function updateCountdown() {
    const hoursEl = document.getElementById('hours');
    const minutesEl = document.getElementById('minutes');
    const secondsEl = document.getElementById('seconds');
    
    if (!hoursEl || !minutesEl || !secondsEl) return;
    
    const now = new Date();
    const midnight = new Date();
    midnight.setHours(24, 0, 0, 0);
    
    const diff = midnight - now;
    
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    
    hoursEl.textContent = String(hours).padStart(2, '0');
    minutesEl.textContent = String(minutes).padStart(2, '0');
    secondsEl.textContent = String(seconds).padStart(2, '0');
}

// Initialize countdown if elements exist
if (document.getElementById('hours')) {
    setInterval(updateCountdown, 1000);
    updateCountdown();
}

// Add to Cart
function addToCart(productId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showNotification('Silakan login terlebih dahulu', 'error');
        return;
    }
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken.content
        },
        body: JSON.stringify({
            product_id: productId,
            jumlah: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            updateCartCount();
        } else {
            showNotification(data.message || 'Gagal menambahkan produk ke keranjang', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan', 'error');
    });
}

// Update Cart Count
function updateCartCount() {
    const cartCountEl = document.getElementById('cartCount');
    const cartCountElAlt = document.querySelector('.cart-count');
    
    fetch('/cart/count', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.error('Non-JSON response from cart/count');
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.count !== undefined) {
                // Update all cart count elements
                if (cartCountEl) {
                    cartCountEl.textContent = data.count;
                }
                if (cartCountElAlt && cartCountElAlt !== cartCountEl) {
                    cartCountElAlt.textContent = data.count;
                }
            }
        })
        .catch(error => {
            console.error('Error updating cart count:', error);
        });
}

// Handle Subscribe
function handleSubscribe(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const submitButton = form.querySelector('.btn-subscribe');
    const originalText = submitButton.textContent;

    // Disable button and show loading
    submitButton.disabled = true;
    submitButton.textContent = 'Mengirim...';

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            form.reset();
        } else {
            showNotification(data.message || 'Terjadi kesalahan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
    })
    .finally(() => {
        // Re-enable button
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    });
}


/* =====================================================
   PAYMENT PAGE FUNCTIONS (Midtrans)
   ===================================================== */

// Initialize Payment Button
function initializePaymentButton(snapToken, orderId, finishUrl) {
    const payButton = document.getElementById('pay-button');
    if (!payButton || !snapToken) return;
    
    payButton.addEventListener('click', function() {
        if (typeof snap !== 'undefined') {
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    window.location.href = finishUrl + '?transaction_status=settlement';
                },
                onPending: function(result) {
                    window.location.href = finishUrl + '?transaction_status=pending';
                },
                onError: function(result) {
                    showNotification('Pembayaran gagal! Silakan coba lagi.', 'error');
                    console.error(result);
                },
                onClose: function() {
                    console.log('Payment popup closed');
                }
            });
        }
    });
}

/* =====================================================
   WELCOME / SPLASH SCREEN FUNCTIONS
   ===================================================== */

// Initialize Splash Screen - runs immediately when called
function initializeSplashScreen() {
    const splashScreen = document.getElementById('splash-screen');
    const welcomeScreen = document.getElementById('welcome-screen');
    
    // If elements don't exist, exit early
    if (!splashScreen || !welcomeScreen) {
        console.log('Splash screen elements not found, skipping initialization');
        return;
    }
    
    console.log('Initializing splash screen...');
    
    // After loader animation completes (2.5s), transition to welcome
    setTimeout(function() {
        console.log('Fading out splash screen...');
        splashScreen.style.opacity = '0';
        
        setTimeout(function() {
            splashScreen.style.display = 'none';
            welcomeScreen.style.display = 'block';
            
            // Force reflow before opacity transition
            welcomeScreen.offsetHeight;
            
            setTimeout(function() {
                welcomeScreen.style.opacity = '1';
                console.log('Welcome screen displayed');
            }, 50);
        }, 500);
    }, 2500);
}

// Tab Switcher for Auth Forms
function switchTab(tab) {
    const registerTab = document.getElementById('registerTab');
    const loginTab = document.getElementById('loginTab');
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');

    if (!registerTab || !loginTab || !registerForm || !loginForm) return;

    if (tab === 'register') {
        registerTab.classList.add('active');
        loginTab.classList.remove('active');
        registerForm.style.display = 'block';
        loginForm.style.display = 'none';
    } else {
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
    }
}

// Show welcome screen after preloader is hidden (for welcome page)
document.addEventListener('preloader:hidden', function() {
    const welcomeScreen = document.getElementById('welcome-screen');
    if (welcomeScreen) {
        welcomeScreen.style.display = 'block';
        // Force reflow
        welcomeScreen.offsetHeight;
        setTimeout(function() {
            welcomeScreen.style.opacity = '1';
        }, 50);
    }
});

// Initialize splash screen as soon as possible
// Use both DOMContentLoaded and load events for reliability
(function() {
    // Try to initialize on DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeSplashScreen);
    } else {
        // DOM already loaded, run immediately
        initializeSplashScreen();
    }
})();

/* =====================================================
   AUTH PAGE FUNCTIONS
   ===================================================== */

// Toggle Password Visibility
function togglePassword(fieldId) {
    const field = fieldId || 'password';
    const passwordInput = document.getElementById(field);
    if (passwordInput) {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
    }
}

/* =====================================================
   HEADER / USER MENU FUNCTIONS
   ===================================================== */

// Toggle User Menu
function toggleUserMenu() {
    const menu = document.getElementById('userMenu');
    if (menu) {
        menu.classList.toggle('show');
    }
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    const menu = document.getElementById('userMenu');
    const btn = document.querySelector('.user-profile-btn');
    const wrapper = document.querySelector('.user-profile-wrapper');

    if (menu && menu.classList.contains('show')) {
        // Check if click is outside the dropdown and the button
        const isClickInsideMenu = menu.contains(event.target);
        const isClickOnButton = btn && btn.contains(event.target);
        const isClickInsideWrapper = wrapper && wrapper.contains(event.target);
        
        if (!isClickInsideMenu && !isClickOnButton) {
            menu.classList.remove('show');
        }
    }
});

// Initialize Search functionality
function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;
    
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const productCards = document.querySelectorAll('.product-card');
        
        productCards.forEach(card => {
            const productName = card.querySelector('.product-name');
            if (productName) {
                const name = productName.textContent.toLowerCase();
                if (name.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            }
        });
    });
}

// Initialize search on DOM load
document.addEventListener('DOMContentLoaded', initializeSearch);

/* =====================================================
   PRODUCTS FILTER & SORT FUNCTIONS
   ===================================================== */

// Current filter state
let currentFilters = {
    category: 'all',
    sort: 'popular'
};

// Initialize filter functionality
function initializeProductFilters() {
    const categoryTabs = document.querySelectorAll('.category-tab');
    const sortSelect = document.getElementById('sortSelect');
    
    // Category tab click handlers
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active state
            categoryTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Update filter state
            currentFilters.category = this.dataset.category;
            
            // Apply filters
            applyFilters();
        });
    });
    
    // Sort select change handler
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            currentFilters.sort = this.value;
            applyFilters();
        });
    }
    
    // Initialize current state from URL params
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('category')) {
        currentFilters.category = urlParams.get('category');
    }
    if (urlParams.has('sort')) {
        currentFilters.sort = urlParams.get('sort');
    }
}

// Apply filters (AJAX request)
function applyFilters() {
    const productsGrid = document.getElementById('productsGrid');
    const productsLoading = document.getElementById('productsLoading');
    const productsEmpty = document.getElementById('productsEmpty');
    const productsCount = document.getElementById('productsCount');
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (!productsGrid) return;
    
    // Show loading state
    productsGrid.style.display = 'none';
    if (productsEmpty) productsEmpty.style.display = 'none';
    if (productsLoading) productsLoading.style.display = 'flex';
    
    // Build query string
    const params = new URLSearchParams({
        category: currentFilters.category,
        sort: currentFilters.sort
    });
    
    // Update URL without reload
    const newUrl = window.location.pathname + '?' + params.toString();
    window.history.pushState({ path: newUrl }, '', newUrl);
    
    // Fetch filtered products
    fetch('/api/products/filter?' + params.toString(), {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken ? csrfToken.content : ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (productsLoading) productsLoading.style.display = 'none';
        
        if (data.success && data.products.length > 0) {
            // Render products
            renderProducts(data.products);
            productsGrid.style.display = 'grid';
            if (productsCount) productsCount.textContent = data.count;
        } else {
            // Show empty state
            if (productsEmpty) productsEmpty.style.display = 'block';
            if (productsCount) productsCount.textContent = '0';
        }
    })
    .catch(error => {
        console.error('Error fetching products:', error);
        if (productsLoading) productsLoading.style.display = 'none';
        productsGrid.style.display = 'grid';
        showNotification('Gagal memuat produk. Silakan coba lagi.', 'error');
    });
}

// Render products to grid
function renderProducts(products) {
    const productsGrid = document.getElementById('productsGrid');
    if (!productsGrid) return;
    
    productsGrid.innerHTML = products.map(product => {
        const imageUrl = product.gambar 
            ? `/storage/${product.gambar}` 
            : '/images/product-placeholder.png';
        
        const placeholderSvg = `data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22300%22 height=%22300%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22monospace%22 font-size=%2216px%22 fill=%22%23999%22%3ENo Image%3C/text%3E%3C/svg%3E`;
        
        const categoryName = product.category ? product.category.nama : 'Uncategorized';
        
        // Price display
        let priceHtml;
        if (product.harga_diskon) {
            priceHtml = `
                <span class="price-discounted">Rp${formatNumber(product.harga_diskon)}</span>
                <span class="price-original">Rp${formatNumber(product.harga)}</span>
            `;
        } else {
            priceHtml = `<span class="price">Rp${formatNumber(product.harga)}</span>`;
        }
        
        // Discount badge
        const discountBadge = product.diskon_persen 
            ? `<span class="badge-discount">-${product.diskon_persen}%</span>` 
            : '';
        
        return `
            <div class="product-card" data-product-id="${product.id}">
                ${discountBadge}
                <div class="product-image">
                    <img src="${imageUrl}" 
                         alt="${product.nama}"
                         onerror="this.src='${placeholderSvg}'">
                    <button class="btn-favorite" title="Tambah ke Favorit">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                </div>
                <div class="product-info">
                    <span class="product-category">${categoryName}</span>
                    <h3 class="product-name">${product.nama}</h3>
                    <div class="product-price">
                        ${priceHtml}
                    </div>
                    <div class="product-stock">
                        <span>Tersedia: ${product.stok}</span>
                        <span>Terjual: ${product.terjual}</span>
                    </div>
                    <button class="btn-add-cart" onclick="addToCart(${product.id})">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        `;
    }).join('');
    
    // Re-initialize favorite buttons and highlight favorites
    initializeFavoriteButtons();
    highlightFavoriteProducts();
}

// Format number helper (for Indonesian format)
function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

// Reset filters
function resetFilters() {
    currentFilters = {
        category: 'all',
        sort: 'popular'
    };
    
    // Reset UI
    const categoryTabs = document.querySelectorAll('.category-tab');
    categoryTabs.forEach(tab => {
        tab.classList.remove('active');
        if (tab.dataset.category === 'all') {
            tab.classList.add('active');
        }
    });
    
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.value = 'popular';
    }
    
    // Apply reset filters
    applyFilters();
}

// Initialize product filters on DOM load
document.addEventListener('DOMContentLoaded', function() {
    initializeProductFilters();
});

/* =====================================================
   SCROLL TOP BUTTON FUNCTIONS
   ===================================================== */

// Initialize scroll top button
function initScrollTop() {
    var scrollTop = document.querySelector(".scroll-top");
    if (scrollTop == null) {
        return;
    }
    
    // Prevent double initialization
    if (scrollTop.classList.contains('initialized')) {
        return;
    }
    scrollTop.classList.add('initialized');
    
    var scrollProgressPath = document.querySelector(".scroll-top .progress-circle path");
    var pathLength = 0;
    var offset = 200; // Show after scrolling 200px
    
    // Setup progress circle if path exists
    if (scrollProgressPath) {
        try {
            pathLength = scrollProgressPath.getTotalLength();
            scrollProgressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
            scrollProgressPath.style.strokeDashoffset = pathLength;
        } catch(e) {
            // SVG path methods may not be available in all browsers
        }
    }
    
    // Function to update scroll button visibility
    function updateScrollTop() {
        var scroll = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
        var height = document.documentElement.scrollHeight - window.innerHeight;
        
        // Show/hide button based on scroll position (using 'visible' class as per CSS)
        if (scroll > offset) {
            scrollTop.classList.add("visible");
        } else {
            scrollTop.classList.remove("visible");
        }
        
        // Update progress circle
        if (scrollProgressPath && pathLength > 0 && height > 0) {
            var progress = pathLength - (scroll * pathLength / height);
            scrollProgressPath.style.strokeDashoffset = progress;
        }
    }
    
    // Scroll event listener
    window.addEventListener("scroll", updateScrollTop);
    
    // Initial check
    updateScrollTop();
    
    // Click event listener
    scrollTop.addEventListener("click", function (e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', initScrollTop);

/* =====================================================
   CONTACT POPUP FUNCTIONS
   ===================================================== */

// Toggle contact popup
function toggleContactPopup() {
    var popup = document.getElementById('contactPopup');
    if (popup) {
        popup.classList.toggle('active');
    }
}

// Close contact popup when clicking outside
document.addEventListener('click', function(event) {
    var popup = document.getElementById('contactPopup');
    var headerBtn = document.querySelector('.btn-contact');
    
    if (popup && popup.classList.contains('active')) {
        // Check if click is outside popup and outside header button
        var clickedOutsidePopup = !popup.contains(event.target);
        var clickedOutsideBtn = !headerBtn || !headerBtn.contains(event.target);
        
        if (clickedOutsidePopup && clickedOutsideBtn) {
            popup.classList.remove('active');
        }
    }
});

// Close contact popup on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        var popup = document.getElementById('contactPopup');
        if (popup) {
            popup.classList.remove('active');
        }
    }
});

/* =====================================================
   IMAGE SLIDER AUTOPLAY FUNCTIONALITY
   =====================================================
   Features:
   - Auto-play every 5 seconds
   - Pause on hover
   - Manual navigation (arrows, dots, keyboard)
   - Touch/swipe support for mobile
   - Smooth animations with CSS transitions
   - Progress bar indicator
   - Keyboard shortcuts (arrows, spacebar)
   ===================================================== */

document.addEventListener('DOMContentLoaded', function() {
    // Image Slider AutoPlay Functionality
    const slider = {
        currentSlide: 0,
        slides: document.querySelectorAll('.slider-slide'),
        dots: document.querySelectorAll('.slider-dot'),
        prevBtn: document.querySelector('.slider-arrow-prev'),
        nextBtn: document.querySelector('.slider-arrow-next'),
        autoPlayInterval: null,
        autoPlayDelay: 5000, // 5 seconds
        isPaused: false,

        init() {
            if (this.slides.length <= 1) {
                console.log('Image Slider: Only one slide found, autoplay disabled');
                return;
            }

            this.bindEvents();
            this.startAutoPlay();
            console.log('Image Slider initialized with', this.slides.length, 'slides');
            console.log('Auto-play interval:', this.autoPlayDelay + 'ms');
        },

        bindEvents() {
            // Dot navigation
            this.dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    this.goToSlide(index);
                    this.pauseAutoPlay(); // Pause after manual interaction
                });
            });

            // Arrow navigation
            if (this.prevBtn) {
                this.prevBtn.addEventListener('click', () => {
                    this.prevSlide();
                    this.pauseAutoPlay();
                });
            }
            if (this.nextBtn) {
                this.nextBtn.addEventListener('click', () => {
                    this.nextSlide();
                    this.pauseAutoPlay();
                });
            }

            // Pause on hover
            const sliderContainer = document.querySelector('.slider-container');
            if (sliderContainer) {
                sliderContainer.addEventListener('mouseenter', () => this.pauseAutoPlay());
                sliderContainer.addEventListener('mouseleave', () => this.resumeAutoPlay());
            }

            // Touch/swipe support for mobile
            let touchStartX = 0;
            let touchEndX = 0;

            sliderContainer.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
                this.pauseAutoPlay();
            });

            sliderContainer.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                this.handleSwipe();
                this.resumeAutoPlay();
            });

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    this.prevSlide();
                    this.pauseAutoPlay();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    this.nextSlide();
                    this.pauseAutoPlay();
                } else if (e.key === ' ') { // Spacebar to pause/resume
                    e.preventDefault();
                    if (this.isPaused) {
                        this.resumeAutoPlay();
                    } else {
                        this.pauseAutoPlay();
                    }
                }
            });
        },

        handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe left - next slide
                    this.nextSlide();
                } else {
                    // Swipe right - previous slide
                    this.prevSlide();
                }
            }
        },

        goToSlide(index) {
            if (index === this.currentSlide || index < 0 || index >= this.slides.length) return;

            // Add loading state
            const sliderWrapper = document.querySelector('.slider-wrapper');
            if (sliderWrapper) {
                sliderWrapper.classList.add('transitioning');
            }

            // Remove active class from current slide and dot
            if (this.slides[this.currentSlide]) {
                this.slides[this.currentSlide].classList.remove('active');
            }
            if (this.dots[this.currentSlide]) {
                this.dots[this.currentSlide].classList.remove('active');
            }

            // Add active class to new slide and dot
            if (this.slides[index]) {
                this.slides[index].classList.add('active');
            }
            if (this.dots[index]) {
                this.dots[index].classList.add('active');
            }

            this.currentSlide = index;

            // Remove loading state after transition
            setTimeout(() => {
                if (sliderWrapper) {
                    sliderWrapper.classList.remove('transitioning');
                }
            }, 600);
        },

        nextSlide() {
            const nextIndex = (this.currentSlide + 1) % this.slides.length;
            this.goToSlide(nextIndex);
        },

        prevSlide() {
            const prevIndex = this.currentSlide === 0 ? this.slides.length - 1 : this.currentSlide - 1;
            this.goToSlide(prevIndex);
        },

        startAutoPlay() {
            this.clearAutoPlay();
            this.isPaused = false;
            this.autoPlayInterval = setInterval(() => {
                if (!this.isPaused) {
                    this.nextSlide();
                }
            }, this.autoPlayDelay);
        },

        pauseAutoPlay() {
            this.isPaused = true;
        },

        resumeAutoPlay() {
            this.isPaused = false;
        },

        clearAutoPlay() {
            if (this.autoPlayInterval) {
                clearInterval(this.autoPlayInterval);
                this.autoPlayInterval = null;
            }
        }
    };

    // Initialize slider
    slider.init();
});

/*-- scroll top & contact popup scripts end --*/

/* =====================================================
   PROFILE PHOTO UPLOAD/CROP FUNCTIONS
   ===================================================== */

// Profile photo state variables (initialized by profile page)
let profilePhotoState = {
    originalPhotoUrl: '',
    hasOriginalPhoto: false,
    userInitials: '',
    photoChanged: false,
    photoMarkedForRemoval: false,
    cropper: null,
    currentFile: null
};

// Initialize profile photo functionality
function initializeProfilePhoto(originalUrl, hasPhoto, initials) {
    profilePhotoState.originalPhotoUrl = originalUrl || '';
    profilePhotoState.hasOriginalPhoto = hasPhoto;
    profilePhotoState.userInitials = initials || '';
    profilePhotoState.photoChanged = false;
    profilePhotoState.photoMarkedForRemoval = false;
    profilePhotoState.cropper = null;
    profilePhotoState.currentFile = null;
}

// Toggle photo actions menu
function togglePhotoMenu() {
    const menu = document.getElementById('photoActionsMenu');
    if (menu) {
        menu.classList.toggle('show');
    }
}

// Close photo menu when clicking outside
document.addEventListener('click', function(event) {
    const menu = document.getElementById('photoActionsMenu');
    const wrapper = document.getElementById('profileAvatarWrapper');
    
    if (menu && wrapper && !wrapper.contains(event.target) && !menu.contains(event.target)) {
        menu.classList.remove('show');
    }
});

// Trigger file input click
function selectProfilePhoto() {
    const photoInput = document.getElementById('photoInput');
    if (photoInput) {
        photoInput.click();
    }
    const menu = document.getElementById('photoActionsMenu');
    if (menu) {
        menu.classList.remove('show');
    }
}

// Handle file selection - open crop modal
function previewProfilePhoto(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file size (max 10MB for original, will be compressed after crop)
        if (file.size > 10 * 1024 * 1024) {
            showPhotoToast('Ukuran gambar maksimal 10MB', 'error');
            input.value = '';
            return;
        }
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            showPhotoToast('Format gambar harus JPEG, PNG, JPG, GIF, atau WebP', 'error');
            input.value = '';
            return;
        }
        
        profilePhotoState.currentFile = file;
        
        // Read file and open crop modal
        const reader = new FileReader();
        reader.onload = function(e) {
            openCropModal(e.target.result);
        };
        reader.readAsDataURL(file);
    }
}

// Open crop modal
function openCropModal(imageSrc) {
    const modal = document.getElementById('cropModal');
    const cropImage = document.getElementById('cropImage');
    
    if (!modal || !cropImage) return;
    
    // Set image source
    cropImage.src = imageSrc;
    
    // Show modal
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Initialize cropper after image loads
    cropImage.onload = function() {
        // Destroy existing cropper if any
        if (profilePhotoState.cropper) {
            profilePhotoState.cropper.destroy();
        }
        
        // Initialize Cropper.js
        if (typeof Cropper !== 'undefined') {
            profilePhotoState.cropper = new Cropper(cropImage, {
                aspectRatio: 1, // Square crop for profile photo
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.8,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                minContainerWidth: 300,
                minContainerHeight: 300,
            });
        }
    };
}

// Close crop modal
function closeCropModal() {
    const modal = document.getElementById('cropModal');
    if (modal) {
        modal.classList.remove('show');
    }
    document.body.style.overflow = '';
    
    // Destroy cropper
    if (profilePhotoState.cropper) {
        profilePhotoState.cropper.destroy();
        profilePhotoState.cropper = null;
    }
    
    // Clear file input if cancelled
    if (!profilePhotoState.photoChanged) {
        const photoInput = document.getElementById('photoInput');
        if (photoInput) {
            photoInput.value = '';
        }
    }
}

// Apply crop and get cropped image
function applyCrop() {
    if (!profilePhotoState.cropper) return;
    
    // Get cropped canvas
    const canvas = profilePhotoState.cropper.getCroppedCanvas({
        width: 400,  // Output size
        height: 400,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    });
    
    // Convert canvas to blob
    canvas.toBlob(function(blob) {
        // Create a new file from blob
        const croppedFile = new File([blob], profilePhotoState.currentFile.name, {
            type: 'image/jpeg',
            lastModified: Date.now()
        });
        
        // Create a DataTransfer to update file input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(croppedFile);
        const photoInput = document.getElementById('photoInput');
        if (photoInput) {
            photoInput.files = dataTransfer.files;
        }
        
        // Get data URL for preview
        const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.9);
        
        // Update avatar display
        updateAvatarDisplay(croppedDataUrl);
        
        // Mark as changed
        profilePhotoState.photoChanged = true;
        profilePhotoState.photoMarkedForRemoval = false;
        const removePhotoInput = document.getElementById('removePhotoInput');
        if (removePhotoInput) {
            removePhotoInput.value = '0';
        }
        
        // Show change indicator
        showPhotoChangeIndicator('Foto baru akan disimpan saat klik "Simpan Perubahan"');
        
        // Show remove button
        const removePhotoBtn = document.getElementById('removePhotoBtn');
        const photoActionText = document.getElementById('photoActionText');
        if (removePhotoBtn) removePhotoBtn.style.display = 'flex';
        if (photoActionText) photoActionText.textContent = 'Ganti Foto';
        
        // Close modal
        closeCropModal();
        
        showPhotoToast('Foto berhasil di-crop. Klik "Simpan Perubahan" untuk menyimpan.', 'success');
    }, 'image/jpeg', 0.9);
}

// Mark photo for removal (will be removed on save)
function markPhotoForRemoval() {
    const menu = document.getElementById('photoActionsMenu');
    if (menu) menu.classList.remove('show');
    
    // Clear file input
    const photoInput = document.getElementById('photoInput');
    if (photoInput) photoInput.value = '';
    
    // Mark for removal
    profilePhotoState.photoMarkedForRemoval = true;
    profilePhotoState.photoChanged = false;
    const removePhotoInput = document.getElementById('removePhotoInput');
    if (removePhotoInput) removePhotoInput.value = '1';
    
    // Show placeholder
    showPlaceholder();
    
    // Update UI
    const removePhotoBtn = document.getElementById('removePhotoBtn');
    const photoActionText = document.getElementById('photoActionText');
    if (removePhotoBtn) removePhotoBtn.style.display = 'none';
    if (photoActionText) photoActionText.textContent = 'Upload Foto';
    
    // Show change indicator
    showPhotoChangeIndicator('Foto akan dihapus saat klik "Simpan Perubahan"', true);
    
    showPhotoToast('Foto akan dihapus. Klik "Simpan Perubahan" untuk konfirmasi.', 'success');
}

// Show placeholder instead of photo
function showPlaceholder() {
    const wrapper = document.getElementById('profileAvatarWrapper');
    const existingImg = document.getElementById('profileAvatarImg');
    let placeholder = document.getElementById('profileAvatarPlaceholder');
    
    if (!wrapper) return;
    
    if (existingImg) {
        existingImg.remove();
    }
    
    if (!placeholder) {
        placeholder = document.createElement('div');
        placeholder.className = 'profile-avatar-placeholder';
        placeholder.id = 'profileAvatarPlaceholder';
        placeholder.innerHTML = `<span>${profilePhotoState.userInitials}</span>`;
        wrapper.insertBefore(placeholder, wrapper.firstChild);
    }
}

// Update avatar display with new image
function updateAvatarDisplay(photoUrl) {
    const wrapper = document.getElementById('profileAvatarWrapper');
    let existingImg = document.getElementById('profileAvatarImg');
    const placeholder = document.getElementById('profileAvatarPlaceholder');
    
    if (!wrapper) return;
    
    if (placeholder) {
        placeholder.remove();
    }
    
    if (existingImg) {
        existingImg.src = photoUrl;
    } else {
        const img = document.createElement('img');
        img.src = photoUrl;
        img.alt = 'Profile Photo';
        img.className = 'profile-avatar-img';
        img.id = 'profileAvatarImg';
        wrapper.insertBefore(img, wrapper.firstChild);
    }
}

// Show photo change indicator
function showPhotoChangeIndicator(message, isRemoval) {
    isRemoval = isRemoval || false;
    const indicator = document.getElementById('photoChangeIndicator');
    const text = document.getElementById('photoChangeText');
    
    if (indicator && text) {
        text.textContent = message;
        indicator.style.display = 'flex';
        indicator.className = 'photo-change-indicator' + (isRemoval ? ' photo-removal' : '');
    }
}

// Hide photo change indicator
function hidePhotoChangeIndicator() {
    const indicator = document.getElementById('photoChangeIndicator');
    if (indicator) {
        indicator.style.display = 'none';
    }
}

// Reset to original photo
function resetPhoto() {
    profilePhotoState.photoChanged = false;
    profilePhotoState.photoMarkedForRemoval = false;
    
    const removePhotoInput = document.getElementById('removePhotoInput');
    const photoInput = document.getElementById('photoInput');
    const removePhotoBtn = document.getElementById('removePhotoBtn');
    const photoActionText = document.getElementById('photoActionText');
    
    if (removePhotoInput) removePhotoInput.value = '0';
    if (photoInput) photoInput.value = '';
    
    if (profilePhotoState.hasOriginalPhoto && profilePhotoState.originalPhotoUrl) {
        updateAvatarDisplay(profilePhotoState.originalPhotoUrl);
        if (removePhotoBtn) removePhotoBtn.style.display = 'flex';
        if (photoActionText) photoActionText.textContent = 'Ganti Foto';
    } else {
        showPlaceholder();
        if (removePhotoBtn) removePhotoBtn.style.display = 'none';
        if (photoActionText) photoActionText.textContent = 'Upload Foto';
    }
    
    hidePhotoChangeIndicator();
}

// Show toast notification for photo actions
function showPhotoToast(message, type) {
    // Remove existing toast if any
    const existingToast = document.querySelector('.photo-toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.className = `photo-toast photo-toast-${type}`;
    toast.innerHTML = `
        <i class="fa-solid ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    document.body.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => toast.classList.add('show'), 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Close crop modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCropModal();
    }
});

// Close crop modal on overlay click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('cropModal');
    if (modal && e.target === modal) {
        closeCropModal();
    }
});