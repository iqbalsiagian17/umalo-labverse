@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Create Slider</h2>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('slider.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="image" class="form-label"><span class="text-danger">*</span> Gambar:</label>
                        <input type="file" class="form-control" id="image" name="image" required>
                        @if ($errors->has('image'))
                            <small class="text-danger">{{ $errors->first('image') }}</small>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <label for="description" class="form-label"><span class="text-danger">*</span> Deskripsi:</label>
                        <textarea class="form-control" id="description" name="description" required>{{ old('description') }}</textarea>
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
                                    <option value="{{ $url }}">{{ $name }}</option>
                                @endforeach
                            </optgroup>
                    
                            <!-- Product URLs Group -->
                            <optgroup label="Halaman Detail Product">
                                @foreach($products as $product)
                                    <option value="{{ route('product.show', ['slug' => $product->slug]) }}">
                                        Product: {{ $product->name }}
                                    </option>
                                @endforeach
                            </optgroup>                            
                        </select>
                    </div>
                    

                    <div class="form-group mb-3">
                        <label for="button" class="form-label"><span class="text-danger">*</span> Tulisan Tombol:</label>
                        <input type="text" class="form-control" id="button" name="button" value="{{ old('button') }}">
                        @if ($errors->has('button'))
                            <small class="text-danger">{{ $errors->first('button') }}</small>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                    <a href="{{ route('slider.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
