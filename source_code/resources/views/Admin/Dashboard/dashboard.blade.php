@extends('layouts.admin.master')

@section('content')

@include('customer.partials.home.welcome__messages')

@if(session('warning'))
<div class="alert alert-warning">
    {{ session('warning') }}
</div>
@endif

<div class="row">
  <!-- Reminder Card for Waiting Approval Orders -->
  @if($waitingApprovalOrders->count() > 0)
  <div class="col-md-12">
      <div class="card bg-warning text-white">
          <div class="card-header">
              <strong>Pesanan Menunggu Persetujuan</strong>
          </div>
          <div class="card-body">
              <p>Anda memiliki {{ $waitingApprovalOrders->count() }} pesanan yang menunggu persetujuan. Harap tinjau dan pilih untuk menyetujui atau menolaknya.</p>
              @foreach($waitingApprovalOrders as $order)
                  <p>
                      <a href="{{ route('admin.orders.show', $order->id) }}">Pesanan #{{ $order->id }} dari {{ $order->user->userdetail->perusahaan ?? 'Perusahaan Tidak Diketahui' }} - Dibuat: {{ $order->created_at->diffForHumans() }}</a>
                  </p>
              @endforeach
          </div>
      </div>
  </div>
  @endif

  <!-- Reminder Card for Processing Orders -->
  @if($processingOrders->count() > 0)
  <div class="col-md-12">
      <div class="card bg-info text-white">
          <div class="card-header">
              <strong>Pesanan Sedang Diproses</strong>
          </div>
          <div class="card-body">
              <p>Anda memiliki {{ $processingOrders->count() }} pesanan yang sedang diproses. Harap segera kirimkan pesanan-pesanan tersebut.</p>
              @foreach($processingOrders as $order)
                  <p>
                      <a href="{{ route('admin.orders.show', $order->id) }}">Pesanan #{{ $order->id }} dari {{ $order->user->userdetail->perusahaan ?? 'Perusahaan Tidak Diketahui' }} - Dibuat: {{ $order->created_at->diffForHumans() }}</a>
                  </p>
              @endforeach
          </div>
      </div>
  </div>
  @endif

  <!-- Reminder Card for Pending Payment Orders -->
  @if($pendingPaymentOrders->count() > 0)
  <div class="col-md-12">
      <div class="card bg-danger text-white">
          <div class="card-header">
              <strong>Pesanan Menunggu Pembayaran</strong>
          </div>
          <div class="card-body">
              <p>Anda memiliki {{ $pendingPaymentOrders->count() }} pesanan yang menunggu pembayaran. Harap konfirmasi pembayaran untuk melanjutkan agar customer dapat melakukan pembayaran.</p>
              @foreach($pendingPaymentOrders as $order)
                  <p>
                      <a href="{{ route('admin.orders.show', $order->id) }}">Pesanan #{{ $order->id }} dari {{ $order->user->userdetail->perusahaan ?? 'Perusahaan Tidak Diketahui' }} - Dibuat: {{ $order->created_at->diffForHumans() }}</a>
                  </p>
              @endforeach
          </div>
      </div>
  </div>
  @endif

  @if($confirmPaymentOrders->count() > 0)
  <div class="col-md-12">
      <div class="card bg-warning text-white">
          <div class="card-header">
              <strong>Customer Menunggu Anda Untuk Memproses Orderannya</strong>
          </div>
          <div class="card-body">
              <p>Anda memiliki {{ $confirmPaymentOrders->count() }} pesanan yang menunggu anda untuk melakukan tahap selanjutnya . Harap lakukan proses packing untuk meyakinkan customer jika produk sedang di kerjakan.</p>
              @foreach($confirmPaymentOrders as $order)
                  <p>
                      <a href="{{ route('admin.orders.show', $order->id) }}">Pesanan #{{ $order->id }} dari {{ $order->user->userdetail->perusahaan ?? 'Perusahaan Tidak Diketahui' }} - Dibuat: {{ $order->created_at->diffForHumans() }}</a>
                  </p>
              @endforeach
          </div>
      </div>
  </div>
  @endif
