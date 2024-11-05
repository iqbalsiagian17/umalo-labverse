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
    $productId = $request->input('product_id');

    // Check if the product is already in the wishlist
    if (!Wishlist::where('user_id', $user->id)->where('product_id', $productId)->exists()) {
        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        return response()->json(['success' => true, 'message' => 'Product added to wishlist']);
    }

    return response()->json(['success' => false, 'message' => 'Product already in wishlist']);
}


    // Remove a product from the wishlist
    public function removeFromWishlist($productId)
    {
        $user = Auth::user();

        // Find the wishlist item and delete it
        Wishlist::where('user_id', $user->id)->where('product_id', $productId)->delete();

        return redirect()->back()->with('success', 'Product removed from wishlist');
    }

    // Display the user's wishlist
    public function index()
    {
        $user = Auth::user();
        $wishlistItems = Wishlist::where('user_id', $user->id)->with('product.images')->get();

        return view('customer.wishlist.index', compact('wishlistItems'));
    }

    public function moveToCart($productId)
{
    $user = Auth::user();

    // Find the product by its ID
    $product = Product::findOrFail($productId);

    // Remove the product from the wishlist
    Wishlist::where('user_id', $user->id)->where('product_id', $productId)->delete();

    // Calculate the total price based on the product's price and default quantity (1)
    $totalPrice = $product->price * 1; // You can adjust the quantity if needed

    // Add to Cart
    Cart::create([
        'user_id' => $user->id,
        'product_id' => $productId,
        'quantity' => 1, // Default quantity
        'total_price' => $totalPrice, // Use the product price here
    ]);

    return redirect()->back()->with('success', 'Product moved to cart');
}




}
