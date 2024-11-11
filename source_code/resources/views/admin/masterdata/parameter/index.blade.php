@extends('layouts.admin.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">
                    <h1>Parameter</h1>
                </div>
                <div class="d-flex align-items-center">
                    @if($parameters->isEmpty())  <!-- Check if there are no parameters -->
                        <a href="{{ route('admin.masterdata.parameter.create') }}" class="btn btn-primary">Buat Parameter</a>
                    @endif
                </div>
            </div>

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
            @endif

            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Perusahaan</th>
                                    <th>Email 1</th>
                                    <th>Email 2</th>
                                    <th>Telepon</th>
                                    <th>WhatsApp</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tbody>
                                    @forelse ($parameters as $key => $parameter)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $parameter->company_name }}</td>
                                            <td>{{ $parameter->email1 }}</td>
                                            <td>{{ $parameter->email2 }}</td>
                                            <td>{{ $parameter->telephone_number }}</td>
                                            <td>{{ $parameter->whatsapp_number }}</td>
                                            <td>
                                                <a href="{{ route('admin.masterdata.parameter.edit', $parameter->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada parameter ditemukan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
