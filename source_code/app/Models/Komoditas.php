<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komoditas extends Model
{
    use HasFactory;

    protected $table = 'komoditas';

    protected $fillable = ['nama', 'flag'];

    public function produk()
    {
        return $this->hasMany(Produk::class);
    }

}