</div>




    <div class="row">
    <div class="col-sm-6 col-md-3">
      <div class="card card-stats card-round">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-icon">
              <div class="icon-big text-center icon-primary bubble-shadow-small">
                <i class="fas fa-users"></i>
              </div>
            </div>
            <div class="col col-stats ms-3 ms-sm-0">
              <div class="numbers">
                <p class="card-category">Customer</p>
                <h4 class="card-title">{{ $customerCount }}</h4> <!-- Display the customer count -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card card-stats card-round">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-icon">
              <div class="icon-big text-center icon-success bubble-shadow-small">
                <i class="fas fa-luggage-cart"></i>
              </div>
            </div>
            <div class="col col-stats ms-3 ms-sm-0">
              <div class="numbers">
                  <p class="card-category">Income</p>
                  <h4 class="card-title">Rp {{ number_format($totalSales, 2) }}</h4>
              </div>
          </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card card-stats card-round">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-icon">
              <div class="icon-big text-center icon-info bubble-shadow-small">
                <i class="fas fa-user-check"></i>
              </div>
            </div>
            <div class="col col-stats ms-3 ms-sm-0">
              <div class="numbers">
                <p class="card-category">Order (Proses)</p>
                <h4 class="card-title">{{ $orderNotFinishCount }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="card card-stats card-round">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-icon">
              <div class="icon-big text-center icon-secondary bubble-shadow-small">
                <i class="far fa-check-circle"></i>
              </div>
            </div>
            <div class="col col-stats ms-3 ms-sm-0">
              <a href="{{ route('admin.visits') }}">
              <div class="numbers">
                <p class="card-category">Statistik</p>
                <h4 class="card-title">Pengguna Website</h4> <!-- Display the order count -->
              </div>
            </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @php
            use App\Models\Order;
            use App\Models\Payment;

                $statusMap = [
                    Order::STATUS_WAITING_APPROVAL => 'Menunggu Persetujuan',
                    Order::STATUS_APPROVED => 'Disetujui',
                    Order::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
                    Order::STATUS_CONFIRMED => 'Pembayaran Diterima',
                    Order::STATUS_PROCESSING => 'Sedang Dikemas',
                    Order::STATUS_SHIPPED => 'Dikirim',
                    Order::STATUS_DELIVERED => 'Tiba di Tujuan',
                    Order::STATUS_CANCELLED => 'Dibatalkan',
                    Order::STATUS_CANCELLED_BY_SYSTEM => 'Dibatalkan oleh Sistem',
                    Order::STATUS_CANCELLED_BY_ADMIN => 'Dibatalkan oleh Admin',
                ];

                $paymentStatusMap = [
                    Payment::STATUS_UNPAID => 'Belum Dibayar',
                    Payment::STATUS_PENDING => 'Menunggu Konfirmasi',
                    Payment::STATUS_PAID => 'Dibayar',
                    Payment::STATUS_FAILED => 'Gagal',
                    Payment::STATUS_REFUNDED => 'Dikembalikan',
                    Payment::STATUS_PARTIALLY_REFUNDED => 'Dikembalikan Sebagian',
                ];
            @endphp

            <div class="row">
                <div class="col-md-6 col-lg-6 order-2 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 me-2">Payment</h5>
                        </div>
                        <div class="card-body">
                            <ul class="p-0 m-0">
                                @foreach($payments as $payment)
                                    <li class="d-flex mb-4 pb-1">
                                        <a href="{{ route('admin.payments.index') }}" class="d-flex w-100 text-decoration-none">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <img src="{{ asset('assets/images/user.png') }}" alt="Payment" class="rounded w-100 h-100 " />
                                            </div>
                                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <small class="text-muted d-block">Payment ID: {{ $payment->id }}</small>
                                                    <small class="text-muted d-block">Invoice: {{ $payment->order->invoice_number }}</small>
                                                    <small class="text-muted d-block">Customer: {{ optional($payment->order->user)->name ?? 'N/A' }}</small>
                                                </div>
                                                <div class="user-progress d-flex align-items-center gap-1">
                                                    @if($payment->status === 'completed')
                                                        @if(in_array($payment->order->status, [Order::STATUS_CANCELLED, Order::STATUS_CANCELLED_BY_SYSTEM]))
                                                            <h6 class="mb-0 text-muted">Dibatalkan</h6>
                                                        @else
                                                            <h6 class="mb-0 text-success">+{{ 'Rp' . number_format($payment->amount, 0, ',', '.') }}</h6>
                                                        @endif
                                                    @else
                                                    <h6>{{ $paymentStatusMap[$payment->status] ?? 'Status Tidak Diketahui' }}</h6>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="pagination justify-content-center">
                                {{ $payments->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 order-2 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0 me-2">Orders</h5>
                        </div>
                        <div class="card-body">
                            <ul class="p-0 m-0">
                                @foreach($orders as $order)
                                    <li class="d-flex mb-4 pb-1">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="d-flex w-100 text-decoration-none">
                                            <div class="avatar flex-shrink-0 me-3">
                                              <img src="{{ asset('assets/images/user.png') }}" alt="Payment" class="rounded w-100 h-100 " />
                                            </div>
                                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <small class="text-muted d-block mb-1">Order #{{ $order->id }}</small>
                                                    <h6 class="mb-0">Status: {{ $statusMap[$order->status] ?? ucfirst($order->status) }}</h6>
                                                    <p class="mb-0">Customer: {{ $order->user->name ?? 'N/A' }}</p>
                                                </div>
                                                <div class="user-progress d-flex align-items-center gap-1">
                                                    @if(in_array($order->status, [Order::STATUS_CANCELLED, Order::STATUS_CANCELLED_BY_SYSTEM]))
                                                        <h6 class="mb-0 text-muted">Dibatalkan</h6>
                                                    @else
                                                    <h6 class="mb-0 text-success">
                                                      +{{ 'Rp' . number_format($order->negotiation_total ?? $order->total, 0, ',', '.') }}
                                                    </h6>
                                                    @endif
                                                    <span class="text-muted">Rp</span>
                                                </div>                                            
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="pagination justify-content-center">
                                {{ $orders->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>

    <style>
        .hover-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease-in-out;
        }
        .icon-box {
            font-size: 2.5rem;
        }

        .card-body ul li a {
        transition: background-color 0.3s ease;
    }

    .card-body ul li a:hover {
        background-color: #f1f1f1; /* Light grey background on hover */
        border-radius: 5px;
    }

    /* Hover effect for text color */
    .card-body ul li a:hover .text-muted,
    .card-body ul li a:hover h6,
    .card-body ul li a:hover p {
        color: #333; /* Darker text color on hover */
    }
    </style>

@endsection
