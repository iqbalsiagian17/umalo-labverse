<?php

namespace App\Http\Controllers\Costumer\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use PDF;
use App\Models\PPN;
use App\Models\Materai;
class OrderController extends Controller
{
    public function negoisasi($id)
    {
        $order = Order::findOrFail($id);
        
        // Change the status to 'Negosiasi'
        $order->status = 'Negosiasi';
        $order->save();

        return redirect()->route('order.show', $id)->with('success', 'Negosiasi telah dimulai. Silakan tunggu konfirmasi dari admin.');
    }
    public function show($id)
    {
        $order = Order::with('orderItems.produk')->findOrFail($id);
        return view('customer.order.detail-pesanan', compact('order'));
    }

    public function contract($id)
    {
        $order = Order::with('orderItems.produk')->findOrFail($id);

        return view('customer.order.contract', compact('order'));
    }
    public function history()
    {
        $orders = Order::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();

        return view('customer.order.riwayat-pesanan', compact('orders'));
    }


    public function detail($id)
    {
        $order = Order::findOrFail($id);
        return view('customer.order.detail-pesanan', compact('order'));
    }
    public function cancel($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'cancelled';
        $order->save();

        return redirect()->route('order.detail', $order->id)->with('success', 'Pesanan telah dibatalkan.');
    }
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
    
        // If the user clicks the "Terima Barang" button, set the status to "Selesai"
        if ($order->status == 'Pengiriman') {
            $order->status = 'Selesai';
            $order->save();
    
            return redirect()->route('order.show', $order->id)->with('success', 'Pesanan telah selesai. Terima kasih telah berbelanja!');
        }
    
        // Other status updates can be handled here if necessary
        $order->update($request->all());
    
        return redirect()->route('order.show', $order->id)->with('success', 'Status pesanan berhasil diperbarui.');
    }
    

    public function cancelOrder(Request $request, $id)
{
    $order = Order::findOrFail($id);

    // Allow cancellation if the status is "Menunggu Konfirmasi Admin", "Menunggu Konfirmasi Admin untuk Negosiasi", "Negosiasi", or "Diterima"
    if (in_array($order->status, ['Menunggu Konfirmasi Admin', 'Menunggu Konfirmasi Admin untuk Negosiasi', 'Negosiasi', 'Diterima'])) {
        $order->status = 'Cancelled';
        $order->save();

        return redirect()->route('order.show', $order->id)->with('success', 'Pesanan berhasil dibatalkan.');
    }

    return redirect()->route('order.show', $order->id)->with('error', 'Pesanan tidak dapat dibatalkan pada tahap ini.');
}
public function generatePdf($id)
{
    // Retrieve the order along with related items, user details, and addresses
    $order = Order::with(['orderItems.produk', 'user.userDetail', 'user.addresses'])->findOrFail($id);

    // Retrieve the latest PPN record
    $ppn = PPN::latest()->first();

    // Retrieve all Materai records
    $materai = Materai::all(); 

    // Retrieve the UserDetail from the Order's user relationship
    $userDetail = $order->user->userDetail; 

    // Retrieve the UserAddresses from the User
    $userAddresses = $order->user->addresses;

    // Calculate the total price including PPN
    $totalPriceWithPPN = $order->harga_total + ($order->harga_total * ($ppn->ppn / 100));

    // Convert Materai images to base64
    $materaiImages = [];
    foreach ($materai as $item) {
        $path = public_path($item->image);
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $materaiImages[] = $base64;
        }
    }

    // Load the view and pass the necessary data to it
    $pdf = PDF::loadView('customer.order.pdf', compact('order', 'ppn', 'materaiImages', 'totalPriceWithPPN', 'userDetail', 'userAddresses'));

    // Sanitize the company name to create a valid filename
    $companyName = preg_replace('/[^A-Za-z0-9\-]/', '_', $userDetail->perusahaan); 
    $year = $order->created_at->format('Y');
    $fileName = "invoice-{$companyName}-{$year}.pdf";

    // Return the generated PDF for download
    return $pdf->download($fileName);
}


public function transactionHistory($id)
{
    $order = Order::with('statusHistories')->findOrFail($id);

    return view('customer.order.transaction_history', compact('order'));
}
public function uploadBuktiPembayaran(Request $request, $id)
{
    $request->validate([
        'bukti_pembayaran' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $order = Order::findOrFail($id);

    if ($request->file('bukti_pembayaran')) {
        $file = $request->file('bukti_pembayaran');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/bukti_pembayaran'), $filename);
        $order->bukti_pembayaran = $filename;
    }

    $order->save();

    return redirect()->route('order.show', $id)->with('success', 'Bukti pembayaran berhasil diunggah.');
}
public function submitReview(Request $request, $id)
{
    $order = Order::with('orderItems.produk')->findOrFail($id);

    if ($order->status !== 'Selesai') {
        return redirect()->route('order.show', $id)->with('error', 'Anda hanya dapat mengulas setelah pesanan selesai.');
    }

    $request->validate([
        'review' => 'required|string|max:1000',
        'rating' => 'required|integer|min:1|max:5',
        'review_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'review_videos.*' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
    ]);

    $orderItem = $order->orderItems->first();
    if ($orderItem) {
        $images = [];
        $videos = [];

        // Handle image uploads
        if ($request->hasFile('review_images')) {
            foreach ($request->file('review_images') as $image) {
                $path = $image->store('review_images', 'public');
                $images[] = $path;
            }
        }

        // Handle video uploads
        if ($request->hasFile('review_videos')) {
            foreach ($request->file('review_videos') as $video) {
                $path = $video->store('review_videos', 'public');
                $videos[] = $path;
            }
        }

        $orderItem->produk->reviews()->create([
            'user_id' => auth()->id(),
            'content' => $request->input('review'),
            'rating' => $request->input('rating'),
            'images' => $images ? json_encode($images, JSON_UNESCAPED_SLASHES) : null, // Ensure proper JSON encoding
            'videos' => $videos ? json_encode($videos, JSON_UNESCAPED_SLASHES) : null, // Ensure proper JSON encoding
        ]);

        return redirect()->route('product.show', $orderItem->produk->id)->with('success', 'Ulasan berhasil dikirim.');
    }

    return redirect()->route('order.show', $id)->with('error', 'Terjadi kesalahan saat mengirim ulasan.');
}

                
    
}
