<?php

namespace App\Http\Controllers\Admin\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\InvoiceService;


class TransaksiController extends Controller
{
    public function index(Request $request)
    {
    // Ambil input pencarian
    $searchName = $request->input('search_name');
    $searchInvoice = $request->input('search_invoice');

    // Query order dengan filter nama user dan/atau nomor invoice jika ada pencarian
    $orders = Order::with(['orderItems', 'user.userdetail'])
        ->when($searchName, function ($query, $searchName) {
            return $query->whereHas('user', function ($query) use ($searchName) {
                $query->where('name', 'like', '%' . $searchName . '%');
            });
        })
        ->when($searchInvoice, function ($query, $searchInvoice) {
            return $query->orWhere('invoice_number', 'like', '%' . $searchInvoice . '%');
        })
        ->paginate(10); // Menampilkan 10 transaksi per halaman

    // Jika pencarian kosong
    if (($searchName || $searchInvoice) && $orders->isEmpty()) {
        session()->flash('no_results', 'Tidak ada hasil untuk pencarian.');
    }

    // Lihat transaksi yang sudah dilihat
    $seenOrders = Session::get('seen_orders', []);

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

    public function update(Request $request, $id, InvoiceService $invoiceService)
    {
        $order = Order::findOrFail($id);

        // Check if the subtotal (negotiated price) is being updated
    if ($request->has('subtotal')) {
        // Loop through each order item
        foreach ($order->orderItems as $item) {
            if ($item->Product->nego == 'ya') {
                // If the product is negotiable, set harga_setelah_nego
                $order->harga_setelah_nego = $request->input('subtotal');
            } else {
                // If the product is not negotiable, set harga_setelah_nego to null
                $order->harga_setelah_nego = null;
            }
        }
    }


    if ($request->status == 'Negosiasi' && $request->has('negotiation_rejected')) {
        $order->status = 'Diterima';
        $order->status = 'Negosiasi Ditolak, Orderan Berlanjut Ke Order Reguler'; // Set message to be displayed to the user
    } else if ($request->status == 'Packing' && !$order->bukti_pembayaran) {
        return response()->json([
            'success' => false,
            'message' => 'Pelanggan belum mengunggah bukti pembayaran, status tidak dapat diubah menjadi Packing.'
        ], 400);
    } else {
        $order->status = $request->status;
    }

    // **Generate Invoice Number when status is 'Diterima'**
    if ($request->status == 'Diterima' && !$order->invoice_number) {
        $invoiceNumber = $invoiceService->generateInvoiceNumber($order); // Use the service here
        $order->invoice_number = $invoiceNumber;
    }

    if ($request->status == 'Negosiasi' && $request->has('negotiation_rejected')) {
        $order->status = 'Diterima';
        $order->user_message = 'Negosiasi Ditolak, Orderan Berlanjut Ke Order Reguler';
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
    public function updateEdit(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Validation rules based on the status
        $rules = [
            'status' => 'required|string',
            'harga_total' => 'required|numeric',
        ];

        // Add validation for tracking number if the status is Pengiriman
        if ($request->status == 'Pengiriman') {
            $rules['nomor_resi'] = 'required|string';
        }

        if ($request->status == 'Negosiasi') {
            $rules['whatsapp_number'] = 'required|string';
        }

        $validated = $request->validate($rules);

        // Block if status is Packing without proof of payment
        if ($request->status == 'Packing' && !$order->bukti_pembayaran) {
            return redirect()->back()->withErrors(['message' => 'Pelanggan belum mengunggah bukti pembayaran, status tidak dapat diubah menjadi Packing.']);
        }

        // Handle the negotiated price (subtotal) update
        if ($request->has('subtotal')) {
            foreach ($order->orderItems as $item) {
                if ($item->Product->nego == 'ya') {
                    $order->harga_setelah_nego = $request->input('subtotal');
                } else {
                    $order->harga_setelah_nego = null;
                }
            }
        }

        // Handle status updates and other logic
        if ($request->status == 'Pengiriman') {
            $order->nomor_resi = $request->input('nomor_resi');
        }

        if ($request->status == 'Negosiasi') {
            $order->whatsapp_number = $request->input('whatsapp_number');
        }

        // Update the order status and other fields
        $order->status = $request->status;
        $order->harga_total = $request->input('harga_total');

        // Save the updated order
        $order->save();

        // Log the status change with any additional info
        $order->statusHistories()->create([
            'status' => $request->status,
            'extra_info' => $request->status == 'Pengiriman' ? $order->nomor_resi : null,
            'created_at' => now(),
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Status updated successfully!');
    }



}
