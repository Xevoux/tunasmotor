<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tunas Motor - Toko Sparepart Motor Terpercaya</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />
        <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
        <style>
            /* Splash Screen Styles */
            .splash-screen {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                background: linear-gradient(135deg, #000000 0%, #BC1D24 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                transition: opacity 0.5s ease;
            }

            .splash-content {
                text-align: center;
            }

            .splash-logo {
                margin-bottom: 30px;
            }

            @keyframes rotate {
                from { stroke-dashoffset: 283; }
                to { stroke-dashoffset: 0; }
            }

            @keyframes pulse {
                0%, 100% { transform: scale(1); opacity: 1; }
                50% { transform: scale(1.1); opacity: 0.8; }
            }

            .circle-animation {
                animation: rotate 2s ease-in-out forwards;
            }

            .star-animation {
                animation: pulse 1.5s ease-in-out infinite;
            }

            .splash-title {
                font-size: 48px;
                font-weight: 700;
                color: #BC1D24;
                margin-bottom: 10px;
                font-family: 'Poppins', sans-serif;
            }

            .splash-subtitle {
                font-size: 18px;
                color: #E0E0E0;
                margin-bottom: 40px;
            }

            .splash-loader {
                width: 200px;
                height: 4px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 2px;
                overflow: hidden;
                margin: 0 auto;
            }

            .loader-bar {
                height: 100%;
                background: linear-gradient(90deg, #BC1D24, #9a1720);
                width: 0;
                animation: loading 2s ease-in-out forwards;
            }

            @keyframes loading {
                to { width: 100%; }
            }

            /* Welcome Screen Styles */
            .welcome-screen {
                display: none;
                min-height: 100vh;
                opacity: 0;
                transition: opacity 0.5s ease;
            }

            .welcome-container {
                display: flex;
                min-height: 100vh;
            }

            .welcome-left {
                flex: 1;
                background: linear-gradient(135deg, #000000 0%, #BC1D24 100%);
                padding: 80px 60px;
                display: flex;
                align-items: center;
                position: relative;
                overflow: hidden;
            }

            .welcome-left::before {
                content: '';
                position: absolute;
                top: -50%;
                right: -20%;
                width: 500px;
                height: 500px;
                background: radial-gradient(circle, rgba(188, 29, 36, 0.2) 0%, transparent 70%);
                border-radius: 50%;
            }

            .welcome-content {
                max-width: 600px;
                position: relative;
                z-index: 1;
            }

            .welcome-brand {
                font-size: 56px;
                font-weight: 700;
                color: #BC1D24;
                margin-bottom: 20px;
                font-family: 'Poppins', sans-serif;
            }

            .welcome-brand sup {
                font-size: 24px;
                top: -1.5em;
            }

            .welcome-tagline {
                font-size: 32px;
                font-weight: 600;
                color: white;
                line-height: 1.3;
                margin-bottom: 20px;
            }

            .welcome-description {
                font-size: 16px;
                color: #B8C1CC;
                line-height: 1.7;
                margin-bottom: 40px;
            }

            .welcome-features {
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .feature-item {
                display: flex;
                align-items: center;
                gap: 15px;
                color: white;
                font-size: 16px;
            }

            .feature-item svg {
                flex-shrink: 0;
            }

            .welcome-right {
                flex: 0 0 540px;
                background: #F5F5F5;
                padding: 80px 60px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .auth-card {
                background: white;
                border-radius: 16px;
                padding: 40px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 420px;
            }

            .auth-title {
                font-size: 28px;
                font-weight: 700;
                color: #111;
                margin-bottom: 10px;
                text-align: center;
            }

            .auth-subtitle {
                font-size: 14px;
                color: #6B7280;
                text-align: center;
                margin-bottom: 30px;
                line-height: 1.6;
            }

            .auth-tabs {
                display: flex;
                gap: 10px;
                background: #F3F4F6;
                border-radius: 10px;
                padding: 5px;
                margin-bottom: 30px;
            }

            .auth-tab {
                flex: 1;
                padding: 12px;
                background: transparent;
                border: none;
                border-radius: 8px;
                font-size: 15px;
                font-weight: 600;
                color: #6B7280;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .auth-tab.active {
                background: white;
                color: #111;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .auth-form-container {
                animation: fadeIn 0.3s ease;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .form-field {
                margin-bottom: 20px;
            }

            .form-field label {
                display: block;
                font-size: 14px;
                font-weight: 600;
                color: #374151;
                margin-bottom: 8px;
            }

            .form-field input {
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #D1D5DB;
                border-radius: 8px;
                font-size: 15px;
                transition: all 0.3s ease;
            }

            .form-field input:focus {
                outline: none;
                border-color: #BC1D24;
                box-shadow: 0 0 0 3px rgba(188, 29, 36, 0.1);
            }

            .btn-auth {
                width: 100%;
                padding: 14px;
                background: #BC1D24;
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
            }

            .btn-auth:hover {
                background: #9a1720;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(188, 29, 36, 0.3);
            }

            .auth-divider {
                position: relative;
                text-align: center;
                margin: 30px 0;
            }

            .auth-divider::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                height: 1px;
                background: #E5E7EB;
            }

            .auth-divider span {
                position: relative;
                background: white;
                padding: 0 15px;
                color: #9CA3AF;
                font-size: 14px;
            }

            .auth-notice {
                text-align: center;
                font-size: 12px;
                color: #6B7280;
                line-height: 1.6;
            }

            .auth-notice a {
                color: #BC1D24;
                text-decoration: none;
                font-weight: 600;
            }

            .auth-notice a:hover {
                text-decoration: underline;
            }

            /* Social Login Buttons */
            .social-login-btns {
                display: flex;
                flex-direction: column;
                gap: 12px;
                margin-bottom: 20px;
            }

            .social-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
                padding: 12px 20px;
                border: 1px solid #E5E7EB;
                border-radius: 8px;
                background: white;
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.3s ease;
                color: #374151;
            }

            .social-btn:hover {
                background: #F9FAFB;
                border-color: #D1D5DB;
                transform: translateY(-1px);
            }

            .social-btn svg {
                flex-shrink: 0;
            }

            /* Responsive */
            @media (max-width: 1024px) {
                .welcome-container {
                    flex-direction: column;
                }

                .welcome-left {
                    padding: 60px 40px;
                }

                .welcome-right {
                    flex: 1;
                    padding: 60px 40px;
                }

                .welcome-brand {
                    font-size: 42px;
                }

                .welcome-tagline {
                    font-size: 24px;
                }
            }

            @media (max-width: 768px) {
                .welcome-left, .welcome-right {
                    padding: 40px 20px;
                }

                .welcome-brand {
                    font-size: 36px;
                }

                .welcome-tagline {
                    font-size: 20px;
                }

                .splash-title {
                    font-size: 36px;
                }

                .auth-card {
                    padding: 30px 20px;
                }
            }
        </style>
    </head>
    <body>
        <!-- Splash Screen -->
        <div id="splash-screen" class="splash-screen">
            <div class="splash-content">
                <div class="splash-logo">
                    <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="50" r="45" stroke="#BC1D24" stroke-width="3" stroke-dasharray="283" stroke-dashoffset="0" class="circle-animation"/>
                        <path d="M50 25 L60 45 L80 45 L65 57 L70 75 L50 65 L30 75 L35 57 L20 45 L40 45 Z" fill="#BC1D24" class="star-animation"/>
                    </svg>
                </div>
                <h1 class="splash-title">Tunas Motor</h1>
                <p class="splash-subtitle">Sparepart Motor Terpercaya</p>
                <div class="splash-loader">
                    <div class="loader-bar"></div>
                </div>
            </div>
        </div>

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
                                <a href="#">Kebijakan Privasi</a> kami.
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

        <script>
            // Splash Screen Animation
            window.addEventListener('load', function() {
                setTimeout(function() {
                    document.getElementById('splash-screen').style.opacity = '0';
                    setTimeout(function() {
                        document.getElementById('splash-screen').style.display = 'none';
                        document.getElementById('welcome-screen').style.display = 'block';
                        setTimeout(function() {
                            document.getElementById('welcome-screen').style.opacity = '1';
                        }, 50);
                    }, 500);
                }, 2500);
            });

            // Tab Switcher
            function switchTab(tab) {
                const registerTab = document.getElementById('registerTab');
                const loginTab = document.getElementById('loginTab');
                const registerForm = document.getElementById('registerForm');
                const loginForm = document.getElementById('loginForm');

                if (tab === 'register') {
                    registerTab.classList.add('active');
                    loginTab.classList.remove('active');
                    registerForm.style.display = 'block';
                    loginForm.style.display = 'none';
                } else {
                    loginTab.classList.add('active');
                    registerTab.classList.remove('active');
                    loginForm.style.display = 'block';
                    registerForm.style.display = 'none';
                }
            }
        </script>
    </body>
</html>
