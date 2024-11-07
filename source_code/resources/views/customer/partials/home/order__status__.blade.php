@foreach ($orders as $order)
    @if ($order->status === App\Models\Order::STATUS_WAITING_APPROVAL)
        <div class="alert alert-warning mt-2" role="alert">
            <strong>Mohon Bersabar!</strong> 
            Orderan mu dengan nomor #{{ $order->id }} sedang di check oleh admin. Jika Anda merasa telah menunggu terlalu lama, 
            silakan hubungi admin melalui 
{{--             <a href="https://wa.me/{{ $parameters->nomor_wa }}" target="_blank">
                {{ $parameters->nomor_wa }}
            </a>. --}}
        </div>
    @elseif ($order->status === App\Models\Order::STATUS_APPROVED)
        <div class="alert alert-info mt-2" role="alert">
            <strong>Order Disetujui!</strong> 
            Orderan mu dengan nomor #{{ $order->id }} telah disetujui. Silakan lanjutkan pembayaran untuk pemrosesan lebih lanjut.
        </div>
    @elseif ($order->status === App\Models\Order::STATUS_PENDING_PAYMENT)
        <div class="alert alert-danger mt-2" role="alert">
            <strong>Pembayaran Tertunda!</strong> 
            Orderan mu dengan nomor #{{ $order->id }} menunggu pembayaran. Pastikan Anda melakukan pembayaran segera.
        </div>
    @elseif ($order->status === App\Models\Order::STATUS_CONFIRMED)
        <div class="alert alert-info mt-2" role="alert">
            <strong>Order Dikonfirmasi!</strong> 
            Orderan mu dengan nomor #{{ $order->id }} telah dikonfirmasi. Kami akan memprosesnya sesegera mungkin.
        </div>
    @elseif ($order->status === App\Models\Order::STATUS_PROCESSING)
        <div class="alert alert-primary mt-2" role="alert">
            <strong>Sedang Diproses!</strong> 
            Orderan mu dengan nomor #{{ $order->id }} sedang diproses oleh tim kami.
        </div>
    @elseif ($order->status === App\Models\Order::STATUS_SHIPPED)
        <div class="alert alert-success mt-2" role="alert">
            <strong>Dikirim!</strong> 
            Orderan mu dengan nomor #{{ $order->id }} telah dikirim. Silakan cek nomor pelacakan: {{ $order->tracking_number }}.
        </div>
    @elseif ($order->status === App\Models\Order::STATUS_DELIVERED)
        <div class="alert alert-success mt-2" role="alert">
            <strong>Order Diterima!</strong> 
            Orderan mu dengan nomor #{{ $order->id }} telah sampai. Terima kasih telah berbelanja!
        </div>
    @elseif ($order->status === App\Models\Order::STATUS_CANCELLED)
        <div class="alert alert-danger mt-2" role="alert">
            <strong>Order Dibatalkan!</strong> 
            Orderan mu dengan nomor #{{ $order->id }} telah dibatalkan. Silakan hubungi admin jika ini terjadi karena kesalahan.
        </div>
    @endif
@endforeach
