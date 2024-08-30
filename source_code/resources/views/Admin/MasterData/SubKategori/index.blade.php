@extends('layouts.admin.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title"><h1>Sub Kategori</h1></div>
                <a href="{{ route('admin.masterdata.subkategori.create') }}" class="btn btn-primary mb-3">Buat Sub Kategori</a>
            </div>
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
        
            <div class="card-body">
                <div class="row">
                    <table class="table table-striped table-responsive table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subkategoris as $index => $subkategori)
                            <tr>
                                <td>{{ $index + 1 }}</td>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
