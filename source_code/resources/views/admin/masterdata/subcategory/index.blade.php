@extends('layouts.admin.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title"><h1>Sub Category</h1></div>
                <div>
                    <!-- Form Pencarian -->
                    <form action="{{ route('admin.masterdata.subcategory.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari Nama Sub Category atau Category" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary me-2">Cari</button>
                        <a href="{{ route('admin.masterdata.subcategory.index') }}" class="btn btn-secondary">Refresh</a>
                    </form>
                </div>
                <a href="{{ route('admin.masterdata.subcategory.create') }}" class="btn btn-primary">Buat Sub Category</a>
            </div>

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Category</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subcategories as $index => $subCategory)
                            <tr>
                                <td>{{ $subcategories->firstItem() + $index }}</td>
                                <td>{{ $subCategory->name }}</td>
                                <td>{{ $subCategory->Category->name }}</td>
                                <td>
                                    <a href="{{ route('admin.masterdata.subcategory.edit', $subCategory->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.masterdata.subcategory.destroy', $subCategory->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus sub Category ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada sub Category ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $subcategories->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
