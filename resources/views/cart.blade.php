@extends('layouts.app')

@section('title', 'Keranjang Belanja - Tunas Motor')

@section('content')
@include('partials.header', ['hideSearch' => true])

<!-- Cart Content -->
<section class="cart-section">
    <div class="container">
        <h1 class="page-title">Keranjang Belanja</h1>
        
        @if($carts->count() > 0)
            <div class="cart-layout">
                <!-- Cart Items -->
                <div class="cart-items">
                    <div class="cart-header">
                        <span class="cart-label">Tarikan</span>
                        <span class="free-shipping">Rp14.000 Dapatkan gratis ongkir!</span>
                    </div>
                    
                    @foreach($carts as $cart)
                    <div class="cart-item" data-cart-id="{{ $cart->id }}">
                        <div class="item-image">
                            <img src="{{ $cart->product->gambar ? asset('storage/' . $cart->product->gambar) : asset('images/product-placeholder.png') }}" 
                                 alt="{{ $cart->product->nama }}"
                                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22100%22 height=%22100%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22monospace%22 font-size=%2212px%22 fill=%22%23999%22%3ENo Image%3C/text%3E%3C/svg%3E'">
                        </div>
                        
                        <div class="item-details">
                            <h3 class="item-name">{{ $cart->product->nama }}</h3>
                            <p class="item-category">{{ $cart->product->category->nama }}</p>
                            <div class="item-price">
                                @if($cart->product->harga_diskon)
                                    <span class="price-current">Rp{{ number_format($cart->product->harga_diskon, 0, ',', '.') }}</span>
                                    <span class="price-original">Rp{{ number_format($cart->product->harga, 0, ',', '.') }}</span>
                                @else
                                    <span class="price-current">Rp{{ number_format($cart->product->harga, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="item-quantity">
                            <button class="qty-btn" onclick="updateQuantity({{ $cart->id }}, -1)">-</button>
                            <input type="number" value="{{ $cart->jumlah }}" min="1" id="qty-{{ $cart->id }}" onchange="updateQuantityInput({{ $cart->id }})">
                            <button class="qty-btn" onclick="updateQuantity({{ $cart->id }}, 1)">+</button>
                        </div>
                        
                        <div class="item-subtotal">
                            @php
                                $harga = $cart->product->harga_diskon ?? $cart->product->harga;
                                $subtotal = $harga * $cart->jumlah;
                            @endphp
                            <span class="subtotal-price">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <button class="btn-remove" onclick="removeItem({{ $cart->id }})">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 6l8 8M14 6l-8 8"/>
                            </svg>
                        </button>
                    </div>
                    @endforeach
                    
                    <div class="cart-actions">
                        <button class="btn-use-coupon" onclick="showCouponInput()">
                            Gunakan Kupon
                        </button>
                        <button class="btn-clear-all" onclick="clearAll()">
                            Clear All
                        </button>
                    </div>
                    
                    <div class="coupon-input" id="couponInput" style="display: none;">
                        <input type="text" placeholder="Masukkan kode kupon">
                        <button class="btn-apply">Terapkan</button>
                    </div>
                </div>
                
                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h3>Total Tagihan</h3>
                    
                    <div class="summary-row">
                        <span>Total Harga</span>
                        <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Promo Antar</span>
                        <span class="discount">Diskon</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Pengiriman</span>
                        <span>Pilih Ongkir</span>
                    </div>
                    
                    <div class="summary-total">
                        <span>Total</span>
                        <span class="total-amount">Rp{{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <button class="btn-checkout" onclick="proceedToCheckout()">
                        Proceed to checkout
                    </button>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <svg width="100" height="100" viewBox="0 0 100 100" fill="none">
                    <circle cx="50" cy="50" r="40" stroke="#d1d5db" stroke-width="2"/>
                    <path d="M30 50h40M50 30v40" stroke="#d1d5db" stroke-width="2"/>
                </svg>
                <h3>Keranjang Anda Kosong</h3>
                <p>Mulai belanja sekarang dan temukan produk terbaik untuk motor Anda!</p>
                <a href="{{ route('home') }}" class="btn-shop-now">Belanja Sekarang</a>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
// Update Quantity
function updateQuantity(cartId, change) {
    const input = document.getElementById('qty-' + cartId);
    let currentValue = parseInt(input.value);
    let newValue = currentValue + change;
    
    if (newValue < 1) return;
    
    input.value = newValue;
    saveQuantity(cartId, newValue);
}

function updateQuantityInput(cartId) {
    const input = document.getElementById('qty-' + cartId);
    let value = parseInt(input.value);
    
    if (value < 1) {
        input.value = 1;
        value = 1;
    }
    
    saveQuantity(cartId, value);
}

function saveQuantity(cartId, quantity) {
    fetch(`/cart/${cartId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
    
    fetch(`/cart/${cartId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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

// Show Coupon Input
function showCouponInput() {
    const couponInput = document.getElementById('couponInput');
    couponInput.style.display = couponInput.style.display === 'none' ? 'flex' : 'none';
}

// Clear All
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
    alert('Fitur checkout sedang dalam pengembangan');
}
</script>
@endpush
@endsection

