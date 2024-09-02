<?php

namespace App\Http\Controllers\Admin\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index()
    {
    $orders = Order::with('orderItems')->get();
    $seenOrders = Session::get('seen_orders', []);

    // Perbarui session dengan transaksi yang baru saja dilihat
    foreach ($orders as $order) {
        if (!in_array($order->id, $seenOrders)) {
            $seenOrders[] = $order->id;
        }
    }

    Session::put('seen_orders', $seenOrders);

    return view('admin.transaksi.index', compact('orders'));
    }


    public function show($id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
        $this->markAsSeen($order);
        return view('admin.transaksi.show', compact('order'));
    }
    

    public function edit($id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
        return view('admin.transaksi.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
    
        // Check if the subtotal (negotiated price) is being updated
    if ($request->has('subtotal')) {
        // Loop through each order item
        foreach ($order->orderItems as $item) {
            if ($item->produk->nego == 'ya') {
                // If the product is negotiable, set harga_setelah_nego
                $order->harga_setelah_nego = $request->input('subtotal');
            } else {
                // If the product is not negotiable, set harga_setelah_nego to null
                $order->harga_setelah_nego = null;
            }
        }
    }

    if ($request->status == 'Packing' && !$order->bukti_pembayaran) {
        return response()->json([
            'success' => false,
            'message' => 'Pelanggan belum mengunggah bukti pembayaran, sehingga status tidak dapat diubah menjadi Packing.'
        ], 400);
    }
    
        // Handle status updates and other logic
        if ($request->status == 'Pengiriman') {
            $request->validate([
                'nomor_resi' => 'required|string',
            ]);
            $order->nomor_resi = $request->nomor_resi;
        }
    
        if ($request->status == 'Negosiasi') {
            $request->validate([
                'whatsapp_number' => 'required|string',
            ]);
            $order->whatsapp_number = $request->whatsapp_number;
        }
    
        $order->status = $request->status;

        if ($request->status == 'Packing' && $request->has('nomor_resi')) {
            $order->nomor_resi = $request->nomor_resi;
        }

        $order->save();
    
        // Log the status change with any additional info
        $order->statusHistories()->create([
            'status' => $request->status,
            'extra_info' => $request->status == 'Pengiriman' ? $order->nomor_resi : null,
            'created_at' => now(),
        ]);
    
        return response()->json(['success' => true, 'message' => 'Status updated successfully!']);
    }
    

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function markAsSeen(Order $order)
    {
        $order->seen_by_users()->syncWithoutDetaching([Auth::id()]);
        return redirect()->back();
    }
    

    
}
