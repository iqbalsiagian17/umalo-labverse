<?php
namespace App\Http\Controllers\Costumer\Favorite;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function showFavorites()
    {
        $userId = Auth::id();
    
        // Get the user's favorite products with related images
        $favorites = DB::table('favorites')
                        ->join('Product', 'favorites.Product_id', '=', 'Product.id')
                        ->where('favorites.user_id', $userId)
                        ->select('Product.*')
                        ->get();
    
        // Eager load the images for each product
        foreach ($favorites as $favorite) {
            $favorite->images = Product::find($favorite->id)->images()->get();
        }
        
    
        return view('customer.favorite.show', compact('favorites'));
    }
    



    // Toggle favorite status (add/remove)
        public function toggleFavorite(Request $request, $productId)
    {
        $userId = Auth::id();  // Get the authenticated user's ID
        $product = Product::find($productId);  // Find the product

        if (!$product) {
            return response()->json(['message' => 'Product tidak ditemukan'], 404);
        }

        // Check if the product is already favorited by the user
        $exists = DB::table('favorites')
                    ->where('user_id', $userId)
                    ->where('Product_id', $productId)
                    ->exists();

        if ($exists) {
            // Remove from favorites
            DB::table('favorites')
                ->where('user_id', $userId)
                ->where('Product_id', $productId)
                ->delete();

            return response()->json(['message' => 'Removed from favorites']);
        } else {
            // Add to favorites
            DB::table('favorites')->insert([
                'user_id' => $userId,
                'Product_id' => $productId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['message' => 'Added to favorites']);
        }
    }

    public function removeFavorite($productId)
    {
        $userId = Auth::id();  // Get the authenticated user's ID

        // Check if the product is in the user's favorites
        $exists = DB::table('favorites')
                    ->where('user_id', $userId)
                    ->where('Product_id', $productId)
                    ->exists();

        if ($exists) {
            // Remove the product from the favorites table
            DB::table('favorites')
                ->where('user_id', $userId)
                ->where('Product_id', $productId)
                ->delete();

            return response()->json(['message' => 'Removed from favorites']);
        } else {
            return response()->json(['message' => 'Product is not in favorites'], 404);
        }
    }


    public function moveToCart($productId)
{
    $userId = Auth::id();
    $product = Product::find($productId);

    if (!$product) {
        return response()->json(['message' => 'Product tidak ditemukan'], 404);
    }

    // Add product to session cart
    $cart = session()->get('cart', []);

    // Check if product already exists in cart and update quantity
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] += 1;
    } else {
        $cart[$productId] = [
            'name' => $product->nama,
            'quantity' => 1,
            'harga_tayang' => $product->harga_tayang,
            'harga_potongan' => $product->harga_potongan,
            'image' => $product->images->first()->gambar ?? 'default.png'
        ];
    }

    // Save updated cart back to session
    session()->put('cart', $cart);

    // Remove product from favorites table directly
    DB::table('favorites')
        ->where('user_id', $userId)
        ->where('Product_id', $productId)
        ->delete();

    return response()->json(['message' => 'Moved to cart']);
}
}
