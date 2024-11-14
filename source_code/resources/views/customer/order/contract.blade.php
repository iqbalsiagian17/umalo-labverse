@extends('layouts.customer.master')
@section('content')
    <div class="container">
        <h1>Kontrak Pesanan</h1>

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

        <div class="mt-4">
            <a href="{{ route('order.show', $order->id) }}" class="btn btn-secondary">Back to Order</a>
            <button onclick="window.print();" class="btn btn-primary">Print Invoice</button>
        </div>
    </div>
@endsection
