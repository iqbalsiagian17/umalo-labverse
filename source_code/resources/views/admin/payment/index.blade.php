@extends('layouts.admin.master')

@section('content')

@php
    use App\Models\Payment;

    $paymentStatusMap = [
        Payment::STATUS_UNPAID => 'Belum Dibayar',
        Payment::STATUS_PENDING => 'Menunggu Konfirmasi',
        Payment::STATUS_PAID => 'Dibayar',
        Payment::STATUS_FAILED => 'Gagal',
        Payment::STATUS_REFUNDED => 'Dikembalikan',
        Payment::STATUS_PARTIALLY_REFUNDED => 'Dikembalikan Sebagian',
    ];
@endphp


<div class="container py-5">

    <!-- Kartu untuk Manajemen Pembayaran -->
    <div class="card">
        <!-- Header Kartu -->
        <div class="card-header">
            <h1 class="mb-4">Pembayaran</h1>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Bagian Pencarian dan Filter -->
            <form action="{{ route('admin.payments.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <!-- Pencarian berdasarkan nama pelanggan -->
                    <div class="col-md-3">
                        <input type="text" name="name" class="form-control" placeholder="Cari berdasarkan nama" value="{{ request()->name }}">
                    </div>

                    <!-- Pencarian berdasarkan nomor faktur -->
                    <div class="col-md-3">
                        <input type="text" name="invoice_number" class="form-control" placeholder="Cari berdasarkan nomor faktur" value="{{ request()->invoice_number }}">
                    </div>

                    <!-- Filter berdasarkan status -->
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="all" {{ request()->status == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="{{ Payment::STATUS_PENDING }}" {{ request()->status == Payment::STATUS_PENDING ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                            <option value="{{ Payment::STATUS_PAID }}" {{ request()->status == Payment::STATUS_PAID ? 'selected' : '' }}>Dibayar</option>
                            <option value="{{ Payment::STATUS_FAILED }}" {{ request()->status == Payment::STATUS_FAILED ? 'selected' : '' }}>Gagal</option>
                            <option value="{{ Payment::STATUS_REFUNDED }}" {{ request()->status == Payment::STATUS_REFUNDED ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="{{ Payment::STATUS_PARTIALLY_REFUNDED }}" {{ request()->status == Payment::STATUS_PARTIALLY_REFUNDED ? 'selected' : '' }}>Dikembalikan Sebagian</option>
                        </select>
                    </div>


                    <!-- Tombol Cari dan Reset -->
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search p-1"></i> 
                        </button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-sync-alt p-1"></i> 
                        </a>
                    </div>
                </div>
            </form>

            <!-- Isi Kartu -->
            @if($payments->isEmpty())
                <!-- Notifikasi jika tidak ada hasil -->
                <div class="alert alert-warning text-center" role="alert">
                    <strong>Tidak ada pembayaran ditemukan</strong> yang sesuai dengan kriteria pencarian atau filter Anda.
                </div>
            @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Pembayaran</th>
                        <th>ID Pesanan</th>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', ['id' => $payment->order_id]) }}">
                                {{ $payment->order_id }}
                            </a>
                        </td>
                        <td>{{ $payment->order->invoice_number }} 
                        </span>
                        @if (!$payment->is_viewed_by_admin)
                            <span class="badge bg-danger ms-2">Baru</span>
                        @endif
                        </td>
                        <td>{{ optional($payment->order->user)->full_name ?? optional($payment->order->user)->name }}</td>
                        <td>{{ $paymentStatusMap[$payment->status] ?? 'Status Tidak Diketahui' }}</td>
                        <td>
                            <!-- Tombol untuk membuka modal -->
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal-{{ $payment->id }}">Lihat Detail</button>
                        </td>
                    </tr>

                    <!-- Modal untuk Detail Pembayaran -->
                    <div class="modal fade" id="paymentModal-{{ $payment->id }}" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="paymentModalLabel">Detail Pembayaran - ID: {{ $payment->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h6><strong>ID Pesanan:</strong> {{ $payment->order_id }}</h6>
                                    <h6><strong>Pelanggan:</strong> {{ optional($payment->order->user)->full_name ?? optional($payment->order->user)->name }}</h6>
                                    <h6><strong>Status:</strong> {{ $paymentStatusMap[$payment->status] ?? 'Status Tidak Diketahui' }}</h6>
                                    <h6><strong>Bukti Pembayaran:</strong></h6>
                                    @if($payment->payment_proof)
                                        <div class="mb-3">
                                            @if(in_array(pathinfo($payment->payment_proof, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                                <img src="{{ asset($payment->payment_proof) }}" alt="Bukti Pembayaran" class="img-fluid mb-2" style="max-width: 100%; height: auto; border-radius: 5px;">
                                            @else
                                                <a href="{{ asset($payment->payment_proof) }}" target="_blank" class="btn btn-primary">Lihat Bukti</a>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">Tidak ada bukti yang diunggah</span>
                                    @endif
                                    <hr>
                                    @if($payment->status === 'pending')
                                        <div class="d-flex justify-content-between">
                                            <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success">Verifikasi Pembayaran</button>
                                            </form>
                                            <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak pembayaran ini?')">Tolak</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        <div class="pagination justify-content-center">
            {{ $payments->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
