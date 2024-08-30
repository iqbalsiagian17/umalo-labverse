<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    use HasFactory;

    protected $table = 'sub_kategori';

    protected $fillable = ['nama', 'kategori_id', 'flag']; // Tambahkan flag ke fillable

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function produk()
    {
        return $this->hasMany(Produk::class);
    }

}
