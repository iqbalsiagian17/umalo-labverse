@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2>Big Sales</h2>
                <a href="{{ route('admin.bigsales.create') }}" class="btn btn-primary">Create Big Sale</a>
            </div>
            <div class="card-body">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Judul</th>
                                <th>Mulai</th>
                                <th>Berakhir</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bigSales as $bigSale)
                                <tr>
                                    <td>{{ $bigSale->title }}</td>
                                    <td>{{ $bigSale->start_time }}</td>
                                    <td>{{ $bigSale->end_time }}</td>
                                    <td>{{ $bigSale->status ? 'Active' : 'Inactive' }}</td>
                                    <td>
                                        <a href="{{ route('admin.bigsales.show', $bigSale->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('admin.bigsales.edit', $bigSale->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('admin.bigsales.destroy', $bigSale->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this Big Sale?');">
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
                    {{ $bigSales->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
