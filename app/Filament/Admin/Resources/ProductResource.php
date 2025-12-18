<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $modelLabel = 'Produk';

    protected static ?string $pluralModelLabel = 'Produk';

    protected static ?string $navigationGroup = 'Manajemen Toko';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->description('Data dasar produk')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->options(Category::all()->pluck('nama', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('gambar')
                            ->label('Gambar Produk')
                            ->image()
                            ->directory('products')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Harga & Stok')
                    ->description('Pengaturan harga dan ketersediaan')
                    ->schema([
                        Forms\Components\TextInput::make('harga')
                            ->label('Harga (Rp)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                $diskonPersen = $get('diskon_persen');
                                if ($state && $diskonPersen) {
                                    $hargaDiskon = $state - ($state * $diskonPersen / 100);
                                    $set('harga_diskon', round($hargaDiskon));
                                }
                            }),
                        Forms\Components\TextInput::make('diskon_persen')
                            ->label('Diskon (%)')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100)
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                $harga = $get('harga');
                                if ($harga && $state) {
                                    $hargaDiskon = $harga - ($harga * $state / 100);
                                    $set('harga_diskon', round($hargaDiskon));
                                } elseif (!$state) {
                                    $set('harga_diskon', null);
                                }
                            }),
                        Forms\Components\TextInput::make('harga_diskon')
                            ->label('Harga Setelah Diskon (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('stok')
                            ->label('Stok')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                    ])->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_new')
                            ->label('Produk Baru')
                            ->default(false),
                        Forms\Components\TextInput::make('terjual')
                            ->label('Total Terjual')
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->circular(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('category.nama')
                    ->label('Kategori')
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('diskon_persen')
                    ->label('Diskon')
                    ->formatStateUsing(fn ($state) => $state ? $state . '%' : '-')
                    ->badge()
                    ->color(fn ($state) => $state ? 'danger' : 'gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_diskon')
                    ->label('Harga Setelah Diskon')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        (int)$state === 0 => 'danger',
                        (int)$state < 10 => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\TextColumn::make('terjual')
                    ->label('Terjual')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_new')
                    ->label('Baru')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->options(Category::all()->pluck('nama', 'id')),
                SelectFilter::make('is_new')
                    ->label('Status')
                    ->options([
                        '1' => 'Produk Baru',
                        '0' => 'Produk Lama',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
