<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingService extends Model
{
    use HasFactory;

    protected $table = 't_md_shipping_services';
    protected $fillable = ['name'];

}
