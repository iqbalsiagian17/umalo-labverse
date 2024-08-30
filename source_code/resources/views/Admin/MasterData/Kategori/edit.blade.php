@extends('layouts.admin.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Edit Kategori</h2>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.masterdata.kategori.update', $kategori->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" class="form-control" id="nama" value="{{ old('nama', $kategori->nama) }}" placeholder="Masukkan nama kategori">
                        @if ($errors->has('nama'))
                            <small class="text-danger">{{ $errors->first('nama') }}</small>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('admin.masterdata.kategori.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
