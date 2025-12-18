<!-- Header -->
<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="{{ route('home') }}" style="display: flex; align-items: center; text-decoration: none; gap: 12px;">
                    <img src="{{ asset('assets/images/tmlogo.png') }}" alt="Tunas Motor" style="height: 60px; width: auto;" onerror="this.style.display='none'">
                    <span style="font-weight: 700; color: #111; font-size: 24px; letter-spacing: -0.5px;">TUNAS MOTOR</span>
                </a>
            </div>
            
            <nav class="nav">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">Produk</a>
                <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">Riwayat</a>
            </nav>
            
            <div class="header-actions">
                @if(!isset($hideSearch) || !$hideSearch)
                <div class="search-box">
                    <input type="text" placeholder="Cari produk..." id="searchInput">
                    <button class="search-btn">
                        <i class="fa-solid fa-magnifying-glass" style="font-size: 18px;"></i>
                    </button>
                </div>
                @endif
                
                <button class="icon-btn favorite-btn" onclick="window.location.href='{{ route('favorites.index') }}'" title="Favorit Saya">
                    <i class="fa-regular fa-heart"></i>
                    <span class="favorite-count" id="favoriteCount">0</span>
                </button>
                
                <button class="icon-btn cart-btn" onclick="window.location.href='{{ route('cart.index') }}'" title="Keranjang Belanja">
                    <i class="fa-solid fa-cart-shopping" style="font-size: 20px;"></i>
                    <span class="cart-count" id="cartCount">0</span>
                </button>
                
                <button class="icon-btn user-btn" onclick="toggleUserMenu()">
                    <i class="fa-regular fa-user" style="font-size: 20px;"></i>
                </button>
                
                <div class="user-menu" id="userMenu">
                    <div class="user-info">
                        <p class="user-name">{{ Auth::user()->name }}</p>
                        <p class="user-email">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.show') }}" class="user-menu-item">Lihat Profil</a>
                    <a href="{{ route('favorites.index') }}" class="user-menu-item">Favorit Saya</a>
                    <a href="{{ route('cart.index') }}" class="user-menu-item">Keranjang Saya</a>
                    <a href="{{ route('orders.index') }}" class="user-menu-item">Riwayat Pesanan</a>
                    <form method="POST" action="{{ route('logout') }}" class="user-menu-item">
                        @csrf
                        <button type="submit" class="logout-btn">Keluar</button>
                    </form>
                </div>
                
                <button class="btn-contact" onclick="toggleContactPopup()">Kontak</button>
            </div>
        </div>
    </div>
</header>
