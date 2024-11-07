@extends('layouts.customer.master')
@section('content')
    <div class="container">
        <h1>Riwayat Transaksi untuk Pesanan ID: {{ $order->id }}</h1>

        @if($order->statusHistories->isEmpty())
            <p>Tidak ada riwayat transaksi untuk pesanan ini.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Tanggal dan Waktu</th>
                        <th>Nomor Resi (Jika ada)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->statusHistories as $history)
                        <tr>
                            <td>{{ $history->status }}</td>
                            <td>{{ $history->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                @if($history->status == 'Pengiriman' && $history->extra_info)
                                    {{ $history->extra_info }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        

        <div class="mt-4">
            <a href="{{ route('order.detail', $order->id) }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
