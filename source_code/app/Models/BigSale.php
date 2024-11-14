<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BigSale extends Model
{
    use HasFactory;

    protected $table = 't_bigsales';

    protected $fillable = [
        'title',
        'slug',
        'banner',
        'modal_image',
        'start_time',
        'end_time',
        'discount_amount',
        'discount_percentage',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bigSale) {
            $bigSale->slug = Str::slug($bigSale->title);
        });
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 't_bigsales_product', 'bigsale_id', 'product_id');
    }

    public function isActive()
    {
        return $this->status && now()->between($this->start_time, $this->end_time);
    }
}
