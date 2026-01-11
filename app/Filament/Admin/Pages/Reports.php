<?php

namespace App\Filament\Admin\Pages;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Filament\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class Reports extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.admin.pages.reports';

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $title = 'Sistem Pelaporan';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 3;

    public ?array $orderFilters = [];
    public ?array $productFilters = [];
    public ?array $salesFilters = [];
    
    public bool $showOrderPreview = false;
    public bool $showProductPreview = false;
    public bool $showSalesPreview = false;

    public function mount(): void
    {
        $this->orderFilters = [
            'start_date' => now()->startOfMonth()->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'status' => 'all',
        ];

        $this->productFilters = [
            'category_id' => null,
            'stock_filter' => 'all',
        ];

        $this->salesFilters = [
            'start_date' => now()->startOfMonth()->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
        ];
    }
    
    public function toggleOrderPreview(): void
    {
        $this->showOrderPreview = !$this->showOrderPreview;
    }
    
    public function toggleProductPreview(): void
    {
        $this->showProductPreview = !$this->showProductPreview;
    }
    
    public function toggleSalesPreview(): void
    {
        $this->showSalesPreview = !$this->showSalesPreview;
    }

    protected function getForms(): array
    {
        return [
            'orderForm',
            'productForm',
            'salesForm',
        ];
    }

    public function orderForm(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->default(now()->startOfMonth())
                    ->live(),
                DatePicker::make('end_date')
                    ->label('Tanggal Akhir')
                    ->default(now())
                    ->live(),
                Select::make('status')
                    ->label('Status')
                    ->options(array_merge(['all' => 'Semua Status'], Order::getStatuses()))
                    ->default('all')
                    ->live(),
            ])
            ->columns(3)
            ->statePath('orderFilters');
    }

    public function productForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->label('Kategori')
                    ->options(Category::all()->pluck('nama', 'id'))
                    ->placeholder('Semua Kategori')
                    ->live(),
                Select::make('stock_filter')
                    ->label('Filter Stok')
                    ->options([
                        'all' => 'Semua Produk',
                        'low' => 'Stok Rendah (≤10)',
                        'out' => 'Stok Habis',
                    ])
                    ->default('all')
                    ->live(),
            ])
            ->columns(2)
            ->statePath('productFilters');
    }

    public function salesForm(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->default(now()->startOfMonth())
                    ->live(),
                DatePicker::make('end_date')
                    ->label('Tanggal Akhir')
                    ->default(now())
                    ->live(),
            ])
            ->columns(2)
            ->statePath('salesFilters');
    }

    public function getOrderStats(): array
    {
        $query = Order::query()
            ->whereBetween('created_at', [
                $this->orderFilters['start_date'] ?? now()->startOfMonth(),
                \Carbon\Carbon::parse($this->orderFilters['end_date'] ?? now())->endOfDay()
            ]);

        if (($this->orderFilters['status'] ?? 'all') !== 'all') {
            $query->where('status', $this->orderFilters['status']);
        }

        $orders = $query->get();
        
        return [
            'total' => $orders->count(),
            'paid' => $orders->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED, Order::STATUS_PROCESSING, Order::STATUS_SHIPPED])->count(),
            'pending' => $orders->where('status', Order::STATUS_PENDING)->count(),
            'cancelled' => $orders->where('status', Order::STATUS_CANCELLED)->count(),
            'revenue' => $orders->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED, Order::STATUS_PROCESSING, Order::STATUS_SHIPPED])->sum('total_bayar'),
        ];
    }
    
    public function getFilteredOrders(): Collection
    {
        $query = Order::query()
            ->with('user')
            ->whereBetween('created_at', [
                $this->orderFilters['start_date'] ?? now()->startOfMonth(),
                \Carbon\Carbon::parse($this->orderFilters['end_date'] ?? now())->endOfDay()
            ]);

        if (($this->orderFilters['status'] ?? 'all') !== 'all') {
            $query->where('status', $this->orderFilters['status']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getProductStats(): array
    {
        $query = Product::query();

        if ($this->productFilters['category_id'] ?? null) {
            $query->where('category_id', $this->productFilters['category_id']);
        }

        if (($this->productFilters['stock_filter'] ?? 'all') === 'low') {
            $query->where('stok', '<=', 10)->where('stok', '>', 0);
        } elseif (($this->productFilters['stock_filter'] ?? 'all') === 'out') {
            $query->where('stok', 0);
        }

        $products = $query->get();

        return [
            'total' => $products->count(),
            'totalStock' => $products->sum('stok'),
            'totalSold' => $products->sum('terjual'),
            'lowStock' => $products->where('stok', '<=', 10)->where('stok', '>', 0)->count(),
            'outOfStock' => $products->where('stok', 0)->count(),
        ];
    }
    
    public function getFilteredProducts(): Collection
    {
        $query = Product::query()->with('category');

        if ($this->productFilters['category_id'] ?? null) {
            $query->where('category_id', $this->productFilters['category_id']);
        }

        if (($this->productFilters['stock_filter'] ?? 'all') === 'low') {
            $query->where('stok', '<=', 10)->where('stok', '>', 0);
        } elseif (($this->productFilters['stock_filter'] ?? 'all') === 'out') {
            $query->where('stok', 0);
        }

        return $query->orderBy('nama', 'asc')->get();
    }
    
    public function getSalesData(): array
    {
        $orders = Order::query()
            ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED, Order::STATUS_PROCESSING, Order::STATUS_SHIPPED])
            ->whereBetween('created_at', [
                $this->salesFilters['start_date'] ?? now()->startOfMonth(),
                \Carbon\Carbon::parse($this->salesFilters['end_date'] ?? now())->endOfDay()
            ])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $dailySales = $orders->groupBy(fn ($order) => $order->created_at->format('Y-m-d'))
            ->map(fn ($dayOrders) => [
                'date' => $dayOrders->first()->created_at->format('d/m/Y'),
                'count' => $dayOrders->count(),
                'total' => $dayOrders->sum('total_bayar'),
            ])
            ->values();
            
        return [
            'daily' => $dailySales,
            'totalOrders' => $orders->count(),
            'totalRevenue' => $orders->sum('total_bayar'),
        ];
    }


    public function getOrderExportUrl(): string
    {
        $params = http_build_query([
            'start_date' => $this->orderFilters['start_date'] ?? now()->startOfMonth()->format('Y-m-d'),
            'end_date' => $this->orderFilters['end_date'] ?? now()->format('Y-m-d'),
            'status' => $this->orderFilters['status'] ?? 'all',
        ]);

        return route('admin-reports.orders.export-pdf') . '?' . $params;
    }

    public function getProductExportUrl(): string
    {
        $params = http_build_query([
            'category_id' => $this->productFilters['category_id'] ?? '',
            'stock_filter' => $this->productFilters['stock_filter'] ?? 'all',
        ]);

        return route('admin-reports.products.export-pdf') . '?' . $params;
    }

    public function getSalesExportUrl(): string
    {
        $params = http_build_query([
            'start_date' => $this->salesFilters['start_date'] ?? now()->startOfMonth()->format('Y-m-d'),
            'end_date' => $this->salesFilters['end_date'] ?? now()->format('Y-m-d'),
        ]);

        return route('admin-reports.sales-summary.export-pdf') . '?' . $params;
    }

    // Excel Export URLs
    public function getOrderExcelUrl(): string
    {
        $params = http_build_query([
            'start_date' => $this->orderFilters['start_date'] ?? now()->startOfMonth()->format('Y-m-d'),
            'end_date' => $this->orderFilters['end_date'] ?? now()->format('Y-m-d'),
            'status' => $this->orderFilters['status'] ?? 'all',
        ]);

        return route('admin-reports.orders.export-excel') . '?' . $params;
    }

    public function getProductExcelUrl(): string
    {
        $params = http_build_query([
            'category_id' => $this->productFilters['category_id'] ?? '',
            'stock_filter' => $this->productFilters['stock_filter'] ?? 'all',
        ]);

        return route('admin-reports.products.export-excel') . '?' . $params;
    }

    public function getSalesExcelUrl(): string
    {
        $params = http_build_query([
            'start_date' => $this->salesFilters['start_date'] ?? now()->startOfMonth()->format('Y-m-d'),
            'end_date' => $this->salesFilters['end_date'] ?? now()->format('Y-m-d'),
        ]);

        return route('admin-reports.sales-summary.export-excel') . '?' . $params;
    }
}

