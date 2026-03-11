<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleMetric extends Model
{
    protected $fillable = ['business_id', 'date', 'total_sales', 'total_revenue', 'avg_ticket', 'top_product_id'];

    protected $casts = [
        'date' => 'date',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function topProduct()
    {
        return $this->belongsTo(Product::class, 'top_product_id');
    }
}
