<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['produk_id', 'user_id', 'content','rating','images', 
        'videos'];

          // Automatically cast the images and videos fields to and from arrays
    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
    ];
    public function product()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

