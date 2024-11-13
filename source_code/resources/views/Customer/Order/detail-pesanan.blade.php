@extends('layouts.customer.master')

@section('content')

@php
    use App\Models\Order;
    use App\Models\Payment;
@endphp



    <div class="container mt-5 mb-3">
        <div class="card shadow rounded border-0">
            <div class="card-header rounded border-0">
                <h2 class="mb-0">{{ __('messages.order_details') }}</h2>
            </div>
        </div>
        
        @include('customer.partials.order.order__messages')
    
    </div>

    <div class="container mb-5">
        <div class="card rounded border-0">
            <div class="card-body shadow">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="mb-3">{{ __('Invoice') }}: <strong>{{ $order->invoice_number }}</strong></h4>
                        <h4 class="mb-3">{{ __('messages.order_number') }}: <strong>{{ $order->id }}</strong></h4>
                        <p><strong>{{ __('messages.status') }}:</strong> 
                            <span class="badge bg-info text-dark">
                                {{ __('messages.' . $order->status) }}
                            </span>
                        </p>
                        
                                <p>
                                    <strong>{{ __('messages.total_price') }}:</strong>
                                    <span class="text-success">
                                        {{ 'Rp ' . number_format($order->negotiation_total ?? $order->total, 0, ',', '.') }}
                                    </span>
                                </p>
                                
                    </div>
                    <div class="col-md-6 text-md-right">
                        @if(!in_array($order->status, ['cancelled','waiting_approval', '', 'cancelled_by_system', 'cancelled_by_admin']))
                            <a href="{{ route('order.generate_pdf', $order->id) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-file-download"></i> {{ __('messages.download_invoice') }}
                            </a>
                        @endif
                        @if(!in_array($order->status, ['processing', 'shipped', 'delivered','cancelled_by_admin','cancelled_by_system','cancelled']))
                            <form action="{{ route('customer.order.cancel', $order->id) }}" method="POST" class="d-inline-block mt-2">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-times-circle"></i> Cancel Order
                                </button>
                            </form>
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

                        @if($order->status == 'negotiation_in_progress')
                            <div class="mt-4 p-4 bg-white rounded shadow-sm" style="border: 1px solid #ddd;">
                                <h5 class="fw-bold text-primary mb-3">Negosiasi Dikonfirmasi</h5>
                                <p class="text-muted mb-4">Jika admin belum menghubungi Anda, silakan hubungi admin melalui kontak di bawah ini:</p>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="d-flex align-items-center mb-3">
                                                <span class="fw-semibold text-secondary me-2">Telepon:</span>
                                                <span class="text-dark">{{ $parameter->telephone_number ?? 'Tidak tersedia' }}</span>
                                            </li>
                                            <li class="d-flex align-items-center mb-3">
                                                <span class="fw-semibold text-secondary me-2">WhatsApp:</span>
                                                <span class="text-dark">{{ $parameter->whatsapp_number ?? 'Tidak tersedia' }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="d-flex align-items-center mb-3">
                                                <span class="fw-semibold text-secondary me-2">Email 1:</span>
                                                <span class="text-dark">{{ $parameter->email1 ?? 'Tidak tersedia' }}</span>
                                            </li>
                                            <li class="d-flex align-items-center mb-3">
                                                <span class="fw-semibold text-secondary me-2">Email 2:</span>
                                                <span class="text-dark">{{ $parameter->email2 ?? 'Tidak tersedia' }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
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
                                <td>
                                    {{ $index + 1 }}. {{ $item->product->name }}
                                    
                                    {{-- Check if the item is part of an active Big Sale --}}
                                    @php
                                        $activeBigSale = App\Models\BigSale::where('status', true)
                                            ->where('start_time', '<=', now())
                                            ->where('end_time', '>=', now())
                                            ->whereHas('products', function ($query) use ($item) {
                                                $query->where('t_product.id', $item->product->id);
                                            })
                                            ->first();
                                    @endphp
                            
                                    @if ($activeBigSale)
                                        <span class="badge bg-success text-white">Big Sale</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">{{ 'Rp ' . number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-right">{{ 'Rp ' . number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            
                            @endforeach

                            <!-- Total -->
                            <tr>
                                <td colspan="3" class="text-right"><strong>{{ __('messages.total') }}</strong></td>
                                <td class="text-right">
                                    <strong>
                                        @if ($order->negotiation_total)
                                            <span style="text-decoration: line-through; color: #999;">
                                                {{ 'Rp ' . number_format(
                                                    $order->items->sum(function ($item) {
                                                        return $item->price * $item->quantity;
                                                    }),
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </span>
                                            <br>
                                            <span>{{ 'Rp ' . number_format($order->negotiation_total, 0, ',', '.') }}</span>
                                        @else
                                            {{ 'Rp ' . number_format(
                                                $order->items->sum(function ($item) {
                                                    return $item->price * $item->quantity;
                                                }),
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        @endif
                                    </strong>
                                </td>
                                
                                
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right"><strong>{{ __('messages.total_final_payment') }}</strong></td>
                                <td class="text-right">
                                    <strong>
                                        {{ 'Rp ' . number_format(
                                            $order->negotiation_total ?? $order->total,
                                            0,
                                            ',',
                                            '.'
                                        ) }};
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
                @php
                    // Check the number of submitted payment proofs
                    $paymentCount = $order->payments->count();
                @endphp

                @if($paymentCount < 2)
                    <div class="mt-5">
                        <h5 class="fw-bold">Submit Payment Proof</h5>
                        <div class="alert alert-warning" role="alert">
                            <strong>Penting!</strong> Jika Anda mengupload atau mengirim bukti pembayaran yang salah, pesanan Anda akan dibatalkan secara otomatis oleh sistem. Kami tidak bertanggung jawab atas kesalahan transfer.
                        </div>

                        @php
                            // Calculate the time left for payment (48 hours)
                            $approvedTime = $order->approved_at;
                            $timeLimit = 48 * 60 * 60; // 48 hours in seconds
                            $currentTime = now();
                            $elapsedTime = $currentTime->diffInSeconds($approvedTime);
                            $remainingTime = max(0, $timeLimit - $elapsedTime);
                            $hours = floor($remainingTime / 3600);
                        @endphp

                        <div id="countdown-timer" class="mt-2">
                            Waktu tersisa untuk menyelesaikan pembayaran: <strong>{{ $hours }} jam</strong>.
                        </div>

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
                        let remainingTime = {{ $remainingTime }};

                        function updateTimer() {
                            const hours = Math.floor(remainingTime / 3600);
                            document.getElementById('countdown-timer').innerHTML = 
                                `Waktu tersisa untuk menyelesaikan pembayaran: <strong>${hours} jam</strong>.`;

                            if (remainingTime <= 0) {
                                clearInterval(timerInterval);
                            }
                            remainingTime--;
                        }

                        const timerInterval = setInterval(updateTimer, 1000);
                        updateTimer();
                    </script>
                @else
                    <div class="alert alert-info mt-3" role="alert">
                        Anda telah mengirimkan bukti pembayaran sebanyak dua kali. Harap menunggu verifikasi oleh admin.
                    </div>
                @endif
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


                @if($order->status === Order::STATUS_PENDING_PAYMENT)
                @if($order->payments->where('status', Payment::STATUS_FAILED)->isNotEmpty())
                @php
                    // Check if the most recent payment status is failed
                    $latestPayment = $order->payments->sortByDesc('created_at')->first();
                @endphp
            
                @if($latestPayment && $latestPayment->status === Payment::STATUS_FAILED)
                    <div class="alert alert-danger mt-2" role="alert">
                        <strong>Hati-Hati!</strong> Pembayaran Anda gagal. Silakan coba kembali. Anda hanya memiliki satu kesempatan lagi sebelum pesanan dibatalkan oleh sistem karena deteksi kejanggalan dalam pembayaran.
                    </div>
                @endif
                @endif
                @endif

                @if($order->status === Order::STATUS_CANCELLED_BY_SYSTEM)
                    <div class="alert alert-danger mt-2" role="alert">
                        <strong>Pesanan Dibatalkan!</strong> Pesanan Anda telah dibatalkan oleh sistem karena aktivitas yang mencurigakan. Mohon diperhatikan untuk transaksi berikutnya.
                    </div>
                @endif

            

                <!-- Payment Details -->
                @if($order->payments->isNotEmpty())
                <div class="mt-5">
                    <h5 class="fw-bold mb-4">Detail Pembayaran</h5>
                    <table class="table table-bordered">
                        <thead class="table-light text-white" style="background-color: #416bbf;">
                            <tr>
                                <th>Status Pembayaran</th>
                                <th>Bukti Pembayaran</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->payments as $payment)
                                <tr>
                                    <td>
                                        <span class="badge rounded-pill {{ $payment->status == 'verified' ? 'bg-success' : 'bg-warning' }}">
                                            {{ $payment->statusMessage() }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#paymentProofModal"
                                                data-image-url="{{ asset($payment->payment_proof) }}">
                                            Lihat Bukti
                                        </button>
                                    </td>
                                    <td>{{ $payment->created_at->translatedFormat('d M Y, H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal for Payment Proof -->
                <div class="modal fade" id="paymentProofModal" tabindex="-1" aria-labelledby="paymentProofModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentProofModalLabel">Bukti Pembayaran</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" 
                                style="position: absolute; top: 10px; right: 10px; border: none; background-color: #f8f9fa; 
                                    color: #333; font-size: 18px; padding: 5px 10px; border-radius: 50%; cursor: pointer; 
                                    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2); transition: background-color 0.3s, color 0.3s;">
                            X
                        </button>
                        <script>
                            document.querySelector('.btn-close').addEventListener('mouseenter', function() {
                                this.style.backgroundColor = '#e0e0e0';
                                this.style.color = '#000';
                            });
                            document.querySelector('.btn-close').addEventListener('mouseleave', function() {
                                this.style.backgroundColor = '#f8f9fa';
                                this.style.color = '#333';
                            });
                        </script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const paymentProofModal = document.getElementById('paymentProofModal');
                                paymentProofModal.addEventListener('show.bs.modal', function (event) {
                                    const button = event.relatedTarget;
                                    const imageUrl = button.getAttribute('data-image-url');
                                    const image = paymentProofModal.querySelector('#paymentProofImage');
                                    image.setAttribute('src', imageUrl);
                                });
                            });
                            </script>
                            </div>
                            <div class="modal-body text-center">
                                <img src="" id="paymentProofImage" alt="Bukti Pembayaran" class="img-fluid rounded">
                            </div>
                        </div>
                    </div>
                </div>
                @endif




                <!-- Transaction History -->
                <div class="container">
                    <div class="row mt-5">
                        <!-- Main Order Timeline -->
                        @if ($order->waiting_approval_at || $order->approved_at || $order->pending_payment_at || $order->confirmed_at || $order->processing_at || $order->shipped_at || $order->delivered_at || $order->cancelled_at || $order->cancelled_by_admin_at || $order->cancelled_by_system_at)
                            <div class="col-md-6">
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
                        @endif
                
                        <!-- Negotiation Timeline -->
                        @if ($order->negotiation_pending_at || $order->negotiation_approved_at || $order->negotiation_rejected_at || $order->negotiation_in_progress_at || $order->negotiation_completed_at)
                            <div class="col-md-6">
                                <h4>{{ __('messages.negotiation_history') }}</h4>
                                <ul class="timeline">
                                    @if ($order->negotiation_pending_at)
                                        <li class="timeline-item">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                <span class="timeline-date">{{ \Carbon\Carbon::parse($order->negotiation_pending_at)->format('d-m-Y H:i') }}</span>
                                                <h5 class="timeline-status">{{ __('messages.negotiation_pending') }}</h5>
                                            </div>
                                        </li>
                                    @endif
                                    @if ($order->negotiation_approved_at)
                                        <li class="timeline-item">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                <span class="timeline-date">{{ \Carbon\Carbon::parse($order->negotiation_approved_at)->format('d-m-Y H:i') }}</span>
                                                <h5 class="timeline-status">{{ __('messages.negotiation_approved') }}</h5>
                                            </div>
                                        </li>
                                    @endif
                                    @if ($order->negotiation_in_progress_at)
                                        <li class="timeline-item">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                <span class="timeline-date">{{ \Carbon\Carbon::parse($order->negotiation_in_progress_at)->format('d-m-Y H:i') }}</span>
                                                <h5 class="timeline-status">{{ __('messages.negotiation_in_progress') }}</h5>
                                            </div>
                                        </li>
                                    @endif
                                    @if ($order->negotiation_rejected_at)
                                        <li class="timeline-item">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                <span class="timeline-date">{{ \Carbon\Carbon::parse($order->negotiation_rejected_at)->format('d-m-Y H:i') }}</span>
                                                <h5 class="timeline-status">{{ __('messages.negotiation_rejected') }}</h5>
                                            </div>
                                        </li>
                                    @endif
                                    @if ($order->negotiation_finished_at)
                                        <li class="timeline-item">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                <span class="timeline-date">{{ \Carbon\Carbon::parse($order->negotiation_finished_at)->format('d-m-Y H:i') }}</span>
                                                <h5 class="timeline-status">{{ __('messages.negotiation_finished') }}</h5>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                
            

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
