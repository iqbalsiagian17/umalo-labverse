<?php

namespace App\Http\Controllers\Costumer\Order;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use PDF;
use Illuminate\Support\Facades\DB;
use App\Models\PPN;
use App\Models\Materai;
use Illuminate\Support\Facades\Log;

class OrderHandleCustomerController extends Controller
{

    public function showOrder($orderId)
    {
        $order = Order::find($orderId);
        return view('customer.order.detail-pesanan', compact('order'));
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

        // Validasi stok produk
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->back()->with('error', "Product {$item->product->name} is out of stock.");
            }
        }

        // Mulai transaksi database
        DB::beginTransaction();
        try {
            // Hitung total
            $total = $cartItems->sum(function ($item) {
                return $item->quantity * ($item->product->discount_price ?? $item->product->price);
            });

            // Buat pesanan
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => Order::STATUS_WAITING_APPROVAL, // gunakan konstanta untuk status
                'waiting_approval_at' => now(),
            ]);

            // Tambahkan item ke pesanan
            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->discount_price ?? $item->product->price,
                    'total' => $item->quantity * ($item->product->discount_price ?? $item->product->price),
                ]);

                // Kurangi stok produk
                $item->product->decrement('stock', $item->quantity);
            }

            // Kosongkan keranjang setelah checkout
            Cart::where('user_id', $user->id)->delete();

            // Commit transaksi database
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
            $order = Order::find($orderId);
    
            if ($order->status !== 'pending') {
                return redirect()->back()->with('error', 'You cannot cancel this order.');
            }
    
            $order->update(['status' => 'cancelled']);
    
            return redirect()->back()->with('success', 'Order has been cancelled successfully.');
        }
}
