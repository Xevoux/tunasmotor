@php
    $hero = \App\Models\Hero::active()->ordered()->first();
@endphp

@if($hero)
<!-- Hero Section -->
<section class="hero" id="home">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                @if($hero->deal_label)
                <div class="deal-badge">
                    <span class="deal-label">{{ $hero->deal_label }}</span>
                    @if($hero->countdown_end_date)
                    <div class="countdown" id="countdown" data-end-date="{{ $hero->countdown_end_date->format('Y-m-d H:i:s') }}">
                        <span id="hours">00</span>:<span id="minutes">00</span>:<span id="seconds">00</span>
                    </div>
                    @endif
                </div>
                @endif

                <h1 class="hero-title">
                    {!! nl2br(e($hero->title)) !!}
                </h1>

                <p class="hero-description">
                    {!! nl2br(e($hero->description)) !!}
                </p>

                <a class="btn-shop" href="{{ $hero->button_link ?: route('products.index') }}">
                    {{ $hero->button_text }}
                    <i class="fa-solid fa-arrow-right-long" style="font-size: 18px;"></i>
                </a>
            </div>

            <div class="hero-image">
                <img src="{{ $hero->image_url }}" alt="{{ $hero->alt_text ?: 'Hero Image' }}" onerror="this.src='{{ asset('assets/images/tmlogo.png') }}'">
            </div>
        </div>
    </div>
</section>

<script>
// Countdown Timer
@if($hero->countdown_end_date)
document.addEventListener('DOMContentLoaded', function() {
    const countdownElement = document.getElementById('countdown');
    if (countdownElement) {
        const endDate = new Date(countdownElement.dataset.endDate).getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endDate - now;

            if (distance > 0) {
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById('hours').innerText = String(hours).padStart(2, '0');
                document.getElementById('minutes').innerText = String(minutes).padStart(2, '0');
                document.getElementById('seconds').innerText = String(seconds).padStart(2, '0');
            } else {
                document.getElementById('hours').innerText = '00';
                document.getElementById('minutes').innerText = '00';
                document.getElementById('seconds').innerText = '00';
            }
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
});
@endif
</script>
@else
<!-- Default Hero Section (Fallback) -->
<section class="hero" id="home">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <div class="deal-badge">
                    <span class="deal-label">PROMO SPESIAL MINGGU INI</span>
                    <div class="countdown" id="countdown">
                        <span id="hours">09</span>:<span id="minutes">36</span>:<span id="seconds">21</span>
                    </div>
                </div>

                <h1 class="hero-title">
                    LENGKAPI MOTOR ANDA.<br>
                    TEMUKAN SUKU CADANG<br>
                    TERBAIK.
                </h1>

                <p class="hero-description">
                    Tingkatkan performa motor Anda dengan suku cadang berkualitas tinggi dan andal.<br>
                    Komponen teruji yang memberikan ketenangan saat berkendara.
                </p>

                <a class="btn-shop" href="{{ route('products.index') }}">
                    Belanja Sekarang
                    <i class="fa-solid fa-arrow-right-long" style="font-size: 18px;"></i>
                </a>
            </div>

            <div class="hero-image">
                <img src="{{ asset('assets/images/hero.png') }}" alt="Tunas Motor Hero Banner" onerror="this.src='{{ asset('assets/images/tmlogo.png') }}'">
            </div>
        </div>
    </div>
</section>
@endif

