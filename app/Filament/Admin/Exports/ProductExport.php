<?php

namespace App\Filament\Admin\Exports;

use App\Models\Product;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class ProductExport extends ExcelExport
{
    public function setUp(): void
    {
        $this->withFilename('Laporan_Produk_' . date('Y-m-d_H-i-s'));
        
        $this->withColumns([
            Column::make('id')->heading('ID'),
            Column::make('nama')->heading('Nama Produk'),
            Column::make('category.nama')->heading('Kategori'),
            Column::make('deskripsi')->heading('Deskripsi'),
            Column::make('harga')
                ->heading('Harga')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            Column::make('diskon_persen')
                ->heading('Diskon (%)')
                ->formatStateUsing(fn ($state) => $state ? $state . '%' : '-'),
            Column::make('harga_diskon')
                ->heading('Harga Diskon')
                ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-'),
            Column::make('stok')->heading('Stok'),
            Column::make('terjual')->heading('Terjual'),
            Column::make('is_new')
                ->heading('Produk Baru')
                ->formatStateUsing(fn ($state) => $state ? 'Ya' : 'Tidak'),
            Column::make('created_at')
                ->heading('Tanggal Dibuat')
                ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('d/m/Y H:i')),
        ]);
    }
}

