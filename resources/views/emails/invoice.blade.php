<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->nomor_pesanan }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #BC1D24 0%, #8B0000 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            margin-top: 10px;
        }
        .status-unpaid {
            background-color: #f59e0b;
            color: white;
        }
        .status-paid {
            background-color: #10b981;
            color: white;
        }
        .content {
            padding: 40px 30px;
        }
        .invoice-header {
            border-bottom: 2px solid #BC1D24;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #BC1D24;
            margin-bottom: 10px;
        }
        .invoice-number {
            font-size: 16px;
            color: #666;
            margin-bottom: 5px;
        }
        .invoice-date {
            font-size: 14px;
            color: #999;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #BC1D24;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            padding: 8px 0;
            font-weight: bold;
            width: 150px;
            color: #666;
        }
        .info-value {
            display: table-cell;
            padding: 8px 0;
        }
        .order-items {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }
        .items-header {
            background-color: #f8f9fa;
            padding: 15px;
            font-weight: bold;
            border-bottom: 1px solid #e9ecef;
        }
        .item-row {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .item-row:last-child {
            border-bottom: none;
        }
        .item-info {
            flex: 1;
        }
        .item-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .item-details {
            color: #666;
            font-size: 14px;
        }
        .item-price {
            text-align: right;
            font-weight: bold;
        }
        .totals {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total-row.final {
            border-top: 2px solid #BC1D24;
            padding-top: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #BC1D24;
        }
        .payment-info {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .payment-info h3 {
            margin: 0 0 10px 0;
            color: #856404;
        }
        .payment-info p {
            margin: 5px 0;
            color: #856404;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer-content {
            color: #666;
            font-size: 14px;
        }
        .brand {
            font-size: 18px;
            font-weight: bold;
            color: #BC1D24;
            margin-bottom: 10px;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
            }
            .header, .content, .footer {
                padding: 20px 15px !important;
            }
            .header h1 {
                font-size: 24px !important;
            }
            .item-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .item-price {
                text-align: left;
                margin-top: 10px;
            }
            .total-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .total-row.final {
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="brand">TUNAS MOTOR</div>
            <h1>Invoice</h1>
            <div class="status-badge {{ $isPaid ? 'status-paid' : 'status-unpaid' }}">
                {{ $isPaid ? 'LUNAS' : 'BELUM BAYAR' }}
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="invoice-header">
                <h1 class="invoice-title">Invoice Pesanan</h1>
                <div class="invoice-number">Nomor Pesanan: {{ $order->nomor_pesanan }}</div>
                <div class="invoice-date">Tanggal: {{ $order->created_at->format('d M Y H:i') }}</div>
            </div>

            <!-- Customer Information -->
            <div class="section">
                <h2 class="section-title">Informasi Penerima</h2>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Nama:</div>
                        <div class="info-value">{{ $order->nama_penerima }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Telepon:</div>
                        <div class="info-value">{{ $order->telepon_penerima }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value">{{ $order->user->email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Alamat:</div>
                        <div class="info-value">{{ $order->alamat_pengiriman }}</div>
                    </div>
                    @if($order->catatan)
                    <div class="info-row">
                        <div class="info-label">Catatan:</div>
                        <div class="info-value">{{ $order->catatan }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="section">
                <h2 class="section-title">Detail Pesanan</h2>
                <div class="order-items">
                    <div class="items-header">
                        <strong>Produk</strong>
                    </div>
                    @foreach($order->orderItems as $item)
                    <div class="item-row">
                        <div class="item-info">
                            <div class="item-name">{{ $item->product ? $item->product->nama : 'Produk #' . $item->product_id }}</div>
                            <div class="item-details">
                                Jumlah: {{ $item->jumlah }} × Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="item-price">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Information -->
            <div class="section">
                <h2 class="section-title">Informasi Pembayaran</h2>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Metode Pembayaran:</div>
                        <div class="info-value">{{ $order->metode_pembayaran }}</div>
                    </div>
                    @if($order->transaction_id)
                    <div class="info-row">
                        <div class="info-label">Transaction ID:</div>
                        <div class="info-value">{{ $order->transaction_id }}</div>
                    </div>
                    @endif
                    @if($isPaid && $order->paid_at)
                    <div class="info-row">
                        <div class="info-label">Tanggal Bayar:</div>
                        <div class="info-value">{{ $order->paid_at->format('d M Y H:i') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Totals -->
            <div class="totals">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                </div>
                @if($order->diskon > 0)
                <div class="total-row">
                    <span>Diskon:</span>
                    <span>-Rp {{ number_format($order->diskon, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="total-row final">
                    <span>Total Pembayaran:</span>
                    <span>Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Payment Instructions for Unpaid -->
            @if(!$isPaid)
            <div class="payment-info">
                <h3>⚠️ Pembayaran Belum Diterima</h3>
                <p>Silakan selesaikan pembayaran Anda sesuai metode yang dipilih.</p>
                @if($order->metode_pembayaran === 'midtrans')
                <p><strong>Transfer Bank:</strong> Ikuti instruksi pembayaran yang muncul di halaman checkout.</p>
                @elseif($order->metode_pembayaran === 'COD')
                <p><strong>COD (Bayar di Tempat):</strong> Pembayaran dilakukan saat barang diterima.</p>
                @endif
            </div>
            @endif

            <!-- Success Message for Paid -->
            @if($isPaid)
            <div class="payment-info" style="background-color: #d1ecf1; border-color: #bee5eb;">
                <h3 style="color: #0c5460;">✅ Pembayaran Berhasil</h3>
                <p style="color: #0c5460;">Terima kasih atas pembayaran Anda. Pesanan sedang diproses.</p>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <p><strong>Tunas Motor</strong></p>
                <p>+(62) 1234 5678 90 | info@tunasmotor.com | Cirebon, Indonesia</p>
                <p style="margin-top: 20px; font-size: 12px; color: #999;">
                    &copy; 2025 Tunas Motor. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
