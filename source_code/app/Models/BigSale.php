<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BigSale extends Model
{
    use HasFactory;

    protected $table = 't_big_sale';

    protected $fillable = ['title', 'start', 'end', 'status', 'image'];

    public function Product()
    {
        return $this->belongsToMany(Product::class)->withPivot('harga_diskon');
    }
}
