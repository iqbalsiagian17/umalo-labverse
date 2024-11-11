@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
            <div class="card-title"><h1>FAQ</h1></div>
            <a href="{{ route('faq.create') }}" class="btn btn-primary mb-3">Tambah Q&A</a>
            </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

    <div class="card-body">
        <div class="row">
            <div class="table-responsive">
    <table class="table table-striped table-responsive table-hover">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Pertanyaan</th>
                <th>Jawaban</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($faq as $index => $qa)
            <tr>
                <td>{{ ($faq->currentPage() - 1) * $faq->perPage() + $index + 1 }}</td>
                <td>{{ $qa->question }}</td>
                <td>{{ $qa->answer }}</td>
                <td>
                    <form action="{{ route('faq.destroy', $qa->id) }}" method="POST">
                        <a class="btn btn-info btn-sm" href="{{ route('faq.show', $qa->id) }}">Show</a>
                        <a class="btn btn-primary btn-sm" href="{{ route('faq.edit', $qa->id) }}">Edit</a>

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
            </div>
    <div class="d-flex justify-content-center">
        {{ $faq->links('pagination::bootstrap-5') }}
    </div>
</div>
    </div>
        </div>
</div>

@endsection
