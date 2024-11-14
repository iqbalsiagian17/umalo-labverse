<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 't_p_sub_category';

    protected $fillable = ['name', 'category_id','slug']; // Tambahkan flag ke fillable

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function Product()
    {
        return $this->hasMany(Product::class);
    }

}
