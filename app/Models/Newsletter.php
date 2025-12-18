<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'status',
        'sent_at',
        'recipient_count',
        'sent_to',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'sent_to' => 'array',
        'recipient_count' => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function publish()
    {
        $this->update(['status' => 'published']);
    }

    public function markAsSent($recipientCount = 0, $emails = [])
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
            'recipient_count' => $recipientCount,
            'sent_to' => $emails,
        ]);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'gray',
            'published' => 'success',
            'sent' => 'primary',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'published' => 'Published',
            'sent' => 'Sent',
            default => 'Unknown',
        };
    }
}
