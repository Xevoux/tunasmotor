@extends('layouts.app')

@section('title', 'Detail Pesanan - Tunas Motor')

@section('content')
@include('layouts.partials.header', ['hideSearch' => true])

<section class="order-detail-section">
    <div class="container">
        <div class="back-nav">
            <a href="{{ route('orders.index') }}" class="btn-back">
                <i class="fa-solid fa-arrow-left" style="font-size: 18px;"></i>
                Kembali ke Riwayat Pesanan
            </a>
        </div>
        
        @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i>
            {{ session('success') }}
        </div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 18px;"></i>
            {{ session('error') }}
        </div>
        @endif
        
        @if(session('info'))
        <div class="alert alert-info">
            <i class="fa-solid fa-circle-info" style="font-size: 18px;"></i>
            {{ session('info') }}
        </div>
        @endif
        
        <div class="order-detail-layout">
            <!-- Order Info -->
            <div class="order-main">
                <div class="detail-card">
                    <div class="detail-header">
                        <div>
                            <h1 class="order-number">{{ $order->nomor_pesanan }}</h1>
                            <p class="order-date">Dipesan pada {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                        <span class="order-status" style="background-color: {{ $order->status_color }}20; color: {{ $order->status_color }}">
                            {{ $order->status_label }}
                        </span>
                    </div>
                    
                    @if($order->canBePaid())
                    <div class="payment-alert">
                        <div class="payment-alert-content">
                            <i class="fa-solid fa-clock-rotate-left" style="font-size: 20px; color: #f59e0b;"></i>
                            <div>
                                <strong>Menunggu Pembayaran</strong>
                                <p>Segera selesaikan pembayaran Anda untuk memproses pesanan</p>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <a href="{{ route('checkout.payment', $order->id) }}" class="btn-pay">
                                Bayar Sekarang
                            </a>
                            <!-- Check Status Button for Midtrans Sandbox -->
                            <form action="{{ route('checkout.finish', $order->id) }}" method="GET" style="display: inline;">
                                <button type="submit" class="btn-pay" style="background: #6b7280;">
                                    Cek Status
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Order Items -->
                <div class="detail-card">
                    <h2 class="card-title">Produk Dipesan</h2>
                    <div class="items-list">
                        @foreach($order->orderItems as $item)
                        <div class="item-row">
                            <div class="item-image">
                                <img src="{{ $item->product->gambar ? asset('storage/' . $item->product->gambar) : asset('images/product-placeholder.png') }}" 
                                     alt="{{ $item->product->nama }}"
                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22100%22 height=%22100%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22monospace%22 font-size=%2212px%22 fill=%22%23999%22%3ENo Image%3C/text%3E%3C/svg%3E'">
                            </div>
                            <div class="item-info">
                                <h4>{{ $item->product->nama }}</h4>
                                <p class="item-price">Rp{{ number_format($item->harga, 0, ',', '.') }} x {{ $item->jumlah }}</p>
                            </div>
                            <div class="item-subtotal">
                                Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Shipping Address -->
                <div class="detail-card">
                    <h2 class="card-title">
                        <i class="fa-solid fa-location-dot" style="font-size: 18px;"></i>
                        Alamat Pengiriman
                    </h2>
                    <div class="shipping-details">
                        <p class="recipient-name">{{ $order->nama_penerima }}</p>
                        <p class="recipient-phone">{{ $order->telepon_penerima }}</p>
                        <p class="recipient-address">{{ $order->alamat_pengiriman }}</p>
                        @if($order->catatan)
                        <div class="order-notes">
                            <strong>Catatan:</strong>
                            <p>{{ $order->catatan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Order Summary Sidebar -->
            <div class="order-sidebar">
                <div class="summary-card">
                    <h3>Ringkasan Pembayaran</h3>
                    
                    <div class="summary-row">
                        <span>Subtotal ({{ $order->orderItems->sum('jumlah') }} item)</span>
                        <span>Rp{{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($order->diskon > 0)
                    <div class="summary-row discount">
                        <span>Diskon</span>
                        <span>-Rp{{ number_format($order->diskon, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <div class="summary-row">
                        <span>Ongkos Kirim</span>
                        <span class="free">Gratis</span>
                    </div>
                    
                    <div class="summary-total">
                        <span>Total Pembayaran</span>
                        <span>Rp{{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($order->payment_type)
                    <div class="payment-method-info">
                        <span class="method-label">Metode Pembayaran</span>
                        <span class="method-value">{{ ucwords(str_replace('_', ' ', $order->payment_type)) }}</span>
                    </div>
                    @endif
                    
                    @if($order->paid_at)
                    <div class="payment-date-info">
                        <span class="date-label">Dibayar pada</span>
                        <span class="date-value">{{ $order->paid_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                </div>
                
                <!-- Actions -->
                <div class="actions-card">
                    @if($order->canBePaid())
                    <a href="{{ route('checkout.payment', $order->id) }}" class="btn-action btn-primary">
                        <i class="fa-solid fa-credit-card" style="font-size: 18px;"></i>
                        Bayar Sekarang
                    </a>
                    
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                        @csrf
                        <button type="submit" class="btn-action btn-cancel">
                            <i class="fa-solid fa-circle-xmark" style="font-size: 18px;"></i>
                            Batalkan Pesanan
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('home') }}" class="btn-action btn-secondary">
                        <i class="fa-solid fa-cart-shopping" style="font-size: 18px;"></i>
                        Belanja Lagi
                    </a>
                </div>
                
                <!-- Help -->
                <div class="help-card">
                    <h4>Butuh Bantuan?</h4>
                    <p>Hubungi customer service kami</p>
                    <a href="tel:+6212345678" class="help-link">
                        <i class="fa-solid fa-phone" style="font-size: 16px;"></i>
                        +62 1234 5678 90
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

