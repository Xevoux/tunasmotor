<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'nama',
        'deskripsi',
        'harga',
        'harga_diskon',
        'stok',
        'terjual',
        'gambar',
        'rating',
        'jumlah_rating',
        'is_new',
        'diskon_persen',
    ];

    protected $casts = [
        'is_new' => 'boolean',
        'harga' => 'decimal:2',
        'harga_diskon' => 'decimal:2',
        'rating' => 'decimal:1',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
