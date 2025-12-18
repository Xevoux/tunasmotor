<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Produk</title>
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
            width: 20%;
            text-align: center;
            padding: 10px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
        }
        .stat-box .value {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
        }
        .stat-box .label {
            font-size: 9px;
            color: #666;
        }
        .stat-box.warning .value {
            color: #d97706;
        }
        .stat-box.danger .value {
            color: #dc2626;
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
        .stock-badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            display: inline-block;
        }
        .stock-out { background: #fee2e2; color: #991b1b; }
        .stock-low { background: #fef3c7; color: #92400e; }
        .stock-ok { background: #d1fae5; color: #065f46; }
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
    </style>
</head>
<body>
    <div class="header">
        <h1>🏍️ Tunas Motor</h1>
        <p>Laporan Produk & Inventaris</p>
    </div>

    <div class="stats-container">
        <div class="stat-box">
            <div class="value">{{ $totalProducts }}</div>
            <div class="label">Total Produk</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ number_format($totalStock, 0, ',', '.') }}</div>
            <div class="label">Total Stok</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ number_format($totalSold, 0, ',', '.') }}</div>
            <div class="label">Total Terjual</div>
        </div>
        <div class="stat-box warning">
            <div class="value">{{ $lowStockCount }}</div>
            <div class="label">Stok Rendah</div>
        </div>
        <div class="stat-box danger">
            <div class="value">{{ $outOfStockCount }}</div>
            <div class="label">Stok Habis</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th class="text-right">Harga</th>
                <th class="text-center">Diskon</th>
                <th class="text-right">Harga Diskon</th>
                <th class="text-center">Stok</th>
                <th class="text-center">Terjual</th>
                <th class="text-center">Produk Baru</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->nama }}</td>
                <td>{{ $product->category->nama ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                <td class="text-center">{{ $product->diskon_persen ? $product->diskon_persen . '%' : '-' }}</td>
                <td class="text-right">{{ $product->harga_diskon ? 'Rp ' . number_format($product->harga_diskon, 0, ',', '.') : '-' }}</td>
                <td class="text-center">
                    @if($product->stok === 0)
                        <span class="stock-badge stock-out">HABIS</span>
                    @elseif($product->stok <= 10)
                        <span class="stock-badge stock-low">{{ $product->stok }}</span>
                    @else
                        <span class="stock-badge stock-ok">{{ $product->stok }}</span>
                    @endif
                </td>
                <td class="text-center">{{ $product->terjual }}</td>
                <td class="text-center">{{ $product->is_new ? 'Ya' : 'Tidak' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center;">Tidak ada data produk</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Digenerate pada: {{ $generatedAt }} | Tunas Motor Admin Panel</p>
    </div>
</body>
</html>

