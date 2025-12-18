<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tunas Motor - Toko Sparepart Motor Terpercaya</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/preloader.css') }}" rel="stylesheet" />
    </head>
    <body>
        {{-- Include Preloader Component --}}
        @include('layouts.utilities.preloader')

        <!-- Welcome Screen -->
        <div id="welcome-screen" class="welcome-screen">
            <div class="welcome-container">
                <!-- Left Section -->
                <div class="welcome-left">
                    <div class="welcome-content">
                        <h1 class="welcome-brand">
                            Tunas Motor<sup>®</sup>
                        </h1>
                        
                        <h2 class="welcome-tagline">
                            Partner terpercaya untuk sparepart motor Anda
                        </h2>
                        
                        <p class="welcome-description">
                            Temukan berbagai macam sparepart motor berkualitas dengan harga terbaik. 
                            Kami menyediakan produk original dan bergaransi untuk segala jenis motor Anda.
                        </p>

                        <div class="welcome-features">
                            <div class="feature-item">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#BC1D24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>Produk 100% Original</span>
                            </div>
                            <div class="feature-item">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13 10V3L4 14H11V21L20 10H13Z" stroke="#BC1D24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>Pengiriman Cepat</span>
                            </div>
                            <div class="feature-item">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" stroke="#BC1D24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>Bergaransi Resmi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Section -->
                <div class="welcome-right">
                    <div class="auth-card">
                        <h3 class="auth-title">Mulai Belanja Sekarang</h3>
                        <p class="auth-subtitle">Daftar atau masuk untuk mendapatkan pengalaman belanja terbaik</p>
                        
                        <!-- Tab Switcher -->
                        <div class="auth-tabs">
                            <button class="auth-tab active" id="registerTab" onclick="switchTab('register')">
                                Daftar
                            </button>
                            <button class="auth-tab" id="loginTab" onclick="switchTab('login')">
                                Masuk
                            </button>
                        </div>

                        <!-- Register Form -->
                        <div id="registerForm" class="auth-form-container">
                            <form action="{{ route('register') }}" method="GET">
                                <div class="form-field">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="name" placeholder="Masukkan nama lengkap" required>
                                </div>
                                <div class="form-field">
                                    <label>Email</label>
                                    <input type="email" name="email" placeholder="nama@email.com" required>
                                </div>
                                
                                <button type="submit" class="btn-auth">
                                    Mulai Daftar
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </form>

                            <p class="auth-notice" style="margin-top: 20px;">
                                Dengan mendaftar, Anda menyetujui 
                                <a href="{{ route('privacy-policy') }}">Kebijakan Privasi</a> kami.
                            </p>
                        </div>

                        <!-- Login Form -->
                        <div id="loginForm" class="auth-form-container" style="display: none;">
                            <!-- Social Login Buttons -->
                            <div class="social-login-btns">
                                <button class="social-btn google-btn">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/>
                                        <path d="M9.003 18c2.43 0 4.467-.806 5.956-2.18L12.05 13.56c-.806.54-1.837.86-3.047.86-2.344 0-4.328-1.584-5.036-3.711H.96v2.332C2.44 15.983 5.485 18 9.003 18z" fill="#34A853"/>
                                        <path d="M3.964 10.712c-.18-.54-.282-1.117-.282-1.71 0-.593.102-1.17.282-1.71V4.96H.957C.347 6.175 0 7.55 0 9.002c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/>
                                        <path d="M9.003 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.464.891 11.426 0 9.003 0 5.485 0 2.44 2.017.96 4.958L3.967 7.29c.708-2.127 2.692-3.71 5.036-3.71z" fill="#EA4335"/>
                                    </svg>
                                    Lanjutkan dengan Google
                                </button>
                                
                                <button class="social-btn facebook-btn">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18 9C18 4.03125 13.9688 0 9 0C4.03125 0 0 4.03125 0 9C0 13.4906 3.29062 17.2031 7.59375 17.8875V11.6016H5.30859V9H7.59375V7.01719C7.59375 4.76156 8.93906 3.51562 10.9931 3.51562C11.9719 3.51562 13.0078 3.69141 13.0078 3.69141V5.90625H11.8734C10.7578 5.90625 10.4062 6.60078 10.4062 7.3125V9H12.9023L12.5033 11.6016H10.4062V17.8875C14.7094 17.2031 18 13.4906 18 9Z" fill="#1877F2"/>
                                    </svg>
                                    Lanjutkan dengan Facebook
                                </button>
                            </div>

                            <div class="auth-divider">
                                <span>atau masuk dengan email</span>
                            </div>

                            <form action="{{ route('login') }}" method="GET">
                                <div class="form-field">
                                    <label>Email</label>
                                    <input type="email" name="email" placeholder="nama@email.com" required>
                                </div>
                                
                                <button type="submit" class="btn-auth">
                                    Lanjutkan
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </form>

                            <p class="auth-notice" style="margin-top: 20px;">
                                Belum punya akun?
                                <a href="#" onclick="switchTab('register'); return false;">Daftar sekarang</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/js/preloader.js') }}"></script>
        <script src="{{ asset('assets/js/app.js') }}"></script>
    </body>
</html>
