<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BigSaleProduct extends Model
{
    use HasFactory;

    protected $table = 't_big_sale_Product';

    protected $fillable = ['big_sale_id', 'product_id', 'discount_price'];

}
