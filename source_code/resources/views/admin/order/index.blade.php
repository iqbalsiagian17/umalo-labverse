@extends('layouts.admin.master')

@section('content')
@php
    use App\Models\Order;
@endphp

<div class="container py-5">
    <!-- Kartu untuk Mengelola Pesanan -->
    <div class="card">
        <div class="card-header">
            <h1 class="mb-4">Kelola Pesanan</h1>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Bagian Pencarian dan Filter -->
            <form action="{{ route('admin.orders.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama atau invoice" value="{{ request()->search }}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="invoice" class="form-control" placeholder="Filter berdasarkan Invoice" value="{{ request()->invoice }}">
                    </div>
                    <div class="col-md-2">
                        <select name="total_range" class="form-select">
                            <option value="">-- Pilih Anggaran --</option>
                            <option value="less_1m" {{ request()->total_range == 'less_1m' ? 'selected' : '' }}>Kurang dari 1 Juta</option>
                            <option value="1m_5m" {{ request()->total_range == '1m_5m' ? 'selected' : '' }}>1 Juta - 5 Juta</option>
                            <option value="5m_10m" {{ request()->total_range == '5m_10m' ? 'selected' : '' }}>5 Juta - 10 Juta</option>
                            <option value="10m_up" {{ request()->total_range == '10m_up' ? 'selected' : '' }}>Lebih dari 10 Juta</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="all" {{ request()->status == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="{{ Order::STATUS_WAITING_APPROVAL }}" {{ request()->status == Order::STATUS_WAITING_APPROVAL ? 'selected' : '' }}>Menunggu Persetujuan</option>
                            <option value="{{ Order::STATUS_APPROVED }}" {{ request()->status == Order::STATUS_APPROVED ? 'selected' : '' }}>Disetujui</option>
                            <option value="{{ Order::STATUS_PENDING_PAYMENT }}" {{ request()->status == Order::STATUS_PENDING_PAYMENT ? 'selected' : '' }}>Menunggu Pembayaran</option>
                            <option value="{{ Order::STATUS_CONFIRMED }}" {{ request()->status == Order::STATUS_CONFIRMED ? 'selected' : '' }}>Pembayaran Diverifikasi</option>
                            <option value="{{ Order::STATUS_PROCESSING }}" {{ request()->status == Order::STATUS_PROCESSING ? 'selected' : '' }}>Dikemas</option>
                            <option value="{{ Order::STATUS_SHIPPED }}" {{ request()->status == Order::STATUS_SHIPPED ? 'selected' : '' }}>Dikirim</option>
                            <option value="{{ Order::STATUS_DELIVERED }}" {{ request()->status == Order::STATUS_DELIVERED ? 'selected' : '' }}>Selesai</option>
                            <option value="{{ Order::STATUS_CANCELLED }}" {{ request()->status == Order::STATUS_CANCELLED ? 'selected' : '' }}>Dibatalkan</option>
                            <option value="{{ Order::STATUS_CANCELLED_BY_SYSTEM }}" {{ request()->status == Order::STATUS_CANCELLED_BY_SYSTEM ? 'selected' : '' }}>Dibatalkan oleh Sistem</option>
                            <option value="{{ Order::STATUS_CANCELLED_BY_ADMIN }}" {{ request()->status == Order::STATUS_CANCELLED_BY_ADMIN ? 'selected' : '' }}>Dibatalkan oleh Admin</option>
                        </select>
                    </div>                    
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search p-1"></i> <!-- Ikon Pencarian -->
                        </button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-sync-alt p-1"></i> <!-- Ikon Refresh -->
                        </a>
                    </div>
                </div>
            </form>
            
            <!-- Bagian Pesanan -->
            @if($orders->isEmpty())
                <!-- Tampilkan pesan jika tidak ada hasil -->
                <div class="alert alert-warning text-center" role="alert">
                    Tidak ada pesanan yang sesuai dengan kriteria pencarian atau filter Anda.
                </div>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Invoice</th>
                            <th>Pelanggan</th>
                            <th>Final Price</th>
                            <th class="text-center">Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>
                                <a href="{{ $order->invoice_number ? route('order.generate_pdf', $order->id) : '#' }}">
                                    {{ $order->invoice_number ?? '-' }}
                                </a>
                            </td>                        
                            <td>{{ optional($order->user)->full_name ?? optional($order->user)->name }}</td>
                            <td>
                                Rp{{ number_format($order->negotiation_total ?? $order->total, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="badge 
                                    @switch($order->status)
                                        @case(Order::STATUS_WAITING_APPROVAL) bg-warning @break
                                        @case(Order::STATUS_APPROVED) bg-info @break
                                        @case(Order::STATUS_PENDING_PAYMENT) bg-success @break
                                        @case(Order::STATUS_CONFIRMED) bg-success @break
                                        @case(Order::STATUS_PROCESSING) bg-primary @break
                                        @case(Order::STATUS_SHIPPED) bg-secondary @break
                                        @case(Order::STATUS_DELIVERED) bg-success @break
                                        @case(Order::STATUS_CANCELLED)
                                        @case(Order::STATUS_CANCELLED_BY_SYSTEM)
                                        @case(Order::STATUS_CANCELLED_BY_ADMIN) bg-danger @break
                                        @case(Order::STATUS_NEGOTIATION_PENDING) bg-warning @break <!-- Background color for negotiation pending -->
                                        @default bg-light
                                    @endswitch
                            
                                    @switch($order->negotiation_status)
                                        @case(Order::STATUS_NEGOTIATION_PENDING) bg-warning text-dark @break <!-- Color for negotiation pending -->
                                        @default
                                    @endswitch
                                ">
                                    @switch($order->status)
                                        @case(Order::STATUS_WAITING_APPROVAL)
                                            Menunggu Konfirmasi
                                            @break
                                        @case(Order::STATUS_APPROVED)
                                            Menunggu Pembayaran
                                            @break
                                        @case(Order::STATUS_PENDING_PAYMENT)
                                            Menunggu Pembayaran
                                            @break
                                        @case(Order::STATUS_CONFIRMED)
                                            Pembayaran Diterima
                                            @break
                                        @case(Order::STATUS_PROCESSING)
                                            Sedang Dikemas
                                            @break
                                        @case(Order::STATUS_SHIPPED)
                                            Dikirim
                                            @break
                                        @case(Order::STATUS_DELIVERED)
                                            Tiba di Tujuan
                                            @break
                                        @case(Order::STATUS_CANCELLED)
                                        @case(Order::STATUS_CANCELLED_BY_SYSTEM)
                                        @case(Order::STATUS_CANCELLED_BY_ADMIN)
                                            Dibatalkan
                                            @break
                                        @default
                                            {{ ucfirst($order->status) }}
                                    @endswitch
                            
                                    @switch($order->negotiation_status)
                                        @case(Order::STATUS_NEGOTIATION_PENDING)
                                            Menunggu Persetujuan Negoisasi
                                            @break
                                        @default
                                    @endswitch
                                </span>
                            
                                @if (!$order->is_viewed_by_admin)
                                    <span class="badge bg-danger ms-2">Baru</span>
                                @endif
                            </td>
                            
                            
                            
                            <td>
                                <!-- Tampilkan Detail Pesanan -->
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">Lihat Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif           
        </div>
        <div class="pagination justify-content-center">
            {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection
