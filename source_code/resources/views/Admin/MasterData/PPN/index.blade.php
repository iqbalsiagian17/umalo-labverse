@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title"><h1>PPN</h1></div>
                <a href="{{ route('admin.masterdata.ppn.create') }}" class="btn btn-primary mb-3">Buat PPN</a>
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
                <th>NO</th>
                <th>PPN (%)</th>
                <th width="280px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ppns as $index => $ppn)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $ppn->ppn }}</td>
                <td>
                    <a href="{{ route('admin.masterdata.ppn.show', $ppn->id) }}" class="btn btn-info btn-sm">Lihat</a>
                    <a href="{{ route('admin.masterdata.ppn.edit', $ppn->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.masterdata.ppn.destroy', $ppn->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus PPN ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Tidak ada data PPN tersedia</td>
            </tr>
            @endforelse
        </tbody>
    </table>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection
