@extends('layouts.Admin.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title"><h1>Sub Kategori</h1></div>
                <div>
                    <!-- Form Pencarian -->
                    <form action="{{ route('admin.masterdata.subkategori.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari Nama Sub Kategori atau Kategori" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary me-2">Cari</button>
                        <a href="{{ route('admin.masterdata.subkategori.index') }}" class="btn btn-secondary">Refresh</a>
                    </form>
                </div>
                <a href="{{ route('admin.masterdata.subkategori.create') }}" class="btn btn-primary">Buat Sub Kategori</a>
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
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subkategoris as $index => $subkategori)
                            <tr>
                                <td>{{ $subkategoris->firstItem() + $index }}</td>
                                <td>{{ $subkategori->nama }}</td>
                                <td>{{ $subkategori->kategori->nama }}</td>
                                <td>
                                    <a href="{{ route('admin.masterdata.subkategori.edit', $subkategori->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.masterdata.subkategori.destroy', $subkategori->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus sub kategori ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada sub kategori ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $subkategoris->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
