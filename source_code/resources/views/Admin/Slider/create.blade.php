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
                        <label for="image" class="form-label">Image:</label>
                        <input type="file" class="form-control" id="image" name="image" required>
                        @if ($errors->has('image'))
                            <small class="text-danger">{{ $errors->first('image') }}</small>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <label for="deskripsi" class="form-label">Description:</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" required>{{ old('deskripsi') }}</textarea>
                        @if ($errors->has('deskripsi'))
                            <small class="text-danger">{{ $errors->first('deskripsi') }}</small>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <label for="url" class="form-label">URL:</label>
                        <input type="text" class="form-control" id="url" name="url" value="{{ old('url') }}">
                        
                        @if ($errors->has('url'))
                            <small class="text-danger">{{ $errors->first('url') }}</small>
                        @endif
                        
                        <small class="form-text text-muted">Default: "/shop"</small>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Submit</button>
                    <a href="{{ route('slider.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
