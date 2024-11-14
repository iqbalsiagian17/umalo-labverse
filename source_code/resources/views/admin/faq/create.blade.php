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

                <form action="{{ route('faq.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="question">Pertanyaan:</label>
                        <input type="text" name="question" class="form-control" id="question" value="{{ old('question') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="answer">Jawaban:</label>
                        <textarea name="answer" class="form-control" id="answer" rows="4" required>{{ old('answer') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                    <a href="{{ route('faq.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                </form>


        </div>
    </div>
    </div>
</div>




@endsection
