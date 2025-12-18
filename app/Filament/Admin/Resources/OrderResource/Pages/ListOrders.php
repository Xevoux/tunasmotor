<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

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
                        ->withFilename('Laporan_Pesanan_' . date('Y-m-d_H-i-s'))
                        ->withColumns([
                            Column::make('nomor_pesanan')->heading('No. Pesanan'),
                            Column::make('user.name')->heading('Nama Customer'),
                            Column::make('nama_penerima')->heading('Nama Penerima'),
                            Column::make('telepon_penerima')->heading('Telepon'),
                            Column::make('alamat_pengiriman')->heading('Alamat Pengiriman'),
                            Column::make('total_harga')
                                ->heading('Subtotal')
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                            Column::make('diskon')
                                ->heading('Diskon')
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                            Column::make('total_bayar')
                                ->heading('Total Bayar')
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.')),
                            Column::make('status')
                                ->heading('Status')
                                ->formatStateUsing(fn ($state) => Order::getStatuses()[$state] ?? $state),
                            Column::make('metode_pembayaran')->heading('Metode Pembayaran'),
                            Column::make('paid_at')
                                ->heading('Tanggal Bayar')
                                ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('d/m/Y H:i') : '-'),
                            Column::make('created_at')
                                ->heading('Tanggal Order')
                                ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('d/m/Y H:i')),
                        ]),
                ]),

            Actions\Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-text')
                ->color('danger')
                ->url(route('admin.orders.export-pdf'))
                ->openUrlInNewTab(),
        ];
    }
}
