<!-- Header -->
<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="{{ route('home') }}" style="display: flex; align-items: center; text-decoration: none; gap: 12px;">
                    <img src="{{ asset('assets/images/tmlogo2.png') }}" alt="Tunas Motor" style="height: 50px; width: auto;" onerror="this.style.display='none'">
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
                
                <!-- User Profile Button with Photo -->
                <div class="user-profile-wrapper">
                    <button class="user-profile-btn" onclick="toggleUserMenu()" title="Profil Saya">
                        @if(Auth::user()->hasProfilePhoto())
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="user-avatar-img">
                        @else
                            <div class="user-avatar-icon">
                                <i class="fa-regular fa-user"></i>
                            </div>
                        @endif
                    </button>
                    
                    <!-- Enhanced User Dropdown Menu -->
                    <div class="user-dropdown-menu" id="userMenu">
                        <div class="user-dropdown-header">
                            <div class="user-dropdown-avatar">
                                @if(Auth::user()->hasProfilePhoto())
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                                @else
                                    <div class="avatar-placeholder">
                                        <span>{{ Auth::user()->initials }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="user-dropdown-info">
                                <p class="user-dropdown-name">{{ Auth::user()->name }}</p>
                                <p class="user-dropdown-email">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        
                        <div class="user-dropdown-divider"></div>
                        
                        <div class="user-dropdown-body">
                            <a href="{{ route('profile.show') }}" class="user-dropdown-item">
                                <i class="fa-regular fa-user"></i>
                                <span>Lihat Profil</span>
                            </a>
                            <a href="{{ route('favorites.index') }}" class="user-dropdown-item">
                                <i class="fa-regular fa-heart"></i>
                                <span>Favorit Saya</span>
                            </a>
                            <a href="{{ route('cart.index') }}" class="user-dropdown-item">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span>Keranjang Saya</span>
                            </a>
                            <a href="{{ route('orders.index') }}" class="user-dropdown-item">
                                <i class="fa-solid fa-box"></i>
                                <span>Riwayat Pesanan</span>
                            </a>
                        </div>
                        
                        <div class="user-dropdown-divider"></div>
                        
                        <div class="user-dropdown-footer">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="user-dropdown-logout">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <button class="btn-contact" onclick="toggleContactPopup()">Kontak</button>
            </div>
        </div>
    </div>
</header>
