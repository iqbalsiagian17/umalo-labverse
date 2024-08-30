@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h2>Buat FAQ</h2>
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

                <form action="{{ route('qas.store') }}" method="POST">
                    @csrf
            
                    <div class="form-group">
                        <label for="pertanyaan">Pertanyaan:</label>
                        <input type="text" name="pertanyaan" class="form-control" id="pertanyaan" value="{{ old('pertanyaan') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="jawaban">Jawaban:</label>
                        <textarea name="jawaban" class="form-control" id="jawaban" rows="4" required>{{ old('jawaban') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                    <a href="{{ route('qas.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                </form>


        </div>
    </div>
    </div>
</div>



    
@endsection
