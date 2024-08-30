<?php

namespace App\Http\Controllers\Costumer\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
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
        $product = Produk::find($id);
        if ($product && $details['quantity'] > $product->stok) {
            return redirect()->route('cart.view')->with('error', 'Kuantitas untuk produk ' . $product->nama . ' melebihi stok yang tersedia.');
        }
    }

    // Determine initial status based on product negotiation availability
    $initialStatus = 'Menunggu Konfirmasi Admin';
    foreach ($cart as $id => $details) {
        $product = Produk::find($id);
        if ($product && $product->nego == 'ya') {
            $initialStatus = 'Menunggu Konfirmasi Admin untuk Negosiasi';
            break;
        }
    }

    $totalHarga = 0;
    foreach ($cart as $id => $details) {
        $harga = isset($details['harga_potongan']) && $details['harga_potongan'] > 0 
            ? $details['harga_potongan'] 
            : $details['harga_tayang'];
        $totalHarga += $harga * $details['quantity'];
    }

    $order = Order::create([
        'user_id' => auth()->id(),
        'harga_total' => $totalHarga,
        'status' => $initialStatus,
    ]);

    foreach ($cart as $id => $details) {
        OrderItem::create([
            'order_id' => $order->id,
            'produk_id' => $id,
            'jumlah' => $details['quantity'],
            'harga' => isset($details['harga_potongan']) && $details['harga_potongan'] > 0 
                ? $details['harga_potongan'] 
                : $details['harga_tayang'],
        ]);

        $product = Produk::find($id);
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
    $product = Produk::find($id);

    if (!$product) {
        return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan!'], 404);
    }

    $quantity = $request->input('quantity', 1);

    // Cek apakah kuantitas melebihi stok
    if ($quantity > $product->stok) {
        return response()->json(['success' => false, 'message' => 'Kuantitas melebihi stok yang tersedia!'], 400);
    }

    $harga = $product->harga_potongan ?: $product->harga_tayang;

    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        $cart[$id]['quantity'] += $quantity;

        // Cek apakah kuantitas total melebihi stok setelah penambahan
        if ($cart[$id]['quantity'] > $product->stok) {
            return response()->json(['success' => false, 'message' => 'Kuantitas total dalam keranjang melebihi stok yang tersedia!'], 400);
        }
    } else {
        $cart[$id] = [
            "name" => $product->nama,
            "quantity" => $quantity,
            "harga_tayang" => $product->harga_tayang,
            "harga_potongan" => $product->harga_potongan,
            "image" => $product->images->first()->gambar ?? 'default.png'
        ];
    }

    session()->put('cart', $cart);

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

        return redirect()->route('cart.view')->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    
}