@extends('layouts.app')

@section('title', 'Profile - Tunas Motor')

@section('content')
@include('partials.header', ['hideSearch' => true])

<!-- Profile Section -->
<section class="profile-section">
    <div class="container">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-avatar">
                    <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <circle cx="40" cy="40" r="38" fill="#BC1D24" stroke="#fff" stroke-width="2"/>
                        <path d="M55 60v-4a8 8 0 0 0-8-8H33a8 8 0 0 0-8 8v4" stroke="#fff" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="40" cy="32" r="8" stroke="#fff" stroke-width="3"/>
                    </svg>
                </div>
                <div class="profile-info">
                    <h1 class="profile-name">{{ $user->name }}</h1>
                    <p class="profile-email">{{ $user->email }}</p>
                    <p class="profile-joined">Bergabung sejak {{ $user->created_at->format('d F Y') }}</p>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 6L7.5 14.5L4 11" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            <div class="profile-content">
                <h2 class="section-title">Edit Profile</h2>
                
                <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <h3 class="form-section-title">Informasi Pribadi</h3>
                        
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M15 15v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="5" r="3"/>
                                </svg>
                                Nama Lengkap
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="form-input @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 3h12a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1z"/>
                                    <path d="M16 4l-7 5-7-5"/>
                                </svg>
                                Email
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-input @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">Ubah Password</h3>
                        <p class="form-section-desc">Kosongkan jika tidak ingin mengganti password</p>
                        
                        <div class="form-group">
                            <label for="current_password" class="form-label">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="8" width="12" height="8" rx="1"/>
                                    <path d="M6 8V5a3 3 0 0 1 6 0v3"/>
                                </svg>
                                Password Lama
                            </label>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   class="form-input @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="new_password" class="form-label">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="8" width="12" height="8" rx="1"/>
                                    <path d="M6 8V5a3 3 0 0 1 6 0v3"/>
                                </svg>
                                Password Baru
                            </label>
                            <input type="password" 
                                   id="new_password" 
                                   name="new_password" 
                                   class="form-input @error('new_password') is-invalid @enderror">
                            @error('new_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <span class="form-hint">Minimal 8 karakter</span>
                        </div>

                        <div class="form-group">
                            <label for="new_password_confirmation" class="form-label">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="8" width="12" height="8" rx="1"/>
                                    <path d="M6 8V5a3 3 0 0 1 6 0v3"/>
                                </svg>
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   class="form-input">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 4L7 13L3 9" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('home') }}" class="btn-cancel">
                            Kembali ke Home
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer" id="contact">
    <div class="container">
        <div class="footer-columns">
            <div class="footer-column">
                <h4>Contact Us</h4>
                <p><strong>Phone Number</strong><br>+(62) 1234 5678 90</p>
                <p><strong>E-Mail</strong><br>info@example.com</p>
                <p><strong>Address</strong><br>Cirebon</p>
            </div>
            
            <div class="footer-column">
                <h4>Let Us Help You</h4>
                <ul>
                    <li><a href="#">Bantuan</a></li>
                    <li><a href="#">Cara Pembelian</a></li>
                    <li><a href="#">Pembayaran</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h4>Customer Service</h4>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Pengiriman</a></li>
                    <li><a href="#">Pengembalian</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h4>Get to Know Us</h4>
                <ul>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Karir</a></li>
                    <li><a href="#">Berita</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2025 Tunas Motor. All rights reserved.</p>
        </div>
    </div>
</footer>

@push('styles')
<style>
.profile-section {
    min-height: calc(100vh - 80px);
    padding: 40px 0;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.profile-container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.profile-header {
    background: linear-gradient(135deg, #BC1D24 0%, #000000 100%);
    padding: 40px;
    text-align: center;
    color: white;
}

.profile-avatar {
    margin-bottom: 20px;
}

.profile-avatar svg {
    display: inline-block;
}

.profile-name {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
}

.profile-email {
    font-size: 16px;
    opacity: 0.9;
    margin-bottom: 4px;
}

.profile-joined {
    font-size: 14px;
    opacity: 0.8;
}

.alert-success {
    margin: 20px;
    padding: 16px 20px;
    background: #d1fae5;
    border: 1px solid #10b981;
    border-radius: 8px;
    color: #065f46;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-success svg {
    stroke: #10b981;
    flex-shrink: 0;
}

.profile-content {
    padding: 40px;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 30px;
    color: #111;
}

.profile-form {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.form-section {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-section-title {
    font-size: 18px;
    font-weight: 600;
    color: #111;
    margin-bottom: 4px;
}

.form-section-desc {
    font-size: 14px;
    color: #6b7280;
    margin-top: -12px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-label {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-label svg {
    stroke: #6b7280;
}

.form-input {
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.2s ease;
}

.form-input:focus {
    outline: none;
    border-color: #BC1D24;
    box-shadow: 0 0 0 3px rgba(188, 29, 36, 0.1);
}

.form-input.is-invalid {
    border-color: #BC1D24;
}

.error-message {
    font-size: 13px;
    color: #BC1D24;
    margin-top: -4px;
}

.form-hint {
    font-size: 13px;
    color: #6b7280;
    margin-top: -4px;
}

.form-actions {
    display: flex;
    gap: 16px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

.btn-submit {
    flex: 1;
    padding: 14px 24px;
    background: #BC1D24;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-submit:hover {
    background: #9a1720;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(188, 29, 36, 0.3);
}

.btn-cancel {
    flex: 1;
    padding: 14px 24px;
    background: white;
    color: #374151;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-header {
        padding: 30px 20px;
    }

    .profile-content {
        padding: 30px 20px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-submit,
    .btn-cancel {
        width: 100%;
    }
}
</style>
@endpush
@endsection

