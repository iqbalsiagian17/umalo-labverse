<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 't_p_category';


    protected $fillable = ['name', 'slug',];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }



    
}
