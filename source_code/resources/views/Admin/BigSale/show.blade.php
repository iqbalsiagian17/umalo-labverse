@extends('layouts.admin.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1 class="mb-0">Big Sale</h1>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Judul:</strong></p>
                    <p class="text-muted">{{ $bigSale->judul }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Mulai:</strong></p>
                    <p class="text-muted">{{ \Carbon\Carbon::parse($bigSale->mulai)->format('d M Y H:i') }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Berakhir:</strong></p>
                    <p class="text-muted">{{ \Carbon\Carbon::parse($bigSale->berakhir)->format('d M Y H:i') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong></p>
                    <span class="badge {{ $bigSale->status == 'aktif' ? 'badge-success' : 'badge-danger' }}">
                        {{ $bigSale->status == 'aktif' ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>

            <hr>

            <h2 class="mb-4">Produk</h2>
            @if($bigSale->produk->isEmpty())
                <p class="text-muted">Tidak ada produk yang terdaftar dalam Big Sale ini.</p>
            @else
                <div class="row">
                    @foreach($bigSale->produk as $product)
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <img src="{{ asset($product->images->first()->gambar ?? 'path/to/default/image.jpg') }}" class="card-img-top" alt="{{ $product->nama }}" style="height: 150px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->nama }}</h5>
                                    <p class="card-text">Harga Diskon: <strong>Rp{{ number_format($product->pivot->harga_diskon, 0, ',', '.') }}</strong></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <a href="{{ route('bigsale.index') }}" class="btn btn-secondary mt-4">Back</a>
        </div>
    </div>
@endsection
