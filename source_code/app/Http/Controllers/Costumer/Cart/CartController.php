<?php

namespace App\Http\Controllers\Costumer\Cart;

use App\Http\Controllers\Controller;
use App\Models\BigSale;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;
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

    // Check if the product is out of stock
    if ($product->stock <= 0) {
        return response()->json(['error' => 'Product is out of stock.'], 400);
    }

    // Check if the requested quantity is available in stock
    if ($product->stock < $quantity) {
        return response()->json(['error' => 'Not enough stock available.'], 400);
    }

    // Determine if the product is part of an active Big Sale
    $bigSalePrice = $product->price; // Default to the regular price

    $activeBigSale = BigSale::where('status', true)
        ->where('start_time', '<=', now())
        ->where('end_time', '>=', now())
        ->whereHas('products', function ($query) use ($product) {
            $query->where('t_product.id', $product->id);
        })
        ->first();

    if ($activeBigSale) {
        // Apply Big Sale discount
        if ($activeBigSale->discount_amount) {
            $bigSalePrice = $product->price - $activeBigSale->discount_amount;
        } elseif ($activeBigSale->discount_percentage) {
            $bigSalePrice = $product->price - ($activeBigSale->discount_percentage / 100) * $product->price;
        }
    } elseif ($product->discount_price) {
        // If no Big Sale, apply the product-specific discount price
        $bigSalePrice = $product->discount_price;
    }

    // Calculate the total price for this item based on the determined price
    $totalPrice = $bigSalePrice * $quantity;

    // Check if the product is already in the cart
    $cartItem = Cart::where('user_id', Auth::id())
                    ->where('product_id', $productId)
                    ->first();

    if ($cartItem) {
        // Update quantity and total price if the product is already in the cart
        $cartItem->quantity += $quantity;
        $cartItem->total_price += $totalPrice;
        $cartItem->save();
    } else {
        // Add new item to the cart
        Cart::create([
            'user_id' => Auth::id(), // Use user ID, or session ID for guest users if needed
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
    try {
        // Retrieve the cart item based on the provided ID
        $cartItem = Cart::findOrFail($request->id);

        // Update the quantity in the database
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // Retrieve the associated product
        $product = $cartItem->product;
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found for this cart item.'
            ], 404);
        }

        // Calculate the price, checking for active Big Sale discounts
        $price = $product->price;
        $activeBigSale = BigSale::where('status', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->whereHas('products', function ($query) use ($product) {
                $query->where('t_product.id', $product->id);
            })
            ->first();

        if ($activeBigSale) {
            if ($activeBigSale->discount_amount) {
                $price = $product->price - $activeBigSale->discount_amount;
            } elseif ($activeBigSale->discount_percentage) {
                $price = $product->price - ($activeBigSale->discount_percentage / 100) * $product->price;
            }
        } elseif ($product->discount_price) {
            $price = $product->discount_price;
        }

        // Calculate the subtotal for this cart item
        $subtotal = $price * $cartItem->quantity;

        // Calculate the total for all items in the cart
        $total = Cart::where('user_id', auth()->id())->get()->sum(function ($item) {
            $itemPrice = $item->product->price;

            $activeBigSale = BigSale::where('status', true)
                ->where('start_time', '<=', now())
                ->where('end_time', '>=', now())
                ->whereHas('products', function ($query) use ($item) {
                    $query->where('t_product.id', $item->product->id);
                })
                ->first();

            if ($activeBigSale) {
                if ($activeBigSale->discount_amount) {
                    $itemPrice = $item->product->price - $activeBigSale->discount_amount;
                } elseif ($activeBigSale->discount_percentage) {
                    $itemPrice = $item->product->price - ($activeBigSale->discount_percentage / 100) * $item->product->price;
                }
            } elseif ($item->product->discount_price) {
                $itemPrice = $item->product->discount_price;
            }

            return $itemPrice * $item->quantity;
        });

        return response()->json([
            'success' => true,
            'subtotal' => $subtotal,
            'total' => $total
        ]);
    } catch (\Exception $e) {
        // Log the error for debugging
        Log::error('Error updating cart quantity:', [
            'error' => $e->getMessage(),
            'request_data' => $request->all()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error updating cart: ' . $e->getMessage()
        ], 500);
    }
}




    
    
}