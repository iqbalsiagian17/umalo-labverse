@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h2>Edit Transaksi ID: {{ $order->id }}</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('transaksi.update', $order->id) }}" method="POST">
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

                    <!-- Input WhatsApp Number -->
                    <div class="form-group" id="whatsapp-number-group" style="display: none;">
                        <label for="whatsapp_number">Nomor WhatsApp</label>
                        <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $order->whatsapp_number) }}">
                        @if ($errors->has('whatsapp_number'))
                            <small class="text-danger">{{ $errors->first('whatsapp_number') }}</small>
                        @endif
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
                                <th>Produk ID</th>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->produk_id }}</td>
                                    <td>{{ $item->produk->nama }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ 'Rp ' . number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>{{ 'Rp ' . number_format($item->jumlah * $item->harga, 0, ',', '.') }}</td>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const whatsappNumberGroup = document.getElementById('whatsapp-number-group');

        function toggleWhatsAppField() {
            if (statusSelect.value === 'Negosiasi') {
                whatsappNumberGroup.style.display = 'block';
            } else {
                whatsappNumberGroup.style.display = 'none';
            }
        }

        // Initial check in case the status is already set to Negosiasi
        toggleWhatsAppField();

        // Listen for changes on the status select field
        statusSelect.addEventListener('change', toggleWhatsAppField);
    });
</script>
@endsection
