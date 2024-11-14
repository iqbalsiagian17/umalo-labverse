@extends('layouts.admin.master')

@section('content')
<div class="row">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><strong>Nama:</strong></label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext">{{ $user->name }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><strong>Email:</strong></label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><strong>Sebagai:</strong></label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>

                <hr>

                <h5 class="mb-4">Informasi Kontak</h5>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><strong>Nomor Telephone / WhatsApp:</strong></label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext">
                            {{ optional($user->userDetail)->no_telepone ?? 'User belum mengisi data diri dengan lengkap' }}
                        </p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><strong>Alamat:</strong></label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext">
                            {{ optional($user->userDetail)->alamat ?? 'User belum mengisi data diri dengan lengkap' }}
                        </p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><strong>Kota:</strong></label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext">
                            {{ optional($user->userDetail)->kota ?? 'User belum mengisi data diri dengan lengkap' }}
                        </p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><strong>Provinsi:</strong></label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext">
                            {{ optional($user->userDetail)->provinsi ?? 'User belum mengisi data diri dengan lengkap' }}
                        </p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><strong>Perusahaan:</strong></label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext">
                            {{ optional($user->userDetail)->perusahaan ?? 'User belum mengisi data diri dengan lengkap' }}
                        </p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><strong>Kode Pos:</strong></label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext">
                            {{ optional($user->userDetail)->kode_pos ?? 'User belum mengisi data diri dengan lengkap' }}
                        </p>
                    </div>
                </div>

                <hr>

                <h5 class="mb-4">Informasi Pribadi</h5>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><strong>Tanggal Lahir:</strong></label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext">
                            @if(optional($user->userDetail)->lahir)
                                {{ \Carbon\Carbon::parse(optional($user->userDetail)->lahir)->format('d M Y') }}
                            @else
                                User belum mengisi data diri dengan lengkap
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label"><strong>Jenis Kelamin:</strong></label>
                    <div class="col-sm-9">
                        <p class="form-control-plaintext">
                            {{ optional($user->userDetail)->jenis_kelamin ?? 'User belum mengisi data diri dengan lengkap' }}
                        </p>
                    </div>
                </div>

                <a href="{{ route('users.index') }}" class="btn btn-secondary mt-4">Kembali</a>
            </div>
        </div>
    </div>
@endsection
