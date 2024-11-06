<?php

namespace App\Http\Controllers\Costumer\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        
        // Calculate the subtotal
        $subtotal = $cartItems->sum('total_price'); // Sum the total prices of all cart items
        $total = $subtotal; // Add any taxes or shipping costs if needed
        
        return view('customer.cart.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $subtotal,  // Total is the same as the subtotal since shipping is not included
        ]);
    }

    public function addToCart(Request $request)
{
    // Validate request data
    $request->validate([
        'product_id' => 'required|integer|exists:t_product,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $productId = $request->input('product_id');
    $quantity = $request->input('quantity');

    // Get the product
    $product = Product::findOrFail($productId);

    // Cek jika stok produk kurang dari 1 (stok nol atau kurang)
    if ($product->stock <= 0) {
        return response()->json(['error' => 'Product is out of stock.'], 400);
    }

    // Check if the stock is available
    if ($product->stock < $quantity) {
        return response()->json(['error' => 'Not enough stock available.'], 400);
    }

    // Determine which price to use (discount or regular price)
    $price = $product->discount_price ?? $product->price; // If discount_price exists, use it, otherwise use price

    // Calculate the total price for this item
    $totalPrice = $price * $quantity;

    // Check if the product is already in the cart
    $cartItem = Cart::where('user_id', Auth::id())
                    ->where('product_id', $productId)
                    ->first();

    if ($cartItem) {
        // Update the quantity and total price if product already exists in the cart
        $cartItem->quantity += $quantity;
        $cartItem->total_price += $totalPrice;
        $cartItem->save();
    } else {
        // Add new item to the cart
        Cart::create([
            'user_id' => Auth::id(), // or session ID for guest users
            'product_id' => $productId,
            'quantity' => $quantity,
            'total_price' => $totalPrice, // Store the calculated total price
        ]);
    }

    return response()->json(['success' => 'Product added to cart.']);
    }

    public function removeFromCart($id)
    {
        try {
            // Find the cart item
            $cartItem = Cart::findOrFail($id);
    
            // Get the associated product
            $product = $cartItem->product;
    
            // Restore the product's stock by adding the cart item's quantity back to it
            if ($product) {
                $product->stock += $cartItem->quantity;
                $product->save();
            }
    
            // Remove the item from the cart
            $cartItem->delete();
    
            // Return a JSON response
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    
    public function updateQuantity(Request $request)
    {
        $cartItem = Cart::find($request->id);

        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            // Calculate subtotal and total
            $price = $cartItem->product->discount_price ?? $cartItem->product->price;
            $subtotal = $price * $cartItem->quantity;

            $total = Cart::where('user_id', auth()->id())->get()->sum(function($item) {
                return ($item->product->discount_price ?? $item->product->price) * $item->quantity;
            });

            return response()->json([
                'success' => true,
                'subtotal' => $subtotal,
                'total' => $total
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to update cart item.'
        ]);
    }


    
    
}