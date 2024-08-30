<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BigSale extends Model
{
    use HasFactory;

    protected $table = 'big_sale';

    protected $fillable = ['judul', 'mulai', 'berakhir', 'status', 'image'];

    public function produk()
    {
        return $this->belongsToMany(Produk::class)->withPivot('harga_diskon');
    }
}
