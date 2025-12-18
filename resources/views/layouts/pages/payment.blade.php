@extends('layouts.app')

@section('title', 'Pembayaran - Tunas Motor')

@section('content')
@include('layouts.partials.header', ['hideSearch' => true])

<section class="payment-section">
    <div class="container">
        <div class="payment-container">
            <div class="payment-header">
                <div class="order-badge">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                        <rect x="9" y="3" width="6" height="4" rx="1"/>
                    </svg>
                    <span>{{ $order->nomor_pesanan }}</span>
                </div>
                <h1 class="payment-title">Selesaikan Pembayaran</h1>
                <p class="payment-subtitle">Silakan pilih metode pembayaran yang Anda inginkan</p>
            </div>
            
            <div class="payment-details">
                <div class="payment-amount">
                    <span class="amount-label">Total Pembayaran</span>
                    <span class="amount-value">Rp{{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                </div>
                
                <div class="order-summary">
                    <h3>Ringkasan Pesanan</h3>
                    <div class="order-items">
                        @foreach($order->orderItems as $item)
                        <div class="order-item">
                            <span class="item-name">{{ $item->product->nama }}</span>
                            <span class="item-qty">x{{ $item->jumlah }}</span>
                            <span class="item-price">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="shipping-info">
                    <h3>Alamat Pengiriman</h3>
                    <p class="recipient-name">{{ $order->nama_penerima }}</p>
                    <p class="recipient-phone">{{ $order->telepon_penerima }}</p>
                    <p class="recipient-address">{{ $order->alamat_pengiriman }}</p>
                    @if($order->catatan)
                    <p class="order-note"><strong>Catatan:</strong> {{ $order->catatan }}</p>
                    @endif
                </div>
            </div>
            
            <div class="payment-actions">
                <button id="pay-button" class="btn-pay">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                        <line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                    Bayar Sekarang
                </button>
                
                <a href="{{ route('orders.show', $order->id) }}" class="btn-later">
                    Bayar Nanti
                </a>
            </div>
            
            <div class="payment-info-box">
                <div class="info-item">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="#10b981" stroke-width="2">
                        <path d="M10 2l6 3v5c0 4.418-6 8-6 8s-6-3.582-6-8V5l6-3z"/>
                    </svg>
                    <span>Transaksi aman & terenkripsi</span>
                </div>
                <div class="info-item">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="#3b82f6" stroke-width="2">
                        <circle cx="10" cy="10" r="8"/>
                        <path d="M10 6v4l3 3"/>
                    </svg>
                    <span>Selesaikan pembayaran dalam 24 jam</span>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<!-- Midtrans Snap JS -->
<script src="https://app.{{ config('services.midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js" 
        data-client-key="{{ $clientKey }}"></script>
<script>
    // Initialize payment with data from blade
    initializePaymentButton('{{ $snapToken }}', '{{ $order->id }}', '{{ route('checkout.finish', $order->id) }}');
</script>
@endpush
@endsection

