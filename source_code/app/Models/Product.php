<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 't_product';

    protected $fillable = [
        'name',
        'slug',
        'stock',
        'product_expiration_date',
        'brand',
        'provider_product_number',
        'measurement_unit',
        'product_type',
        'kbki_code',
        'tkdn_value',
        'sni_number',
        'product_warranty',
        'sni',
        'function_test',
        'has_svlk',
        'tool_type',
        'function',
        'product_specifications',
        'status',
        'negotiable',
        'is_price_displayed',
        'discount_price',
        'price',
        'e_catalog_link',
        'subcategory_id',
        'category_id'
    ];
    
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function videos()
    {
        return $this->hasMany(ProductVideos::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id', 'id')
                    ->where('status', 'delivered');
    }

    public function productList()
    {
        return $this->hasMany(ProductList::class, 'product_id');
    }

    public function bigSales()
    {
        return $this->belongsToMany(BigSale::class, 't_bigsales_product', 'product_id', 'bigsale_id');
    }

    




}
