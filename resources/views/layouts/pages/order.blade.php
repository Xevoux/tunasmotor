@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Tunas Motor')

@section('content')
@include('layouts.partials.header', ['hideSearch' => true])

<section class="orders-section">
    <div class="container">
        <div class="orders-header">
            <h1 class="page-title">Riwayat Pesanan</h1>
            <a href="{{ route('home') }}" class="btn-shop">
                <i class="fa-solid fa-cart-shopping" style="font-size: 18px;"></i>
                Belanja Lagi
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
        
        @if($orders->count() > 0)
            <div class="orders-list">
                @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <span class="order-number">{{ $order->nomor_pesanan }}</span>
                            <span class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <span class="order-status" style="background-color: {{ $order->status_color }}20; color: {{ $order->status_color }}">
                            {{ $order->status_label }}
                        </span>
                    </div>
                    
                    <div class="order-items">
                        @foreach($order->orderItems->take(2) as $item)
                        <div class="order-item">
                            <div class="item-image">
                                <img src="{{ $item->product->gambar ? asset('storage/' . $item->product->gambar) : asset('images/product-placeholder.png') }}" 
                                     alt="{{ $item->product->nama }}"
                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22100%22 height=%22100%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22monospace%22 font-size=%2212px%22 fill=%22%23999%22%3ENo Image%3C/text%3E%3C/svg%3E'">
                            </div>
                            <div class="item-details">
                                <h4>{{ $item->product->nama }}</h4>
                                <span class="item-qty">{{ $item->jumlah }} x Rp{{ number_format($item->harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($order->orderItems->count() > 2)
                        <p class="more-items">+{{ $order->orderItems->count() - 2 }} produk lainnya</p>
                        @endif
                    </div>
                    
                    <div class="order-footer">
                        <div class="order-total">
                            <span class="total-label">Total Pesanan</span>
                            <span class="total-amount">Rp{{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="order-actions">
                            @if($order->canBePaid())
                            <a href="{{ route('checkout.payment', $order->id) }}" class="btn-pay-now">
                                Bayar Sekarang
                            </a>
                            @endif
                            
                            <a href="{{ route('orders.show', $order->id) }}" class="btn-detail">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="pagination-wrapper">
                {{ $orders->links() }}
            </div>
        @else
            <div class="empty-orders">
                <i class="fa-solid fa-box-open" style="font-size: 80px; color: #d1d5db;"></i>
                <h3>Belum Ada Pesanan</h3>
                <p>Anda belum memiliki pesanan. Mulai belanja sekarang!</p>
                <a href="{{ route('home') }}" class="btn-shop-now">
                    <i class="fa-solid fa-cart-shopping" style="font-size: 18px;"></i>
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</section>

@endsection

