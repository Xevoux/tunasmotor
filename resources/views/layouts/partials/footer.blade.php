@php
    $footer = \App\Models\Footer::active()->latest()->first();
@endphp

<!-- Footer -->
<footer class="footer" id="contact">
    <div class="container">
        <!-- Join Member Section -->
        <div class="join-member">
            <h3>Bergabung Bersama Kami!</h3>
            <p>Dapatkan informasi terbaru tentang produk, penawaran spesial, dan berita menarik langsung ke email Anda.</p>
            <form class="subscribe-form" id="subscribe-form" action="{{ route('subscribe') }}" method="POST">
                @csrf
                <input type="email" name="email" placeholder="Masukkan alamat email Anda" required>
                <button type="submit" class="btn-subscribe">Berlangganan</button>
            </form>
            <div class="footer-links">
                <a href="{{ route('privacy-policy') }}">Kebijakan Privasi</a> |
                <a href="{{ route('terms') }}">Syarat & Ketentuan</a>
            </div>
        </div>

        <!-- Footer Columns -->
        <div class="footer-columns">
            <div class="footer-column">
                <div class="footer-logo" style="margin-bottom: 20px;">
                    <img src="{{ asset('assets/images/tmlogo3.png') }}" alt="Tunas Motor Logo" style="max-width: 120px; height: auto;">
                </div>
                <h4>Hubungi Kami</h4>
                @if($footer && $footer->phone_number)
                    <p><strong>Nomor Telepon</strong><br>{{ $footer->phone_number }}</p>
                @else
                    <p><strong>Nomor Telepon</strong><br>+(62) 1234 5678 90</p>
                @endif

                @if($footer && $footer->email)
                    <p><strong>Email</strong><br>{{ $footer->email }}</p>
                @else
                    <p><strong>Email</strong><br>info@tunasmotor.com</p>
                @endif

                @if($footer && $footer->address)
                    <p><strong>Alamat</strong><br>{!! nl2br(e($footer->address)) !!}</p>
                @else
                    <p><strong>Alamat</strong><br>Cirebon, Jawa Barat</p>
                @endif
            </div>

            <div class="footer-column">
                <h4>Lokasi Tunas Motor</h4>
                <div class="map-container">
                    @if($footer && $footer->map_embed_code)
                        {!! $footer->map_embed_code !!}
                    @else
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.902!2d108.552!3d-6.717!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwNDMnMDEuMiJTIDEwOMKwMzMnMDcuMiJF!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid"
                            width="100%"
                            height="350"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            @if($footer && $footer->copyright_text)
                <p>{!! $footer->copyright_text !!}</p>
            @else
                <p>&copy; {{ date('Y') }} Tunas Motor. Hak Cipta Dilindungi.</p>
            @endif
        </div>
    </div>
</footer>
