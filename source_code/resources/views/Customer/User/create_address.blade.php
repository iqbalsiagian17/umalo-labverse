@extends('layouts.customer.master')

@section('content')
<div class="container mt-5 mb-5">
    <div class="card shadow rounded border-0">
        <div class="card-body">
            <h4 class="mb-3">{{ __('messages.add_new_address') }}</h4>
            <form method="POST" action="{{ route('user.storeAddress') }}">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="alamat" class="form-label">{{ __('messages.alamat') }}</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat') }}">
                            @if ($errors->has('alamat'))
                                <small class="text-danger">{{ $errors->first('alamat') }}</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="kota" class="form-label">{{ __('messages.city') }}</label>
                            <input type="text" class="form-control" id="kota" name="kota" value="{{ old('kota') }}">
                            @if ($errors->has('kota'))
                                <small class="text-danger">{{ $errors->first('kota') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kode_pos" class="form-label">{{ __('messages.postal_code') }}</label>
                            <input type="number" class="form-control" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" maxlength="5">
                            @if ($errors->has('kode_pos'))
                                <small class="text-danger">{{ $errors->first('kode_pos') }}</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="provinsi" class="form-label">{{ __('messages.province') }}</label>
                            <input type="text" class="form-control" id="provinsi" name="provinsi" value="{{ old('provinsi') }}">
                            @if ($errors->has('provinsi'))
                                <small class="text-danger">{{ $errors->first('provinsi') }}</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="tambahan" class="form-label">{{ __('messages.additional_info') }}</label>
                            <input type="text" class="form-control" id="tambahan" name="tambahan" value="{{ old('tambahan') }}">
                            @if ($errors->has('tambahan'))
                                <small class="text-danger">{{ $errors->first('tambahan') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
