<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->exports([
                    ExcelExport::make('excel')
                        ->fromTable()
                        ->withFilename('Laporan_Produk_' . date('Y-m-d_H-i-s'))
                        ->withColumns([
                            Column::make('id')->heading('ID'),
                            Column::make('nama')->heading('Nama Produk'),
                            Column::make('category.nama')->heading('Kategori'),
                            Column::make('deskripsi')->heading('Deskripsi'),
                            Column::make('harga')
                                ->heading('Harga')
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
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
                        ]),
                ]),

            Actions\Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-text')
                ->color('danger')
                ->url(route('admin.products.export-pdf'))
                ->openUrlInNewTab(),

            Actions\CreateAction::make(),
        ];
    }
}
