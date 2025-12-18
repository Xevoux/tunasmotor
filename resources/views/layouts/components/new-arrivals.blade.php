<!-- New Arrivals Section -->
<section class="new-arrivals" id="new-arrivals">
    <div class="container">
        <h2 class="section-title">Produk Terbaru</h2>
        <p class="section-subtitle">Koleksi terbaru untuk motor Anda</p>
        
        <div class="products-grid">
            @foreach($newProducts as $product)
            <div class="product-card" data-product-id="{{ $product->id }}">
                @if($product->diskon_persen)
                    <span class="badge-discount">-{{ $product->diskon_persen }}%</span>
                @endif
                <span class="badge-new">BARU</span>
                <div class="product-image">
                    <img src="{{ $product->gambar ? asset('storage/' . $product->gambar) : asset('images/product-placeholder.png') }}" 
                         alt="{{ $product->nama }}"
                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22300%22 height=%22300%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22monospace%22 font-size=%2216px%22 fill=%22%23999%22%3ENo Image%3C/text%3E%3C/svg%3E'">
                    <button class="btn-favorite" title="Tambah ke Favorit">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                </div>
                <div class="product-info">
                    <span class="product-category">{{ $product->category->nama ?? 'Tanpa Kategori' }}</span>
                    <h3 class="product-name">{{ $product->nama }}</h3>
                    <div class="product-price" style="margin-top: 12px;">
                        @if($product->harga_diskon)
                            <span class="price-discounted">Rp{{ number_format($product->harga_diskon, 0, ',', '.') }}</span>
                            <span class="price-original">Rp{{ number_format($product->harga, 0, ',', '.') }}</span>
                        @else
                            <span class="price">Rp{{ number_format($product->harga, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <div class="product-stock">
                        <span>Tersedia: {{ $product->stok }}</span>
                        <span>Terjual: {{ $product->terjual }}</span>
                    </div>
                    <button class="btn-add-cart" onclick="addToCart({{ $product->id }})">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
