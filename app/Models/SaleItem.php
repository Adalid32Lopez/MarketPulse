<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    public $timestamps = false; // solo tiene created_at

    protected $fillable = ['sale_id', 'product_id', 'quantity', 'unit_price', 'subtotal'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function computeSubtotal(): float
    {
        return $this->quantity * $this->unit_price;
    }
}
