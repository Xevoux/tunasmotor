<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class LowStockAlert extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected static ?string $heading = '⚠️ Peringatan Stok';

    public function mount(): void
    {
        $this->checkAndNotify();
    }

    protected function checkAndNotify(): void
    {
        // Cek stok habis
        $outOfStock = Product::where('stok', 0)->count();
        $lowStock = Product::where('stok', '>', 0)->where('stok', '<=', 10)->count();

        // Gunakan cache untuk menghindari notifikasi berulang
        $cacheKey = 'stock_alert_shown_' . auth()->id();
        
        if (!Cache::has($cacheKey) && ($outOfStock > 0 || $lowStock > 0)) {
            if ($outOfStock > 0) {
                Notification::make()
                    ->title('Stok Habis!')
                    ->body("Ada {$outOfStock} produk dengan stok habis. Segera restock!")
                    ->danger()
                    ->icon('heroicon-o-exclamation-triangle')
                    ->persistent()
                    ->send();
            }

            if ($lowStock > 0) {
                Notification::make()
                    ->title('Stok Hampir Habis')
                    ->body("Ada {$lowStock} produk dengan stok di bawah 10 unit.")
                    ->warning()
                    ->icon('heroicon-o-exclamation-circle')
                    ->persistent()
                    ->send();
            }

            // Cache selama 1 jam agar tidak spam notifikasi
            Cache::put($cacheKey, true, now()->addHour());
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->where('stok', '<=', 10)
                    ->orderBy('stok', 'asc')
            )
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Produk')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('category.nama')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        (int)$state === 0 => 'danger',
                        (int)$state <= 5 => 'warning',
                        default => 'info',
                    })
                    ->formatStateUsing(fn (string $state): string => 
                        (int)$state === 0 ? 'HABIS' : $state . ' unit'
                    ),
                Tables\Columns\TextColumn::make('terjual')
                    ->label('Terjual')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('restock')
                    ->label('Restock')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->url(fn (Product $record): string => 
                        route('filament.admin.resources.products.edit', ['record' => $record])
                    ),
            ])
            ->emptyStateHeading('Semua Stok Aman 👍')
            ->emptyStateDescription('Tidak ada produk dengan stok rendah saat ini.')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5);
    }

    public static function canView(): bool
    {
        // Widget hanya tampil jika ada produk dengan stok rendah
        return Product::where('stok', '<=', 10)->exists();
    }
}

