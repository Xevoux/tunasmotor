@extends('layouts.app')

@section('title', 'Favorit Saya - Tunas Motor')

@section('content')
@include('layouts.partials.header', ['hideSearch' => true])

<!-- Favorites Content -->
<section class="favorites-section">
    <div class="container">
        <div class="favorites-header">
            <h1 class="page-title">Favorit Saya</h1>
            <p class="favorites-subtitle">Produk yang Anda simpan untuk nanti</p>
        </div>
        
        @if($favorites->count() > 0)
            <div class="favorites-grid" id="favoritesGrid">
                @foreach($favorites as $favorite)
                <div class="favorite-item" data-favorite-id="{{ $favorite->id }}">
                    <div class="favorite-card {{ $favorite->product->stok == 0 ? 'out-of-stock' : '' }}">
                        @if($favorite->product->diskon_persen)
                            <span class="badge-discount">-{{ $favorite->product->diskon_persen }}%</span>
                        @endif
                        
                        <button class="btn-remove-favorite" onclick="removeFavorite({{ $favorite->id }}, this)" title="Hapus dari favorit">
                            <i class="fa-solid fa-xmark" style="font-size: 18px;"></i>
                        </button>
                        
                        <div class="favorite-image">
                            <img src="{{ $favorite->product->gambar ? asset('storage/' . $favorite->product->gambar) : asset('images/product-placeholder.png') }}" 
                                 alt="{{ $favorite->product->nama }}"
                                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22300%22 height=%22300%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22monospace%22 font-size=%2216px%22 fill=%22%23999%22%3ENo Image%3C/text%3E%3C/svg%3E'">
                        </div>
                        
                        <div class="favorite-info">
                            <span class="favorite-category">{{ $favorite->product->category->nama ?? 'Tanpa Kategori' }}</span>
                            <h3 class="favorite-name">{{ $favorite->product->nama }}</h3>
                            
                            <div class="favorite-price" style="margin-top: 12px;">
                                @if($favorite->product->harga_diskon)
                                    <span class="price-discounted">Rp{{ number_format($favorite->product->harga_diskon, 0, ',', '.') }}</span>
                                    <span class="price-original">Rp{{ number_format($favorite->product->harga, 0, ',', '.') }}</span>
                                @else
                                    <span class="price">Rp{{ number_format($favorite->product->harga, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            
                            <div class="favorite-stock">
                                @if($favorite->product->stok > 0)
                                    <span class="in-stock">
                                        <i class="fas fa-check-circle" style="font-size: 14px;"></i>
                                        Stok: {{ $favorite->product->stok }}
                                    </span>
                                @else
                                    <span class="out-of-stock">
                                        <i class="fas fa-times-circle" style="font-size: 14px;"></i>
                                        Stok Habis
                                    </span>
                                @endif
                            </div>
                            
                            <div class="favorite-actions">
                                @if($favorite->product->stok > 0)
                                    <button class="btn-add-to-cart" onclick="addToCart({{ $favorite->product->id }})">
                                        <i class="fas fa-shopping-cart"></i>
                                        Tambah ke Keranjang
                                    </button>
                                @else
                                    <button class="btn-out-of-stock" disabled>
                                        <i class="fas fa-ban"></i>
                                        Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="favorite-added-date">
                        Ditambahkan {{ $favorite->created_at->diffForHumans() }}
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-favorites" id="emptyFavorites">
                <i class="far fa-heart" style="font-size: 80px; color: #d1d5db; margin-bottom: 20px;"></i>
                <h3>Belum Ada Favorit</h3>
                <p>Simpan produk favorit Anda dengan mengklik ikon hati pada produk</p>
                <a href="{{ route('home') }}" class="btn-browse-products">
                    <i class="fas fa-search"></i>
                    Jelajahi Produk
                </a>
            </div>
        @endif
    </div>
</section>

@endsection

