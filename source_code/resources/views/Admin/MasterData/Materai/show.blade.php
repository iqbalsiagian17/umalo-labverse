@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Detail Materai</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="id">ID:</label>
                    <p class="form-control-static">{{ $materai->id }}</p>
                </div>
                <div class="form-group">
                    <label for="image">Gambar Materai:</label>
                    <br>
                    <img src="{{ asset($materai->image) }}" width="100" class="img-fluid img-thumbnail">
                </div>
                <a href="{{ route('admin.masterdata.materai.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
