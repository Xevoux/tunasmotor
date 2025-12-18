<!-- Categories Section -->
<section class="categories-section" id="categories">
    <div class="container">
        <h2 class="section-title">Kategori Produk</h2>
        <p class="section-subtitle">Temukan suku cadang sesuai kebutuhan motor Anda</p>
        
        <div class="categories-grid">
            <!-- All Products Card -->
            <a href="{{ route('products.index') }}" class="category-card category-card-all">
                <div class="category-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                </div>
                <h3 class="category-name">Semua Produk</h3>
                <p class="category-count">{{ $totalProducts ?? 0 }} produk</p>
                <span class="category-arrow">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 10h10M10 5l5 5-5 5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </a>
            
            @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->id]) }}" class="category-card" data-category-id="{{ $category->id }}">
                <div class="category-icon">
                    @switch($category->nama)
                        @case('Oli')
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M5 5h14l-2 15H7L5 5z"/>
                                <path d="M8 5V3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2"/>
                                <path d="M9 9l6 6"/>
                            </svg>
                            @break
                        @case('Kampas Rem')
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="9"/>
                                <circle cx="12" cy="12" r="4"/>
                                <line x1="12" y1="3" x2="12" y2="5"/>
                                <line x1="12" y1="19" x2="12" y2="21"/>
                                <line x1="3" y1="12" x2="5" y2="12"/>
                                <line x1="19" y1="12" x2="21" y2="12"/>
                            </svg>
                            @break
                        @case('Aki')
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="4" y="6" width="16" height="14" rx="2"/>
                                <line x1="7" y1="6" x2="7" y2="3"/>
                                <line x1="17" y1="6" x2="17" y2="3"/>
                                <line x1="8" y1="11" x2="16" y2="11"/>
                                <line x1="12" y1="8" x2="12" y2="14"/>
                            </svg>
                            @break
                        @case('Ban')
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="10"/>
                                <circle cx="12" cy="12" r="6"/>
                                <circle cx="12" cy="12" r="2"/>
                            </svg>
                            @break
                        @case('Busi')
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M10 2h4l1 4h-6l1-4z"/>
                                <rect x="9" y="6" width="6" height="4"/>
                                <path d="M10 10v6l2 6 2-6v-6"/>
                            </svg>
                            @break
                        @case('Rantai')
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                            </svg>
                            @break
                        @case('Filter')
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                            </svg>
                            @break
                        @case('Lampu')
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M9 18h6"/>
                                <path d="M10 22h4"/>
                                <path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/>
                            </svg>
                            @break
                        @default
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                            </svg>
                    @endswitch
                </div>
                <h3 class="category-name">{{ $category->nama }}</h3>
                <p class="category-count">{{ $category->products_count ?? $category->products->count() }} produk</p>
                <span class="category-arrow">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 10h10M10 5l5 5-5 5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </a>
            @endforeach
        </div>
    </div>
</section>

