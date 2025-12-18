<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerService extends Model
{
    use HasFactory;

    protected $table = 'customer_services';

    protected $fillable = [
        'nama',
        'nomor_whatsapp',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Scope untuk CS yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan urutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }

    /**
     * Get inisial nama untuk avatar
     */
    public function getInitialAttribute(): string
    {
        $words = explode(' ', $this->nama);
        $initial = '';
        foreach ($words as $word) {
            $initial .= strtoupper(substr($word, 0, 1));
            if (strlen($initial) >= 2) break;
        }
        return $initial ?: strtoupper(substr($this->nama, 0, 1));
    }

    /**
     * Get formatted WhatsApp number untuk link
     */
    public function getWhatsappLinkAttribute(): string
    {
        // Remove semua karakter non-digit
        $number = preg_replace('/[^0-9]/', '', $this->nomor_whatsapp);
        
        // Jika dimulai dengan 0, ganti dengan 62
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }
        
        return "https://wa.me/{$number}";
    }

    /**
     * Get formatted phone number untuk display
     */
    public function getFormattedPhoneAttribute(): string
    {
        $number = preg_replace('/[^0-9]/', '', $this->nomor_whatsapp);
        
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }
        
        return '+' . $number;
    }
}

