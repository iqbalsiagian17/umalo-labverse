@extends('layouts.customer.master')

@section('content')
<div class="container mt-5 mb-3">
    <div class="card shadow rounded border-0">
        <div class="card-header rounded border-0">
            <h2 class="mb-0">{{ __('messages.order_details') }}</h2>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
</div>

<div class="container mb-5">
    <div class="card rounded border-0">
        <div class="card-body shadow">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="mb-3">{{ __('messages.order_number') }}: <strong>{{ $order->id }}</strong></h4>
                    <p><strong>{{ __('messages.status') }}:</strong> <span class="badge bg-info text-dark">{{ $order->status }}</span></p>
                    
                    <p>
                        <strong>{{ __('messages.total_price') }}:</strong>
                        @if($order->orderItems->contains(function($item) { return $item->produk->nego == 'ya'; }) && $order->harga_setelah_nego && $order->harga_setelah_nego != $order->harga_total)
                            <!-- Display the original total price with a strikethrough only if harga_setelah_nego is set and different from harga_total -->
                            <span class="text-danger" style="text-decoration: line-through;">
                                {{ 'Rp ' . number_format($order->harga_total, 0, ',', '.') }}
                            </span>
                            <!-- Display the new negotiated price next to it -->
                            <span class="text-success">
                                {{ 'Rp ' . number_format($order->harga_setelah_nego, 0, ',', '.') }}
                            </span>
                        @else
                            <!-- If harga_setelah_nego is not set or is the same as harga_total, just display the total price -->
                            <span class="text-success">
                                {{ 'Rp ' . number_format($order->harga_total, 0, ',', '.') }}
                            </span>
                        @endif
                    </p>
                </div>
                
                
                
                <div class="col-md-6 text-md-right">
                    <a href="{{ route('order.history') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_order_history') }}
                    </a>
                    @if(in_array($order->status, ['Diterima', 'Selesai']))
                        <a href="{{ route('order.generate_pdf', $order->id) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-download"></i> {{ __('messages.download_invoice') }}
                        </a>
                    @endif
                </div>
            </div>

            <h4 class="mt-4">{{ __('messages.order_items') }}:</h4>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>{{ __('messages.product') }}</th>
                            <th class="text-center">{{ __('messages.quantity') }}</th>
                            <th class="text-right">{{ __('messages.price') }}</th>
                            <!-- Conditionally display the negotiated price column if any product is negotiable -->
                            @if($order->orderItems->contains(function($item) { return $item->produk->nego == 'ya'; }))
                                <th class="text-right">{{ __('messages.negotiated_price') }}</th>
                            @endif
                            <th class="text-right">{{ __('messages.subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->produk->nama }}</td>
                                <td class="text-center">{{ $item->jumlah }}</td>
                                <!-- Display original price; strikethrough only if negotiated price exists -->
                                <td class="text-right">
                                    @if($item->produk->nego == 'ya' && $order->harga_setelah_nego)
                                        <span class="text-danger" style="text-decoration: line-through;">
                                            {{ 'Rp ' . number_format($item->harga, 0, ',', '.') }}
                                        </span>
                                    @else
                                        {{ 'Rp ' . number_format($item->harga, 0, ',', '.') }}
                                    @endif
                                </td>
                                <!-- Display negotiated price only if the product is negotiable and the price has been updated -->
                                @if($item->produk->nego == 'ya' && $order->harga_setelah_nego)
                                    <td class="text-right">{{ 'Rp ' . number_format($order->harga_setelah_nego, 0, ',', '.') }}</td>
                                @endif
                                <!-- Calculate and display the subtotal -->
                                <td class="text-right">
                                    {{ 'Rp ' . number_format(($order->harga_setelah_nego && $item->produk->nego == 'ya') ? $order->harga_setelah_nego * $item->jumlah : $item->harga * $item->jumlah, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="{{ $order->orderItems->contains(function($item) { return $item->produk->nego == 'ya'; }) ? 4 : 3 }}" class="text-right">{{ __('messages.total') }}</th>
                            <th class="text-right">
                                <!-- Display the total using negotiated price if applicable -->
                                {{ 'Rp ' . number_format($order->harga_setelah_nego ?? $order->harga_total, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
      
            <div class="mt-4">
                @if($order->orderItems->contains(function($item) { return $item->produk->nego == 'ya'; }))
                    @if($order->status == 'Negosiasi' && $order->whatsapp_number)
                    <div class="alert alert-info">
                        <strong>{{ __('messages.whatsapp_number_for_negotiation') }}:</strong> {{ $order->whatsapp_number }}<br>
                        {{ __('messages.or_you_can_contact_admin') }} <a href="/chatify">{{ __('messages.here') }}</a>.
                    </div>
                    
                    @endif
                @endif

                @if(in_array($order->status, ['Menunggu Konfirmasi Admin', 'Menunggu Konfirmasi Admin untuk Negosiasi', 'Negosiasi', 'Diterima']))
                    <form action="{{ route('order.cancel', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">{{ __('messages.cancel_order') }}</button>
                    </form>
                @endif

                @if($order->status == 'Pengiriman')
                    <form action="{{ route('order.updateStatus', $order->id) }}" method="POST" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-primary">{{ __('messages.receive_item') }}</button>
                    </form>
                    @if($order->nomor_resi)
                        <div class="mt-3">
                            <strong>{{ __('messages.tracking_number') }}:</strong> {{ $order->nomor_resi }}
                        </div>
                    @endif
                @endif

                @if($order->status == 'Diterima')
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>{{ __('messages.upload_payment_proof') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('order.upload_bukti_pembayaran', $order->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="bukti_pembayaran">{{ __('messages.select_payment_proof_file') }}</label>
                                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" required>
                                    @if ($errors->has('bukti_pembayaran'))
                                        <small class="text-danger">{{ $errors->first('bukti_pembayaran') }}</small>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">{{ __('messages.upload') }}</button>
                            </form>
                        </div>
                    </div>
                @endif

                @if($order->bukti_pembayaran)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>{{ __('messages.payment_proof') }}</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>{{ __('messages.payment_proof_file') }}:</strong></p>
                            <a href="{{ asset('uploads/bukti_pembayaran/' . $order->bukti_pembayaran) }}" target="_blank" class="btn btn-info">{{ __('messages.view_payment_proof') }}</a>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Transaction History -->
            @if($order->statusHistories->isNotEmpty())
            <div class="container">
                <div class="mt-5">
                    <h4>{{ __('messages.transaction_history') }}</h4>
                    <ul class="timeline">
                        @foreach($order->statusHistories as $history)
                            <li class="timeline-item {{ $loop->first ? '' : '' }}">
                                <span class="timeline-date">{{ $history->created_at->format('d-m-Y H:i') }}</span>
                                <span class="timeline-status">{{ __('messages.' . strtolower(str_replace(' ', '_', $history->status))) }}</span>
                                <span class="timeline-desc">{{ $history->description ?? '' }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            <!-- Link to Review Section -->
            @if($order->status == 'Selesai')
                <div class="mt-4 text-center">
                    <a href="{{ route('product.show', $order->orderItems->first()->produk->id) }}#tabs-3" class="btn btn-success">
                        {{ __('messages.review') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
