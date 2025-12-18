<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Pesanan';

    protected static ?string $modelLabel = 'Pesanan';

    protected static ?string $pluralModelLabel = 'Pesanan';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pesanan')
                    ->schema([
                        Forms\Components\TextInput::make('nomor_pesanan')
                            ->label('Nomor Pesanan')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(Order::getStatuses())
                            ->required(),
                        Forms\Components\TextInput::make('total_bayar')
                            ->label('Total Bayar')
                            ->disabled()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Penerima')
                    ->schema([
                        Forms\Components\TextInput::make('nama_penerima')
                            ->label('Nama Penerima')
                            ->disabled(),
                        Forms\Components\TextInput::make('telepon_penerima')
                            ->label('Telepon')
                            ->disabled(),
                        Forms\Components\Textarea::make('alamat_pengiriman')
                            ->label('Alamat Pengiriman')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pesanan')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Nomor pesanan disalin'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
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
                        Order::STATUS_PROCESSING => 'info',
                        Order::STATUS_SHIPPED => 'primary',
                        Order::STATUS_COMPLETED => 'success',
                        Order::STATUS_CANCELLED => 'danger',
                        Order::STATUS_EXPIRED => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => Order::getStatuses()[$state] ?? $state),
                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->label('Metode')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nama_penerima')
                    ->label('Penerima')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Dibayar')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(Order::getStatuses()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status Baru')
                            ->options(Order::getStatuses())
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $record->update(['status' => $data['status']]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Pesanan')
                    ->schema([
                        Infolists\Components\TextEntry::make('nomor_pesanan')
                            ->label('Nomor Pesanan'),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                Order::STATUS_PENDING => 'warning',
                                Order::STATUS_PAID => 'success',
                                Order::STATUS_PROCESSING => 'info',
                                Order::STATUS_SHIPPED => 'primary',
                                Order::STATUS_COMPLETED => 'success',
                                Order::STATUS_CANCELLED => 'danger',
                                Order::STATUS_EXPIRED => 'gray',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => Order::getStatuses()[$state] ?? $state),
                        Infolists\Components\TextEntry::make('total_harga')
                            ->label('Subtotal')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('diskon')
                            ->label('Diskon')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('total_bayar')
                            ->label('Total Bayar')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('metode_pembayaran')
                            ->label('Metode Pembayaran'),
                        Infolists\Components\TextEntry::make('transaction_id')
                            ->label('Transaction ID'),
                        Infolists\Components\TextEntry::make('paid_at')
                            ->label('Waktu Pembayaran')
                            ->dateTime('d M Y H:i'),
                    ])->columns(2),

                Infolists\Components\Section::make('Informasi Pelanggan')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Nama Customer'),
                        Infolists\Components\TextEntry::make('user.email')
                            ->label('Email'),
                        Infolists\Components\TextEntry::make('nama_penerima')
                            ->label('Nama Penerima'),
                        Infolists\Components\TextEntry::make('telepon_penerima')
                            ->label('Telepon'),
                        Infolists\Components\TextEntry::make('alamat_pengiriman')
                            ->label('Alamat Pengiriman')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('catatan')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ])->columns(2),

                Infolists\Components\Section::make('Item Pesanan')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('orderItems')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('product.nama')
                                    ->label('Produk'),
                                Infolists\Components\TextEntry::make('jumlah')
                                    ->label('Qty'),
                                Infolists\Components\TextEntry::make('harga')
                                    ->label('Harga')
                                    ->money('IDR'),
                                Infolists\Components\TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('IDR'),
                            ])->columns(4),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', Order::STATUS_PENDING)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}

