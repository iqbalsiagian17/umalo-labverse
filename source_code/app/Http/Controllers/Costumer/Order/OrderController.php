<?php

namespace App\Http\Controllers\Costumer\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use PDF;
use App\Models\PPN;
use App\Models\Materai;
use Illuminate\Support\Facades\Log;

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

        // Retrieve all Materai records

        // Retrieve the UserDetail from the Order's user relationship
        $userDetail = $order->user->userDetail;

        // Retrieve the UserAddresses from the User
        $userAddresses = $order->user->addresses;

        // Generate the invoice number
$invoiceNumber = $order->invoice_number;

        // Handle case where invoice_number is not set
        if (!$invoiceNumber) {
            return redirect()->back()->with('error', 'Invoice number not found for this order.');
        }

        // Get the company abbreviation
        $companyAbbreviation = $this->getCompanyAbbreviation($order->user->userDetail->perusahaan);

        // Get the Roman numeral for the month the order was created
        $romanMonth = $this->getRomanMonth($order->created_at->month);

        // Convert ags.jpeg, maps-and-flags.png, email.png, phone-call.png to base64
        $logoPath = public_path('assets/images/ags.jpeg');
        $agsLogo = '';
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $agsLogo = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // Convert maps-and-flags.png to base64
        $mapsPath = public_path('assets/images/maps-and-flags.png');
        $mapsIcon = '';
        if (file_exists($mapsPath)) {
            $type = pathinfo($mapsPath, PATHINFO_EXTENSION);
            $data = file_get_contents($mapsPath);
            $mapsIcon = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // Convert email.png to base64
        $emailPath = public_path('assets/images/email.png');
        $emailIcon = '';
        if (file_exists($emailPath)) {
            $type = pathinfo($emailPath, PATHINFO_EXTENSION);
            $data = file_get_contents($emailPath);
            $emailIcon = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // Convert phone-call.png to base64
        $phonePath = public_path('assets/images/phone-call.png');
        $phoneIcon = '';
        if (file_exists($phonePath)) {
            $type = pathinfo($phonePath, PATHINFO_EXTENSION);
            $data = file_get_contents($phonePath);
            $phoneIcon = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // Pass all the images to the view along with other data
        $pdf = PDF::loadView('customer.order.pdf', compact('order',  'userDetail', 'userAddresses', 'agsLogo', 'mapsIcon', 'emailIcon', 'phoneIcon', 'invoiceNumber', 'companyAbbreviation', 'romanMonth'));

        // Sanitize the company name to create a valid filename
        $companyName = preg_replace('/[^A-Za-z0-9\-]/', '_', $userDetail->perusahaan);
        $year = $order->created_at->format('Y');

        // Replace "/" with "_" in the invoice number for the filename
        $safeInvoiceNumber = str_replace('/', '_', $invoiceNumber);

        // Create the filename, ensuring it is safe
        $fileName = "invoice-{$companyName}-{$safeInvoiceNumber}.pdf";

        // Return the generated PDF for download
        return $pdf->download($fileName);
    }

    public function generateInvoiceNumber($order)
{
    // Step 1: Get the current month and year
    $currentMonth = \Carbon\Carbon::now()->month;
    $currentYear = \Carbon\Carbon::now()->year;

    // Step 2: Count the number of orders in the current month and year
    // This will reset the count each month
    $orderCount = Order::whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->count();

    // Step 3: Increment the count to generate the next sequence number
    $sequence = $orderCount + 1;

    // Step 4: Format the sequence as a 4-digit number
    $uniqueNumber = str_pad($sequence, 4, '0', STR_PAD_LEFT); // e.g., 0001, 0002, etc.

    // Step 5: Set the invoice prefix (for AGS company)
    $invoicePrefix = 'INV-AGS';

    // Step 6: Get Roman numeral for the current month
    $romanMonths = [
        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V',
        6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X',
        11 => 'XI', 12 => 'XII'
    ];
    $monthRoman = $romanMonths[$currentMonth]; // Convert month to Roman numeral

    // Step 7: Combine the parts to generate the invoice number
    // Format: 0001/INV-AGS/X/2024
    $invoiceNumber = "{$uniqueNumber}/{$invoicePrefix}/{$monthRoman}/{$currentYear}";

    // Step 8: Save the invoice number to the order
    $order->invoice_number = $invoiceNumber;
    $order->save();

    return $invoiceNumber;
}




    // Helper function to get the company abbreviation
    public function getCompanyAbbreviation($companyName)
{
    // Define a list of words to ignore (like PT, CV, UD)
    $ignoreWords = ['PT', 'CV', 'UD'];

    // Split the company name into words
    $words = explode(' ', $companyName);

    // Filter out the words that are in the $ignoreWords array
    $filteredWords = array_filter($words, function($word) use ($ignoreWords) {
        return !in_array(strtoupper($word), $ignoreWords); // Compare case-insensitive
    });

    // Create abbreviation from the filtered words
    return strtoupper(implode('', array_map(function($word) {
        return $word[0];
    }, $filteredWords))); // Take the first letter of each remaining word
}

    // Helper function to get the Roman numeral for a month
    public function getRomanMonth($month)
    {
        $romanMonths = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romanMonths[$month];
    }




    public function transactionHistory($id)
    {
        $order = Order::with('statusHistories')->findOrFail($id);

        return view('customer.order.transaction_history', compact('order'));
    }
    public function uploadBuktiPembayaran(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|mimes:jpg,jpeg,png|max:12048',
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
            'review_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
            'review_videos.*' => 'nullable|mimes:mp4,mov,ogg,qt|max:280000',
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
            // dd($images, $videos);

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
