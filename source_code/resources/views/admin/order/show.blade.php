@extends('layouts.admin.master')

@section('content')
@php
    use App\Models\Order;

    $statusMap = [
        Order::STATUS_WAITING_APPROVAL => 'Menunggu Persetujuan',
        Order::STATUS_APPROVED => 'Disetujui',
        Order::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
        Order::STATUS_CONFIRMED => 'Pembayaran Diterima',
        Order::STATUS_PROCESSING => 'Sedang Dikemas',
        Order::STATUS_SHIPPED => 'Dikirim',
        Order::STATUS_DELIVERED => 'Selesai',
        Order::STATUS_CANCELLED => 'Dibatalkan',
        Order::STATUS_CANCELLED_BY_SYSTEM => 'Dibatalkan oleh Sistem',
        Order::STATUS_CANCELLED_BY_ADMIN => 'Dibatalkan oleh Admin',

        Order::STATUS_NEGOTIATION_PENDING => 'Menunggu Persetujuan Negosiasi',
        Order::STATUS_NEGOTIATION_IN_PROGRESS => 'Negosiasi Sedang Berlangsung',
        Order::STATUS_NEGOTIATION_REJECTED => 'Negosiasi Ditolak',
    ];

    $statusBgClasses = [
        Order::STATUS_WAITING_APPROVAL => 'bg-warning text-dark',
        Order::STATUS_APPROVED => 'bg-primary text-white',
        Order::STATUS_PENDING_PAYMENT => 'bg-success text-white',
        Order::STATUS_CONFIRMED => 'bg-success text-white',
        Order::STATUS_PROCESSING => 'bg-info text-white',
        Order::STATUS_SHIPPED => 'bg-primary text-white',
        Order::STATUS_DELIVERED => 'bg-success text-white',
        Order::STATUS_CANCELLED => 'bg-danger text-white',
        Order::STATUS_CANCELLED_BY_SYSTEM => 'bg-secondary text-white',
        Order::STATUS_CANCELLED_BY_ADMIN => 'bg-danger text-white',

        Order::STATUS_NEGOTIATION_PENDING => 'bg-warning text-dark',
        Order::STATUS_NEGOTIATION_IN_PROGRESS => 'bg-info text-white',
        Order::STATUS_NEGOTIATION_REJECTED => 'bg-danger text-white',
    ];
@endphp


    <!-- Notification for Error Messages -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

