<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Order::where('status', Order::STATUS_PAID)
            ->orWhere('status', Order::STATUS_COMPLETED)
            ->sum('total_bayar');

        $pendingOrders = Order::where('status', Order::STATUS_PENDING)->count();

        return [
            Stat::make('Total Produk', Product::count())
                ->description('Produk terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5]),

            Stat::make('Total Customer', User::where('role', User::ROLE_CUSTOMER)->count())
                ->description('Customer terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([3, 5, 7, 8, 6, 9, 10]),

            Stat::make('Pesanan Pending', $pendingOrders)
                ->description('Menunggu pembayaran')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 0 ? 'warning' : 'gray')
                ->chart([2, 4, 6, 4, 3, 5, 4]),

            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Dari pesanan selesai')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([5, 8, 10, 7, 12, 15, 18]),
        ];
    }
}

