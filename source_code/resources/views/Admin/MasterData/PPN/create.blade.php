@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2>Buat PPN</h2>
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

                <form action="{{ route('admin.masterdata.ppn.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="ppn">PPN (%)</label>
                        <input type="number" name="ppn" id="ppn" class="form-control" placeholder="Masukkan persentase PPN" step="0.01" min="0" max="100" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                    <a href="{{ route('admin.masterdata.ppn.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