<div class="container py-5">
    <!-- Judul dan Tombol Aksi -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Detail Pesanan - ID Pesanan: {{ $order->id }}</h1>
    
        <!-- Aksi untuk Admin -->
        <div class="d-flex align-items-center gap-2">
            @if($order->status === Order::STATUS_WAITING_APPROVAL || $order->status === Order::STATUS_NEGOTIATION_REJECTED)
            <!-- Display content for when the order is either waiting approval or negotiation is rejected -->
            <form action="{{ route('admin.orders.approve', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-success btn-sm">Setujui Pesanan</button>
            </form>
        @endif

            @if($order->status === Order::STATUS_APPROVED)
            <form action="{{ route('customer.orders.payment', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-success btn-sm">Berikan Akses Pembayaran</button>
            </form>
            @endif
    
            @if($order->status === 'confirmed')
                <form action="{{ route('admin.mark.packing', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-warning btn-sm">Tandai Sebagai Dikemas</button>
                </form>
            @endif
    
            @if($order->status === 'processing')
                <!-- Tombol untuk modal pengiriman -->
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#shippingModal">
                    Tandai Sebagai Dikirim
                </button>
            @endif

            @if($order->status === 'delivered')
                <!-- Tombol untuk modal pengiriman -->
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
            @endif

            @if($order->negotiation_status === Order::STATUS_NEGOTIATION_PENDING)
                <form action="{{ route('admin.orders.approveNegotiation', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success btn-sm">Setujui Negosiasi</button>
                </form>
                <form action="{{ route('admin.orders.rejectNegotiation', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger btn-sm">Tolak Negosiasi</button>
                </form>
            @endif

            @if($order->negotiation_status === Order::STATUS_NEGOTIATION_APPROVED)
            <form action="{{ route('admin.orders.rejectNegotiation', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-danger btn-sm">Tolak Negosiasi</button>
            </form>
                
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#finalizeNegotiationModal">
                    Finalisasi Negosiasi
            </button>
            @endif

        </div>
    </div>
    

    @if($order->status === 'pending_payment')
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>Menunggu customer melakukan pembayaran</strong> dan menunggu persetujuan admin.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <!-- Bagian Informasi Pesanan -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Informasi Pesanan</h5>
            
            @if(!in_array($order->status, ['cancelled', 'cancelled_by_system', 'cancelled_by_admin', 'delivered']))
            <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="mb-0">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-danger btn-sm">Batalkan Pesanan</button>
            </form>
        @endif
        
        </div>
        
        <div class="card-body">
            <table class="table table-striped">
                <tr>
                    <th>Nama Pelanggan</th>
                    <td>{{ $order->user->full_name ?? $order->user->name }}</td>
                </tr>
                <tr>
                    <th>Nomor Invoice</th>
                    <td><a href="{{ route('order.generate_pdf', $order->id) }}" target="_blank">{{ $order->invoice_number }}</a></td>
                </tr>
                <tr>
                    <th>Total Pembayaran</th>
                    <td>Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
                @if($order->negotiation_total)
                <tr>
                    <th>Total Pembayaran Akhir</th>
                    <td>
                        Rp{{ number_format($order->negotiation_total, 0, ',', '.') }}
                        <span style="color: rgb(255, 0, 0);">
                            ({{ round((-($order->total - $order->negotiation_total) / $order->total) * 100, 2) }}%)
                        </span>
                    </td>
                </tr>
                                   
                @endif
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="px-2 py-1 rounded {{ $statusBgClasses[$order->status] ?? 'bg-light text-dark' }}">
                            {{ $statusMap[$order->status] ?? ucfirst($order->status) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Bagian Item Pesanan -->
    <div class="card mb-4">
        <div class="card-header">
            Item Pesanan
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kuantitas</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bagian Alamat Pelanggan (Hanya tampil jika status adalah 'packing') -->
    @if($order->status === 'packing')
    <div class="card mb-4">
        <div class="card-header">
            Alamat Pelanggan
        </div>
        <div class="card-body">
            @if($order->user)
                @php
                    $activeAddress = $order->user->addresses->where('is_active', 1)->first();
                @endphp

                @if($activeAddress)
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Label Alamat</strong></td>
                                <td>{{ $activeAddress->label_alamat }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Penerima</strong></td>
                                <td>{{ $activeAddress->nama_penerima }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nomor Telepon</strong></td>
                                <td>{{ $activeAddress->nomor_telepon }}</td>
                            </tr>
                            <tr>
                                <td><strong>Provinsi</strong></td>
                                <td>{{ $activeAddress->provinsi }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kota/Kabupaten</strong></td>
                                <td>{{ $activeAddress->kota_kabupaten }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kodepos</strong></td>
                                <td>{{ $activeAddress->kodepos }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kecamatan</strong></td>
                                <td>{{ $activeAddress->kecamatan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Detail Alamat</strong></td>
                                <td>{{ $activeAddress->detail_alamat }}</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <p>Tidak ada alamat aktif untuk pelanggan ini.</p>
                @endif
            @else
                <p>Informasi pelanggan tidak ditemukan untuk pesanan ini.</p>
            @endif
        </div>
    </div>
    @endif

    <!-- Modal Pengiriman -->
    <div class="modal fade" id="shippingModal" tabindex="-1" aria-labelledby="shippingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shippingModalLabel">Tandai Pesanan Sebagai Dikirim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.orders.shipped', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Input Nomor Pelacakan -->
                        <div class="mb-3">
                            <label for="tracking_number" class="form-label">Nomor Pelacakan</label>
                            <input type="text" class="form-control" name="tracking_number" required>
                        </div>

                        <!-- Pilihan Layanan Pengiriman -->
                        <div class="mb-3">
                            <label for="shipping_service_id" class="form-label">Layanan Pengiriman</label>
                            <select class="form-select" name="shipping_service_id" required>
                                <option value="" disabled selected>Pilih layanan pengiriman</option>
                                @foreach($shipping as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="btn btn-primary">Tandai Sebagai Dikirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="finalizeNegotiationModal" tabindex="-1" aria-labelledby="finalizeNegotiationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="finalizeNegotiationModalLabel">Finalisasi Negosiasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.orders.finalizeNegotiation', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Input for negotiated total -->
                        <div class="mb-3">
                            <label for="negotiated_total" class="form-label">Total Negosiasi</label>
                            <input type="number" class="form-control" name="negotiated_total" required min="0" step="0.01" value="{{ $order->total }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Finalisasi Negosiasi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
