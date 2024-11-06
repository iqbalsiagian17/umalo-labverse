@extends('layouts.customer.master')
@section('content')
<style>
/* Styling untuk nav-tabs */
.nav-tabs {
    border-bottom: none; /* Menghilangkan garis bawah tab */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Menambahkan shadow pada nav */
    background-color: #fff; /* Memberikan background putih */
    padding: 10px 15px;
    border-radius: 4px; /* Membuat sudut sedikit melengkung */
}

/* Styling untuk tab aktif dan hover */
.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
    color: #494c57;
    background-color: #f8f9fa;
    font-weight: bold;
    transition: background-color 0.3s ease-in-out;
    border: none; /* Menghilangkan border */
    box-shadow: none; /* Menghilangkan shadow ekstra */
}

/* Styling untuk tab yang tidak aktif */
.nav-tabs .nav-link {
    border: none; /* Menghilangkan border pada tab */
    padding: 12px 20px;
    color: #413937;
    transition: color 0.3s ease-in-out;
}

/* Styling untuk hover pada tab */
.nav-tabs .nav-link:hover {
    background-color: #f8f9fa;
    color: #007bff;
}

/* Styling untuk konten tab */
.tab-content > .tab-pane {
    padding: 20px;
    border: none; /* Menghilangkan garis border */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Menambahkan shadow */
    background-color: #fff; /* Memberikan background putih */
    border-radius: 4px; /* Membuat sudut konten sedikit melengkung */
}
</style>

<div class="container mt-5 mb-5">

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-2" id="orderTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="all-tab" data-bs-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">{{ __('messages.all') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="waiting-approval-tab" data-bs-toggle="tab" href="#waiting-approval" role="tab" aria-controls="waiting-approval" aria-selected="false">{{ __('messages.waiting_approval') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="approved-tab" data-bs-toggle="tab" href="#approved" role="tab" aria-controls="approved" aria-selected="false">{{ __('messages.approved') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pending-payment-tab" data-bs-toggle="tab" href="#pending-payment" role="tab" aria-controls="pending-payment" aria-selected="false">{{ __('messages.pending_payment') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="confirmed-tab" data-bs-toggle="tab" href="#confirmed" role="tab" aria-controls="confirmed" aria-selected="false">{{ __('messages.confirmed') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="processing-tab" data-bs-toggle="tab" href="#processing" role="tab" aria-controls="processing" aria-selected="false">{{ __('messages.processing') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="shipped-tab" data-bs-toggle="tab" href="#shipped" role="tab" aria-controls="shipped" aria-selected="false">{{ __('messages.shipped') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="delivered-tab" data-bs-toggle="tab" href="#delivered" role="tab" aria-controls="delivered" aria-selected="false">{{ __('messages.delivered') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="canceled-tab" data-bs-toggle="tab" href="#canceled" role="tab" aria-controls="canceled" aria-selected="false">{{ __('messages.cancelled') }}</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="orderTabsContent">
        <!-- Semua -->
        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
            @include('customer.order.tab-content', ['orders' => $orders])
        </div>

        <!-- Menunggu Persetujuan -->
        <div class="tab-pane fade" id="waiting-approval" role="tabpanel" aria-labelledby="waiting-approval-tab">
            @include('customer.order.tab-content', ['orders' => $orders->where('status', \App\Models\Order::STATUS_WAITING_APPROVAL)])
        </div>

        <!-- Disetujui -->
        <div class="tab-pane fade" id="approved" role="tabpanel" aria-labelledby="approved-tab">
            @include('customer.order.tab-content', ['orders' => $orders->where('status', \App\Models\Order::STATUS_APPROVED)])
        </div>

        <!-- Menunggu Pembayaran -->
        <div class="tab-pane fade" id="pending-payment" role="tabpanel" aria-labelledby="pending-payment-tab">
            @include('customer.order.tab-content', ['orders' => $orders->where('status', \App\Models\Order::STATUS_PENDING_PAYMENT)])
        </div>

        <!-- Dikonfirmasi -->
        <div class="tab-pane fade" id="confirmed" role="tabpanel" aria-labelledby="confirmed-tab">
            @include('customer.order.tab-content', ['orders' => $orders->where('status', \App\Models\Order::STATUS_CONFIRMED)])
        </div>

        <!-- Sedang Diproses -->
        <div class="tab-pane fade" id="processing" role="tabpanel" aria-labelledby="processing-tab">
            @include('customer.order.tab-content', ['orders' => $orders->where('status', \App\Models\Order::STATUS_PROCESSING)])
        </div>

        <!-- Dikirim -->
        <div class="tab-pane fade" id="shipped" role="tabpanel" aria-labelledby="shipped-tab">
            @include('customer.order.tab-content', ['orders' => $orders->where('status', \App\Models\Order::STATUS_SHIPPED)])
        </div>

        <!-- Diterima -->
        <div class="tab-pane fade" id="delivered" role="tabpanel" aria-labelledby="delivered-tab">
            @include('customer.order.tab-content', ['orders' => $orders->where('status', \App\Models\Order::STATUS_DELIVERED)])
        </div>

        <!-- Dibatalkan -->
        <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
            @include('customer.order.tab-content', ['orders' => $orders->where('status', \App\Models\Order::STATUS_CANCELLED)])
        </div>
    </div>
</div>
@endsection
