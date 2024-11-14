@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h2>Edit Transaksi ID: {{ $order->id }}</h2>
            </div>
            <div class="card-body">
                <form id="statusForm" action="{{ route('transaksi.updateEdit', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="user_id">User ID</label>
                        <input type="text" name="user_id" id="user_id" class="form-control" value="{{ $order->user->id }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="harga_total">Harga Total</label>
                        <input type="number" name="harga_total" id="harga_total" class="form-control" value="{{ $order->harga_total }}" step="0.01">
                        @if ($errors->has('harga_total'))
                            <small class="text-danger">{{ $errors->first('harga_total') }}</small>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="Menunggu ACC Admin" {{ $order->status == 'Menunggu ACC Admin' ? 'selected' : '' }}>Menunggu ACC Admin</option>
                            <option value="Menunggu ACC Admin untuk Negosiasi" {{ $order->status == 'Menunggu ACC Admin untuk Negosiasi' ? 'selected' : '' }}>Menunggu ACC Admin untuk Negosiasi</option>
                            <option value="Negosiasi" {{ $order->status == 'Negosiasi' ? 'selected' : '' }}>Negosiasi</option>
                            <option value="Diterima" {{ $order->status == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="Packing" {{ $order->status == 'Packing' ? 'selected' : '' }}>Packing</option>
                            <option value="Pengiriman" {{ $order->status == 'Pengiriman' ? 'selected' : '' }}>Pengiriman</option>
                            <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @if ($errors->has('status'))
                            <small class="text-danger">{{ $errors->first('status') }}</small>
                        @endif
                    </div>

                    <!-- Tracking Number Input -->
                    <div class="form-group" id="tracking-number-group" style="display: none;">
                        <label for="nomor_resi">Nomor Resi (Tracking Number)</label>
                        <input type="text" name="nomor_resi" id="nomor_resi" class="form-control" value="{{ old('nomor_resi', $order->nomor_resi) }}">
                        @if ($errors->has('nomor_resi'))
                            <small class="text-danger">{{ $errors->first('nomor_resi') }}</small>
                        @endif
                    </div>

                    <!-- Input WhatsApp Number -->
                    <div class="form-group" id="whatsapp-number-group" style="display: none;">
                        <label for="whatsapp_number">Nomor WhatsApp</label>
                        <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $order->whatsapp_number) }}">
                        @if ($errors->has('whatsapp_number'))
                            <small class="text-danger">{{ $errors->first('whatsapp_number') }}</small>
                        @endif
                    </div>

                    <div class="form-group" id="subtotalGroup" style="display: none;">
                        <label for="subtotal">Ubah Harga Total Setelah Negosiasi</label>
                        <input type="number" name="subtotal" id="subtotal" class="form-control" value="{{ $order->harga_setelah_nego ?? $order->harga_total }}">
                    </div>

                    <button type="submit" class="btn btn-success">Update Transaksi</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h2>Item Transaksi</h2>
            </div>
            <div class="card-body">
                @if($order->orderItems && $order->orderItems->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Nama Product</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->Product_id }}</td>
                                    <td>{{ $item->Product->nama }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ 'Rp ' . number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>
                                        @if($order->harga_setelah_nego)
                                            <span style="text-decoration: line-through;">Rp {{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}</span><br>
                                            <span>Rp {{ number_format($item->jumlah * ($order->harga_setelah_nego / $order->orderItems->sum('jumlah')), 0, ',', '.') }}</span>
                                        @else
                                            Rp {{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No Transaction Items Available</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bukti Pembayaran -->
@if($order->bukti_pembayaran)
<div class="card mt-4">
    <div class="card-header">
        <h5>Bukti Pembayaran</h5>
    </div>
    <div class="card-body">
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#buktiPembayaranModal">
            Lihat Bukti Pembayaran
        </button>

        <div class="modal fade" id="buktiPembayaranModal" tabindex="-1" aria-labelledby="buktiPembayaranModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="buktiPembayaranModalLabel">Bukti Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ asset('uploads/bukti_pembayaran/' . $order->bukti_pembayaran) }}" class="img-fluid" alt="Bukti Pembayaran" style="max-width: 100%;">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const trackingNumberGroup = document.getElementById('tracking-number-group');
        const whatsappNumberGroup = document.getElementById('whatsapp-number-group');
        const subtotalGroup = document.getElementById('subtotalGroup');

        function toggleFields() {
            if (statusSelect.value === 'Negosiasi') {
                whatsappNumberGroup.style.display = 'block';
                subtotalGroup.style.display = 'block';
            } else {
                whatsappNumberGroup.style.display = 'none';
                subtotalGroup.style.display = 'none';
            }

            if (statusSelect.value === 'Pengiriman') {
                trackingNumberGroup.style.display = 'block';
            } else {
                trackingNumberGroup.style.display = 'none';
            }
        }

        // Initial check to display correct fields
        toggleFields();

        // Listen for changes on the status select field
        statusSelect.addEventListener('change', toggleFields);
    });
</script>
@endsection
