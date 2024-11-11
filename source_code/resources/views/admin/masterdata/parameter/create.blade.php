@extends('layouts.admin.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3>Buat Parameter</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.masterdata.parameter.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Nama Perusahaan -->
                    <div class="form-group">
                        <label for="company_name">Nama Perusahaan</label>
                        <input type="text" name="company_name" id="company_name" class="form-control" value="{{ old('company_name') }}" required>
                    </div>

                    <!-- Nama Ecommerce -->
                    <div class="form-group">
                        <label for="ecommerce_name">Nama Ecommerce</label>
                        <input type="text" name="ecommerce_name" id="ecommerce_name" class="form-control" value="{{ old('ecommerce_name') }}" required>
                    </div>

                    <!-- Email 1 -->
                    <div class="form-group">
                        <label for="email1">Email 1</label>
                        <input type="email" name="email1" id="email1" class="form-control" value="{{ old('email1') }}" required>
                    </div>

                    <!-- Email 2 -->
                    <div class="form-group">
                        <label for="email2">Email 2</label>
                        <input type="email" name="email2" id="email2" class="form-control" value="{{ old('email2') }}">
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="form-group">
                        <label for="telephone_number">Nomor Telepon</label>
                        <input type="text" name="telephone_number" id="telephone_number" class="form-control" value="{{ old('telephone_number') }}" required>
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div class="form-group">
                        <label for="whatsapp_number">Nomor WhatsApp</label>
                        <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control" value="{{ old('whatsapp_number') }}" required>
                    </div>

                    <!-- Address -->
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
                    </div>

                    <!-- Slogan -->
                    <div class="form-group">
                        <label for="slogan">Slogan</label>
                        <input type="text" name="slogan" id="slogan" class="form-control" value="{{ old('slogan') }}">
                    </div>

                    <!-- Account Name -->
                    <div class="form-group">
                        <label for="account_name">Nama Rekening</label>
                        <input type="text" name="account_name" id="account_name" class="form-control" value="{{ old('account_name') }}">
                    </div>

                    <!-- Bank Name -->
                    <div class="form-group">
                        <label for="bank_name">Nama Bank</label>
                        <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ old('bank_name') }}">
                    </div>

                    <!-- Account Number -->
                    <div class="form-group">
                        <label for="account_number">Nomor Rekening</label>
                        <input type="text" name="account_number" id="account_number" class="form-control" value="{{ old('account_number') }}">
                    </div>

                    <!-- Bank City -->
                    <div class="form-group">
                        <label for="bank_city">Kota Bank</label>
                        <input type="text" name="bank_city" id="bank_city" class="form-control" value="{{ old('bank_city') }}">
                    </div>

                    <!-- Bank Address -->
                    <div class="form-group">
                        <label for="bank_address">Alamat Bank</label>
                        <input type="text" name="bank_address" id="bank_address" class="form-control" value="{{ old('bank_address') }}">
                    </div>

                    <!-- Director -->
                    <div class="form-group">
                        <label for="director">Direktur</label>
                        <input type="text" name="director" id="director" class="form-control" value="{{ old('director') }}">
                    </div>

                    <!-- Logo 1 -->
                    <div class="form-group">
                        <label for="logo1">Logo 1</label>
                        <input type="file" name="logo1" id="logo1" class="form-control-file">
                    </div>

                    <!-- Logo 2 -->
                    <div class="form-group">
                        <label for="logo2">Logo 2</label>
                        <input type="file" name="logo2" id="logo2" class="form-control-file">
                    </div>

                    <!-- Logo 3 -->
                    <div class="form-group">
                        <label for="logo3">Logo 3</label>
                        <input type="file" name="logo3" id="logo3" class="form-control-file">
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Simpan Parameter</button>
                        <a href="{{ route('admin.masterdata.parameter.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
