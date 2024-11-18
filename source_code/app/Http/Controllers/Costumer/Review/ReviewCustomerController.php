<?php

namespace App\Http\Controllers\Costumer\Review;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewCustomerController extends Controller
{
    public function storeReview(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'product_id' => 'required|exists:t_product,slug',  // Menggunakan slug di sini
            'order_id' => 'required|exists:t_orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        // Cek apakah order valid
        $order = Order::where('id', $validated['order_id'])
                    ->where('user_id', auth()->id())
                    ->where('status', Order::STATUS_DELIVERED)
                    ->first();

        if (!$order) {
            return back()->with('error', 'You do not have a delivered order for this product.');
        }

        // Ambil produk berdasarkan slug
        $product = Product::where('slug', $validated['product_id'])->first();

        if (!$product) {
            return back()->with('error', 'Product not found.');
        }

        // Simpan ulasan
        $review = Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'order_id' => $validated['order_id'],
            'rating' => $validated['rating'],
            'content' => $validated['comment'],
        ]);

        // Redirect kembali dengan session sukses
        return redirect()->route('product.show', ['slug' => $product->slug])  // Menggunakan slug di sini
                        ->with('success', 'Thank you for your review!');
    }






}
