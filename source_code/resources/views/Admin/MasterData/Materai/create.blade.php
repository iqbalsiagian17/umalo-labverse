@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Tambah Materai</h2>
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> Ada masalah dengan input Anda.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.masterdata.materai.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="image">Gambar Materai</label>
                        <input type="file" name="image" id="image" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                    <a href="{{ route('admin.masterdata.materai.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
