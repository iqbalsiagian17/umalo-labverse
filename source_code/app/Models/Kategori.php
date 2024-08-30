<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = ['nama', 'flag'];
    
    public function subkategoris()
    {
        return $this->hasMany(SubKategori::class);
    }

    public function produk()
    {
        return $this->hasMany(Produk::class);
    }

}