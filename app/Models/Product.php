<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['business_id', 'category_id', 'name', 'description', 'price', 'stock', 'sku', 'is_active'];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function decreaseStock(int $qty): void
    {
        $this->decrement('stock', $qty);
    }
}
