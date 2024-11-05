<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    use HasFactory;

    // Specify the table name
    protected $table = 't_cart';

    // Define the fillable properties (attributes you want to mass assign)
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total_price',
    ];

    // Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to the Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function navbar()
{
    $user = auth()->user();
    
    // Fetch the cart item count for the logged-in user
    $cartItemCount = 0; // Default count is 0 for guests
    if ($user) {
        $cartItemCount = Cart::where('user_id', $user->id)->count();
    }

    return view('layouts.customer.navbar', compact('cartItemCount'));
}

}
