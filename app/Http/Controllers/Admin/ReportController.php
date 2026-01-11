<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Writer\XLSX\Options;

class ReportController extends Controller
{
    /**
     * Export orders to PDF
     */
    public function exportOrdersPdf(Request $request)
    {
        try {
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
            $status = $request->get('status');

            // Validate dates
            $startDateObj = Carbon::parse($startDate)->startOfDay();
            $endDateObj = Carbon::parse($endDate)->endOfDay();

            $query = Order::with(['user', 'orderItems.product'])
                ->whereBetween('created_at', [$startDateObj, $endDateObj]);

            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }

            $orders = $query->orderBy('created_at', 'desc')->get();

            // Filter orders with valid data to prevent errors
            $orders = $orders->filter(function ($order) {
                return !empty($order->nomor_pesanan) && !empty($order->nama_penerima);
            });

            // Hitung statistik
            $totalRevenue = $orders->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED, Order::STATUS_PROCESSING, Order::STATUS_SHIPPED])->sum('total_bayar');
            $totalOrders = $orders->count();
            $paidOrders = $orders->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED, Order::STATUS_PROCESSING, Order::STATUS_SHIPPED])->count();

            $data = [
                'orders' => $orders,
                'startDate' => $startDateObj->format('d M Y'),
                'endDate' => $endDateObj->format('d M Y'),
                'totalRevenue' => $totalRevenue ?? 0,
                'totalOrders' => $totalOrders ?? 0,
                'paidOrders' => $paidOrders ?? 0,
                'generatedAt' => Carbon::now()->format('d M Y H:i'),
            ];

            $pdf = Pdf::loadView('admin.reports.orders-pdf', $data);
            $pdf->setPaper('A4', 'landscape');

            return $pdf->download('Laporan_Pesanan_' . date('Y-m-d_H-i-s') . '.pdf');
        } catch (\Exception $e) {
            // Log error and return error response
            \Log::error('Export Orders PDF Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat export PDF: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Export products to PDF
     */
    public function exportProductsPdf(Request $request)
    {
        $categoryId = $request->get('category_id');
        $stockFilter = $request->get('stock_filter');

        $query = Product::with('category')->orderBy('nama');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($stockFilter === 'low') {
            $query->where('stok', '<=', 10)->where('stok', '>', 0);
        } elseif ($stockFilter === 'out') {
            $query->where('stok', 0);
        }

        $products = $query->get();

        // Statistik
        $totalProducts = $products->count();
        $totalStock = $products->sum('stok');
        $totalSold = $products->sum('terjual');
        $lowStockCount = $products->where('stok', '<=', 10)->where('stok', '>', 0)->count();
        $outOfStockCount = $products->where('stok', 0)->count();

        $data = [
            'products' => $products,
            'totalProducts' => $totalProducts,
            'totalStock' => $totalStock,
            'totalSold' => $totalSold,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
            'generatedAt' => Carbon::now()->format('d M Y H:i'),
        ];

        $pdf = Pdf::loadView('admin.reports.products-pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Laporan_Produk_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Export sales summary to PDF
     */
    public function exportSalesSummaryPdf(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // Get daily sales data
        $dailySales = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total_orders, SUM(total_bayar) as total_revenue')
            ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED, Order::STATUS_PROCESSING, Order::STATUS_SHIPPED])
            ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date', 'desc')
            ->get();

        // Top selling products
        $topProducts = Product::withCount(['orderItems as sold_count' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                    $q->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED, Order::STATUS_PROCESSING, Order::STATUS_SHIPPED])
                        ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()]);
                });
            }])
            ->orderBy('sold_count', 'desc')
            ->limit(10)
            ->get();

        $data = [
            'dailySales' => $dailySales,
            'topProducts' => $topProducts,
            'startDate' => Carbon::parse($startDate)->format('d M Y'),
            'endDate' => Carbon::parse($endDate)->format('d M Y'),
            'totalRevenue' => $dailySales->sum('total_revenue'),
            'totalOrders' => $dailySales->sum('total_orders'),
            'generatedAt' => Carbon::now()->format('d M Y H:i'),
        ];

        $pdf = Pdf::loadView('admin.reports.sales-summary-pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Ringkasan_Penjualan_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Export orders to Excel
     */
    public function exportOrdersExcel(Request $request)
    {
        \Log::info('Export Orders Excel accessed', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()?->role,
            'params' => $request->all(),
            'url' => $request->fullUrl()
        ]);

        try {
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
            $status = $request->get('status');

            \Log::info('Export Orders Excel parameters', compact('startDate', 'endDate', 'status'));

            // Validate dates
            $startDateObj = Carbon::parse($startDate)->startOfDay();
            $endDateObj = Carbon::parse($endDate)->endOfDay();

            $query = Order::with(['user', 'orderItems.product'])
                ->whereBetween('created_at', [$startDateObj, $endDateObj]);

            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }

            $orders = $query->orderBy('created_at', 'desc')->get();

            // Filter orders with valid data to prevent errors
            $orders = $orders->filter(function ($order) {
                return !empty($order->nomor_pesanan) && !empty($order->nama_penerima);
            });

            $filename = 'Laporan_Pesanan_' . date('Y-m-d_H-i-s') . '.xlsx';
            $tempFile = storage_path('app/public/' . $filename);

            $options = new Options();
            $writer = new Writer($options);
            $writer->openToFile($tempFile);

            // Header
            $headerStyle = (new Style())->setFontBold();
            $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
                'No. Pesanan',
                'Nama Customer',
                'Email',
                'Nama Penerima',
                'Telepon',
                'Alamat Pengiriman',
                'Subtotal',
                'Diskon',
                'Total Bayar',
                'Status',
                'Metode Pembayaran',
                'Tanggal Bayar',
                'Tanggal Order',
            ], $headerStyle));

            // Data
            foreach ($orders as $order) {
                $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
                    $order->nomor_pesanan ?? '-',
                    $order->user?->name ?? '-',
                    $order->user?->email ?? '-',
                    $order->nama_penerima ?? '-',
                    $order->telepon_penerima ?? '-',
                    $order->alamat_pengiriman ?? '-',
                    'Rp ' . number_format($order->total_harga ?? 0, 0, ',', '.'),
                    'Rp ' . number_format($order->diskon ?? 0, 0, ',', '.'),
                    'Rp ' . number_format($order->total_bayar ?? 0, 0, ',', '.'),
                    Order::getStatuses()[$order->status] ?? $order->status ?? '-',
                    $order->metode_pembayaran ?? '-',
                    $order->paid_at ? Carbon::parse($order->paid_at)->format('d/m/Y H:i') : '-',
                    $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-',
                ]));
            }

            $writer->close();

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            // Log error and return error response
            \Log::error('Export Orders Excel Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat export Excel: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Export products to Excel
     */
    public function exportProductsExcel(Request $request)
    {
        $categoryId = $request->get('category_id');
        $stockFilter = $request->get('stock_filter');

        $query = Product::with('category')->orderBy('nama');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($stockFilter === 'low') {
            $query->where('stok', '<=', 10)->where('stok', '>', 0);
        } elseif ($stockFilter === 'out') {
            $query->where('stok', 0);
        }

        $products = $query->get();

        $filename = 'Laporan_Produk_' . date('Y-m-d_H-i-s') . '.xlsx';
        $tempFile = storage_path('app/public/' . $filename);

        $options = new Options();
        $writer = new Writer($options);
        $writer->openToFile($tempFile);

        // Header
        $headerStyle = (new Style())->setFontBold();
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
            'ID',
            'Nama Produk',
            'Kategori',
            'Deskripsi',
            'Harga',
            'Diskon (%)',
            'Harga Diskon',
            'Stok',
            'Terjual',
            'Produk Baru',
            'Tanggal Dibuat',
        ], $headerStyle));

        // Data
        foreach ($products as $product) {
            $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
                $product->id,
                $product->nama,
                $product->category->nama ?? '-',
                $product->deskripsi,
                'Rp ' . number_format($product->harga ?? 0, 0, ',', '.'),
                $product->diskon_persen ? $product->diskon_persen . '%' : '-',
                $product->harga_diskon ? 'Rp ' . number_format($product->harga_diskon, 0, ',', '.') : '-',
                $product->stok,
                $product->terjual,
                $product->is_new ? 'Ya' : 'Tidak',
                $product->created_at->format('d/m/Y H:i'),
            ]));
        }

        $writer->close();

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Export sales summary to Excel
     */
    public function exportSalesSummaryExcel(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // Get daily sales data
        $dailySales = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total_orders, SUM(total_bayar) as total_revenue')
            ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED, Order::STATUS_PROCESSING, Order::STATUS_SHIPPED])
            ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date', 'desc')
            ->get();

        // Top selling products
        $topProducts = Product::withCount(['orderItems as sold_count' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                    $q->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED, Order::STATUS_PROCESSING, Order::STATUS_SHIPPED])
                        ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()]);
                });
            }])
            ->orderBy('sold_count', 'desc')
            ->limit(10)
            ->get();

        $filename = 'Ringkasan_Penjualan_' . date('Y-m-d_H-i-s') . '.xlsx';
        $tempFile = storage_path('app/public/' . $filename);

        $options = new Options();
        $writer = new Writer($options);
        $writer->openToFile($tempFile);

        // Daily Sales Header
        $headerStyle = (new Style())->setFontBold();
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
            'PENJUALAN HARIAN',
            '',
            '',
        ], $headerStyle));
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
            'Tanggal',
            'Jumlah Pesanan',
            'Pendapatan',
        ], $headerStyle));

        foreach ($dailySales as $sale) {
            $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
                Carbon::parse($sale->date)->format('d M Y'),
                $sale->total_orders,
                'Rp ' . number_format($sale->total_revenue, 0, ',', '.'),
            ]));
        }

        // Empty row
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['', '', '']));
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['', '', '']));

        // Top Products Header
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
            'TOP 10 PRODUK TERLARIS',
            '',
            '',
            '',
        ], $headerStyle));
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
            'Peringkat',
            'Nama Produk',
            'Terjual',
            'Harga',
        ], $headerStyle));

        foreach ($topProducts as $index => $product) {
            $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
                '#' . ($index + 1),
                $product->nama,
                $product->sold_count ?? 0,
                'Rp ' . number_format($product->harga, 0, ',', '.'),
            ]));
        }

        $writer->close();

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}

