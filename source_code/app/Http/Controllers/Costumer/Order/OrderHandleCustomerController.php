<?php

namespace App\Http\Controllers\Costumer\Order;

use App\Http\Controllers\Controller;
use App\Models\BigSale;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use PDF;
use Illuminate\Support\Facades\DB;
use App\Models\PPN;
use App\Models\Materai;
use App\Models\TParameter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderHandleCustomerController extends Controller
{

    public function showOrder($orderId)
    {
        // Temukan pesanan berdasarkan ID
        $order = Order::find($orderId);
    
        // Pastikan pesanan ditemukan
        if (!$order) {
            abort(404, 'Pesanan tidak ditemukan');
        }
    
        // Pastikan pesanan milik pengguna yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini');
        }
    
        // Update kolom is_viewed_by_customer menjadi true
        $order->is_viewed_by_customer = true;
        $order->save();

        foreach ($order->payments as $payment) {
            $payment->is_viewed_by_customer = true;
            $payment->save();
        }

        $parameter= TParameter::first();
    
        // Tampilkan halaman detail pesanan
        return view('customer.order.detail-pesanan', compact('order', 'parameter'));
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        // Fetch orders based on the status filter
        $orders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('customer.order.riwayat-pesanan', compact('orders','user'));
    }

    public function checkout(Request $request)
{
    $user = auth()->user();
    $cartItems = Cart::where('user_id', $user->id)->get();

    if ($cartItems->isEmpty()) {
        return redirect()->back()->with('error', 'Your cart is empty.');
    }

    // Validate product stock
    foreach ($cartItems as $item) {
        if ($item->quantity > $item->product->stock) {
            return redirect()->back()->with('error', "Product {$item->product->name} is out of stock.");
        }
    }

    $isNegotiable = false;

    // Begin database transaction
    DB::beginTransaction();
    try {
        $total = 0;

        // Calculate the total, applying Big Sale price if applicable
        foreach ($cartItems as $item) {
            $product = $item->product;
            $bigSalePrice = $product->price;

            // Check if the product is in an active Big Sale
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
                // If not in Big Sale, apply product-specific discount
                $bigSalePrice = $product->discount_price;
            }

            // Calculate total for each item
            $itemTotal = $item->quantity * $bigSalePrice;
            $total += $itemTotal;
        }

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'total' => $total,
            'status' => Order::STATUS_WAITING_APPROVAL,
            'waiting_approval_at' => now(),
        ]);

        // Add items to the order
        foreach ($cartItems as $item) {
            $negotiable = $item->product->negotiable === 'yes';
            if ($negotiable) $isNegotiable = true;

            $product = $item->product;
            $bigSalePrice = $product->price;

            // Re-check if the product is in an active Big Sale
            $activeBigSale = BigSale::where('status', true)
                ->where('start_time', '<=', now())
                ->where('end_time', '>=', now())
                ->whereHas('products', function ($query) use ($product) {
                    $query->where('t_product.id', $product->id);
                })
                ->first();

            if ($activeBigSale) {
                if ($activeBigSale->discount_amount) {
                    $bigSalePrice = $product->price - $activeBigSale->discount_amount;
                } elseif ($activeBigSale->discount_percentage) {
                    $bigSalePrice = $product->price - ($activeBigSale->discount_percentage / 100) * $product->price;
                }
            } elseif ($product->discount_price) {
                $bigSalePrice = $product->discount_price;
            }

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item->quantity,
                'price' => $bigSalePrice,
                'total' => $item->quantity * $bigSalePrice,
                'is_negotiated' => $negotiable,
            ]);

            // Decrement stock
            $product->decrement('stock', $item->quantity);
        }

        // Clear the cart after checkout
        Cart::where('user_id', $user->id)->delete();

        // If there are negotiable items, mark order for negotiation
        if ($isNegotiable) {
            $order->update([
                'status' => null,
                'negotiation_status' => Order::STATUS_NEGOTIATION_PENDING,
                'negotiation_pending_at' => now(),
            ]);
            DB::commit();
            return redirect()->route('customer.order.show', $order->id)->with('success', 'Order submitted for negotiation.');
        }

        DB::commit();
        return redirect()->route('customer.order.show', $order->id)->with('success', 'Checkout completed.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'An error occurred during checkout: ' . $e->getMessage());
    }
}



   

    public function completeOrder($orderId)
        {
            $order = Order::find($orderId);
        
            // Periksa apakah pesanan ditemukan
            if (!$order) {
                return back()->with('error', 'Order not found.');
            }
        
            // Set status order menjadi completed dengan menggunakan setStatus
            $order->setStatus(Order::STATUS_DELIVERED);
        
            return back()->with('success', 'Order marked as completed.');
        }

        public function cancelOrder($orderId)
        {
            $order = Order::findOrFail($orderId);

            // Allow cancellation only if the order is not in 'processing', 'shipped', or 'delivered' status
            if (in_array($order->status, ['processing', 'shipped', 'delivered'])) {
                return redirect()->back()->with('error', 'You cannot cancel this order.');
            }

            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Order has been cancelled successfully.');
        }




}
