@extends('layouts.app')

@section('title', 'Register - Tunas Motor')

@section('content')
<div class="login-container">
    <div class="login-box">
        <h1 class="login-title">Daftar Akun Baru</h1>
        <p style="text-align: center; color: #6b7280; margin-bottom: 30px; font-size: 14px;">
            Lengkapi data di bawah untuk membuat akun
        </p>

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" class="login-form">
            @csrf
            
            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="Masukkan nama lengkap Anda" 
                    value="{{ old('name', request()->get('name')) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Masukkan alamat email Anda" 
                    value="{{ old('email', request()->get('email')) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-input">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Buat password (min. 8 karakter)"
                        required
                    >
                    <button type="button" class="toggle-password" onclick="togglePassword('password')">
                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <div class="password-input">
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        placeholder="Ulangi password Anda"
                        required
                    >
                    <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">Daftar</button>

            <div class="terms">
                Dengan mendaftar, Anda menyetujui 
                <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi</a> Tunas Motor.
            </div>
        </form>

        <div class="signup-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
}
</script>
@endsection

