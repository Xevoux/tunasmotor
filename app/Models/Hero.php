<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    protected $table = 'hero_settings';

    protected $fillable = [
        'deal_label',
        'countdown_end_date',
        'title',
        'description',
        'button_text',
        'button_link',
        'image_path',
        'image_name',
        'alt_text',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'countdown_end_date' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }

    public function getFullImagePathAttribute()
    {
        return storage_path('app/public/' . $this->image_path);
    }
}