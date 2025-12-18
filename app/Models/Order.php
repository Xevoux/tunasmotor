<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'nomor_pesanan',
        'total_harga',
        'diskon',
        'total_bayar',
        'status',
        'metode_pembayaran',
        'alamat_pengiriman',
        // Midtrans payment fields
        'snap_token',
        'payment_type',
        'transaction_id',
        'transaction_status',
        'paid_at',
        'payment_details',
        // Shipping info
        'nama_penerima',
        'telepon_penerima',
        'catatan',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total_bayar' => 'decimal:2',
        'paid_at' => 'datetime',
        'payment_details' => 'array',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_PAID => 'Dibayar',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_SHIPPED => 'Dikirim',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_EXPIRED => 'Kadaluarsa',
        ];
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => '#f59e0b',
            self::STATUS_PAID => '#10b981',
            self::STATUS_PROCESSING => '#3b82f6',
            self::STATUS_SHIPPED => '#8b5cf6',
            self::STATUS_COMPLETED => '#059669',
            self::STATUS_CANCELLED => '#ef4444',
            self::STATUS_EXPIRED => '#6b7280',
            default => '#6b7280',
        };
    }

    /**
     * Check if order can be paid
     */
    public function canBePaid(): bool
    {
        return $this->status === self::STATUS_PENDING && $this->snap_token;
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'TM';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return "{$prefix}{$date}{$random}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
