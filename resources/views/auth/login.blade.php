@extends('layouts.app')

@section('title', 'Login - Tunas Motor')

@section('content')
<div class="login-container">
    <div class="login-box">
        <h1 class="login-title">Masuk ke Akun Anda</h1>
        <p style="text-align: center; color: #6b7280; margin-bottom: 30px; font-size: 14px;">
            Masukkan email dan password untuk melanjutkan
        </p>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf
            
            @if ($errors->any())
                <div class="error-message">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="Masukkan alamat email Anda" 
                    value="{{ old('email', request()->get('email')) }}"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-input">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Masukkan password Anda"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">Masuk</button>

            <div class="forgot-password">
                <a href="#">Lupa password?</a>
            </div>
        </form>

        <div class="signup-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>
    </div>
</div>

@endsection

