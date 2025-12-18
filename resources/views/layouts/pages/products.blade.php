@extends('layouts.app')

@section('title', 'Semua Produk - Tunas Motor')

@section('content')
@include('layouts.partials.header')

<section class="products-section">
    <div class="container">
        <!-- Page Header -->
        <div class="products-header">
            <a href="{{ route('home') }}" class="back-link">
                <i class="fa-solid fa-arrow-left" style="font-size: 18px;"></i>
                Kembali ke Beranda
            </a>
            <h1 class="page-title">Semua Produk</h1>
            <p class="page-subtitle">Temukan suku cadang berkualitas untuk motor Anda</p>
        </div>
        
        <!-- Filter & Sort Controls -->
        <div class="filter-sort-container">
            <!-- Category Filter -->
            <div class="filter-group">
                <label class="filter-label">
                    <i class="fa-solid fa-filter" style="font-size: 16px;"></i>
                    Kategori
                </label>
                <div class="category-tabs" id="categoryTabs">
                    <button type="button" class="category-tab {{ (!isset($currentCategory) || $currentCategory == 'all' || $currentCategory == null) ? 'active' : '' }}" data-category="all">
                        Semua
                    </button>
                    @foreach($categories as $category)
                    <button type="button" class="category-tab {{ (isset($currentCategory) && $currentCategory == $category->id) ? 'active' : '' }}" data-category="{{ $category->id }}">
                        {{ $category->nama }}
                    </button>
                    @endforeach
                </div>
            </div>
            
            <!-- Sort Options -->
            <div class="sort-group">
                <label class="filter-label">
                    <i class="fa-solid fa-arrow-down-short-wide" style="font-size: 16px;"></i>
                    Urutkan
                </label>
                <select class="sort-select" id="sortSelect">
                    <option value="popular" {{ (!isset($currentSort) || $currentSort == 'popular') ? 'selected' : '' }}>Terpopuler</option>
                    <option value="newest" {{ (isset($currentSort) && $currentSort == 'newest') ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_low" {{ (isset($currentSort) && $currentSort == 'price_low') ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_high" {{ (isset($currentSort) && $currentSort == 'price_high') ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="discount" {{ (isset($currentSort) && $currentSort == 'discount') ? 'selected' : '' }}>Diskon Terbesar</option>
                </select>
            </div>
            
            <!-- Results Count -->
            <div class="results-count">
                <span id="productsCount">{{ $products->count() }}</span> produk ditemukan
            </div>
        </div>
        
        <!-- Loading Indicator -->
        <div class="products-loading" id="productsLoading" style="display: none;">
            <div class="loading-spinner"></div>
            <span>Memuat produk...</span>
        </div>
        
        <!-- Empty State -->
        <div class="products-empty" id="productsEmpty" style="display: {{ $products->count() == 0 ? 'block' : 'none' }};">
            <i class="fa-solid fa-magnifying-glass" style="font-size: 64px; color: #9ca3af;"></i>
            <h3>Produk tidak ditemukan</h3>
            <p>Coba ubah filter atau kata kunci pencarian Anda</p>
            <button class="btn-reset-filter" onclick="resetFilters()">Reset Filter</button>
        </div>
        
        <div class="products-grid" id="productsGrid" style="display: {{ $products->count() > 0 ? 'grid' : 'none' }};">
            @foreach($products as $product)
            <div class="product-card" data-product-id="{{ $product->id }}">
                @if($product->diskon_persen)
                    <span class="badge-discount">-{{ $product->diskon_persen }}%</span>
                @endif
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
                    <div class="product-price">
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

@endsection