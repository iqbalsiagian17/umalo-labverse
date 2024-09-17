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
        return view('Customer.Order.detail-pesanan', compact('order'));
    }

    public function contract($id)
    {
        $order = Order::with('orderItems.produk')->findOrFail($id);

        return view('Customer.Order.contract', compact('order'));
    }
    public function history()
    {
        $orders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Customer.Order.riwayat-pesanan', compact('orders'));
    }


    public function detail($id)
    {
        $order = Order::findOrFail($id);
        return view('Customer.Order.detail-pesanan', compact('order'));
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
        $invoiceNumber = $this->generateInvoiceNumber($order);
         // Get the company abbreviation
// Get the company abbreviation
$companyAbbreviation = $this->getCompanyAbbreviation($order->user->userDetail->perusahaan);

// Get the Roman numeral for the month the order was created
$romanMonth = $this->getRomanMonth($order->created_at->month);

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
        $pdf = PDF::loadView('Customer.Order.pdf', compact('order', 'ppn', 'materaiImages', 'totalPriceWithPPN', 'userDetail', 'userAddresses', 'agsLogo', 'mapsIcon', 'emailIcon', 'phoneIcon', 'invoiceNumber','companyAbbreviation', 'romanMonth'));


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
    // Step 1: Generate unique number (use order ID or auto-increment)
    $uniqueNumber = str_pad($order->id, 6, '0', STR_PAD_LEFT); // e.g., 000001, 000002, etc.

    // Step 2: Default INV-AGS
    $invoicePrefix = 'INV-AGS';

    // Step 3: Get company abbreviation, excluding "PT", "CV", etc.
    $companyName = $order->user->userDetail->perusahaan;

    // Define a list of words to ignore (like PT, CV, UD)
    $ignoreWords = ['PT', 'CV', 'UD'];

    // Split the company name into words and filter out the ignore words
    $filteredWords = array_filter(explode(' ', $companyName), function ($word) use ($ignoreWords) {
        return !in_array(strtoupper($word), $ignoreWords); // Exclude "PT", "CV", etc.
    });

    // Create abbreviation from the filtered words
    $companyAbbreviation = strtoupper(implode('', array_map(function ($word) {
        return $word[0];
    }, $filteredWords))); // Take the first letter of each remaining word

    // Step 4: Get Roman numeral for the month
    $romanMonths = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII'
    ];
    $currentMonth = \Carbon\Carbon::now()->month; // Get current month
    $currentYear = \Carbon\Carbon::now()->year; // Get current year
    $monthRoman = $romanMonths[$currentMonth]; // Convert month to Roman numeral

    // Step 5: Combine the parts to generate the invoice number
    $invoiceNumber = "{$uniqueNumber}/{$invoicePrefix}-{$companyAbbreviation}/{$monthRoman}/{$currentYear}";

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

        return view('Customer.Order.transaction_history', compact('order'));
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
