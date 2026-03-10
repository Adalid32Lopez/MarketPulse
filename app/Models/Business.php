<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model {
    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function products() {
        return $this->hasMany(Product::class);
    }
    public function sales() {
        return $this->hasMany(Sale::class);
    }
    // etc...
}
