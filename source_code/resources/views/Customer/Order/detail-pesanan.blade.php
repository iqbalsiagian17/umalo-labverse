@extends('layouts.customer.master')
@section('content')
    <div class="container mt-5 mb-3">
        <div class="card shadow rounded border-0">
            <div class="card-header rounded border-0">
                <h2 class="mb-0">{{ __('messages.order_details') }}</h2>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="container mb-5">
        <div class="card rounded border-0">
            <div class="card-body shadow">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="mb-3">{{ __('Invoice') }}: <strong>{{ $order->invoice_number }}</strong></h4>
                        <h4 class="mb-3">{{ __('messages.order_number') }}: <strong>{{ $order->id }}</strong></h4>
                        <p><strong>{{ __('messages.status') }}:</strong> <span
                                class="badge bg-info text-dark">{{ $order->status }}</span></p>

                        <p>
                            <strong>{{ __('messages.total_price') }}:</strong>
                                <span class="text-success">
                                    {{ 'Rp ' . number_format($order->total, 0, ',', '.') }}
                                </span>
                        </p>
                    </div>
                    <div class="col-md-6 text-md-right">
                        @if(!in_array($order->status, ['cancelled', 'cancelled_by_system', 'cancelled_by_admin']))
                            <a href="{{ route('order.generate_pdf', $order->id) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-file-download"></i> {{ __('messages.download_invoice') }}
                            </a>
                        @endif
                        <a href="{{ route('customer.orders.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_order_history') }}
                        </a>
                        @if($order->user_message)
                            <div class="alert alert-info">
                                {{ $order->user_message }}
                            </div>
                        @endif
                        
                        <!-- Shipping Information -->
                        @if($order->status == 'shipped')
                        <div class="mt-3">
                            <h5 class="fw-bold">Shipping Information</h5>
                            <p>Shipping Service: <strong>{{ $order->shippingService->name ?? 'Not specified' }}</strong></p>
                            <p>Tracking Number: <strong class="bg-warning rounded">{{ $order->tracking_number }}</strong></p>
                        </div>
                        @endif

                    </div>
                </div>

                <h4 class="mt-4">{{ __('messages.order_items') }}:</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-white" style="background-color: #416bbf;">
                            <tr>
                                <th>{{ __('messages.product') }}</th>
                                <th class="text-center">{{ __('messages.quantity') }}</th>
                                <th class="text-right">{{ __('messages.price') }}</th>
                                <th class="text-right">{{ __('messages.total_price') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}. {{ $item->product->name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-right">{{ 'Rp ' . number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-right">
                                        {{ 'Rp ' . number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach

                            <!-- Total -->
                            <tr>
                                <td colspan="3" class="text-right"><strong>{{ __('messages.total') }}</strong></td>
                                <td class="text-right">
                                    <strong>
                                        {{ 'Rp ' . number_format(
                                            $order->items->sum(function ($item) {
                                                return $item->price * $item->quantity;
                                            }),
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                @if($order->status === 'shipped')
                <div class="mt-4">
                    <form action="{{ route('customer.complete.order', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">Mark as Completed</button>
                    </form>
                </div>
                @endif
                
                @if($order->status === 'approved')
                <div class="alert alert-warning" role="alert">
                    <strong>Mohon Bersabar!</strong> Menunggu Admin Memberikan Akses Pembayaran
                </div>
                @endif

                <!-- Payment Proof Submission -->
                @if($order->status == 'pending_payment')
                    <div class="mt-5">
                        <h5 class="fw-bold">Submit Payment Proof</h5>
                        <div class="alert alert-warning" role="alert">
                            <strong>Penting!</strong> Jika Anda mengupload atau mengirim bukti pembayaran yang salah, pesanan Anda akan dibatalkan secara otomatis oleh sistem. Kami tidak bertanggung jawab atas kesalahan transfer.
                        </div>

                        @php
                            // Calculate the time left for payment (48 hours)
                            $approvedTime = $order->approved_at; // The timestamp when the order was approved
                            $timeLimit = 48 * 60 * 60; // 48 hours in seconds
                            $currentTime = now(); // Current timestamp
                            $elapsedTime = $currentTime->diffInSeconds($approvedTime); // Time elapsed since approved
                            $remainingTime = max(0, $timeLimit - $elapsedTime); // Calculate remaining time
                            $hours = floor($remainingTime / 3600); // Calculate hours only
                        @endphp

                        <div id="countdown-timer" class="mt-2">
                            Waktu tersisa untuk menyelesaikan pembayaran: <strong>{{ $hours }} jam</strong>.
                        </div> <!-- Countdown timer display -->

                        <form action="{{ route('customer.payment.submit', $order->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label">Upload Payment Proof</label>
                                <input type="file" class="form-control" name="payment_proof" required>
                            </div>
                            <button type="submit" class="btn btn-success">Submit Payment</button>
                        </form>
                    </div>

                    <script>
                        // Set the countdown time in seconds for this order
                        let remainingTime = {{ $remainingTime }};
                        
                        function updateTimer() {
                            // Calculate hours only
                            const hours = Math.floor(remainingTime / 3600);

                            // Display the countdown timer
                            document.getElementById('countdown-timer').innerHTML = 
                                `Waktu tersisa untuk menyelesaikan pembayaran: <strong>${hours} jam</strong>.`;

                            // If time is up, you can implement logic to handle this case
                            if (remainingTime <= 0) {
                                clearInterval(timerInterval);
                                // Logic to cancel the order can be added here (optional)
                            }

                            remainingTime--; // Decrease the remaining time by one second
                        }

                        // Update timer every second
                        const timerInterval = setInterval(updateTimer, 1000);
                        updateTimer(); // Initial call to display timer immediately
                    </script>
                @endif

                @if($order->payments->where('status', 'pending')->isNotEmpty())
                    <div class="alert alert-warning mt-2" role="alert">
                        <strong>Mohon Bersabar!</strong> 
                        Kami sedang memproses bukti pembayaran Anda. Jika Anda merasa telah menunggu terlalu lama, 
                        silakan hubungi admin melalui 
                        <a href="https://wa.me/{{-- {{ $parameters->nomor_wa }} --}}" target="_blank">
                            {{-- {{ $parameters->nomor_wa }} --}}
                        </a>.
                    </div>
                @endif


                @if($order->payments->where('status', 'failed')->isNotEmpty())
                @if($order->status === 'payment_pending')
                    <div class="alert alert-danger mt-2" role="alert">
                        <strong>Hati Hati!</strong> Silahkan lakukan pembayaran kembali, dan Anda hanya memiliki 1 kesempatan lagi sebelum pesanan Anda dibatalkan oleh sistem karena terdeteksi kejanggalan dalam pembayaran Anda.
                    </div>
                @elseif($order->status === 'cancelled_by_system')
                    <div class="alert alert-danger mt-2" role="alert">
                        <strong>Pesanan Dibatalkan!</strong>  Pesanan Anda telah dibatalkan oleh sistem karena terdeteksi adanya aktivitas yang mencurigakan dan berpotensi merugikan. Mohon diperhatikan untuk transaksi berikutnya.
                    </div>
                @endif
            @endif
            
            




                <!-- Payment Details -->
                @if($order->payments->isNotEmpty())
                <div class="mt-5">
                    <h5 class="fw-bold">Payment Details</h5>
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Payment Proof</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->payments as $payment)
                            <tr>
                                <td>
                                    <a href="{{ asset($payment->payment_proof) }}" target="_blank" class="btn btn-sm btn-primary">View Proof</a>
                                </td>
                                <td>
                                    <span class="badge {{ $payment->status == 'verified' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif



                <!-- Transaction History -->
                @if ($order->waiting_approval_at || $order->approved_at || $order->pending_payment_at || $order->confirmed_at || $order->processing_at || $order->shipped_at || $order->delivered_at || $order->cancelled_at || $order->cancelled_by_admin_at || $order->cancelled_by_system_at)
                <div class="container">
                    <div class="mt-5">
                        <h4>{{ __('messages.transaction_history') }}</h4>
                        <ul class="timeline">
                            @if ($order->waiting_approval_at)
                                <li class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <span class="timeline-date">{{ \Carbon\Carbon::parse($order->waiting_approval_at)->format('d-m-Y H:i') }}</span>
                                        <h5 class="timeline-status">{{ __('messages.waiting_approval') }}</h5>
                                    </div>
                                </li>
                            @endif
                            @if ($order->approved_at)
                                <li class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <span class="timeline-date">{{ \Carbon\Carbon::parse($order->approved_at)->format('d-m-Y H:i') }}</span>
                                        <h5 class="timeline-status">{{ __('messages.approved') }}</h5>
                                    </div>
                                </li>
                            @endif
                            @if ($order->pending_payment_at)
                                <li class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <span class="timeline-date">{{ \Carbon\Carbon::parse($order->pending_payment_at)->format('d-m-Y H:i') }}</span>
                                        <h5 class="timeline-status">{{ __('messages.pending_payment') }}</h5>
                                    </div>
                                </li>
                            @endif
                            @if ($order->confirmed_at)
                                <li class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <span class="timeline-date">{{ \Carbon\Carbon::parse($order->confirmed_at)->format('d-m-Y H:i') }}</span>
                                        <h5 class="timeline-status">{{ __('messages.confirmed') }}</h5>
                                    </div>
                                </li>
                            @endif
                            @if ($order->processing_at)
                                <li class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <span class="timeline-date">{{ \Carbon\Carbon::parse($order->processing_at)->format('d-m-Y H:i') }}</span>
                                        <h5 class="timeline-status">{{ __('messages.processing') }}</h5>
                                    </div>
                                </li>
                            @endif
                            @if ($order->shipped_at)
                                <li class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <span class="timeline-date">{{ \Carbon\Carbon::parse($order->shipped_at)->format('d-m-Y H:i') }}</span>
                                        <h5 class="timeline-status">{{ __('messages.shipped') }}</h5>
                                    </div>
                                </li>
                            @endif
                            @if ($order->delivered_at)
                                <li class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <span class="timeline-date">{{ \Carbon\Carbon::parse($order->delivered_at)->format('d-m-Y H:i') }}</span>
                                        <h5 class="timeline-status">{{ __('messages.delivered') }}</h5>
                                    </div>
                                </li>
                            @endif
                            @if ($order->cancelled_at)
                                <li class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <span class="timeline-date">{{ \Carbon\Carbon::parse($order->cancelled_at)->format('d-m-Y H:i') }}</span>
                                        <h5 class="timeline-status">{{ __('messages.cancelled') }}</h5>
                                    </div>
                                </li>
                            @endif
                            @if ($order->cancelled_by_admin_at)
                                <li class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <span class="timeline-date">{{ \Carbon\Carbon::parse($order->cancelled_by_admin_at)->format('d-m-Y H:i') }}</span>
                                        <h5 class="timeline-status">{{ __('messages.cancelled_by_admin') }}</h5>
                                    </div>
                                </li>
                            @endif
                            @if ($order->cancelled_by_system_at)
                                <li class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <span class="timeline-date">{{ \Carbon\Carbon::parse($order->cancelled_by_system_at)->format('d-m-Y H:i') }}</span>
                                        <h5 class="timeline-status">{{ __('messages.cancelled_by_system') }}</h5>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif
            

                <style>
                    /* Timeline container */
                    .timeline {
                        list-style-type: none;
                        position: relative;
                        padding-left: 40px;
                        margin: 0;
                    }

                    /* Timeline item */
                    .timeline-item {
                        margin-bottom: 20px;
                        position: relative;
                        padding-left: 25px;
                    }

                    /* Marker on the timeline */
                    .timeline-marker {
                        position: absolute;
                        left: 0;
                        width: 15px;
                        height: 15px;
                        background-color: #e0e0e0;
                        border-radius: 50%;
                        top: 5px;
                        border: 3px solid #ffffff;
                        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
                    }

                    /* First marker (slightly larger) */
                    .timeline-item-first .timeline-marker {
                        width: 18px;
                        height: 18px;
                        top: 4px;
                    }

                    /* Content next to the marker */
                    .timeline-content {
                        padding-left: 40px;
                    }

                    /* Date */
                    .timeline-date {
                        display: block;
                        font-weight: 600;
                        margin-bottom: 5px;
                        color: #999999;
                    }

                    /* Status */
                    .timeline-status {
                        font-size: 16px;
                        font-weight: 500;
                        color: #333333;
                    }

                    /* Description */
                    .timeline-desc {
                        margin-top: 5px;
                        font-size: 14px;
                        color: #666666;
                    }

                    /* Vertical line connecting the timeline markers */
                    .timeline::before {
                        content: '';
                        background-color: #e0e0e0;
                        width: 2px;
                        position: absolute;
                        top: 0;
                        bottom: 0;
                        left: 7px;
                    }

                    /* Hover effects */
                    .timeline-item:hover .timeline-marker {
                        background-color: #999999;
                    }

                    .timeline-item:hover .timeline-status {
                        color: #000000;
                    }

                    .timeline-item:hover .timeline-date {
                        color: #666666;
                    }
                </style>

            @if($order->status === 'delivered')
            <div class="mt-4 bg-light p-4 rounded mb-4">
                <h5 class="fw-bold">Give a Review</h5>
                <ul class="list-unstyled">
                    @foreach($order->items as $item)
                        @php
                            // Check if the review exists for this product and order
                            $reviewExists = $item->product->reviews()
                                ->where('user_id', auth()->id())
                                ->where('order_id', $order->id)
                                ->exists();
                        @endphp

                        @if(!$reviewExists)
                            <li class="mb-2">
                                <a href="{{ route('product.show', ['slug' => $item->product->slug]) }}?order={{ $order->id }}" class="btn btn-outline-primary">
                                    Review {{ $item->product->name }}
                                </a>
                            </li>
                        @else
                            <li>
                                <span class="text-muted">You have already reviewed {{ $item->product->name }} for this order.</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif
                
            </div>
        </div>
    </div>
@endsection
