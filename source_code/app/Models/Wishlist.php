<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    // Specify the table name if necessary
    protected $table = 't_wishlist';

    // Define the fillable properties
    protected $fillable = [
        'user_id',
        'Product_id',
    ];

    // Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to the Product model
    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_id'); // Gunakan 'Product_id' jika nama kolom benar demikian
    }
    
    
}
