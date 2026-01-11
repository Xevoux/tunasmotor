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

                <div class="flex flex-wrap gap-3 mt-4">
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
                    <x-filament::button
                        wire:click="toggleOrderPreview"
                        color="info"
                        icon="{{ $showOrderPreview ? 'heroicon-o-eye-slash' : 'heroicon-o-eye' }}"
                    >
                        {{ $showOrderPreview ? 'Sembunyikan Preview' : 'Lihat Preview' }}
                    </x-filament::button>
                </div>
                
                {{-- Order Preview Table --}}
                @if($showOrderPreview)
                    <div class="mt-4 border dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="bg-gray-100 dark:bg-gray-800 px-4 py-3 border-b dark:border-gray-700">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300">
                                <x-heroicon-o-table-cells class="w-5 h-5 inline-block mr-2" />
                                Preview Data Pesanan
                            </h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">No. Pesanan</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Customer</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Penerima</th>
                                        <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Total</th>
                                        <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Status</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Metode</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Tanggal Order</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($this->getFilteredOrders() as $order)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <td class="px-4 py-3 text-gray-800 dark:text-gray-200 font-medium">{{ $order->nomor_pesanan }}</td>
                                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $order->user?->name ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $order->nama_penerima }}</td>
                                            <td class="px-4 py-3 text-gray-800 dark:text-gray-200 text-right font-medium">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-center">
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
                                                        'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                                        'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
                                                        'shipped' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300',
                                                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
                                                        'expired' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-300',
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ \App\Models\Order::getStatuses()[$order->status] ?? $order->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $order->metode_pembayaran ?? '-' }}</td>
                                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                                <x-heroicon-o-inbox class="w-12 h-12 mx-auto mb-2 text-gray-400" />
                                                Tidak ada data pesanan untuk filter yang dipilih
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
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

                <div class="flex flex-wrap gap-3 mt-4">
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
                    <x-filament::button
                        wire:click="toggleProductPreview"
                        color="info"
                        icon="{{ $showProductPreview ? 'heroicon-o-eye-slash' : 'heroicon-o-eye' }}"
                    >
                        {{ $showProductPreview ? 'Sembunyikan Preview' : 'Lihat Preview' }}
                    </x-filament::button>
                </div>
                
                {{-- Product Preview Table --}}
                @if($showProductPreview)
                    <div class="mt-4 border dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="bg-gray-100 dark:bg-gray-800 px-4 py-3 border-b dark:border-gray-700">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300">
                                <x-heroicon-o-table-cells class="w-5 h-5 inline-block mr-2" />
                                Preview Data Produk
                            </h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Nama Produk</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Kategori</th>
                                        <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Harga</th>
                                        <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Stok</th>
                                        <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Terjual</th>
                                        <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Status Stok</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($this->getFilteredProducts() as $product)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <td class="px-4 py-3 text-gray-800 dark:text-gray-200 font-medium">{{ $product->nama }}</td>
                                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $product->category?->nama ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 text-gray-800 dark:text-gray-200 text-right font-medium">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ number_format($product->stok, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ number_format($product->terjual ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-center">
                                                @if($product->stok <= 0)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                                        Habis
                                                    </span>
                                                @elseif($product->stok <= 10)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">
                                                        Rendah
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                                        Tersedia
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                                <x-heroicon-o-inbox class="w-12 h-12 mx-auto mb-2 text-gray-400" />
                                                Tidak ada data produk untuk filter yang dipilih
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
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
                
                @php
                    $salesData = $this->getSalesData();
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $salesData['totalOrders'] }}</div>
                        <div class="text-sm text-gray-500">Total Transaksi Berhasil</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4 text-center">
                        <div class="text-xl font-bold text-green-600">Rp {{ number_format($salesData['totalRevenue'], 0, ',', '.') }}</div>
                        <div class="text-sm text-gray-500">Total Pendapatan</div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 mt-4">
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
                    <x-filament::button
                        wire:click="toggleSalesPreview"
                        color="info"
                        icon="{{ $showSalesPreview ? 'heroicon-o-eye-slash' : 'heroicon-o-eye' }}"
                    >
                        {{ $showSalesPreview ? 'Sembunyikan Preview' : 'Lihat Preview' }}
                    </x-filament::button>
                </div>
                
                {{-- Sales Preview Table --}}
                @if($showSalesPreview)
                    <div class="mt-4 border dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="bg-gray-100 dark:bg-gray-800 px-4 py-3 border-b dark:border-gray-700">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300">
                                <x-heroicon-o-table-cells class="w-5 h-5 inline-block mr-2" />
                                Preview Ringkasan Penjualan Harian
                            </h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Tanggal</th>
                                        <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Jumlah Transaksi</th>
                                        <th class="px-4 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Total Penjualan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($salesData['daily'] as $daySale)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                            <td class="px-4 py-3 text-gray-800 dark:text-gray-200 font-medium">{{ $daySale['date'] }}</td>
                                            <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400">{{ $daySale['count'] }}</td>
                                            <td class="px-4 py-3 text-gray-800 dark:text-gray-200 text-right font-medium">Rp {{ number_format($daySale['total'], 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                                <x-heroicon-o-inbox class="w-12 h-12 mx-auto mb-2 text-gray-400" />
                                                Tidak ada data penjualan untuk filter yang dipilih
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if($salesData['daily']->isNotEmpty())
                                    <tfoot class="bg-gray-100 dark:bg-gray-800 font-semibold">
                                        <tr>
                                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">Total</td>
                                            <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">{{ $salesData['totalOrders'] }}</td>
                                            <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">Rp {{ number_format($salesData['totalRevenue'], 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>

