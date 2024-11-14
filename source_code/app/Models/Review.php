<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 't_reviews';
    protected $fillable = ['product_id', 'user_id', 'content','rating','images', 'videos','order_id'];

          // Automatically cast the images and videos fields to and from arrays
    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

