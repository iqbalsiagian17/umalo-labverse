<?php

namespace App\Http\Controllers\Costumer\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PaymentHandleCustomerController extends Controller
{
    public function submitPaymentProof(Request $request, $orderId)
    {
    // Find the order
    $order = Order::find($orderId);

    // Check if the order is cancelled
    if ($order->status === Order::STATUS_CANCELLED_BY_SYSTEM) {
        return back()->with('info', 'Your order has been cancelled due to non-payment.');
    }

    // Validate the payment proof
    $request->validate([
        'payment_proof' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    // Store the payment proof in public/payments
    $fileName = time() . '_' . $request->file('payment_proof')->getClientOriginalName();
    $request->file('payment_proof')->move(public_path('payments'), $fileName);


    DB::beginTransaction();
    try {
        // Buat data pembayaran dengan status 'pending'
        Payment::create([
            'order_id' => $orderId,
            'payment_proof' => 'payments/' . $fileName,
            'status' => Payment::STATUS_PENDING,
            'peding_at' => now(),
        ]);

        // Perbarui status pesanan menjadi 'pending_payment'
        $order->update([
            'status' => Order::STATUS_PENDING_PAYMENT,
            'pending_payment_at' => now(),
        ]);

        DB::commit();

        return back()->with('success', 'Payment proof submitted. Awaiting verification.');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to submit payment proof: ' . $e->getMessage());
    }
    }


}
