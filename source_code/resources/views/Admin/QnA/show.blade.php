@extends('layouts.admin.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Detail Q&A</h1>

    <div class="form-group">
        <label>Pertanyaan:</label>
        <p>{{ $qa->pertanyaan }}</p>
    </div>
    <div class="form-group">
        <label>Jawaban:</label>
        <p>{{ $qa->jawaban }}</p>
    </div>

    <a href="{{ route('qas.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
