@extends('layouts.admin.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h2>Edit Shipping Service</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.masterdata.shippingservice.update', $shippingService->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $shippingService->name) }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="image">Upload New Image:</label>
                            <input type="file" name="images" class="form-control">
                            @error('images')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            @if ($shippingService->images)
                                <div class="mt-3">
                                    <p>Current Image:</p>
                                    <img src="{{ asset($shippingService->images) }}" alt="Current Image" style="max-width: 200px;">
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.masterdata.shippingservice.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
