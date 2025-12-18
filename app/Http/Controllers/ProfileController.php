<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profile user
     */
    public function show()
    {
        $user = Auth::user();
        return view('layouts.pages.profile', compact('user'));
    }

    /**
     * Update profile user
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_photo' => 'nullable|in:0,1',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'current_password.required_with' => 'Password lama harus diisi jika ingin mengganti password',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
            'profile_photo.image' => 'File harus berupa gambar',
            'profile_photo.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp',
            'profile_photo.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Cek jika user ingin mengganti password
        if ($request->filled('current_password')) {
            // Verifikasi password lama
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Password lama tidak sesuai'
                ])->withInput();
            }

            // Update password baru
            $user->password = Hash::make($request->new_password);
        }

        // Handle photo removal
        if ($request->input('remove_photo') === '1') {
            if ($user->profile_photo) {
                Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
                $user->profile_photo = null;
            }
        }
        // Handle photo upload (only if not marked for removal)
        elseif ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo) {
                Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
            }

            // Upload foto baru
            $file = $request->file('profile_photo');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('profile-photos', $filename, 'public');
            $user->profile_photo = $filename;
        }

        // Update profile fields
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->address = $validated['address'] ?? $user->address;
        $user->city = $validated['city'] ?? $user->city;
        $user->postal_code = $validated['postal_code'] ?? $user->postal_code;
        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Profile berhasil diperbarui!');
    }

    /**
     * Upload atau update foto profil
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'profile_photo.required' => 'Silakan pilih foto untuk diupload',
            'profile_photo.image' => 'File harus berupa gambar',
            'profile_photo.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp',
            'profile_photo.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $user = Auth::user();

        // Hapus foto lama jika ada
        if ($user->profile_photo) {
            Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
        }

        // Upload foto baru
        $file = $request->file('profile_photo');
        $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('profile-photos', $filename, 'public');

        // Update database
        $user->profile_photo = $filename;
        $user->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diupload!',
                'photo_url' => $user->profile_photo_url,
            ]);
        }

        return redirect()->route('profile.show')
            ->with('success', 'Foto profil berhasil diupload!');
    }

    /**
     * Hapus foto profil
     */
    public function removePhoto(Request $request)
    {
        $user = Auth::user();

        if ($user->profile_photo) {
            // Hapus file dari storage
            Storage::disk('public')->delete('profile-photos/' . $user->profile_photo);
            
            // Update database
            $user->profile_photo = null;
            $user->save();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil dihapus!',
            ]);
        }

        return redirect()->route('profile.show')
            ->with('success', 'Foto profil berhasil dihapus!');
    }
}

