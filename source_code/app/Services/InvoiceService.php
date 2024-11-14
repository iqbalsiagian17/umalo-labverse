<?php

namespace App\Services;

use App\Models\Order;

class InvoiceService
{
    public function generateInvoiceNumber(Order $order)
    {
        $currentMonth = \Carbon\Carbon::now()->month;
        $currentYear = \Carbon\Carbon::now()->year;

        // Ambil invoice dengan nomor terbesar di bulan ini
        $lastOrder = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastOrder) {
            // Jika ada invoice sebelumnya, tambahkan 1
            $lastSequence = intval(substr($lastOrder->invoice_number, 0, 4));
            $sequence = $lastSequence + 1;
        } else {
            // Jika tidak ada, mulai dari 1
            $sequence = 1;
        }

        // Format nomor invoice dengan leading zero (0001, 0002, dst.)
        $uniqueNumber = str_pad($sequence, 4, '0', STR_PAD_LEFT);

        $invoicePrefix = 'INV-AGS';

        // Bulan dalam format romawi
        $romanMonths = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V',
            6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X',
            11 => 'XI', 12 => 'XII'
        ];
        $monthRoman = $romanMonths[$currentMonth];

        // Buat nomor invoice
        $invoiceNumber = "{$uniqueNumber}/{$invoicePrefix}/{$monthRoman}/{$currentYear}";

        // Simpan invoice di order
        $order->invoice_number = $invoiceNumber;
        $order->save();

        return $invoiceNumber;
    }
}
