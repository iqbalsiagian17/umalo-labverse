<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShippingService;
use App\Models\TParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; 


class OrderHandleAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->orderBy('created_at', 'desc');

        // Search by customer name or invoice number
        if ($request->input('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('full_name', 'like', "%{$search}%");
            })->orWhere('invoice_number', 'like', "%{$search}%");
        }

        // Filter by invoice number
        if ($request->input('invoice')) {
            $invoiceInput = $request->input('invoice');
            
            // Check if the input is numeric and 4 digits long (for last 4 digits filtering)
            if (is_numeric($invoiceInput) && strlen($invoiceInput) === 4) {
                // Filter by the last 4 digits of the invoice number
                $query->whereRaw('RIGHT(invoice_number, 4) = ?', [$invoiceInput]);
            } 
            // Check if the input is year and month (e.g., 202410)
            elseif (is_numeric($invoiceInput) && strlen($invoiceInput) === 6) {
                // Filter by the year and month in the invoice number
                $query->where('invoice_number', 'like', $invoiceInput . '%');
            } 
            // Fallback to filtering by the entire invoice number
            else {
                $query->where('invoice_number', $invoiceInput);
            }
        }

        // Filter by total range
        if ($request->input('total_range')) {
            switch ($request->input('total_range')) {
                case 'less_1m':
                    $query->whereRaw('COALESCE(negotiation_total, total) < ?', [1000000]);
                    break;
                case '1m_5m':
                    $query->whereBetween(DB::raw('COALESCE(negotiation_total, total)'), [1000000, 5000000]);
                    break;
                case '5m_10m':
                    $query->whereBetween(DB::raw('COALESCE(negotiation_total, total)'), [5000000, 10000000]);
                    break;
                case '10m_up':
                    $query->whereRaw('COALESCE(negotiation_total, total) > ?', [10000000]);
                    break;
            }
        }
        

        // Filter by status
        if ($request->input('status') && $request->input('status') != 'all') {
            $query->where('status', $request->input('status'));
        }

        // Paginate the result
        $orders = $query->paginate(10);
        
        $shipping = ShippingService::all();
        
        return view('admin.order.index', compact('orders', 'shipping'));
    }

    public function show($id)
    {
        $order = Order::with('user.userAddresses', 'items')->findOrFail($id);
        
        // Mark the order as viewed
        if (!$order->is_viewed_by_admin) {
            $order->is_viewed_by_admin = true; // Set is_viewed_by_admin to true
            $order->save(); // Save the change to the database
        }
    
        $shipping = ShippingService::all(); // Fetch shipping services
        return view('admin.order.show', compact('order', 'shipping'));
    }

    // Admin approval of order
    public function approveOrder($orderId)
    {
        // Temukan pesanan berdasarkan ID
        $order = Order::find($orderId);

        // Periksa apakah pesanan ditemukan
        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        // Periksa apakah pesanan sudah disetujui
        if ($order->status === Order::STATUS_APPROVED) {
            return back()->with('error', 'This order has already been approved.');
        }

        DB::beginTransaction();
        try {
            // Buat nomor faktur jika belum ada
            if (!$order->invoice_number) {
                $order->invoice_number = OrderHandleAdminController::generateInvoiceNumber();
            }

            // Perbarui status dan waktu persetujuan
            $order->update([
                'status' => Order::STATUS_APPROVED,
                'approved_at' => now(),
                'is_viewed_by_customer' => false, 
            ]);

            DB::commit();
            return back()->with('success', 'Order approved successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve the order: ' . $e->getMessage());
        }
    }

    public function allowPayment($orderId)
    {
        // Temukan pesanan berdasarkan ID
        $order = Order::find($orderId);

        // Periksa apakah pesanan ditemukan dan apakah statusnya sudah disetujui
        if (!$order || $order->status !== Order::STATUS_APPROVED) {
            return back()->with('error', 'Order not eligible for payment.');
        }

        try {
            // Ubah status pesanan menjadi 'pending_payment'
            $order->update([
                'status' => Order::STATUS_PENDING_PAYMENT,
                'pending_payment_at' => now(),
                'is_viewed_by_customer' => false, 
            ]);

            // Redirect customer ke halaman pembayaran
            return back()->with('success', 'Access Payment approved successfully.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to proceed to payment: ' . $e->getMessage());
        }
    }

    // Admin marks order as packing
    public function markAsPacking($orderId)
    {
        // Temukan pesanan berdasarkan ID
        $order = Order::find($orderId);

        // Periksa apakah pesanan ditemukan dan statusnya adalah 'processing'
        if (!$order || $order->status !== Order::STATUS_CONFIRMED) {
            return back()->with('error', 'Order is not eligible for packing.');
        }

        // Perbarui status dan catat timestamp
        $order->update([
            'status' => Order::STATUS_PROCESSING, // gunakan konstanta status
            'packing_at' => now(),              // Set packing timestamp
            'processing_at' => now(),           // Set processing timestamp
            'is_viewed_by_customer' => false, 
        ]);

        return back()->with('success', 'Order is now in the packing process.');
    }

    // Admin marks order as shipped and adds tracking number
    public function markAsShipped(Request $request, $orderId)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
            'shipping_service_id' => 'required|exists:t_shipping_services,id', // Ensure the shipping service exists
        ]);

        $order = Order::find($orderId);
        $order->update([
            'status' => 'shipped',
            'tracking_number' => $request->tracking_number,
            'shipping_service_id' => $request->shipping_service_id, // Save the selected shipping service
            'is_viewed_by_customer' => false, 
            'shipped_at' => now() // Set shipped timestamp
        ]);

        return back()->with('success', 'Order marked as shipped.');
    }

    public function cancelOrder(Request $request, $orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);

        if ($order->status !== Order::STATUS_CANCELLED && $order->status !== Order::STATUS_CANCELLED_BY_ADMIN) {
            // Kembalikan jumlah quantity setiap produk ke stok
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->stock += $item->quantity; // Kembalikan jumlah ke stok produk
                    $product->save();
                }
            }

            // Set status pesanan ke 'cancelled_by_admin'
            $order->status = Order::STATUS_CANCELLED_BY_ADMIN;
            $order->is_viewed_by_customer = false; 
            $order->cancelled_by_admin_at = now(); // Set timestamp cancelled_by_admin
            $order->save();

            return redirect()->back()->with('success', 'Order has been cancelled by the admin, and stock has been updated.');
        }

        return redirect()->back()->with('error', 'Order has already been cancelled.');
    }

    public function startNegotiation($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        DB::beginTransaction();
        try {
            $order->update([
                'status' => Order::STATUS_NEGOTIATION_PENDING,
                'negotiation_pending_at' => now(),
                'is_viewed_by_customer' => false,
            ]);

            DB::commit();
            return back()->with('success', 'Negotiation started successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to start negotiation: ' . $e->getMessage());
        }
    }

    public function approveNegotiation($orderId)
    {
        $order = Order::find($orderId);

        if (!$order || $order->negotiation_status !== Order::STATUS_NEGOTIATION_PENDING) {
            return back()->with('error', 'Order is not eligible for negotiation approval.');
        }

        DB::beginTransaction();
        try {
            $order->update([
                'negotiation_status' => Order::STATUS_NEGOTIATION_APPROVED,
                'status' => Order::STATUS_NEGOTIATION_IN_PROGRESS,
                'negotiation_approved_at' => now(),
                'negotiation_in_progress_at' => now(),
                'is_viewed_by_customer' => false,
            ]);

            DB::commit();
            return back()->with('success', 'Negotiation approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve negotiation: ' . $e->getMessage());
        }
    }

    public function rejectNegotiation($orderId)
    {
        $order = Order::find($orderId);

        if (!$order || !in_array($order->negotiation_status, [Order::STATUS_NEGOTIATION_PENDING, Order::STATUS_NEGOTIATION_APPROVED])) {
            return back()->with('error', 'Order is not eligible for negotiation rejection.');
        }        

        DB::beginTransaction();
        try {
            $order->update([
                'negotiation_status' => Order::STATUS_NEGOTIATION_REJECTED,
                'status' => Order::STATUS_WAITING_APPROVAL,
                'negotiation_rejected_at' => now(),
                'negotiation_finished_at' => now(),
                'is_viewed_by_customer' => false,
            ]);

            DB::commit();
            return back()->with('success', 'Negotiation rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject negotiation: ' . $e->getMessage());
        }
    }

    public function finalizeNegotiation(Request $request, $orderId)
    {
        $request->validate([
            'negotiated_total' => 'required|numeric|min:0',
        ]);

        $order = Order::find($orderId);

        if (!$order || $order->negotiation_status !== Order::STATUS_NEGOTIATION_APPROVED) {
            return back()->with('error', 'Order is not eligible for negotiation finalization.');
        }

        DB::beginTransaction();
        try {
            // Generate an invoice number if one doesn’t already exist
            if (!$order->invoice_number) {
                $order->invoice_number = self::generateInvoiceNumber();
            }

            // Update the order with the finalized negotiation total and new status
            $order->update([
                'negotiation_total' => $request->input('negotiated_total'),
                'status' => Order::STATUS_PENDING_PAYMENT,
                'negotiation_status' => Order::STATUS_NEGOTIATION_FINISHED,
                'negotiation_finished_at' => now(),
                'pending_payment_at' => now(),
                'is_viewed_by_customer' => false,
                'invoice_number' => $order->invoice_number, // save the generated invoice number
            ]);

            DB::commit();
            return back()->with('success', 'Negotiation finalized successfully and order is now pending payment.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to finalize negotiation: ' . $e->getMessage());
        }
    }

    



    public static function generateInvoiceNumber()
    {
        // Format untuk tahun dan bulan
        $yearMonth = date('Ym'); 
        
        // Ambil nomor terakhir di bulan ini dari database
        $lastOrder = DB::table('t_orders')
        ->whereRaw('YEAR(created_at) = ?', [date('Y')])
        ->whereRaw('MONTH(created_at) = ?', [date('m')])
        ->orderBy('invoice_number', 'desc')
        ->first();

        // Tentukan nomor urut baru
        if ($lastOrder) {
            // Jika sudah ada order di bulan ini, ambil angka terakhir dari nomor invoice dan tambahkan 1
            $lastInvoiceNumber = intval(substr($lastOrder->invoice_number, -4));
            $nextInvoiceNumber = str_pad($lastInvoiceNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Jika belum ada order di bulan ini, mulai dari 0001
            $nextInvoiceNumber = '0001';
        }

        // Gabungkan format untuk nomor invoice
        return 'INV/' . $yearMonth . '/' . $nextInvoiceNumber;
    }

    public function generatePdf($id)
    {
        // Retrieve the order along with related items, user details, and addresses
        $order = Order::with(['items.product', 'user.userAddresses'])->findOrFail($id);

        $parameter = TParameter::first();

        // Retrieve the user and their addresses
        $user = $order->user;
        $userAddresses = $user ? $user->useraddresses : collect(); // Ensure $userAddresses is a collection

        // Generate the invoice number
        $invoiceNumber = $order->invoice_number;

        // Handle case where invoice_number is not set
        if (!$invoiceNumber) {
            return redirect()->back()->with('error', 'Invoice number not found for this order.');
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
        $pdf = PDF::loadView('customer.order.pdf', compact(
            'order', 'user', 'userAddresses', 'agsLogo', 'mapsIcon', 'emailIcon', 'phoneIcon', 'invoiceNumber', 'parameter'
        ));

        // Sanitize the company name for the filename
        $companyName = $user && isset($user->company) ? preg_replace('/[^A-Za-z0-9\-]/', '_', $user->company) : 'unknown_company';

        // Replace "/" with "_" in the invoice number for the filename
        $safeInvoiceNumber = str_replace('/', '_', $invoiceNumber);

        // Create the filename, ensuring it is safe
        $fileName = "invoice-{$companyName}-{$safeInvoiceNumber}.pdf";

        // Return the generated PDF for download
        return $pdf->download($fileName);
    }


}
