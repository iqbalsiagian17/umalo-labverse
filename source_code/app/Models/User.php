<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 't_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'foto_profile',
        'phone_number',
        'company',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime', 
    ];

    protected function role(): Attribute
    {
        return new Attribute(
            get: fn($value) =>  ["costumer", "admin"][$value],
        );
    }

    public function socialite()
    {
        return $this->hasMany(Socialite::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Product::class, 't_favorites', 'user_id', 'Product_id')
                    ->withTimestamps();
    }
    

}
