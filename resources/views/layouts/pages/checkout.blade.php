@extends('layouts.app')

@section('title', 'Checkout - Tunas Motor')

@section('content')
@include('layouts.partials.header', ['hideSearch' => true])

<!-- Checkout Content -->
<section class="checkout-section">
    <div class="container">
        <h1 class="page-title">Checkout</h1>
        
        @if(session('error'))
        <div class="alert alert-error">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="10" cy="10" r="8"/>
                <path d="M10 6v4M10 14h.01"/>
            </svg>
            {{ session('error') }}
        </div>
        @endif
        
        <form action="{{ route('checkout.process') }}" method="POST" class="checkout-form">
            @csrf
            <div class="checkout-layout">
                <!-- Shipping Information -->
                <div class="checkout-info">
                    <div class="info-card">
                        <h2 class="card-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            Informasi Penerima
                        </h2>
                        
                        <div class="form-group">
                            <label for="nama_penerima">Nama Penerima <span class="required">*</span></label>
                            <input type="text" 
                                   id="nama_penerima" 
                                   name="nama_penerima" 
                                   value="{{ old('nama_penerima', $user->name) }}" 
                                   class="form-input @error('nama_penerima') is-invalid @enderror"
                                   required>
                            @error('nama_penerima')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="telepon_penerima">Nomor Telepon <span class="required">*</span></label>
                            <input type="tel" 
                                   id="telepon_penerima" 
                                   name="telepon_penerima" 
                                   value="{{ old('telepon_penerima', $user->phone) }}" 
                                   class="form-input @error('telepon_penerima') is-invalid @enderror"
                                   placeholder="08xxxxxxxxxx"
                                   required>
                            @error('telepon_penerima')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="alamat_pengiriman">Alamat Lengkap <span class="required">*</span></label>
                            <textarea id="alamat_pengiriman" 
                                      name="alamat_pengiriman" 
                                      class="form-textarea @error('alamat_pengiriman') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Masukkan alamat lengkap (Nama jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos)"
                                      required>{{ old('alamat_pengiriman', $user->full_address) }}</textarea>
                            @error('alamat_pengiriman')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="catatan">Catatan (Opsional)</label>
                            <textarea id="catatan" 
                                      name="catatan" 
                                      class="form-textarea"
                                      rows="2"
                                      placeholder="Catatan untuk penjual atau kurir">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                    
                    <!-- Order Items Preview -->
                    <div class="info-card">
                        <h2 class="card-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>
                            Ringkasan Pesanan ({{ $carts->count() }} item)
                        </h2>
                        
                        <div class="order-items-preview">
                            @foreach($carts as $cart)
                            <div class="item-preview">
                                <div class="item-preview-image">
                                    <img src="{{ $cart->product->gambar ? asset('storage/' . $cart->product->gambar) : asset('images/product-placeholder.png') }}" 
                                         alt="{{ $cart->product->nama }}"
                                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22100%22 height=%22100%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22monospace%22 font-size=%2212px%22 fill=%22%23999%22%3ENo Image%3C/text%3E%3C/svg%3E'">
                                </div>
                                <div class="item-preview-info">
                                    <h4>{{ $cart->product->nama }}</h4>
                                    <p class="item-qty">Qty: {{ $cart->jumlah }}</p>
                                </div>
                                <div class="item-preview-price">
                                    @php
                                        $harga = $cart->product->harga_diskon ?? $cart->product->harga;
                                        $itemSubtotal = $harga * $cart->jumlah;
                                    @endphp
                                    Rp{{ number_format($itemSubtotal, 0, ',', '.') }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="checkout-summary">
                    <div class="summary-card">
                        <h3>Ringkasan Pembayaran</h3>
                        
                        <div class="summary-row">
                            <span>Subtotal ({{ $carts->sum('jumlah') }} item)</span>
                            <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Ongkos Kirim</span>
                            <span class="free-badge">Gratis</span>
                        </div>
                        
                        <div class="summary-total">
                            <span>Total Pembayaran</span>
                            <span class="total-amount">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="payment-options">
                            <h4 class="payment-options-title">Pilih Metode Pembayaran</h4>
                            
                            <label class="payment-option">
                                <input type="radio" name="metode_pembayaran" value="midtrans" checked>
                                <div class="payment-option-content">
                                    <div class="payment-option-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                            <line x1="1" y1="10" x2="23" y2="10"/>
                                        </svg>
                                    </div>
                                    <div class="payment-option-info">
                                        <strong>Pembayaran Online</strong>
                                        <p>Transfer Bank, E-Wallet, Kartu Kredit, QRIS, dll.</p>
                                    </div>
                                    <span class="payment-badge recommended">Direkomendasikan</span>
                                </div>
                            </label>
                            
                            <label class="payment-option">
                                <input type="radio" name="metode_pembayaran" value="cod">
                                <div class="payment-option-content">
                                    <div class="payment-option-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                        </svg>
                                    </div>
                                    <div class="payment-option-info">
                                        <strong>COD (Bayar di Tempat)</strong>
                                        <p>Bayar langsung saat barang diterima</p>
                                    </div>
                                    <span class="payment-badge cod">COD</span>
                                </div>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn-checkout">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="4" width="18" height="12" rx="2"/>
                                <path d="M1 8h18"/>
                            </svg>
                            Lanjut ke Pembayaran
                        </button>
                        
                        <a href="{{ route('cart.index') }}" class="btn-back">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 10H5M5 10l5-5M5 10l5 5"/>
                            </svg>
                            Kembali ke Keranjang
                        </a>
                    </div>
                    
                    <div class="secure-badge">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="#10b981" stroke-width="2">
                            <path d="M10 2l6 3v5c0 4.418-6 8-6 8s-6-3.582-6-8V5l6-3z"/>
                            <path d="M7 10l2 2 4-4"/>
                        </svg>
                        <span>Transaksi Anda aman dan terenkripsi</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@endsection

