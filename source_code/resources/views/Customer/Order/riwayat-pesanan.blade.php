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
                <a class="nav-link" id="waiting-confirmation-tab" data-bs-toggle="tab" href="#waiting-confirmation" role="tab" aria-controls="waiting-confirmation" aria-selected="false">{{ __('messages.wait_acc') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="negotiation-tab" data-bs-toggle="tab" href="#negotiation" role="tab" aria-controls="negotiation" aria-selected="false">{{ __('messages.negosing') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="being-packed-tab" data-bs-toggle="tab" href="#being-packed" role="tab" aria-controls="being-packed" aria-selected="false">{{ __('messages.packing') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="shipped-tab" data-bs-toggle="tab" href="#shipped" role="tab" aria-controls="shipped" aria-selected="false">{{ __('messages.send') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="completed-tab" data-bs-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="false">{{ __('messages.finish') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="canceled-tab" data-bs-toggle="tab" href="#canceled" role="tab" aria-controls="canceled" aria-selected="false">{{ __('messages.cancel') }}</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="orderTabsContent">
            <!-- Semua -->
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                @include('customer.order.tab-content', ['orders' => $orders])
            </div>

            <!-- Menunggu Konfirmasi -->
            <div class="tab-pane fade" id="waiting-confirmation" role="tabpanel" aria-labelledby="waiting-confirmation-tab">
                @include('customer.order.tab-content', ['orders' => $orders->whereIn('status', ['Menunggu Konfirmasi Admin', 'Menunggu Konfirmasi Admin untuk Negosiasi'])])
            </div>

            <!-- Negosiasi -->
            <div class="tab-pane fade" id="negotiation" role="tabpanel" aria-labelledby="negotiation-tab">
                @include('customer.order.tab-content', ['orders' => $orders->where('status', 'Negosiasi')])
            </div>

            <!-- Sedang Dikemas -->
            <div class="tab-pane fade" id="being-packed" role="tabpanel" aria-labelledby="being-packed-tab">
                @include('customer.order.tab-content', ['orders' => $orders->where('status', 'Packing')])
            </div>

            <!-- Dikirim -->
            <div class="tab-pane fade" id="shipped" role="tabpanel" aria-labelledby="shipped-tab">
                @include('customer.order.tab-content', ['orders' => $orders->where('status', 'Pengiriman')])
            </div>

            <!-- Selesai -->
            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                @include('customer.order.tab-content', ['orders' => $orders->where('status', 'Selesai')])
            </div>

            <!-- Dibatalkan -->
            <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                @include('customer.order.tab-content', ['orders' => $orders->where('status', 'Cancelled')])
            </div>
        </div>
    </div>
@endsection

