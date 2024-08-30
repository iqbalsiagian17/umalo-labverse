@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Daftar Materai</h2>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.masterdata.materai.create') }}" class="btn btn-primary mb-3">Tambah Materai</a>

                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        {{ $message }}
                    </div>
                @endif

                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Gambar Materai</th>
                            <th width="280px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($materai as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td><img src="{{ asset($item->image) }}" width="100" class="img-fluid img-thumbnail"></td>
                            <td>
                                <a href="{{ route('admin.masterdata.materai.show', $item->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                <a href="{{ route('admin.masterdata.materai.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.masterdata.materai.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus Materai ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data Materai tersedia</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
            </div>
        </div>
    </div>
</div>
@endsection
