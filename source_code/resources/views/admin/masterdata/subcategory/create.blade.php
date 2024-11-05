@extends('layouts.admin.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Buat Sub Category</h2>
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

                <form action="{{ route('admin.masterdata.subCategory.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="Category_id">Category</label>
                        <select name="Category_id" class="form-control" id="Category_id">
                            @foreach ($Category as $item)
                                <option value="{{ $item->id }}" {{ old('Category_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('Category_id'))
                            <small class="text-danger">{{ $errors->first('Category_id') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Sub Category</label>
                        <input type="text" name="nama" class="form-control" id="nama" value="{{ old('nama') }}" placeholder="Masukkan nama sub Category">
                        @if ($errors->has('nama'))
                            <small class="text-danger">{{ $errors->first('nama') }}</small>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('admin.masterdata.subCategory.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
