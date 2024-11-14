@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2>Sliders</h2>
                <a href="{{ route('slider.create') }}" class="btn btn-primary mb-3">Create Slider</a>
            </div>

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif

            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-striped table-hover table-responsive">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Deskripsi</th>
                            <th>URL</th>
                            <th>Button</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sliders as $index => $slider)
                            <tr>
                                <td>{{ $index + 1 }}</td> <!-- Nomor urut -->
                                <td><img src="{{ asset($slider->image) }}" width="100" class="img-fluid img-thumbnail"></td> <!-- Gambar -->
                                <td>{{ $slider->description }}</td> <!-- Deskripsi -->
                                <td>{{ $slider->url }}</td> <!-- URL -->
                                <td>{{ $slider->button }}</td>
                                <td> <!-- Aksi -->
                                    <a href="{{ route('slider.show', $slider->id) }}" class="btn btn-info btn-sm">Show</a>
                                    <a href="{{ route('slider.edit', $slider->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('slider.destroy', $slider->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this slider?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $sliders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
