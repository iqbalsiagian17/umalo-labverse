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
                <table class="table table-bordered">
                    <thead class="text-white" style="background-color: #416bbf;">
                        <tr>
                            <th>{{ __('messages.product') }}</th>
                            <th class="text-center">{{ __('messages.quantity') }}</th>
                            <th class="text-right">{{ __('messages.price') }}</th>
                            <th class="text-right">{{ __('messages.total_price') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}. {{ $item->produk->nama }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-right">{{ 'Rp ' . number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="text-right">{{ 'Rp ' . number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
            
                        <!-- Subtotal before negotiation -->
                        <tr>
                            <td colspan="3" class="text-right"><strong>{{ __('messages.subtotal_before_negotiation') }}</strong></td>
                            <td class="text-right">
                                <strong>{{ 'Rp ' . number_format($order->orderItems->sum(function($item) {
                                    return $item->harga * $item->jumlah;
                                }), 0, ',', '.') }}</strong>
                            </td>
                        </tr>
            
                        @if($order->harga_setelah_nego)
                        <!-- Harga Setelah Nego -->
                        <tr>
                            <td colspan="3" class="text-right"><strong>{{ __('messages.negotiated_price') }}</strong></td>
                            <td class="text-right">
                                <strong>{{ 'Rp ' . number_format($order->harga_setelah_nego, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                        @endif
            
                        <!-- Total -->
                        <tr>
                            <td colspan="3" class="text-right"><strong>{{ __('messages.total') }}</strong></td>
                            <td class="text-right">
                                <strong>{{ 'Rp ' . number_format($order->harga_setelah_nego ? $order->harga_setelah_nego : $order->orderItems->sum(function($item) {
                                    return $item->harga * $item->jumlah;
                                }), 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                    </tbody>
                   
                </table>
                <p class="text-muted small">
                    <span class="text-danger">*</span>Harga yang tertera belum termasuk pajak PPN. Untuk mengetahui total transaksi yang sebenarnya, silakan melihat pada faktur (invoice) yang dapat diunduh.
                </p>
            </div>
            
            
      
            <div class="mt-4">
                @if($order->orderItems->contains(function($item) { return $item->produk->nego == 'ya'; }))
                    @if($order->status == 'Negosiasi' && $order->whatsapp_number)
                    <div class="alert alert-info">
                        <strong>{{ __('messages.whatsapp_number_for_negotiation') }}:</strong> {{ $order->whatsapp_number }}<br>
                        {{ __('messages.or_you_can_contact_admin') }} </a>.
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
                    <div class="card mt-4 shadow-sm">
                        <div class="card-header text-white" style="background-color: #416bbf;">
                            <h5 class="mb-0">{{ __('messages.upload_payment_proof') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('order.upload_bukti_pembayaran', $order->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="bukti_pembayaran" class="form-label">{{ __('messages.select_payment_proof_file') }}</label>
                                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" required>
                                    @if ($errors->has('bukti_pembayaran'))
                                        <small class="text-danger">{{ $errors->first('bukti_pembayaran') }}</small>
                                    @endif
                                </div>
                                <div class="alert alert-warning mt-3 d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle" style="margin-right: 1.5rem;"></i>
                                    <p class="mb-0">
                                        Untuk mengetahui jumlah pembayaran yang harus Anda lakukan, silakan lihat pada faktur yang dapat diunduh melalui tombol 'Download Invoice' di bagian atas halaman ini. Terima kasih atas perhatian Anda.
                                    </p>
                                </div>
                                
                                <button type="submit" class="btn mt-2 w-100 text-white" style="background-color: #416bbf;">{{ __('messages.upload') }}</button>
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
                    <li class="timeline-item {{ $loop->first ? 'timeline-item-first' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <span class="timeline-date">{{ $history->created_at->format('d-m-Y H:i') }}</span>
                            <h5 class="timeline-status">{{ __('messages.' . strtolower(str_replace(' ', '_', $history->status))) }}</h5>
                            @if($history->description)
                                <p class="timeline-desc">{{ $history->description }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<style>
    /* Timeline container */
    .timeline {
        list-style-type: none;
        position: relative;
        padding-left: 40px;
        margin: 0;
    }
    
    /* Timeline item */
    .timeline-item {
        margin-bottom: 20px;
        position: relative;
        padding-left: 25px;
    }
    
    /* Marker on the timeline */
    .timeline-marker {
        position: absolute;
        left: 0;
        width: 15px;
        height: 15px;
        background-color: #e0e0e0;
        border-radius: 50%;
        top: 5px;
        border: 3px solid #ffffff;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }
    
    /* First marker (slightly larger) */
    .timeline-item-first .timeline-marker {
        width: 18px;
        height: 18px;
        top: 4px;
    }
    
    /* Content next to the marker */
    .timeline-content {
        padding-left: 40px;
    }
    
    /* Date */
    .timeline-date {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
        color: #999999;
    }
    
    /* Status */
    .timeline-status {
        font-size: 16px;
        font-weight: 500;
        color: #333333;
    }
    
    /* Description */
    .timeline-desc {
        margin-top: 5px;
        font-size: 14px;
        color: #666666;
    }
    
    /* Vertical line connecting the timeline markers */
    .timeline::before {
        content: '';
        background-color: #e0e0e0;
        width: 2px;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 7px;
    }
    
    /* Hover effects */
    .timeline-item:hover .timeline-marker {
        background-color: #999999;
    }
    
    .timeline-item:hover .timeline-status {
        color: #000000;
    }
    
    .timeline-item:hover .timeline-date {
        color: #666666;
    }
    </style>
    
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
