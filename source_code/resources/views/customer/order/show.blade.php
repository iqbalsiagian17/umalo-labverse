@extends('layouts.customer.master')
@section('content')
    <div class="container">
        <h1>Detail Pesanan</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h3>Pesanan ID: {{ $order->id }}</h3>
        <p>Status: {{ $order->status }}</p>
        <p>Total Harga: {{ $order->harga_total }}</p>

        <h4>Item Pesanan:</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->Product->nama }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->harga }}</td>
                        <td>{{ $item->harga * $item->jumlah }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($order->status == 'Diterima')
        <a href="{{ route('order.generate_pdf', $order->id) }}" class="btn btn-primary mt-3">Download Invoice</a>
        @endif

        <div class="mt-4">
            <a href="{{ route('cart.view') }}" class="btn btn-secondary">Back</a>
        </div>
        <div class="mt-4">
            <a href="{{ route('order.transaction_history', $order->id) }}" class="btn btn-info">Lihat Riwayat Transaksi</a>
        </div>


    </div>
@endsection
