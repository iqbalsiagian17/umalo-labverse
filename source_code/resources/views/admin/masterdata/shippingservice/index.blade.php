@extends('layouts.admin.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">
                    <h1>Shipping Services</h1>
                </div>
                <div class="d-flex align-items-center">
                    <!-- Search Form on the Right -->
                    <form action="{{ route('admin.masterdata.shippingservice.index') }}" method="GET" class="d-flex me-3">
                        <input type="text" name="search" class="form-control me-2" style="width: 300px;" placeholder="Cari Nama Shipping Service" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary me-2">Cari</button>
                        <a href="{{ route('admin.masterdata.shippingservice.index') }}" class="btn btn-secondary">Refresh</a>
                    </form>
                    <!-- Button to Add Shipping Service -->
                    <a href="{{ route('admin.masterdata.shippingservice.create') }}" class="btn btn-primary">Add Shipping Service</a>
                </div>
            </div>

            @if (session('success'))
            <div class="alert alert-success mt-2">
                {{ session('success') }}
            </div>
            @endif

            <div class="card-body">
                <div class="row">
                    @forelse ($shippingServices as $index => $service)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $service->name }}</h5>
                            </div>
                            <div class="card-body">
                                @if ($service->images)
                                    <img src="{{ asset($service->images) }}" alt="{{ $service->name }}" class="img-fluid mb-2" style="max-height: 150px; border-radius: 5px;">
                                @else
                                    <p>No Image Available</p>
                                @endif
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <a href="{{ route('admin.masterdata.shippingservice.edit', $service->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.masterdata.shippingservice.destroy', $service->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this service?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center">
                        <p>No Shipping Services found.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $shippingServices->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
