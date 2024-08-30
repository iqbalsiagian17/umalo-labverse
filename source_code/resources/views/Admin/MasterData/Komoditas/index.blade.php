@extends('layouts.admin.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title"><h1>Komoditas</h1></div>
                <a href="{{ route('admin.masterdata.komoditas.create') }}" class="btn btn-primary mb-3">Buat Komoditas</a>
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
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($komoditas as $index => $komoditas)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $komoditas->nama }}</td>
                                <td>
                                    <a href="{{ route('admin.masterdata.komoditas.edit', $komoditas->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.masterdata.komoditas.destroy', $komoditas->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus komoditas ini?')">Hapus</button>
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
