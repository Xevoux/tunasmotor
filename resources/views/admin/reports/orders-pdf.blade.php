<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Pesanan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 24px;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 11px;
        }
        .stats-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 10px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
        }
        .stat-box .value {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }
        .stat-box .label {
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #3b82f6;
            color: white;
            font-weight: bold;
            font-size: 9px;
        }
        td {
            font-size: 9px;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            display: inline-block;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-shipped { background: #e0e7ff; color: #3730a3; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .status-expired { background: #f3f4f6; color: #4b5563; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏍️ Tunas Motor</h1>
        <p>Laporan Pesanan</p>
        <p>Periode: {{ $startDate }} - {{ $endDate }}</p>
    </div>

    <div class="stats-container">
        <div class="stat-box">
            <div class="value">{{ $totalOrders }}</div>
            <div class="label">Total Pesanan</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $paidOrders }}</div>
            <div class="label">Pesanan Terbayar</div>
        </div>
        <div class="stat-box">
            <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="label">Total Pendapatan</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Pesanan</th>
                <th>Customer</th>
                <th>Penerima</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th class="text-right">Total</th>
                <th>Status</th>
                <th>Metode Bayar</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $order->nomor_pesanan }}</td>
                <td>{{ $order->user->name ?? '-' }}</td>
                <td>{{ $order->nama_penerima }}</td>
                <td>{{ $order->telepon_penerima }}</td>
                <td>{{ \Illuminate\Support\Str::limit($order->alamat_pengiriman, 50) }}</td>
                <td class="text-right">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                <td>
                    <span class="status-badge status-{{ $order->status }}">
                        {{ \App\Models\Order::getStatuses()[$order->status] ?? $order->status }}
                    </span>
                </td>
                <td>{{ $order->metode_pembayaran ?? '-' }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center;">Tidak ada data pesanan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Digenerate pada: {{ $generatedAt }} | Tunas Motor Admin Panel</p>
    </div>
</body>
</html>

