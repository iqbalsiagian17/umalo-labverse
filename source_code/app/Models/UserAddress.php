<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'tambahan',
        'status',
    ];
}
