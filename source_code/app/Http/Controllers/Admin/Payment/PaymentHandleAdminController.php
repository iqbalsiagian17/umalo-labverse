<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentHandleAdminController extends Controller
{
    public function index(Request $request)
    {
        // Create a query for payments
        $query = Payment::with('order.user')->orderBy('created_at', 'desc');
    
        // Search by customer name
        if ($request->input('name')) {
            $name = $request->input('name');
            $query->whereHas('order.user', function ($q) use ($name) {
                $q->where('name', 'like', "%{$name}%")
                  ->orWhere('full_name', 'like', "%{$name}%");
            });
        }
    
        // Search by invoice number
        if ($request->input('invoice_number')) {
            $invoice = $request->input('invoice_number');
            $query->whereHas('order', function ($q) use ($invoice) {
                $q->where('invoice_number', 'like', "%{$invoice}%");
            });
        }
    
        // Filter by payment status
        if ($request->input('status') && $request->input('status') != 'all') {
            $query->where('status', $request->input('status'));
        }
    
        // Paginate the result
        $payments = $query->paginate(10);
    
        return view('admin.payment.index', compact('payments'));
    }

    public function show($paymentId)
    {
        // Fetch the payment by ID, along with the associated order and user
        $payment = Payment::with('order.user')->findOrFail($paymentId);
        
        if (!$payment->is_viewed_by_admin) {
            $payment->is_viewed_by_admin = true; // Set is_viewed_by_admin to true
            $payment->is_viewed_by_customer = false;
            $payment->save(); // Save the change to the database
        }

        // Return the view with payment details
        return view('admin.payment.show', compact('payment'));
    }

    // Handle payment verification by admin
    public function verifyPayment($paymentId)
    {
        $payment = Payment::find($paymentId);

        // Periksa apakah pembayaran ditemukan
        if (!$payment) {
            return back()->with('error', 'Payment not found.');
        }

        // Perbarui status pembayaran menjadi 'paid' dan tandai sebagai dilihat
        $payment->update([
            'status' => Payment::STATUS_PAID,
            'paid_at' => now(),
            'is_viewed_by_admin' => true,
            'is_viewed_by_customer' => false,
        ]);

        // Perbarui status pesanan terkait menjadi 'confirmed' dan catat waktu verifikasi pembayaran
        $order = $payment->order;
        $order->update([
            'status' => Order::STATUS_CONFIRMED,
            'payment_verified_at' => now(),
        ]);

        return back()->with('success', 'Payment verified successfully.');
    }

    public function rejectPayment($paymentId)
    {
        // Temukan pembayaran berdasarkan ID
        $payment = Payment::find($paymentId);

        // Periksa apakah pembayaran ditemukan
        if (!$payment) {
            return back()->with('error', 'Payment not found.');
        }

        // Perbarui status pembayaran menjadi 'failed'
        $payment->update([
            'status' => Payment::STATUS_FAILED,
            'is_viewed_by_admin' => true,
            'is_viewed_by_customer' => false,
        ]);

        $order = $payment->order;

        // Cek jumlah pembayaran gagal pada pesanan terkait
        $failedPaymentsCount = $order->payments()->where('status', Payment::STATUS_FAILED)->count();

        if ($failedPaymentsCount >= 2) {
            // Jika jumlah gagal mencapai 2, batalkan pesanan secara otomatis
            $order->update([
                'status' => Order::STATUS_CANCELLED_BY_SYSTEM,
                'cancelled_by_system_at' => now(),
            ]);

            return back()->with('error', 'Payment rejected and order cancelled due to multiple failed attempts.');
        }

        return back()->with('warning', 'Payment rejected. You may resubmit your payment proof one more time.');
    }
}
