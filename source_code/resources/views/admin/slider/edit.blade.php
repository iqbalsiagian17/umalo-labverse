@extends('layouts.admin.master')

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
                        <label for="description" class="form-label">Description:</label>
                        <textarea class="form-control" id="description" name="description" required>{{ old('description', $slider->description) }}</textarea>
                        @if ($errors->has('description'))
                            <small class="text-danger">{{ $errors->first('description') }}</small>
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
                        <option value="{{ route('product.show', ['slug' => $product->slug]) }}"
                            {{ $slider->url == route('product.show', ['slug' => $product->slug]) ? 'selected' : '' }}>
                            Product: {{ $product->name }}
                        </option>
                    @endforeach
                </optgroup>                
            </select>
        </div>
                    <div class="form-group mb-3">
                        <label for="button" class="form-label">Button Text:</label>
                        <input type="text" class="form-control" id="button" name="button" value="{{ old('button', $slider->button) }}">
                        @if ($errors->has('button'))
                            <small class="text-danger">{{ $errors->first('button') }}</small>
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
