<!-- Image Slider -->
<section class="image-slider">
    <div class="slider-container">
        <div class="slider-wrapper">
            @php
                $sliders = \App\Models\Slider::active()->ordered()->get();
            @endphp

            @if($sliders->count() > 0)
                @foreach($sliders as $index => $slider)
                <div class="slider-slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}" style="background-image: url('{{ $slider->image_url }}')">
                    <div class="slider-overlay"></div>
                    <div class="slider-content">
                        @if($slider->title)
                            <h2 class="slider-title">{{ $slider->title }}</h2>
                        @endif
                        @if($slider->description)
                            <p class="slider-description">{{ $slider->description }}</p>
                        @endif
                        @if($slider->link_url)
                            <a href="{{ $slider->link_url }}" class="slider-button">
                                <span>Lihat Selengkapnya</span>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach

                @if($sliders->count() > 1)
                    <!-- Navigation Arrows -->
                    <button class="slider-arrow slider-arrow-prev" aria-label="Previous slide">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button class="slider-arrow slider-arrow-next" aria-label="Next slide">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <!-- Navigation Dots -->
                    <div class="slider-dots">
                        @for($i = 0; $i < $sliders->count(); $i++)
                            <button class="slider-dot {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}" aria-label="Go to slide {{ $i + 1 }}"></button>
                        @endfor
                    </div>

                    <!-- Progress Bar -->
                    <div class="slider-progress"></div>
                @endif
            @else
                <!-- Default fallback slider -->
                <div class="slider-slide active" style="background: linear-gradient(135deg, #BC1D24 0%, #8B0000 100%)">
                    <div class="slider-overlay"></div>
                    <div class="slider-content">
                        <h2 class="slider-title">Selamat Datang di Tunas Motor</h2>
                        <p class="slider-description">Partner terpercaya untuk motor berkualitas dan solusi otomotif Anda.</p>
                        <a href="{{ route('products.index') }}" class="slider-button">
                            <span>Jelajahi Produk</span>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
