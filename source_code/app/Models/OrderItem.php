<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'produk_id',
        'jumlah',
        'harga',
    ];

    // Definisikan relasi dengan Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Definisikan relasi dengan Produk jika diperlukan
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}

