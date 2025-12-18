<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class NewOrdersNotification extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 0;

    protected static ?string $heading = '🔔 Transaksi Baru Masuk';

    // Polling setiap 30 detik untuk cek transaksi baru
    protected static ?string $pollingInterval = '30s';

    public function mount(): void
    {
        $this->checkNewOrders();
    }

    protected function checkNewOrders(): void
    {
        $cacheKey = 'last_order_check_' . auth()->id();
        $lastCheck = Cache::get($cacheKey, now()->subDay());
        
        // Hitung transaksi baru sejak pengecekan terakhir
        $newPaidOrders = Order::where('status', Order::STATUS_PAID)
            ->where('paid_at', '>=', $lastCheck)
            ->count();

        $pendingOrders = Order::where('status', Order::STATUS_PENDING)
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        if ($newPaidOrders > 0) {
            Notification::make()
                ->title('🎉 Pembayaran Baru Diterima!')
                ->body("Ada {$newPaidOrders} transaksi baru yang sudah dibayar. Segera proses pesanan!")
                ->success()
                ->icon('heroicon-o-banknotes')
                ->persistent()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('lihat')
                        ->label('Lihat Pesanan')
                        ->url(route('filament.admin.resources.orders.index'))
                        ->button(),
                ])
                ->send();
        }

        if ($pendingOrders > 0) {
            Notification::make()
                ->title('Menunggu Pembayaran')
                ->body("Ada {$pendingOrders} transaksi dalam 24 jam terakhir yang belum dibayar.")
                ->warning()
                ->icon('heroicon-o-clock')
                ->send();
        }

        // Update waktu pengecekan terakhir
        Cache::put($cacheKey, now(), now()->addHours(24));
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->whereIn('status', [Order::STATUS_PAID, Order::STATUS_PENDING])
                    ->where('created_at', '>=', now()->subDays(7))
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pesanan')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Nomor pesanan disalin'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_bayar')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Order::STATUS_PENDING => 'warning',
                        Order::STATUS_PAID => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => Order::getStatuses()[$state] ?? $state),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Dibayar')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum dibayar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Order $record): string => 
                        route('filament.admin.resources.orders.view', ['record' => $record])
                    ),
                Tables\Actions\Action::make('process')
                    ->label('Proses')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Order $record): bool => $record->status === Order::STATUS_PAID)
                    ->requiresConfirmation()
                    ->modalHeading('Proses Pesanan')
                    ->modalDescription('Apakah Anda yakin ingin memproses pesanan ini?')
                    ->action(function (Order $record): void {
                        $record->update(['status' => Order::STATUS_PROCESSING]);
                        Notification::make()
                            ->title('Pesanan Diproses')
                            ->body("Pesanan {$record->nomor_pesanan} sedang diproses.")
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('Tidak Ada Transaksi Baru')
            ->emptyStateDescription('Belum ada transaksi baru dalam 7 hari terakhir.')
            ->emptyStateIcon('heroicon-o-inbox')
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5);
    }

    public static function canView(): bool
    {
        // Widget selalu tampil
        return true;
    }
}

