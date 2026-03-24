<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model {
    protected $fillable = ['user_id', 'name', 'industry', 'currency', 'logo', 'is_active'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function saleMetrics()
    {
        return $this->hasMany(SaleMetric::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
    public function members()
    {
    return $this->belongsToMany(User::class, 'business_user')
                ->withPivot('role')
                ->withTimestamps();
    }
}
