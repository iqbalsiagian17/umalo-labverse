<?php

namespace App\Http\Controllers\Costumer\Review;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewCustomerController extends Controller
{
    public function storeReview(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'product_id' => 'required|exists:t_product,id',
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
            return response()->json(['errors' => ['You do not have a delivered order for this product.']], 422);
        }

        // Simpan ulasan
        $review = Review::create([
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'],
            'order_id' => $validated['order_id'],
            'rating' => $validated['rating'],
            'content' => $validated['comment'],
        ]);

        // Kembalikan respons JSON
        return response()->json([
            'success' => true,
            'message' => 'Thank you for your review!',
            'review' => [
                'created_at' => $review->created_at->format('F d, Y'),
                'name' => $review->user->name,
                'rating' => $review->rating,
                'comment' => $review->content,
                'profile_photo' => asset($review->user->profile_photo),
            ]
        ]);
    }






}
