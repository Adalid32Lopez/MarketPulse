<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['business_id', 'user_id', 'title', 'type', 'filters', 'file_path', 'generated_at'];

    protected $casts = [
        'filters' => 'array',
        'generated_at' => 'datetime',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
