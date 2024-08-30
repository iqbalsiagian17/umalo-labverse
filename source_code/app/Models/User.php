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
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'foto_profile'


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

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // User.php
    public function seenByAdmins()
    {
        return $this->belongsToMany(User::class, 'user_seen_by_admin', 'user_id', 'admin_id')->withTimestamps();
    }

    public function newUsersSeenByAdmin()
    {
        return $this->belongsToMany(User::class, 'user_seen_by_admin', 'admin_id', 'user_id')->withTimestamps();
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id');
    }
    

}
