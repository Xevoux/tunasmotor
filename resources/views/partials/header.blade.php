<!-- Header -->
<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="{{ route('home') }}" style="display: flex; align-items: center; text-decoration: none; gap: 12px;">
                    <img src="{{ asset('images/tmlogo.png') }}" alt="Tunas Motor" style="height: 60px; width: auto;" onerror="this.style.display='none'">
                    <span style="font-weight: 700; color: #111; font-size: 24px; letter-spacing: -0.5px;">TUNAS MOTOR</span>
                </a>
            </div>
            
            <nav class="nav">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('home') }}#products" class="nav-link">Products</a>
            </nav>
            
            <div class="header-actions">
                @if(!isset($hideSearch) || !$hideSearch)
                <div class="search-box">
                    <input type="text" placeholder="Search popular products..." id="searchInput">
                    <button class="search-btn">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M9 17A8 8 0 1 0 9 1a8 8 0 0 0 0 16zM19 19l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
                @endif
                
                <button class="icon-btn cart-btn" onclick="window.location.href='{{ route('cart.index') }}'">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    <span class="cart-count" id="cartCount">0</span>
                </button>
                
                <button class="icon-btn user-btn" onclick="toggleUserMenu()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </button>
                
                <div class="user-menu" id="userMenu">
                    <div class="user-info">
                        <p class="user-name">{{ Auth::user()->name }}</p>
                        <p class="user-email">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.show') }}" class="user-menu-item">Lihat Profile</a>
                    <a href="{{ route('cart.index') }}" class="user-menu-item">Keranjang Saya</a>
                    <form method="POST" action="{{ route('logout') }}" class="user-menu-item">
                        @csrf
                        <button type="submit" class="logout-btn">Keluar</button>
                    </form>
                </div>
                
                <a href="#contact" class="btn-contact">Contact</a>
            </div>
        </div>
    </div>
</header>

@push('scripts')
<script>
// Toggle User Menu
function toggleUserMenu() {
    const menu = document.getElementById('userMenu');
    menu.classList.toggle('show');
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    const menu = document.getElementById('userMenu');
    const btn = document.querySelector('.user-btn');
    
    if (!menu.contains(event.target) && !btn.contains(event.target)) {
        menu.classList.remove('show');
    }
});

// Update Cart Count
function updateCartCount() {
    fetch('{{ route('cart.count') }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('cartCount').textContent = data.count;
        });
}

// Initial cart count
updateCartCount();

@if(!isset($hideSearch) || !$hideSearch)
// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        const productName = card.querySelector('.product-name').textContent.toLowerCase();
        if (productName.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});
@endif
</script>
@endpush

