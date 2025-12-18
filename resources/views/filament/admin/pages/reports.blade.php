<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Laporan Pesanan --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-shopping-cart class="w-5 h-5 text-primary-500" />
                    Laporan Pesanan
                </div>
            </x-slot>
            <x-slot name="description">
                Export data pesanan berdasarkan rentang tanggal dan status
            </x-slot>

            <div class="space-y-4">
                {{ $this->orderForm }}

                @php
                    $orderStats = $this->getOrderStats();
                @endphp

                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-4">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-primary-600">{{ $orderStats['total'] }}</div>
                        <div class="text-sm text-gray-500">Total Pesanan</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $orderStats['paid'] }}</div>
                        <div class="text-sm text-gray-500">Terbayar</div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/30 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $orderStats['pending'] }}</div>
                        <div class="text-sm text-gray-500">Pending</div>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/30 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-red-600">{{ $orderStats['cancelled'] }}</div>
                        <div class="text-sm text-gray-500">Dibatalkan</div>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 text-center">
                        <div class="text-xl font-bold text-blue-600">Rp {{ number_format($orderStats['revenue'], 0, ',', '.') }}</div>
                        <div class="text-sm text-gray-500">Pendapatan</div>
                    </div>
                </div>

                <div class="flex gap-3 mt-4">
                    <x-filament::button
                        tag="a"
                        href="{{ $this->getOrderExportUrl() }}"
                        target="_blank"
                        color="danger"
                        icon="heroicon-o-document-text"
                    >
                        Export PDF
                    </x-filament::button>
                    <x-filament::button
                        tag="a"
                        href="{{ $this->getOrderExcelUrl() }}"
                        target="_blank"
                        color="success"
                        icon="heroicon-o-document-arrow-down"
                    >
                        Export Excel
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>

        {{-- Laporan Produk --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-cube class="w-5 h-5 text-primary-500" />
                    Laporan Produk & Inventaris
                </div>
            </x-slot>
            <x-slot name="description">
                Export data produk berdasarkan kategori dan status stok
            </x-slot>

            <div class="space-y-4">
                {{ $this->productForm }}

                @php
                    $productStats = $this->getProductStats();
                @endphp

                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-4">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-primary-600">{{ $productStats['total'] }}</div>
                        <div class="text-sm text-gray-500">Total Produk</div>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($productStats['totalStock'], 0, ',', '.') }}</div>
                        <div class="text-sm text-gray-500">Total Stok</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($productStats['totalSold'], 0, ',', '.') }}</div>
                        <div class="text-sm text-gray-500">Total Terjual</div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/30 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $productStats['lowStock'] }}</div>
                        <div class="text-sm text-gray-500">Stok Rendah</div>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/30 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-red-600">{{ $productStats['outOfStock'] }}</div>
                        <div class="text-sm text-gray-500">Stok Habis</div>
                    </div>
                </div>

                <div class="flex gap-3 mt-4">
                    <x-filament::button
                        tag="a"
                        href="{{ $this->getProductExportUrl() }}"
                        target="_blank"
                        color="danger"
                        icon="heroicon-o-document-text"
                    >
                        Export PDF
                    </x-filament::button>
                    <x-filament::button
                        tag="a"
                        href="{{ $this->getProductExcelUrl() }}"
                        target="_blank"
                        color="success"
                        icon="heroicon-o-document-arrow-down"
                    >
                        Export Excel
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>

        {{-- Ringkasan Penjualan --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-chart-bar class="w-5 h-5 text-primary-500" />
                    Ringkasan Penjualan
                </div>
            </x-slot>
            <x-slot name="description">
                Export ringkasan penjualan harian dan produk terlaris
            </x-slot>

            <div class="space-y-4">
                {{ $this->salesForm }}

                <div class="flex gap-3 mt-4">
                    <x-filament::button
                        tag="a"
                        href="{{ $this->getSalesExportUrl() }}"
                        target="_blank"
                        color="danger"
                        icon="heroicon-o-document-text"
                    >
                        Export PDF
                    </x-filament::button>
                    <x-filament::button
                        tag="a"
                        href="{{ $this->getSalesExcelUrl() }}"
                        target="_blank"
                        color="success"
                        icon="heroicon-o-document-arrow-down"
                    >
                        Export Excel
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>

