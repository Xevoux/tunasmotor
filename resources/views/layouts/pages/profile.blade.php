@extends('layouts.app')

@section('title', 'Profile - Tunas Motor')

@section('content')
@include('layouts.partials.header', ['hideSearch' => true])

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

                        <div class="form-group">
                            <label for="phone" class="form-label">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 12.5v2.82a1.88 1.88 0 01-2.05 1.88A18.62 18.62 0 011.87 4.05 1.88 1.88 0 013.75 2h2.81a1.88 1.88 0 011.88 1.62 12.09 12.09 0 00.66 2.64 1.88 1.88 0 01-.42 1.98l-1.2 1.2a15 15 0 006.78 6.78l1.2-1.2a1.88 1.88 0 011.98-.42 12.09 12.09 0 002.64.66A1.88 1.88 0 0117 12.17v.33z"/>
                                </svg>
                                Nomor Telepon
                            </label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   class="form-input @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="08xxxxxxxxxx">
                            @error('phone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">Alamat Pengiriman</h3>
                        <p class="form-section-desc">Alamat akan digunakan sebagai default untuk checkout</p>
                        
                        <div class="form-group">
                            <label for="address" class="form-label">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M15 7.5c0 4.5-6 9-6 9s-6-4.5-6-9a6 6 0 1112 0z"/>
                                    <circle cx="9" cy="7.5" r="2"/>
                                </svg>
                                Alamat Lengkap
                            </label>
                            <textarea id="address" 
                                      name="address" 
                                      class="form-input @error('address') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Nama jalan, RT/RW, Kelurahan, Kecamatan">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="city" class="form-label">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="6" width="5" height="10"/>
                                        <rect x="7" y="2" width="5" height="14"/>
                                        <rect x="12" y="8" width="4" height="8"/>
                                    </svg>
                                    Kota/Kabupaten
                                </label>
                                <input type="text" 
                                       id="city" 
                                       name="city" 
                                       class="form-input @error('city') is-invalid @enderror" 
                                       value="{{ old('city', $user->city) }}"
                                       placeholder="Contoh: Cirebon">
                                @error('city')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="postal_code" class="form-label">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="3" width="14" height="12" rx="1"/>
                                        <path d="M2 7h14"/>
                                    </svg>
                                    Kode Pos
                                </label>
                                <input type="text" 
                                       id="postal_code" 
                                       name="postal_code" 
                                       class="form-input @error('postal_code') is-invalid @enderror" 
                                       value="{{ old('postal_code', $user->postal_code) }}"
                                       placeholder="Contoh: 45123"
                                       maxlength="10">
                                @error('postal_code')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
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

                    <div class="form-section">
                        <h3 class="form-section-title">Langganan Newsletter</h3>
                        <p class="form-section-desc">Kelola langganan newsletter Anda</p>

                        <div class="subscription-status">
                            @php
                                $subscriber = \App\Models\Subscriber::where('email', $user->email)->first();
                                $isSubscribed = $subscriber && $subscriber->isSubscribed();
                            @endphp

                            @if($isSubscribed)
                                <div class="subscription-active">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" class="text-success">
                                        <path d="M16 6L7.5 14.5L4 11" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span>Anda sedang berlangganan newsletter sejak {{ $subscriber->subscribed_at->format('d F Y') }}</span>
                                </div>

                                <form method="POST" action="{{ route('unsubscribe') }}" id="unsubscribe-form" style="margin-top: 15px;">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                    <button type="submit" class="btn-unsubscribe" style="background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" style="margin-right: 8px;">
                                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1V1zM3 1v2h10V1H3z"/>
                                            <path d="M13 3H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V3z"/>
                                        </svg>
                                        Berhenti Berlangganan
                                    </button>
                                </form>
                            @else
                                <div class="subscription-inactive">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" class="text-muted">
                                        <circle cx="10" cy="10" r="8"/>
                                        <path d="M10 6v4M10 14v-4"/>
                                    </svg>
                                    <span>Anda belum berlangganan newsletter</span>
                                </div>

                                <form method="POST" action="{{ route('subscribe') }}" id="profile-subscribe-form" style="margin-top: 15px;">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                    <input type="hidden" name="name" value="{{ $user->name }}">
                                    <button type="submit" class="btn-subscribe-profile" style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" style="margin-right: 8px;">
                                            <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
                                        </svg>
                                        Berlangganan Newsletter
                                    </button>
                                </form>
                            @endif
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
                            Kembali ke Beranda
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@include('layouts.partials.footer')

@endsection

