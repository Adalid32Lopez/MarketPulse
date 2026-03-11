<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = ['business_id', 'user_id', 'type', 'message', 'threshold', 'is_read', 'triggered_at'];

    protected $casts = [
        'triggered_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }
}
