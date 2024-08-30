<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BigSaleProduk extends Model
{
    use HasFactory;

    protected $table = 'big_sale_produk';

    protected $fillable = ['big_sale_id', 'produk_id', 'harga_diskon'];

}
