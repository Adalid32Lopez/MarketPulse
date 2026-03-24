<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Negocios donde el usuario es miembro (vendedor)
    public function memberOf()
    {
        return $this->belongsToMany(Business::class, 'business_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // Todos los negocios accesibles (propios + donde es miembro)
    public function accessibleBusinesses()
    {
        $owned    = $this->businesses()->pluck('id');
        $member   = $this->memberOf()->pluck('businesses.id');
        return $owned->merge($member)->unique();
    }
}
