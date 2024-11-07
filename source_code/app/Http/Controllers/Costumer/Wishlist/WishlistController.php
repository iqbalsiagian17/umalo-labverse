<?php

namespace App\Http\Controllers\Costumer\Wishlist;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function addToWishlist(Request $request)
    {
        $user = Auth::user();
    
        // Cek apakah produk sudah ada di wishlist pengguna
        $exists = Wishlist::where('user_id', $user->id)
                          ->where('Product_id', $request->product_id)
                          ->exists();
    
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in wishlist'
            ]);
        }
    
        // Tambahkan produk ke wishlist
        Wishlist::create([
            'user_id' => $user->id,
            'Product_id' => $request->product_id,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist'
        ]);
    }


    // Remove a product from the wishlist
    public function removeFromWishlist($productId)
    {
        $user = Auth::user();
    
        Wishlist::where('user_id', $user->id)->where('product_id', $productId)->delete();
    
        return response()->json(['success' => true, 'message' => __('Product removed from wishlist')]);
    }

    // Display the user's wishlist
    public function index()
    {
        $user = Auth::user();
        $wishlistItems = Wishlist::where('user_id', $user->id)->with('product')->get();
    
        return view('customer.wishlist.index', compact('wishlistItems'));
    }
    

    public function moveToCart($productId)
    {
        $user = Auth::user();

        $product = Product::findOrFail($productId);

        Wishlist::where('user_id', $user->id)->where('product_id', $productId)->delete();

        $totalPrice = $product->price * 1;

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'quantity' => 1,
            'total_price' => $totalPrice,
        ]);

        return response()->json(['success' => true, 'message' => __('Product moved to cart')]);
    }




}
