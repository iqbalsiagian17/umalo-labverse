@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Big Sale Details</h2>
                <a href="{{ route('admin.bigsales.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
            <div class="card-body">

                <!-- Title -->
                <div class="mb-4">
                    <h5 class="text-uppercase text-muted">Title</h5>
                    <h4>{{ $bigSale->title }}</h4>
                </div>

                <!-- Banner -->
                <div class="mb-4">
                    <h5 class="text-uppercase text-muted">Banner</h5>
                    @if($bigSale->banner)
                        <div class="mb-3">
                            <img src="{{ asset($bigSale->banner) }}" alt="Banner" class="img-fluid rounded" style="max-width: 300px; max-height: 200px;">
                        </div>
                    @else
                        <p class="text-muted fst-italic">No banner uploaded</p>
                    @endif
                </div>

                <!-- Modal Image -->
                <div class="mb-4">
                    <h5 class="text-uppercase text-muted">Modal Image</h5>
                    @if($bigSale->modal_image)
                        <div class="mb-3">
                            <img src="{{ asset($bigSale->modal_image) }}" alt="Modal Image" class="img-fluid rounded" style="max-width: 300px; max-height: 200px;">
                        </div>
                    @else
                        <p class="text-muted fst-italic">No modal image uploaded</p>
                    @endif
                </div>

                <!-- Start and End Time -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h5 class="text-uppercase text-muted">Start Time</h5>
                        <p><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($bigSale->start_time)->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h5 class="text-uppercase text-muted">End Time</h5>
                        <p><i class="fas fa-calendar-check"></i> {{ \Carbon\Carbon::parse($bigSale->end_time)->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <!-- Discount Amount and Percentage -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h5 class="text-uppercase text-muted">Discount Amount</h5>
                        <p>{{ $bigSale->discount_amount ? 'Rp' . number_format($bigSale->discount_amount, 2) : '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h5 class="text-uppercase text-muted">Discount Percentage</h5>
                        <p>{{ $bigSale->discount_percentage ? $bigSale->discount_percentage . '%' : '-' }}</p>
                    </div>
                </div>

                <!-- Selected Products -->
                <div class="mb-4">
                    <h5 class="text-uppercase text-muted">Selected Products</h5>
                    @if($bigSale->products->isNotEmpty())
                        <ul class="list-group">
                            @foreach($bigSale->products as $product)
                                <li class="list-group-item">
                                    <i class="fas fa-box-open"></i> {{ $product->name }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted fst-italic">No products selected for this Big Sale</p>
                    @endif
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <h5 class="text-uppercase text-muted">Status</h5>
                    <span class="badge {{ $bigSale->status ? 'bg-success' : 'bg-secondary' }}">
                        {{ $bigSale->status ? 'Active' : 'Non-Active' }}
                    </span>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
