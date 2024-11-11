<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TParameter extends Model
{
    use HasFactory;

    protected $table = 't_parameter';

    protected $fillable = [
        'company_name',
        'ecommerce_name',
        'email1',
        'email2',
        'telephone_number',
        'whatsapp_number',
        'address',
        'slogan',
        'account_name',
        'bank_name',
        'account_number',
        'bank_city',
        'bank_address',
        'director',
        'logo1',
        'logo2',
        'logo3',
    ];

}
