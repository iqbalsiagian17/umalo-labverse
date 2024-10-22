@extends('layouts.Admin.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">
                    <h1>Kategori</h1>
                </div>
                <div class="d-flex align-items-center">
                    <!-- Form Pencarian di ujung kanan -->
                    <form action="{{ route('admin.masterdata.kategori.index') }}" method="GET" class="d-flex me-3">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari Nama Kategori" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary me-2">Cari</button>
                        <a href="{{ route('admin.masterdata.kategori.index') }}" class="btn btn-secondary">Refresh</a>
                    </form>
                    <!-- Tombol Buat Kategori di sebelah kanan form -->
                    <a href="{{ route('admin.masterdata.kategori.create') }}" class="btn btn-primary">Buat Kategori</a>
                </div>
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
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kategoris as $index => $kategori)
                                <tr>
                                    <td>{{ $kategoris->firstItem() + $index }}</td>
                                    <td>{{ $kategori->nama }}</td>
                                    <td>
                                        <a href="{{ route('admin.masterdata.kategori.edit', $kategori->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('admin.masterdata.kategori.destroy', $kategori->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada kategori ditemukan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $kategoris->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
