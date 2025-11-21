@extends('layouts.app')

@section('title', 'Home - Tunas Motor')

@section('content')
@include('partials.header')

<!-- Hero Section -->
<section class="hero" id="home">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <div class="deal-badge">
                    <span class="deal-label">REFRESHIN WINTER DEALS</span>
                    <div class="countdown" id="countdown">
                        <span id="hours">09</span>:<span id="minutes">36</span>:<span id="seconds">21</span>
                    </div>
                </div>
                
                <h1 class="hero-title">
                    LENGKAPI MOTOR ANDA.<br>
                    TEMUKAN SUKU CADANG<br>
                    TERBAIK.
                </h1>
                
                <p class="hero-description">
                    Tingkatkan performa motor Anda dengan suku cadang berkualitas tinggi dan andal.<br>
                    Komponen terjui yang memberikan ketenangan saat berkendara.
                </p>
                
                <button class="btn-shop" onclick="document.getElementById('products').scrollIntoView({behavior: 'smooth'})">
                    Shop Now
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M5 10h10M10 5l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
            
            <div class="hero-image">
                <img src="{{ asset('images/hero-oil.png') }}" alt="Motor Oil" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22400%22%3E%3Crect fill=%22%23e5e7eb%22 width=%22400%22 height=%22400%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22monospace%22 font-size=%2220px%22 fill=%22%23666%22%3EOli Motor%3C/text%3E%3C/svg%3E'">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <div class="container">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <circle cx="30" cy="30" r="25" stroke="#BC1D24" stroke-width="2"/>
                        <path d="M30 20l-5 10h10l-5 10" stroke="#BC1D24" stroke-width="2" fill="none"/>
                    </svg>
                </div>
                <h3 class="feature-title">Original Products</h3>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <rect x="15" y="20" width="30" height="20" rx="2" stroke="#BC1D24" stroke-width="2"/>
                        <path d="M20 30h20M25 35h10" stroke="#BC1D24" stroke-width="2"/>
                    </svg>
                </div>
                <h3 class="feature-title">Affordable Rates</h3>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <circle cx="30" cy="30" r="20" stroke="#BC1D24" stroke-width="2"/>
                        <circle cx="30" cy="30" r="10" fill="#BC1D24"/>
                    </svg>
                </div>
                <h3 class="feature-title">Wide Variety</h3>
            </div>
        </div>
    </div>
</section>

