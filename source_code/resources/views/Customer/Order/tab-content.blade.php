@if($orders->isEmpty())
<div class="card mb-3 mt-4 shadow rounded border-0 h-100">
    <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 300px;">
        <h5 class="mb-1">Belum ada Pesanan</h5>
        <a href="/shop" class="btn text-white mt-3" style="background-color: #42378C;">Belanja Sekarang</a>
    </div>
</div>


@else
@foreach($orders as $order)
    <div class="card mb-3 mt-4 shadow rounded border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1"><strong>{{ __('messages.order_no') }}:</strong> {{ $order->id }}</h5>
                    <p class="mb-1"><strong>{{ __('messages.order_date') }}:</strong> {{ $order->created_at->format('d M Y') }}</p>
                    <p class="mb-1"><strong>{{ __('messages.status') }}:</strong> {{ $order->status }}</p>
                </div>
                <div>
                    <a href="{{ route('order.detail', $order->id) }}" class="btn btn-sm text-white" style="background-color: #42378C;">{{ __('messages.view_detail') }}</a>
                    @if(in_array($order->status, ['Diterima', 'Selesai']))
                    <a href="{{ route('order.generate_pdf', $order->id) }}" class="btn btn-success btn-sm">{{ __('messages.download_invoice') }}</a>
                    @endif                
                </div>
            </div>
            
            @foreach($order->orderItems as $item)
                <div class="d-flex mb-3">
                    <div style="flex: 1;">
                        @if($item->produk->images->isNotEmpty())
                            <img src="{{ asset('/umalo/umalo-labverse/source_code/public/' .$item->produk->images->first()->gambar) }}" alt="{{ $item->produk->nama }}" style="width: 250px;" class="rounded">
                        @else
                            <img src="https://via.placeholder.com/150" class="img-fluid mb-2" alt="{{ $item->produk->nama }}" style="width: 250px;" class="rounded">
                        @endif
                    </div>
                    <div style="flex: 3; margin-left: 20px;">
                        <h6 class="mb-1"><strong>{{ $item->produk->nama }}</strong></h6>
                        <p class="mb-1"><strong>x{{ $item->jumlah }}</strong></p>
                    </div>
                    <div style="flex: 2; text-align: right;">
                        <p class="mb-1"><strong>Rp{{ number_format($item->harga, 0, ',', '.') }}</strong></p>
                    </div>
                </div>
            @endforeach


            <hr>
            <div class="d-flex justify-content-between">
                <div>
                    @if($order->status == 'Pengiriman' && $order->nomor_resi)
                    <p class="mt-2"><strong>{{ __('messages.tracking_number') }}:</strong> {{ $order->nomor_resi }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-danger"><strong>{{ __('messages.total_order') }}: Rp{{ number_format($order->harga_total, 0, ',', '.') }}</strong></p>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endif