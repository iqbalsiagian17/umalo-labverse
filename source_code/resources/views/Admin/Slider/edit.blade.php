@extends('layouts.Admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Edit Slider</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('slider.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="image" class="form-label">Image:</label>
                        <input type="file" class="form-control" id="image" name="image">
                        <img src="{{ asset($slider->image) }}" width="100" class="img-fluid img-thumbnail mt-2">
                        @if ($errors->has('image'))
                            <small class="text-danger">{{ $errors->first('image') }}</small>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <label for="deskripsi" class="form-label">Description:</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" required>{{ old('deskripsi', $slider->deskripsi) }}</textarea>
                        @if ($errors->has('deskripsi'))
                            <small class="text-danger">{{ $errors->first('deskripsi') }}</small>
                        @endif
                    </div>
                   
        <div class="form-group">
            <label for="url">Select URL for the Slider:</label>
            <select name="url" class="form-control" id="url">
                <option value="">Select a URL</option>
                
                <!-- Predefined URLs Group -->
                <optgroup label="Halaman General">
                    @foreach($routeOptions as $name => $url)
                        <option value="{{ $url }}" {{ $slider->url == $url ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </optgroup>
        
                <!-- Product URLs Group -->
                <optgroup label="Halaman Detail Product">
                    @foreach($products as $product)
                        <option value="{{ route('Product_customer.user.show', ['id' => $product->id]) }}"
                            {{ $slider->url == route('Product_customer.user.show', ['id' => $product->id]) ? 'selected' : '' }}>
                            Product: {{ $product->nama }}
                        </option>
                    @endforeach
                </optgroup>
            </select>
        </div>
                    <div class="form-group mb-3">
                        <label for="tombol" class="form-label">Button Text:</label>
                        <input type="text" class="form-control" id="tombol" name="tombol" value="{{ old('tombol', $slider->tombol) }}">
                        @if ($errors->has('tombol'))
                            <small class="text-danger">{{ $errors->first('tombol') }}</small>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('slider.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
