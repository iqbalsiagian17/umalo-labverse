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
                        <div class="mb-3">
                            <label for="perusahaan" class="form-label">{{ __('messages.company') }}</label>
                            <input type="text" class="form-control" id="perusahaan" name="perusahaan" value="{{ old('perusahaan') }}">
                            @if ($errors->has('perusahaan'))
                                <small class="text-danger">{{ $errors->first('perusahaan') }}</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="no_telepone" class="form-label">{{ __('messages.phone_number') }}</label>
                            <input type="text" class="form-control" id="no_telepone" name="no_telepone" value="{{ old('no_telepone') }}">
                            @if ($errors->has('no_telepone'))
                                <small class="text-danger">{{ $errors->first('no_telepone') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="lahir" class="form-label">{{ __('messages.birth_date') }}</label>
                            <input type="date" class="form-control" id="lahir" name="lahir" value="{{ old('lahir') }}">
                            @if ($errors->has('lahir'))
                                <small class="text-danger">{{ $errors->first('lahir') }}</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">{{ __('messages.gender') }}</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="jenis_kelamin_laki" name="jenis_kelamin" value="laki-laki" {{ old('jenis_kelamin') == 'laki-laki' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="jenis_kelamin_laki">{{ __('messages.male') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="jenis_kelamin_perempuan" name="jenis_kelamin" value="perempuan" {{ old('jenis_kelamin') == 'perempuan' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="jenis_kelamin_perempuan">{{ __('messages.female') }}</label>
                                </div>
                            </div>
                            @if ($errors->has('jenis_kelamin'))
                                <small class="text-danger">{{ $errors->first('jenis_kelamin') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
    
                <hr>
                <!-- Address Section -->
                <h4 class="mb3">{{ __('messages.location') }}</h4>
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
                        <div class="mb-3">
                            <label for="tambahan" class="form-label">{{ __('messages.additional_info') }}</label>
                            <input type="text" class="form-control" id="tambahan" name="tambahan" value="{{ old('tambahan') }}">
                            @if ($errors->has('tambahan'))
                                <small class="text-danger">{{ $errors->first('tambahan') }}</small>
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
                    </div>
                </div>
    
                <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
            </form>
        </div>
    </div>
    
</div>
@endsection
