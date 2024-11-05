@extends('layouts.admin.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Q&A</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Ada masalah dengan inputan Anda.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('qas.update', $qa->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="pertanyaan">Pertanyaan:</label>
            <input type="text" name="pertanyaan" class="form-control" id="pertanyaan" value="{{ $qa->pertanyaan }}" required>
        </div>
        <div class="form-group">
            <label for="jawaban">Jawaban:</label>
            <textarea name="jawaban" class="form-control" id="jawaban" rows="4" required>{{ $qa->jawaban }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update</button>
        <a href="{{ route('qas.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </form>
</div>
@endsection
