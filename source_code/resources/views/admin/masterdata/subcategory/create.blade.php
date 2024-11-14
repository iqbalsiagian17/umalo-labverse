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

                <form action="{{ route('admin.masterdata.subcategory.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" class="form-control" id="category_id">
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}" {{ old('category_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('category_id'))
                            <small class="text-danger">{{ $errors->first('category_id') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="name">Nama Sub Category</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="Masukkan name sub Category">
                        @if ($errors->has('name'))
                            <small class="text-danger">{{ $errors->first('name') }}</small>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('admin.masterdata.subcategory.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
