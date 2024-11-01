<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductList extends Model
{
    use HasFactory;

    protected $table = 't_product_list';

    protected $fillable = [
        'name',
        'specifications',
        'brand',
        'type',
        'quantity',
        'unit',
        'unit_price',
        'product_id'
    ];

    /**
     * Define relationship with Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
