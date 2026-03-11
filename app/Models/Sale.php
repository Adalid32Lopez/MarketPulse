<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'business_id', 'user_id', 'customer_id', 'code',
        'total', 'discount', 'tax', 'status',
        'payment_method', 'notes', 'sold_at'
    ];

    protected $casts = [
        'sold_at' => 'datetime',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function calculateTotal(): float
    {
        $subtotal = $this->items->sum('subtotal');
        return $subtotal - $this->discount + $this->tax;
    }
}
