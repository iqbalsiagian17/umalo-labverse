@extends('layouts.customer.master')
@section('content')
<div class="container mt-5 mb-5">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-5 shadow rounded border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('user.update') }}">
                @csrf
                @method('PUT')

                <!-- Informasi Tambahan Pengguna -->
                <h4 class="mb-3">{{ __('messages.additional_information') }}</h4>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label">{{ __('messages.name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="company" class="form-label">{{ __('messages.company') }}</label>
                        <input type="text" class="form-control" id="company" name="company" value="{{ old('company', $user->company) }}">
                        @error('company')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="phone_number" class="form-label">{{ __('messages.phone_number') }}</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                        @error('phone_number')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn text-white" style="background: #42378C;">{{ __('messages.update') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
