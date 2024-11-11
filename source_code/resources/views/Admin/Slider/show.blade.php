@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Slider Details</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Image</th>
                            <td>
                                <img src="{{ asset($slider->image) }}" class="img-fluid img-thumbnail" width="300">
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $slider->description }}</td>
                        </tr>
                        <tr>
                            <th>URL</th>
                            <td><a href="{{ $slider->url }}" target="_blank">{{ $slider->url }}</a></td>
                        </tr>
                    </tbody>
                </table>
                <a href="{{ route('slider.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection
