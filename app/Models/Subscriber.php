<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'email',
        'name',
        'subscribed_at',
        'unsubscribed_at',
        'is_active',
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereNull('unsubscribed_at');
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false)->orWhereNotNull('unsubscribed_at');
    }

    public static function subscribe($email, $name = null)
    {
        $subscriber = self::where('email', $email)->first();

        if ($subscriber) {
            if (!$subscriber->is_active) {
                $subscriber->update([
                    'is_active' => true,
                    'unsubscribed_at' => null,
                    'subscribed_at' => now(),
                    'name' => $name,
                ]);
            }
            return $subscriber;
        }

        return self::create([
            'email' => $email,
            'name' => $name,
            'subscribed_at' => now(),
            'is_active' => true,
        ]);
    }

    public function unsubscribe()
    {
        $this->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);
    }

    public function isSubscribed()
    {
        return $this->is_active && is_null($this->unsubscribed_at);
    }
}
