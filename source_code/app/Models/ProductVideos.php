<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVideos extends Model
{
    use HasFactory;

    protected $table = 't_p_video';

        // Tentukan kolom yang dapat diisi (mass assignable)
        protected $fillable = ['video', 'product_id'];

        // Relasi ke model TProduct
        public function product()
        {
            return $this->belongsTo(Product::class, 'product_id');
        }
    
}
