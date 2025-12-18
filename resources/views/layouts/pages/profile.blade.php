@extends('layouts.app')

@section('title', 'Profile - Tunas Motor')

@push('styles')
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
@endpush

@section('content')
@include('layouts.partials.header', ['hideSearch' => true])

<!-- Profile Section -->
<section class="profile-section">
    <div class="container">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-avatar-container">
                    <div class="profile-avatar-wrapper" id="profileAvatarWrapper">
                        @if($user->hasProfilePhoto())
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="profile-avatar-img" id="profileAvatarImg">
                        @else
                            <div class="profile-avatar-placeholder" id="profileAvatarPlaceholder">
                                <span>{{ $user->initials }}</span>
                            </div>
                        @endif
                        
                        <!-- Camera overlay for photo actions -->
                        <div class="profile-avatar-overlay" onclick="togglePhotoMenu()">
                            <i class="fa-solid fa-camera"></i>
                        </div>
                    </div>
                    
                    <!-- Photo Actions Menu -->
                    <div class="photo-actions-menu" id="photoActionsMenu">
                        <button type="button" class="photo-action-btn" onclick="selectProfilePhoto()">
                            <i class="fa-solid fa-upload"></i>
                            <span id="photoActionText">{{ $user->hasProfilePhoto() ? 'Ganti Foto' : 'Upload Foto' }}</span>
                        </button>
                        <button type="button" class="photo-action-btn photo-action-danger" id="removePhotoBtn" onclick="markPhotoForRemoval()" style="{{ $user->hasProfilePhoto() ? '' : 'display: none;' }}">
                            <i class="fa-solid fa-trash"></i>
                            <span>Hapus Foto</span>
                        </button>
                    </div>
                    
                    <!-- Photo change indicator -->
                    <div class="photo-change-indicator" id="photoChangeIndicator" style="display: none;">
                        <i class="fa-solid fa-info-circle"></i>
                        <span id="photoChangeText">Foto akan diperbarui saat menyimpan</span>
                    </div>
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
                
                <form method="POST" action="{{ route('profile.update') }}" class="profile-form" id="profileForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Hidden file input for photo (inside form) -->
                    <input type="file" id="photoInput" name="profile_photo" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" style="display: none;" onchange="previewProfilePhoto(this)">
                    <!-- Hidden input to mark photo for removal -->
                    <input type="hidden" id="removePhotoInput" name="remove_photo" value="0">

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

                <!-- Newsletter Section - Separate from profile form -->
                <div class="form-section newsletter-section">
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

                            <form method="POST" action="{{ route('unsubscribe') }}" id="unsubscribe-form" class="newsletter-form">
                                @csrf
                                <input type="hidden" name="email" value="{{ $user->email }}">
                                <button type="submit" class="btn-newsletter btn-unsubscribe">
                                    <i class="fa-solid fa-bell-slash"></i>
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

                            <a href="#contact" class="btn-newsletter btn-subscribe-profile">
                                <i class="fa-solid fa-bell"></i>
                                Berlangganan Newsletter
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layouts.partials.footer')

<!-- Image Crop Modal -->
<div class="crop-modal-overlay" id="cropModal">
    <div class="crop-modal">
        <div class="crop-modal-header">
            <h3 class="crop-modal-title">
                <i class="fa-solid fa-crop"></i>
                Atur Foto Profil
            </h3>
            <button type="button" class="crop-modal-close" onclick="closeCropModal()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="crop-modal-body">
            <div class="crop-container">
                <img id="cropImage" src="" alt="Crop preview">
            </div>
            <div class="crop-instructions">
                <i class="fa-solid fa-lightbulb"></i>
                <span>Geser dan sesuaikan area yang ingin ditampilkan. Gunakan scroll mouse atau pinch untuk zoom. Hasil crop akan berbentuk lingkaran.</span>
            </div>
        </div>
        <div class="crop-modal-footer">
            <button type="button" class="crop-btn crop-btn-cancel" onclick="closeCropModal()">
                <i class="fa-solid fa-times"></i>
                Batal
            </button>
            <button type="button" class="crop-btn crop-btn-apply" onclick="applyCrop()">
                <i class="fa-solid fa-check"></i>
                Terapkan
            </button>
        </div>
    </div>
</div>

<!-- Cropper.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<!-- Initialize Profile Photo with user data -->
<script>
    // Initialize profile photo state with data from server
    document.addEventListener('DOMContentLoaded', function() {
        initializeProfilePhoto(
            '{{ $user->profile_photo_url ?? "" }}',
            {{ $user->hasProfilePhoto() ? 'true' : 'false' }},
            '{{ $user->initials }}'
        );

        // Handle newsletter subscription button click
        const newsletterBtn = document.querySelector('.btn-subscribe-profile');
        if (newsletterBtn) {
            newsletterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const targetElement = document.getElementById('contact');
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                } else {
                    // If footer is not on the same page, redirect to home page with anchor
                    window.location.href = '{{ route("home") }}#contact';
                }
            });
        }
    });
</script>

@endsection

