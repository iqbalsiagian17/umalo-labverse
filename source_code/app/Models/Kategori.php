<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = ['nama', 'flag'];
    
    public function subKategori()
    {
        return $this->hasMany(SubKategori::class, 'kategori_id');
    }

    public function produk()
    {
        return $this->hasMany(Produk::class);
    }

}