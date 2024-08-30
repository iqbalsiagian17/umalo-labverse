<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukList extends Model
{
    use HasFactory;

    protected $table = 'produk_list';

    protected $fillable = [
        'nama',
        'spesifikasi',
        'merk',
        'tipe',
        'jumlah',
        'satuan',
        'harga_satuan',
        'produk_id',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
}
