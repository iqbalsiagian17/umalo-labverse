@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Detail PPN</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="id">ID:</label>
                    <p class="form-control-static">{{ $ppn->id }}</p>
                </div>
                <div class="form-group">
                    <label for="ppn">PPN (%):</label>
                    <p class="form-control-static">{{ $ppn->ppn }}%</p>
                </div>
                <a href="{{ route('admin.masterdata.ppn.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
