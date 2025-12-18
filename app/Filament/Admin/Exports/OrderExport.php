<?php

namespace App\Filament\Admin\Exports;

use App\Models\Order;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class OrderExport extends ExcelExport
{
    public function setUp(): void
    {
        $this->withFilename('Laporan_Pesanan_' . date('Y-m-d_H-i-s'));
        
        $this->withColumns([
            Column::make('nomor_pesanan')->heading('No. Pesanan'),
            Column::make('user.name')->heading('Nama Customer'),
            Column::make('user.email')->heading('Email'),
            Column::make('nama_penerima')->heading('Nama Penerima'),
            Column::make('telepon_penerima')->heading('Telepon'),
            Column::make('alamat_pengiriman')->heading('Alamat Pengiriman'),
            Column::make('total_harga')
                ->heading('Subtotal')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            Column::make('diskon')
                ->heading('Diskon')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            Column::make('total_bayar')
                ->heading('Total Bayar')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            Column::make('status')
                ->heading('Status')
                ->formatStateUsing(fn ($state) => Order::getStatuses()[$state] ?? $state),
            Column::make('metode_pembayaran')->heading('Metode Pembayaran'),
            Column::make('transaction_id')->heading('Transaction ID'),
            Column::make('paid_at')
                ->heading('Tanggal Bayar')
                ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('d/m/Y H:i') : '-'),
            Column::make('created_at')
                ->heading('Tanggal Order')
                ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->format('d/m/Y H:i')),
        ]);
    }
}