<!-- New Arrivals Section -->
<section class="new-arrivals">
    <div class="container">
        <h2 class="section-title">New Arrivals</h2>
        <div class="products-grid">
            @foreach($newProducts as $product)
            <div class="product-card">
                <div class="product-image">
                    <img src="{{ $product->gambar ? asset('storage/' . $product->gambar) : asset('images/product-placeholder.png') }}" 
                         alt="{{ $product->nama }}"
                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22300%22 height=%22300%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22monospace%22 font-size=%2216px%22 fill=%22%23999%22%3ENo Image%3C/text%3E%3C/svg%3E'">
                    <button class="btn-favorite">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10 18l-1.45-1.32C3.4 12.36 0 9.27 0 5.5 0 2.42 2.42 0 5.5 0c1.74 0 3.41.81 4.5 2.08C11.09.81 12.76 0 14.5 0 17.58 0 20 2.42 20 5.5c0 3.77-3.4 6.86-8.55 11.18L10 18z"/>
                        </svg>
                    </button>
                </div>
                <div class="product-info">
                    <h3 class="product-name">{{ $product->nama }}</h3>
                    <div class="product-rating">
                        <div class="stars">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < floor($product->rating))
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="#fbbf24">
                                        <path d="M8 0l2.4 4.8 5.6.8-4 3.9.9 5.5L8 12.8 3.1 15l.9-5.5-4-3.9 5.6-.8L8 0z"/>
                                    </svg>
                                @else
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="#d1d5db">
                                        <path d="M8 0l2.4 4.8 5.6.8-4 3.9.9 5.5L8 12.8 3.1 15l.9-5.5-4-3.9 5.6-.8L8 0z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="rating-text">{{ number_format($product->rating, 1) }} ({{ $product->jumlah_rating }})</span>
                    </div>
                    <div class="product-price">
                        @if($product->harga_diskon)
                            <span class="price-discounted">Rp{{ number_format($product->harga_diskon, 0, ',', '.') }}</span>
                            <span class="price-original">Rp{{ number_format($product->harga, 0, ',', '.') }}</span>
                        @else
                            <span class="price">Rp{{ number_format($product->harga, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <div class="product-stock">
                        <span>Tersedia: {{ $product->stok }}</span>
                        <span>Terjual: {{ $product->terjual }}</span>
                    </div>
                    <button class="btn-add-cart" onclick="addToCart({{ $product->id }})">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Discount Banner -->
<section class="discount-banner">
    <div class="container">
        <div class="banner-content">
            <div class="banner-text">
                <span class="discount-percentage">-35%</span>
                <div class="banner-info">
                    <h3>Super discount for your first purchase</h3>
                    <p>Use exclusive code in your first purchase</p>
                </div>
            </div>
            <div class="banner-code">
                <span class="code">CRB2025</span>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="products-section" id="products">
    <div class="container">
        <h2 class="section-title">Get More Miles for Less</h2>
        <p class="section-subtitle">Get precision.</p>
        
        <div class="products-grid">
            @foreach($products as $product)
            <div class="product-card">
                @if($product->diskon_persen)
                    <span class="badge-discount">-{{ $product->diskon_persen }}%</span>
                @endif
                <div class="product-image">
                    <img src="{{ $product->gambar ? asset('storage/' . $product->gambar) : asset('images/product-placeholder.png') }}" 
                         alt="{{ $product->nama }}"
                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22300%22 height=%22300%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22monospace%22 font-size=%2216px%22 fill=%22%23999%22%3ENo Image%3C/text%3E%3C/svg%3E'">
                    <button class="btn-favorite">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10 18l-1.45-1.32C3.4 12.36 0 9.27 0 5.5 0 2.42 2.42 0 5.5 0c1.74 0 3.41.81 4.5 2.08C11.09.81 12.76 0 14.5 0 17.58 0 20 2.42 20 5.5c0 3.77-3.4 6.86-8.55 11.18L10 18z"/>
                        </svg>
                    </button>
                </div>
                <div class="product-info">
                    <h3 class="product-name">{{ $product->nama }}</h3>
                    <div class="product-rating">
                        <div class="stars">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < floor($product->rating))
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="#fbbf24">
                                        <path d="M8 0l2.4 4.8 5.6.8-4 3.9.9 5.5L8 12.8 3.1 15l.9-5.5-4-3.9 5.6-.8L8 0z"/>
                                    </svg>
                                @else
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="#d1d5db">
                                        <path d="M8 0l2.4 4.8 5.6.8-4 3.9.9 5.5L8 12.8 3.1 15l.9-5.5-4-3.9 5.6-.8L8 0z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="rating-text">{{ number_format($product->rating, 1) }} ({{ $product->jumlah_rating }})</span>
                    </div>
                    <div class="product-price">
                        @if($product->harga_diskon)
                            <span class="price-discounted">Rp{{ number_format($product->harga_diskon, 0, ',', '.') }}</span>
                            <span class="price-original">Rp{{ number_format($product->harga, 0, ',', '.') }}</span>
                        @else
                            <span class="price">Rp{{ number_format($product->harga, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <div class="product-stock">
                        <span>Tersedia: {{ $product->stok }}</span>
                        <span>Terjual: {{ $product->terjual }}</span>
                    </div>
                    <button class="btn-add-cart" onclick="addToCart({{ $product->id }})">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer" id="contact">
    <div class="container">
        <!-- Join Member Section -->
        <div class="join-member">
            <h3>Join Member!</h3>
            <p>Whether you're switching new contacts or sharing the latest news, 
               you can make your business look good in just a few clicks.</p>
            <form class="subscribe-form" onsubmit="handleSubscribe(event)">
                <input type="email" placeholder="Enter your email address" required>
                <button type="submit" class="btn-subscribe">Subscribe</button>
            </form>
            <div class="footer-links">
                <a href="#">Policy</a>
                <a href="#">Terms & Conditions</a>
                <a href="#">Privacy & Cookies</a>
            </div>
        </div>
        
        <!-- Footer Columns -->
        <div class="footer-columns">
            <div class="footer-column">
                <h4>Contact Us</h4>
                <p><strong>Phone Number</strong><br>+(62) 1234 5678 90</p>
                <p><strong>E-Mail</strong><br>info@example.com</p>
                <p><strong>Address</strong><br>Cirebon</p>
            </div>
            
            <div class="footer-column">
                <h4>Let Us Help You</h4>
                <ul>
                    <li><a href="#">Bantuan</a></li>
                    <li><a href="#">Cara Pembelian</a></li>
                    <li><a href="#">Pembayaran</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h4>Customer Service</h4>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Pengiriman</a></li>
                    <li><a href="#">Pengembalian</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h4>Get to Know Us</h4>
                <ul>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Karir</a></li>
                    <li><a href="#">Berita</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2025 Tunas Motor. All rights reserved.</p>
        </div>
    </div>
</footer>

@push('scripts')
<script>
// Countdown Timer
function updateCountdown() {
    const now = new Date();
    const midnight = new Date();
    midnight.setHours(24, 0, 0, 0);
    
    const diff = midnight - now;
    
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    
    document.getElementById('hours').textContent = String(hours).padStart(2, '0');
    document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
    document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
}

setInterval(updateCountdown, 1000);
updateCountdown();

// Add to Cart
function addToCart(productId) {
    fetch('{{ route('cart.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            product_id: productId,
            jumlah: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            updateCartCount();
        } else {
            alert(data.message || 'Gagal menambahkan produk ke keranjang');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

// Handle Subscribe
function handleSubscribe(event) {
    event.preventDefault();
    const email = event.target.querySelector('input[type="email"]').value;
    alert('Terima kasih telah berlangganan dengan email: ' + email);
    event.target.reset();
}
</script>
@endpush
@endsection

