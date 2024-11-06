<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $table = 't_user_addresses';

    protected $fillable = [
        'user_id',
        'address_label',
        'recipient_name',
        'phone_number',
        'is_active',
        'address',
        'city',
        'province',
        'postal_code',
        'additional_info',    ];
}
