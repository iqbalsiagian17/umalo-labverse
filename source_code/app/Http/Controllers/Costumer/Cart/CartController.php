<?php

namespace App\Http\Controllers\Costumer\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class CartController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart');
        
        if (!$cart || count($cart) == 0) {
            return redirect()->route('cart.view')->with('error', 'Keranjang belanja Anda kosong.');
        }
        
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product && $details['quantity'] > $product->stok) {
                return redirect()->route('cart.view')->with('error', 'Kuantitas untuk Product ' . $product->nama . ' melebihi stok yang tersedia.');
            }
        }
    
        // Determine initial status based on product negotiation availability
        $initialStatus = 'Menunggu Konfirmasi Admin';
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product && $product->nego == 'ya') {
                $initialStatus = 'Menunggu Konfirmasi Admin untuk Negosiasi';
                break;
            }
        }
    
        $totalHarga = 0;
        foreach ($cart as $id => $details) {
            $product = Product::with(['bigSales' => function ($query) {
                $query->where('status', 'aktif')
                      ->whereDate('mulai', '<=', now())
                      ->whereDate('berakhir', '>=', now());
            }])->find($id);
    
            // Check if the product is part of an active Big Sale and apply harga_diskon if available
            $harga_diskon = null;
            if ($product && $product->bigSales->isNotEmpty()) {
                $bigSale = $product->bigSales->first();
                $harga_diskon = $bigSale->pivot->harga_diskon ?? null;
            }
    
            // Determine the price to use: harga_diskon, harga_potongan, or harga_tayang
            $harga = $harga_diskon ?: ($details['harga_potongan'] > 0 ? $details['harga_potongan'] : $details['harga_tayang']);
            
            $totalHarga += $harga * $details['quantity'];
        }
    
        $order = Order::create([
            'user_id' => auth()->id(),
            'harga_total' => $totalHarga,
            'status' => $initialStatus,
        ]);
    
        foreach ($cart as $id => $details) {
            $product = Product::with(['bigSales' => function ($query) {
                $query->where('status', 'aktif')
                      ->whereDate('mulai', '<=', now())
                      ->whereDate('berakhir', '>=', now());
            }])->find($id);
    
            // Check if the product is part of an active Big Sale and apply harga_diskon if available
            $harga_diskon = null;
            if ($product && $product->bigSales->isNotEmpty()) {
                $bigSale = $product->bigSales->first();
                $harga_diskon = $bigSale->pivot->harga_diskon ?? null;
            }
    
            // Determine the price to save in OrderItem: harga_diskon, harga_potongan, or harga_tayang
            $harga = $harga_diskon ?: ($details['harga_potongan'] > 0 ? $details['harga_potongan'] : $details['harga_tayang']);
    
            OrderItem::create([
                'order_id' => $order->id,
                'Product_id' => $id,
                'jumlah' => $details['quantity'],
                'harga' => $harga,
            ]);
    
            // Update stock
            if ($product) {
                $product->stok -= $details['quantity'];
                $product->save();
            }
        }
    
        session()->forget('cart');
    
        return redirect()->route('order.show', $order->id)->with('success', 'Pesanan Anda berhasil dibuat! Menunggu konfirmasi dari admin.');
    }
    


    
    
    

public function add(Request $request, $id)
{
    // Fetch the product along with any active big sales
    $product = Product::with(['bigSales' => function ($query) {
        $query->where('status', 'aktif')
              ->whereDate('mulai', '<=', now())
              ->whereDate('berakhir', '>=', now());
    }])->find($id);

    if (!$product) {
        return response()->json(['success' => false, 'message' => 'Product tidak ditemukan!'], 404);
    }

    $quantity = $request->input('quantity', 1);

    // Check if quantity exceeds available stock
    if ($quantity > $product->stok) {
        return response()->json(['success' => false, 'message' => 'Kuantitas melebihi stok yang tersedia!'], 400);
    }

    // Initialize harga_diskon
    $harga_diskon = null;

    // Check if the product is part of an active Big Sale and apply harga_diskon
    if ($product->bigSales->isNotEmpty()) {
        // Get the first active Big Sale (there should only be one active Big Sale at a time)
        $bigSale = $product->bigSales->first();
        $harga_diskon = $bigSale->pivot->harga_diskon ?? null;
    }

    // Determine which price to use: harga_diskon, harga_potongan, or harga_tayang
    $harga = $harga_diskon ?: ($product->harga_potongan ?: $product->harga_tayang);

    // Fetch the cart from the session (or initialize it if empty)
    $cart = session()->get('cart', []);

    // If product is already in the cart, update its quantity
    if (isset($cart[$id])) {
        $cart[$id]['quantity'] += $quantity;

        // Check if total quantity exceeds stock after the update
        if ($cart[$id]['quantity'] > $product->stok) {
            return response()->json(['success' => false, 'message' => 'Kuantitas total dalam keranjang melebihi stok yang tersedia!'], 400);
        }
    } else {
        // Add the product to the cart
        $cart[$id] = [
            "name" => $product->nama,
            "quantity" => $quantity,
            "harga_tayang" => $product->harga_tayang,
            "harga_potongan" => $product->harga_potongan,
            "harga_diskon" => $harga_diskon,  // Save harga_diskon if available
            "image" => $product->images->first()->gambar ?? 'default.png'
        ];
    }

    // Save the updated cart back to the session
    session()->put('cart', $cart);

    // Calculate total quantity in the cart
    $totalQuantity = array_sum(array_column($cart, 'quantity'));

    return response()->json(['success' => true, 'totalQuantity' => $totalQuantity]);
}



    



    
    

    public function viewCart()
    {
        $cart = session()->get('cart');

        return view('customer.cart.show', compact('cart'));
    }

    public function updateQuantity(Request $request, $id)
{
    $cart = session()->get('cart');
    if (isset($cart[$id])) {
        $cart[$id]['quantity'] = $request->input('quantity');
        session()->put('cart', $cart);

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false]);
}


    public function remove($id)
    {
        $cart = session()->get('cart');
        unset($cart[$id]);
        session()->put('cart', $cart);

        return redirect()->route('cart.view')->with('success', 'Product berhasil dihapus dari keranjang!');
    }

    
}