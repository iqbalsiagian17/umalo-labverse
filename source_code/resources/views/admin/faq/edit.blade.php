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

    <form action="{{ route('faq.update', $faq->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="question">Pertanyaan:</label>
            <input type="text" name="question" class="form-control" id="question" value="{{ $faq->question }}" required>
        </div>
        <div class="form-group">
            <label for="answer">Jawaban:</label>
            <textarea name="answer" class="form-control" id="answer" rows="4" required>{{ $faq->answer }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update</button>
        <a href="{{ route('faq.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </form>
</div>
@endsection
