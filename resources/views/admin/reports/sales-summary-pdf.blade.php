<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ringkasan Penjualan</title>
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
            width: 50%;
            text-align: center;
            padding: 15px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
        }
        .stat-box .value {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
        }
        .stat-box .label {
            font-size: 10px;
            color: #666;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            margin: 25px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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
            font-size: 10px;
        }
        td {
            font-size: 10px;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
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
        .text-center {
            text-align: center;
        }
        .rank-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏍️ Tunas Motor</h1>
        <p>Ringkasan Penjualan</p>
        <p>Periode: {{ $startDate }} - {{ $endDate }}</p>
    </div>

    <div class="stats-container">
        <div class="stat-box">
            <div class="value">{{ $totalOrders }}</div>
            <div class="label">Total Pesanan Sukses</div>
        </div>
        <div class="stat-box">
            <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="label">Total Pendapatan</div>
        </div>
    </div>

    <div class="section-title">📊 Penjualan Harian</div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th class="text-center">Jumlah Pesanan</th>
                <th class="text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dailySales as $sale)
            <tr>
                <td>{{ \Carbon\Carbon::parse($sale->date)->format('d M Y') }}</td>
                <td class="text-center">{{ $sale->total_orders }}</td>
                <td class="text-right">Rp {{ number_format($sale->total_revenue, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Tidak ada data penjualan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">🏆 Top 10 Produk Terlaris</div>
    <table>
        <thead>
            <tr>
                <th class="text-center">Peringkat</th>
                <th>Nama Produk</th>
                <th class="text-center">Terjual</th>
                <th class="text-right">Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topProducts as $index => $product)
            <tr>
                <td class="text-center">
                    <span class="rank-badge">#{{ $index + 1 }}</span>
                </td>
                <td>{{ $product->nama }}</td>
                <td class="text-center">{{ $product->sold_count ?? 0 }}</td>
                <td class="text-right">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Tidak ada data produk</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Digenerate pada: {{ $generatedAt }} | Tunas Motor Admin Panel</p>
    </div>
</body>
</html>

