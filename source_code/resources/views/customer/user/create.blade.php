@extends('layouts.customer.master')
@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-5 mt-5 shadow rounded border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('user.store') }}">
                @csrf

                <!-- User Information Section -->
                <h4 class="mb-3">{{ __('messages.additional_information') }}</h4>
                <div class="row mb-4">
                    <div class="col-md-6">
                            <label for="perusahaan" class="form-label">{{ __('messages.company') }}</label>
                            <input type="text" class="form-control" id="perusahaan" name="perusahaan" value="{{ old('perusahaan') }}">
                            @if ($errors->has('perusahaan'))
                                <small class="text-danger">{{ $errors->first('perusahaan') }}</small>
                            @endif
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="no_telephone" class="form-label">{{ __('messages.phone_number') }}</label>
                            <input type="text" class="form-control" id="no_telephone" name="no_telephone" value="{{ old('no_telephone') }}">
                            @if ($errors->has('no_telephone'))
                                <small class="text-danger">{{ $errors->first('no_telephone') }}</small>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>
                <!-- Address Section -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">{{ __('messages.location') }}</h4>
                    <select id="address_label" name="address_label" class="form-control" required style="width: auto;">
                        <option value="" disabled selected>Label</option>
                        <option value="Kantor">Kantor</option>
                        <option value="Rumah">Rumah</option>
                    </select>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="recipient_name" class="form-label">Nama Penemerima</label>
                            <input type="text" class="form-control" id="recipient_name" name="recipient_name" value="{{ old('recipient_name') }}">
                            @if ($errors->has('recipient_name'))
                                <small class="text-danger">{{ $errors->first('recipient_name') }}</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">{{ __('messages.alamat') }}</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                            @if ($errors->has('address'))
                                <small class="text-danger">{{ $errors->first('address') }}</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">{{ __('messages.city') }}</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                            @if ($errors->has('city'))
                                <small class="text-danger">{{ $errors->first('city') }}</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">{{ __('messages.phone_number') }}</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                            @if ($errors->has('phone_number'))
                                <small class="text-danger">{{ $errors->first('phone_number') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="postal_code" class="form-label">{{ __('messages.postal_code') }}</label>
                            <input type="number" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" maxlength="5">
                            @if ($errors->has('postal_code'))
                                <small class="text-danger">{{ $errors->first('postal_code') }}</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="province" class="form-label">{{ __('messages.province') }}</label>
                            <input type="text" class="form-control" id="province" name="province" value="{{ old('province') }}">
                            @if ($errors->has('province'))
                                <small class="text-danger">{{ $errors->first('province') }}</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="additional_info" class="form-label">{{ __('messages.additional_info') }}</label>
                            <!-- Span to provide additional information -->
                            <input type="text" class="form-control" id="additional_info" name="additional_info" value="{{ old('additional_info') }}" required>
                            <span class="form-text text-muted">{{ __('Berikan ciri-ciri unik tempat Anda agar petugas pengantaran bisa lebih mudah menemukan lokasi pengiriman.') }}</span>
                            @if ($errors->has('additional_info'))
                                <small class="text-danger">{{ $errors->first('additional_info') }}</small>
                            @endif
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn text-white" style="background: #42378C;">{{ __('messages.save') }}</button>
            </form>
        </div>
    </div>

</div>
@endsection
